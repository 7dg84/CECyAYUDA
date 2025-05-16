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