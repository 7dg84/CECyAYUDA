<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
include_once 'config.php';

$retoken = '';

// Generar token en base a la información que expira en una hora
function genToken($folio, $curp, $correo)
{
    $config = new Config(); // Cargar la configuración

    $default_encryption_key = $config['mail']['enckey']; // Clave de encriptación por defecto
    if (empty($default_encryption_key)) {
        throw new Exception("La clave de encriptación no está configurada.");
    }
    $encryption_method = 'AES-256-CBC'; // Método de cifrado

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
function verifyToken($token)
{
    global $errorMsg;
    try {
        $config = new Config(); // Cargar la configuración

        $default_encryption_key = $config['mail']['enckey']; // Clave de encriptación por defecto
        if (empty($default_encryption_key)) {
            $errorMsg = "La clave de encriptación no está configurada.";
            return false; // Clave de encriptación no configurada
        }
        $encryption_method = 'AES-256-CBC'; // Método de cifrado

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
            $errorMsg = "Token inválido o malformado.";
            return false; // Token inválido
        }

        // Verificar si el token ha expirado
        if (time() > $data['expiration']) {
            $errorMsg = "El token ha expirado.";
            return false; // Token expirado
        }

        $database = new Denuncia(); // Conectar a la base de datos
        // Verificar la validez de los datos en la base de datos
        $result = $database->searchDenuncia($data['folio']);
        if ($result->num_rows === 0) {
            $database->closeConnection();
            $errorMsg = "Folio no encontrado.";
            return false; // Folio no encontrado
        }

        $record = $result->fetch_assoc();
        // Verificar si el CURP y correo coinciden con los datos de la denuncia
        if ($record['CURP'] !== $data['curp'] || $record['Correo'] !== $data['correo']) {
            $database->closeConnection();
            $errorMsg = "Token invalido.";
            return false; // CURP o correo no coinciden
        }
        // Verificar si la denuncia ya ha sido verificada
        if ($record['Verified'] == 1) {
            $database->closeConnection();
            $errorMsg = "Denuncia ya verificada.";
            return false; // Denuncia ya verificada
        }

        // Verificar la denuncia en la base de datos
        $isVerified = $database->verifyDenuncia($data['folio']);
        $database->closeConnection();

        return $isVerified; // Retorna true si la denuncia es válida
    } catch (Exception $e) {
        $errorMsg = "Error al verificar el token: " . htmlspecialchars($e->getMessage());
        return false; // Error en la verificación
    }
}

// Enviar el correo con el token
function sendEmaildep($nombre, $folio, $curp, $correo)
{
    $config = new Config(); // Cargar la configuración
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
    $mail->setFrom($config['mail']['from'][0], $config['mail']['from'][1]); // Tu nombre
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
        <p><a href=\"' . $config['mail']['url'] . '/verify.php?token=' . urlencode($token) . '">Verificar mi correo</a></p>
        <p>Si no puedes hacer clic en el enlace, copia y pega la siguiente URL en tu navegador:</p>
        <p>' . htmlspecialchars($config['mail']['url']) . '/verify.php?token=' . urlencode($token) . '</p>
        <br>
        <p>Si no solicitaste esta verificación, puedes ignorar este mensaje.</p>
        <p>Este enlace expirará en 1 hora.</p>
        <br>
        <p>Atentamente,</p>
        <p>El equipo de DragonFly Codes</p>
    </body>
    </html>
    ';
    $mail->AltBody = 'Hola, ' . htmlspecialchars($nombre) . '! Gracias por reportar. Por favor, haz clic en el siguiente enlace para verificar tu correo electrónico: ' . $config['mail']['url'] . urlencode($token) . ' Si no solicitaste esta verificación, puedes ignorar este mensaje. Este enlace expirará en 1 hora. Atentamente, El equipo de DragonFly Codes';

    $mail->send();
}

