<?php
include_once 'db.php';

// File: /e:/CECyTEM/ZTU 405/Programacion/PEC/admin/login.php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    

    if ($_SESSION['loggedin'] === true) {
        header("Location: main.php");
        exit();
    }

    if ($username === $validUsername && password_hash($password, PASSWORD_DEFAULT) === $validPassword) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;

        header("Location: main.php");
        exit();
    } else {
        $error = "Invalid username or password." . password_hash($password, PASSWORD_DEFAULT)."\n";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
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
</body>
</html>