<?php
session_start();

include_once 'config.php';

// Load current config
$config = new Config();
$adminEmail = $config['admin']['email'] ?? '';

// Helper: send verification code
function send_verification_code($email, $code) {
    $subject = "Código de verificación para cambio de credenciales";
    $message = "Tu código de verificación es: $code";
    $headers = "From: noreply@tu-dominio.com\r\n";
    // mail() puede requerir configuración en tu servidor
    return mail($email, $subject, $message, $headers);
}

// Step 1: Request verification code
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_code'])) {
    $code = rand(100000, 999999);
    $_SESSION['verification_code'] = $code;
    $_SESSION['code_expiry'] = time() + 600; // 10 min
    if (send_verification_code($adminEmail, $code)) {
        $msg = "Código enviado al correo del administrador.";
    } else {
        $msg = "Error al enviar el código. Contacta al administrador.";
    }
}

// Step 2: Change credentials
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_credentials'])) {
    $new_user = trim($_POST['new_user'] ?? '');
    $new_pass = trim($_POST['new_pass'] ?? '');
    $code = trim($_POST['code'] ?? '');

    if (!$new_user || !$new_pass || !$code) {
        $msg = "Todos los campos son obligatorios.";
    } elseif (!isset($_SESSION['verification_code']) || !isset($_SESSION['code_expiry']) || time() > $_SESSION['code_expiry']) {
        $msg = "El código ha expirado. Solicita uno nuevo.";
    } elseif ($code != $_SESSION['verification_code']) {
        $msg = "Código de verificación incorrecto.";
    } else {
        // Save new credentials
        $config['admin_user'] = $new_user;
        $config['admin_pass'] = password_hash($new_pass, PASSWORD_DEFAULT);
        file_put_contents($configFile, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        unset($_SESSION['verification_code'], $_SESSION['code_expiry']);
        $msg = "Credenciales actualizadas correctamente.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cambiar Credenciales de Administrador</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2em; }
        form { max-width: 400px; margin: auto; }
        input[type=text], input[type=password], input[type=number] { width: 100%; padding: 8px; margin: 6px 0; }
        button { padding: 8px 16px; }
        .msg { color: #006600; margin-bottom: 1em; }
        .error { color: #cc0000; }
    </style>
</head>
<body>
    <h2>Cambiar Credenciales de Administrador</h2>
    <?php if (isset($msg)): ?>
        <div class="msg"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <?php if (!isset($_SESSION['verification_code']) || time() > ($_SESSION['code_expiry'] ?? 0)): ?>
        <form method="post">
            <p>Se enviará un código de verificación al correo del administrador (<b><?= htmlspecialchars($adminEmail) ?></b>).</p>
            <button type="submit" name="request_code">Solicitar Código</button>
        </form>
    <?php else: ?>
        <form method="post">
            <label>Nuevo usuario:</label>
            <input type="text" name="new_user" required>
            <label>Nueva contraseña:</label>
            <input type="password" name="new_pass" required>
            <label>Código de verificación:</label>
            <input type="number" name="code" required>
            <button type="submit" name="change_credentials">Cambiar Credenciales</button>
        </form>
        <form method="post" style="margin-top:1em;">
            <button type="submit" name="request_code">Reenviar Código</button>
        </form>
    <?php endif; ?>
</body>
</html>