function sendEmail($nombre, $folio, $curp, $correo)
{
    require __DIR__ . '/vendor/autoload.php';
    $config = new Config(); // Cargar la configuración
    // Verificar que la configuración de correo esté completa
    if (
        empty($config['mail']['host']) ||
        empty($config['mail']['user']) ||
        empty($config['mail']['password']) ||
        empty($config['mail']['from']) ||
        !is_array($config['mail']['from']) ||
        empty($config['mail']['from'][0]) ||
        empty($config['mail']['from'][1]) ||
        empty($config['mail']['url'])
    ) {
        throw new Exception("La configuración de correo no está completa.");
    }

    // Contenido del correo
    $token = genToken($folio, $curp, $correo);

    $resend = Resend::client($retoken);

    $resend->emails->send([
        'from' => $config['mail']['from'][1] . ' <' . $config['mail']['from'][0] . '>',
        'to' => [$correo],
        'subject' => 'Verifica tu cuenta de correo',
        'html' => '
    <html>
    <head>
        <title>Verificación de Correo Electrónico</title>
    </head>
    <body>
        <h1>Hola, ' . htmlspecialchars($nombre) . '!</h1>
        <p>Gracias por reportar. Por favor, haz clic en el siguiente enlace para verificar tu correo electrónico:</p>
        <p><a href="' . $config['mail']['url'] . '/verify.php?token=' . urlencode($token) . '">Verificar mi correo</a></p>
        <p>Si no puedes hacer clic en el enlace, copia y pega la siguiente URL en tu navegador:</p>
        <p>' . htmlspecialchars($config['mail']['url']) . '/verify.php?token=' . urlencode($token) . '</p>
        <br>
        <p>Si no solicitaste esta verificación, puedes ignorar este mensaje.</p>
        <p>Este enlace expirará en 1 hora.</p>
        <br>
        <p>Atentamente,</p>
        <p>El equipo de DragonFly Codes</p>
    </body>
    </html>
    ',
        'text' => 'Hola, ' . htmlspecialchars($nombre) . '! Gracias por reportar. Por favor, haz clic en el siguiente enlace para verificar tu correo electrónico: ' . $config['mail']['url'] . urlencode($token) . ' Si no solicitaste esta verificación, puedes ignorar este mensaje. Este enlace expirará en 1 hora. Atentamente, El equipo de DragonFly Codes'
    ]);

    return true; // Correo enviado exitosamente
}

// Funcion para envial un correo con folios encontrados
function sendFolioEmaildep($nombre, $folio, $correo)
{
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
    $mail->Subject = 'Folio Recuperado';
    $mail->Body = '
    <html>
    <head>
        <title>Folio Recuperado</title>
    </head>
    <body>
        <h1>Hola, ' . htmlspecialchars($nombre) . '!</h1>
        <p>Tu folio ha sido recuperado exitosamente.</p>
        <p>Folio: ' . htmlspecialchars($folio) . '</p>
        <br>
        <p>Consulta el estado de tu reporte en cualquier momento.</p>
        <a href="' . htmlspecialchars($config['mail']['url']) . '/consultar.php?folio=' . urlencode($folio) . '">Consultar Reporte</a>
        <p>Atentamente,</p>
        <p>El equipo de DragonFly Codes</p>
    </body>
    </html>
    ';
    $mail->AltBody = 'Hola, ' . htmlspecialchars($nombre) . '! Tu folio ha sido recuperado exitosamente. Folio: ' . htmlspecialchars($folio) . ' Atentamente, El equipo de DragonFly Codes';

    return $mail->send();
}

function sendFolioEmail($nombre, $folio, $correo)
{
    require __DIR__ . '/vendor/autoload.php';
    $config = new Config();
    // Verificar que la configuración de correo esté completa
    if (
        empty($config['mail']['host']) ||
        empty($config['mail']['user']) ||
        empty($config['mail']['password']) ||
        empty($config['mail']['from']) ||
        !is_array($config['mail']['from']) ||
        empty($config['mail']['from'][0]) ||
        empty($config['mail']['from'][1]) ||
        empty($config['mail']['url'])
    ) {
        throw new Exception("La configuración de correo no está completa.");
    }

    // Contenido del correo    
    $resend = Resend::client($retoken);
    $resend->emails->send([
        'from' => $config['mail']['from'][1] . ' <' . $config['mail']['from'][0] . '>',
        'to' => [$correo],
        'subject' => 'Folio Recuperado',
        'html' => '
        <html>
    <head>
        <title>Folio Recuperado</title>
    </head>
    <body>
        <h1>Hola, ' . htmlspecialchars($nombre) . '!</h1>
        <p>Tu folio ha sido recuperado exitosamente.</p>
        <p>Folio: ' . htmlspecialchars($folio) . '</p>
        <br>
        <p>Consulta el estado de tu reporte en cualquier momento.</p>
        <a href="' . htmlspecialchars($config['mail']['url']) . '/consultar.php?folio=' . urlencode($folio) . '">Consultar Reporte</a>
        <p>Atentamente,</p>
        <p>El equipo de DragonFly Codes</p>
    </body>
    </html>
    ',
        'text' => 'Hola, ' . htmlspecialchars($nombre) . '! Tu folio ha sido recuperado exitosamente. Folio: ' . htmlspecialchars($folio) . ' Atentamente, El equipo de DragonFly Codes'
    ]);
}
