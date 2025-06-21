<?php
// File: /e:/CECyTEM/ZTU 405/Programacion/PEC/index.php
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $_SESSION['loggedin'] = false; // Log out the user
    unset($_SESSION['username']); // Remove the username from the session
    session_destroy(); // Destroy the session
    header("Location: login.php");
    exit();
}
?>