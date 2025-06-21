<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
include_once 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Load current config
$config = new Config();
$adminEmail = $config['admin']['email'] ?? '';

// Helper: send verification code
function send_verification_code($email, $code)
{
    global $config;
    $mail = new PHPMailer(true);
    try {
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
        $mail->addAddress($email, "Administrador");

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Codigo de Verificación';
        $mail->Body = '
        <html>
        <head>
            <title>Código de Verificación</title>
        </head>
        <body>
            <h1>Código de Verificación</h1>
            <p>Tu código de verificación es: <strong>' . htmlspecialchars($code) . '</strong></p>
            <p>Este código es válido por 10 minutos.</p>
            <p>Si no solicitaste este código, ignora este mensaje.</p>
            <p>Atentamente,</p>
            <p>El equipo de DragonFly Codes</p>
        </body>
        ';
        $mail->AltBody = '
        Tu código de verificación es: ' . htmlspecialchars($code) . '
        Este código es válido por 10 minutos.
        ';

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("El mensaje no pudo ser enviado. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}

// Step 1: Request verification code
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_code'])) {
    $code = rand(100000, 999999);
    $_SESSION['verification_code'] = $code;
    $_SESSION['code_expiry'] = time() + 600; // 10 
    if ($adminEmail === '') {
        $_SESSION['verification_code'] = $code;
        $msg = "Porfavor cambia el correo del administrador en la configuración.";
        $_SESSION['is_auth'] = true;
    } else {
        if (send_verification_code($adminEmail, $code)) {
            $msg = "Código enviado al correo del administrador.";
        } else {
            $msg = "Error al enviar el código. Contacta al administrador.";
        }
    }
}

// Step 2: Change credentials
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_credentials'])) {
    $host = $_POST['host'];
    $database = $_POST['database'];
    $dbuser = $_POST['dbuser'];
    $dbpassword = $_POST['dbpassword'];
    $aduser = $_POST['aduser'];
    $adpassword = $_POST['adpassword'];
    $ademail = $_POST['ademail'];
    $mail_key = $_POST['mail_key'];
    $mail_host = $_POST['mail_host'];
    $mail_port = $_POST['mail_port'];
    $mail_user = $_POST['mail_user'];
    $mail_password = $_POST['mail_password'];
    $mail_from = $_POST['mail_from'];
    $mail_from_name = $_POST['mail_from_name'];
    $mail_url = $_POST['mail_url'];

    if (isset($_SESSION['verification_code']) && time() < ($_SESSION['code_expiry'] ?? 0)) {
        if ($_SESSION['verification_code'] == $_POST['verification_code']) {
            // Update config
            $config['db'] = [
                'host' => $host,
                'database' => $database,
                'user' => $dbuser,
                'password' => $dbpassword
            ];
            $config['admin'] = [
                'user' => $aduser,
                'passwordhash' => password_hash($adpassword, PASSWORD_BCRYPT),
                'email' => $ademail
            ];
            $config['mail'] = [
                'enckey' => $mail_key,
                'host' => $mail_host,
                'port' => (int)$mail_port,
                'user' => $mail_user,
                'password' => $mail_password,
                'from' => [$mail_from, $mail_from_name],
                'url' => $mail_url
            ];
            // Save config
            if ($config->save()) {
                unset($_SESSION['verification_code']);
                unset($_SESSION['code_expiry']);
                unset($_SESSION['is_auth']);
                echo "<div class='success'>Configuración guardada correctamente.</div>";
                // Redirect to main page or another page
                echo "<a href='main.php'>Volver al inicio</a>";
                exit;
            } else {
                echo "<div class='error'>Error al guardar la configuración.</div>";
            }
        } else {
            echo "<div class='error'>Código de verificación incorrecto.</div>";
        }
    } else {
        echo "<div class='error'>Código de verificación expirado o no solicitado.</div>";
    }
}

// Step 3: Verify code
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verification_code']) && is_numeric($_POST['verification_code']) && $config['admin']['email'] !== '') {
    if (isset($_SESSION['verification_code']) && time() < ($_SESSION['code_expiry'] ?? 0)) {
        if ((int)$_SESSION['verification_code'] == (int)$_POST['verification_code']) {
            $_SESSION['is_auth'] = true;
        } else {
            $msg = "Código de verificación incorrecto.";
        }
    } else {
        $msg = "Código de verificación expirado o no solicitado.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Cambiar Credenciales de Administrador</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <style>
        form {
            background: #fff;
            max-width: 480px;
            margin: 2rem auto 1.5rem auto;
            padding: 2rem 2rem 1.5rem 2rem;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        form h2 {
            margin-top: 0;
            margin-bottom: 1rem;
            font-size: 1.2rem;
            color: #0bb8a9;
        }

        label {
            font-weight: bold;
            margin-bottom: 0.2rem;
            color: #333;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"] {
            padding: 0.7rem;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.2s;
            width: 100%;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="number"]:focus {
            border-color: #11decdbc;
            outline: none;
        }

        button[type="submit"],
        button {
            background: #11decdbc;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 0.8rem;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 0.5rem;
        }

        button[type="submit"]:hover,
        button:hover {
            background: #0bb8a9;
        }

        .msg,
        .success,
        .error {
            text-align: center;
            margin: 1rem auto;
            padding: 0.8rem 1rem;
            border-radius: 8px;
            font-weight: bold;
            max-width: 480px;
        }

        .success {
            background: #d1f7ee;
            color: #0bb8a9;
            border: 1px solid #0bb8a9;
        }

        .error {
            background: #ffeaea;
            color: #d32f2f;
            border: 1px solid #d32f2f;
        }

        @media (max-width: 600px) {
            form {
                max-width: 98vw;
                padding: 1rem 0.5rem;
            }

            header {
                padding: 1rem;
            }
        }
    </style>
</head>

<body>
    <header>
        <h1>Panel de Administracion</h1>
        <p>Usuario: <?php echo htmlspecialchars($_SESSION['username']); ?></p>
        <nav>
            <ul>
                <li><a href="main.php">Inicio</a></li>
                <li><a href="change_password.php">Cambiar Contraseña</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
    </header>
    <h2>Cambiar Credenciales de Administrador</h2>
    <?php if (isset($msg)): ?>
        <div class="msg"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <?php if ((!isset($_SESSION['verification_code']) && !isset($_SESSION['is_auth'])) || time() > ($_SESSION['code_expiry'] ?? 0)): ?>
        <form method="post">
            <p>Se enviará un código de verificación al correo del administrador (<b><?= htmlspecialchars($adminEmail) ?></b>).</p>
            <button type="submit" name="request_code">Solicitar Código</button>
        </form>
    <?php elseif (isset($_SESSION['is_auth']) && $_SESSION['is_auth'] === true): ?>
        <form method="post">
            <h2>Base de Datos</h2>
            <label for="host">Host:</label>
            <input type="text" id="host" name="host" value="<?= htmlspecialchars($config['db']['host']) ?>" required>
            <label for="database">Base de Datos:</label>
            <input type="text" id="database" name="database" value="<?= htmlspecialchars($config['db']['database']) ?>" required>
            <label for="dbuser">Usuario:</label>
            <input type="text" id="dbuser" name="dbuser" value="<?= htmlspecialchars($config['db']['user']) ?>" required>
            <label for="dbpassword">Contraseña:</label>
            <input type="password" id="dbpassword" name="dbpassword" value="<?= htmlspecialchars($config['db']['password']) ?>" required>
            

            <h2>Credenciales de Administrador</h2>
            <label for="aduser">Usuario:</label>
            <input type="text" id="aduser" name="aduser" value="<?= htmlspecialchars($config['admin']['user']) ?>" required>
            <label for="adpassword">Contraseña:</label>
            <input type="password" id="adpassword" name="adpassword" required>
            <label for="ademail">Correo:</label>
            <input type="email" id="ademail" name="ademail" value="<?= htmlspecialchars($adminEmail) ?>" required>

            <h2>Configuracion de Correo</h2>
            <label for="mail_key">Llave de encriptacion para correos de verificacion:</label>
            <input type="text" id="mail_key" name="mail_key" value="<?= htmlspecialchars($config['mail']['enckey']) ?>" required>
            <br>
            <label for="mail_host">Host:</label>
            <input type="text" id="mail_host" name="mail_host" value="<?= htmlspecialchars($config['mail']['host']) ?>" required>
            <label for="mail_port">Puerto:</label>
            <input type="number" id="mail_port" name="mail_port" value="<?= htmlspecialchars($config['mail']['port']) ?>" required>
            <label for="mail_user">Usuario:</label>
            <input type="text" id="mail_user" name="mail_user" value="<?= htmlspecialchars($config['mail']['user']) ?>" required>
            <label for="mail_password">Contraseña:</label>
            <input type="password" id="mail_password" name="mail_password" value="<?= htmlspecialchars($config['mail']['password']) ?>" required>
            <label for="mail_from">De:</label>
            <input type="text" id="mail_from" name="mail_from" value="<?= htmlspecialchars($config['mail']['from'][0]) ?>" required>
            <label for="mail_from_name">Nombre:</label>
            <input type="text" id="mail_from_name" name="mail_from_name" value="<?= htmlspecialchars($config['mail']['from'][1]) ?>" required>
            <label for="mail_url">URL del servidor para los correos de verificacion:</label>
            <input type="text" id="mail_url" name="mail_url" value="<?= htmlspecialchars($config['mail']['url']) ?>" required>

            <label for="verification_code">Código de Verificación:</label>
            <?php if ($config['admin']['email'] === ''): ?>
                <input type="text" id="verification_code" name="verification_code" value="<?= htmlspecialchars($_SESSION['verification_code']) ?>" readonly>
            <?php else: ?>
                <input type="text" id="verification_code" name="verification_code" required>
            <?php endif; ?>

            <button type="submit" name="change_credentials">Guardar cambios</button>
        </form>
        <form method="post">
            <button type="submit" name="request_code">Reenviar Código</button>
        </form>
    <?php else: ?>
        <form method="post">
            <p><?php echo $_SESSION['verification_code'] ?></p>
            <label for="verification_code">Código de Verificación:</label>
            <input type="text" id="verification_code" name="verification_code" required>
            <button type="submit">Verificar Código</button>
        </form>
    <?php endif; ?>
</body>

</html>