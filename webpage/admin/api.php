<?php
session_start();
header('Content-Type: application/json');
// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Unauthorized access. Please log in.',
        'success' => false,
        'value' => null,
    ]);
    exit;
}

if (!isset($_GET['action'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid action specified.',
        'success' => false,
        'value' => null
    ]);
    exit;
}

include_once 'db.php';

// Si se consulta una imagen
if ($_GET['action'] === 'getImage' && isset($_GET['folio']) && !empty($_GET['folio']) && preg_match("/^[a-f0-9]{64}$/", $_GET['folio'])) {
    $db = new Denuncia();
    $img = $db->getDenunciaImage($_GET['folio']);
    if ($img){
        echo json_encode([
            'status' => 'success',
            'message' => 'Image retrieved successfully.',
            'success' => true,
            'value' => base64_encode($img)
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Image not found for the specified folio.',
            'success' => false,
            'value' => null
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request. Folio not specified.',
        'success' => false,
        'value' => null
    ]);
}
?>