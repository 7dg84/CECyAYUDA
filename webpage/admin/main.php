<?php
include_once 'db.php';

// File: /e:/CECyTEM/ZTU 405/Programacion/PEC/admin/index.php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal</title>
</head>
<body>
    <h1>Bienvenido a la Página Principal</h1>
    <p>Has iniciado sesión correctamente.</p>
    <p>Usuario: <?php echo htmlspecialchars($_SESSION['username']); ?></p>
    <!-- Tabala con los registros -->
    <table border="1">
        <tr>
            <th>Folio</th>
            <th>Hechos</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Ubicación</th>
            <th>Nombre</th>
            <th>CURP</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>Tipo</th>
            <th>Verificado</th>
            <th>Status</th>
        </tr>
        <?php
        // Conectar a la base de datos
        $database = new Database();

        // Obtener los registros de la base de datos
        $row = $database->getAllDenuncias();

        // Mostrar los registros en la tabla
        if ($row->num_rows > 0) {
            while ($record = $row->fetch_assoc()) {
            // Mostrar los datos de la fila
            echo "<tr>";
            echo "<td>" . htmlspecialchars($record['Folio']) . "</td>";
            echo "<td>" . htmlspecialchars($record['Descripcion']) . "</td>";
            echo "<td>" . htmlspecialchars($record['Fecha']) . "</td>";
            echo "<td>" . htmlspecialchars($record['Hora']) . "</td>";
            echo "<td>" . htmlspecialchars($record['Ubicacion']) . "</td>";
            echo "<td>" . htmlspecialchars($record['Nombre']) . "</td>";
            echo "<td>" . htmlspecialchars($record['CURP']) . "</td>";
            echo "<td>" . htmlspecialchars($record['Correo']) . "</td>";
            echo "<td>" . htmlspecialchars($record['Numtelefono']) . "</td>";
            echo "<td>" . htmlspecialchars($record['Tipo']) . "</td>";
            echo "<td>" . htmlspecialchars($record['Verified']) . "</td>";
            echo "<td>" . htmlspecialchars($record['Status']) . "</td>";
            echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='12'>No hay registros disponibles</td></tr>";
        }

        // Cerrar la conexión a la base de datos
        $database->closeConnection();
        ?>
    </table>
    <a href="logout.php">Cerrar Sesión</a>
</body>
</html>