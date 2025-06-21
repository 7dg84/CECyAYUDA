<?php
include_once 'config.php';

session_start();
$config = new Config();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    

    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        header("Location: main.php");
        exit();
    }


    if (
    $username === $config['admin']['user'] &&
    password_verify($password, $config['admin']['passwordhash'])
) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;

        header("Location: main.php");
        exit();
    } else {
        $error = "Invalid username or password." . password_verify($password, $config['admin']['passwordhash'])."\n";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        /* Estilos para el formulario de inicio de sesión */
body {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background: linear-gradient(135deg, #11decdbc 0%, #f4f4f4 100%);
}

.login-container {
    background: #fff;
    padding: 2.5rem 2rem;
    border-radius: 18px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.08);
    min-width: 320px;
    max-width: 90vw;
    margin-top: 2rem;
}

.login-container h1 {
    text-align: center;
    margin-bottom: 1.5rem;
    color: #11decdbc;
    font-size: 2rem;
}

.login-container form {
    display: flex;
    flex-direction: column;
    gap: 1.2rem;
}

.login-container label {
    font-weight: bold;
    margin-bottom: 0.3rem;
    color: #333;
}

.login-container input[type="text"],
.login-container input[type="password"] {
    padding: 0.7rem;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.2s;
}

.login-container input[type="text"]:focus,
.login-container input[type="password"]:focus {
    border-color: #11decdbc;
    outline: none;
}

.login-container button[type="submit"] {
    background: #11decdbc;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 0.8rem;
    font-size: 1.1rem;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.2s;
}

.login-container button[type="submit"]:hover {
    background: #0bb8a9;
}

.login-container p {
    text-align: center;
    margin-top: 1rem;
    color: #d32f2f;
    font-weight: bold;
}
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <br>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>