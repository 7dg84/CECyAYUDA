<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
include_once 'config.php';

$config = new Config();

$default_encryption_key = $config['mail']['key']; // Clave de encriptación por defecto
$encryption_method = 'AES-256-CBC'; // Método de cifrado

// Generar token en base a la información que expira en una hora
function genToken($folio, $curp, $correo) {
    global $default_encryption_key, $encryption_method;

    $expiration = time() + 3600; // Expira en una hora
    $data = [
        'folio' => $folio,
        'curp' => $curp,
        'correo' => $correo,
        'expiration' => $expiration,
    ];

    // Convertir los datos a JSON
    $json_data = json_encode($data);

    // Generar un IV (vector de inicialización) para el cifrado
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($encryption_method));

    // Encriptar los datos
    $encrypted_data = openssl_encrypt($json_data, $encryption_method, $default_encryption_key, 0, $iv);

    // Combinar el IV y los datos encriptados
    return base64_encode($iv . $encrypted_data);
}

// Verificar el token
function verifyToken($token) {
    global $default_encryption_key, $encryption_method;

    try {
        // Decodificar el token (base64)
        $decoded_token = base64_decode($token);

        // Extraer el IV y los datos encriptados
        $iv_length = openssl_cipher_iv_length($encryption_method);
        $iv = substr($decoded_token, 0, $iv_length);
        $encrypted_data = substr($decoded_token, $iv_length);

        // Desencriptar los datos
        $json_data = openssl_decrypt($encrypted_data, $encryption_method, $default_encryption_key, 0, $iv);

        // Convertir los datos JSON a un array
        $data = json_decode($json_data, true);

        // Validar que el token tenga el formato esperado
        if (!is_array($data) || !isset($data['folio'], $data['curp'], $data['correo'], $data['expiration'])) {
            return false; // Token inválido
        }

        // Verificar si el token ha expirado
        if (time() > $data['expiration']) {
            return false; // Token expirado
        }

        $database = new Denuncia(); // Conectar a la base de datos
        // Verificar la validez de los datos en la base de datos
        $result = $database->searchDenuncia($data['folio']);
        if ($result->num_rows === 0) {
            return false; // Folio no encontrado
        }

        $record = $result->fetch_assoc();
        // Verificar si el CURP y correo coinciden con los datos de la denuncia
        if ($record['CURP'] !== $data['curp'] || $record['Correo'] !== $data['correo']) {
            return false; // CURP o correo no coinciden
        }
        // Verificar si la denuncia ya ha sido verificada
        if ($record['Verified'] == 1) {
            return false; // Denuncia ya verificada
        }

        // Verificar la denuncia en la base de datos
        $isVerified = $database->verifyDenuncia($data['folio']);
        $database->closeConnection();

        return $isVerified; // Retorna true si la denuncia es válida
    } catch (Exception $e) {
        return false; // Error en la verificación
    }
}

// Enviar el correo con el token
function sendEmail($nombre, $folio, $curp, $correo) {
    global $config;
    $mail = new PHPMailer(true);

    // Configuración del servidor SMTP
    $mail->isSMTP();
    $mail->Host = $config['mail']['host']; // Servidor SMTP de Gmail
    $mail->SMTPAuth = true;
    $mail->Username = $config['mail']['user']; // Tu correo de Gmail
    $mail->Password = $config['mail']['password']; // Tu contraseña de Gmail (usa un App Password si es posible)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = $config['mail']['port']; // Puerto SMTP (587 para TLS)

    // Remitente y destinatario
    $mail->setFrom($config['mail']['from'][0], $config['mail']['from'][0]); // Tu nombre
    $mail->addAddress($correo, $nombre);

    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = 'Verifica tu cuenta de correo';
    $token = genToken($folio, $curp, $correo);
    $mail->Body = '
    <html>
    <head>
        <title>Verificación de Correo Electrónico</title>
    </head>
    <body>
        <h1>Hola, ' . htmlspecialchars($nombre) . '!</h1>
        <p>Gracias por reportar. Por favor, haz clic en el siguiente enlace para verificar tu correo electrónico:</p>
        <p><a href=\"'.$config['mail']['url'].'/verify.php?token=' . urlencode($token) . '">Verificar mi correo</a></p>
        <p>Si no solicitaste esta verificación, puedes ignorar este mensaje.</p>
        <p>Este enlace expirará en 1 hora.</p>
        <br>
        <p>Atentamente,</p>
        <p>El equipo de DragonFly Codes</p>
    </body>
    </html>
    ';
    $mail->AltBody = 'Hola, ' . htmlspecialchars($nombre) . '! Gracias por reportar. Por favor, haz clic en el siguiente enlace para verificar tu correo electrónico: http://localhost:80/verify.php?token=' . urlencode($token) . ' Si no solicitaste esta verificación, puedes ignorar este mensaje. Este enlace expirará en 1 hora. Atentamente, El equipo de DragonFly Codes';

    $mail->send();
}
?>
