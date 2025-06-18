<?php
include_once 'db.php';

// File: /e:/CECyTEM/ZTU 405/Programacion/PEC/admin/index.php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Conectar a la base de datos
try {
    $database = new Denuncia();
} catch (Exception $e) {
    die("Error al conectar a la base de datos:
    <a href='logout.php'>Cerrar Sesion </a><br>
    <a href='change_password.php'>cambiar Contraseña</a><br> " . htmlspecialchars($e->getMessage()));
}

// Modificar la denuncia
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Si se envía el formulario para modificar
    if (isset($_POST['actionfolio'])) {
        $folio = $_POST['actionfolio'];
        $status = $_POST['status'];
        $verify = isset($_POST['verify']) ? 1 : 0;

        // Modificar la denuncia
        $database->updateDenunciaBy($folio, $status, $verify);
    }
    // Si se envía el formulario para eliminar
    if (isset($_POST['deletefolio'])) {
        $folio = $_POST['deletefolio'];

        // Eliminar la denuncia
        $database->deleteDenuncia($folio);
    }
}



// obtener valor el Status
function statusValue($value)
{
    $values = [
        0 => "En Proceso",
        1 => "Resuelto",
        2 => "No Resuelto"
    ];
    return $values[$value] ?? "Desconocido";
}

// renderizar boton para modifocar
function renderActionsButton($folio, $verify)
{
    return "<button type='button' onclick=\"openActionsDialog('$folio', $verify)\">Modificar</button>";
}

// renderizar el boton para eliminar
function renderDeleteButton($folio)
{
    return "<button type='button' onclick=\"openDeleteDialog('$folio')\">Eliminar</button>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal</title>
    <script>
        // No mostar el envio de formulario
        history.replaceState(null, null, location.pathname);
    </script>
    <link rel="stylesheet" href="styles.css">
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
    <h1>Bienvenido al Panel de Administracion</h1>
    <p>Has iniciado sesión correctamente.</p>

    <!-- Buscar -->
    <form method="get" action="" id="searchForm" onchange="typeSearch(this)">
        <label for="field">Buscar:</label>
        <select name="field" id="field">
            <option value="Default" selected>Seleccionar campo</option>
            <option value="Folio">Folio</option>
            <option value="Descripcion">Hechos</option>
            <option value="Fecha">Fecha</option>
            <option value="Hora">Hora</option>
            <option value="Estado">Estado</option>
            <option value="Municipio">Municipio</option>
            <option value="Colonia">Colonia</option>
            <option value="Calle">Calle</option>
            <option value="Nombre">Nombre</option>
            <option value="CURP">CURP</option>
            <option value="Correo">Correo</option>
            <option value="Telefono">Teléfono</option>
            <option value="Tipo">Tipo</option>
            <option value="Verified">Verificado</option>
            <option value="Status">Status</option>
        </select>

        <div id="operatorcontainer"></div>
        <div id="valuecontainer"></div>

        <label for="num_records">Número de registros a mostrar:</label>
        <input type="number" id="num_records" name="num_records" min="1" max="100" value="<?php echo isset($_GET['num_records']) ? htmlspecialchars($_GET['num_records']) : 20; ?>">

        <label for="order">Ordenar por:</label>
        <select name="order" id="order">
            <option value="Folio" selected>Folio</option>
            <option value="Fecha">Fecha</option>
            <option value="Hora">Hora</option>
            <option value="Estado">Estado</option>
            <option value="Municipio">Municipio</option>
            <option value="Colonia">Colonia</option>
            <option value="Calle">Calle</option>
            <option value="Nombre">Nombre</option>
            <option value="CURP">CURP</option>
            <option value="Correo">Correo</option>
            <option value="Telefono">Teléfono</option>
            <option value="Tipo">Tipo</option>
            <option value="Verified">Verificado</option>
            <option value="Status">Status</option>
            <option value="Created">Fecha de Creación</option>
        </select>

        <button type="submit">Buscar</button>
        <button type="reset" onclick="searchFormlastValue = ''; window.location.href = 'main.php';">Limpiar</button>

    </form>
    <!-- Tabala con los registros -->
    <h2>Registros de Denuncias</h2>
    <table border="1">
        <tr>
            <th>Folio</th>
            <th>Hechos</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Estado</th>
            <th>Municipio</th>
            <th>Colonia</th>
            <th>Calle</th>
            <th>Nombre</th>
            <th>CURP</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>Tipo</th>
            <th>Verificado</th>
            <th>Status</th>
            <th>Fecha de Creación</th>
            <th>Imagen</th>
            <th>Acciones</th>
            <th>Eliminar</th>
        </tr>
        <?php
        $max = isset($_GET['num_records']) ? (int)$_GET['num_records'] : 20;

        // Buscar denuncias
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['field'], $_GET['operator'], $_GET['value'],$_GET['num_records'],$_GET['order']) && $_GET['field'] != 'Default') {
            $field = $_GET['field'];
            $operator = $_GET['operator'];
            $value = $_GET['value'];
            $num_records = (int)$_GET['num_records'];
            $order = $_GET['order'];

            // Validar y sanitizar los datos
            $field = htmlspecialchars($field);
            $operator = htmlspecialchars($operator);
            $value = htmlspecialchars($value);
            $num_records = max(1, min($num_records, 100)); // Limitar a un rango de 1 a 100
            $order = htmlspecialchars($order);           

            // Buscar la denuncia
            $row = $database->searchDenunciaBy($field, $operator, $value,$order, $max, 0);
        } elseif($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['num_records'])) {
            // Obtener todas las denuncias si no se busca nada
            $row = $database->getDenuncias($max);
        } else {
            // Obtener todas las denuncias por defecto
            $row = $database->getDenuncias(20);
        }

        // Mostrar los registros en la tabla
        if ($row->num_rows > 0) {

            while (($record = $row->fetch_assoc())) {
                // Mostrar los datos de la fila
                echo "<tr>";
                echo "<td>" . htmlspecialchars($record['Folio']) . "</td>";
                echo "<td>" . htmlspecialchars($record['Descripcion']) . "</td>";
                echo "<td>" . htmlspecialchars($record['Fecha']) . "</td>";
                echo "<td>" . htmlspecialchars($record['Hora']) . "</td>";
                echo "<td>" . htmlspecialchars($record['Estado']) . "</td>";
                echo "<td>" . htmlspecialchars($record['Municipio']) . "</td>";
                echo "<td>" . htmlspecialchars($record['Colonia']) . "</td>";
                echo "<td>" . htmlspecialchars($record['Calle']) . "</td>";
                echo "<td>" . htmlspecialchars($record['Nombre']) . "</td>";
                echo "<td>" . htmlspecialchars($record['CURP']) . "</td>";
                echo "<td>" . htmlspecialchars($record['Correo']) . "</td>";
                echo "<td>" . htmlspecialchars($record['Numtelefono']) . "</td>";
                echo "<td>" . htmlspecialchars($record['Tipo']) . "</td>";
                echo "<td>" . htmlspecialchars($record['Verified']) . "</td>";
                echo "<td>" . statusValue($record['Status']) . "</td>";
                echo "<td>" . htmlspecialchars($record['Created']) . "</td>";
                echo '<td><img src="data:image/png;base64,'.base64_encode($record['Evidencia']).'" alt="Evidencia" class="evidencia-img" onclick="openImageDialog(\''.$record['Folio'].'\')"></td>';
                echo "<td>" . renderActionsButton($record['Folio'], $record['Verified']) . "</td>";
                echo "<td>" . renderDeleteButton($record['Folio']) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='12'>No hay registros disponibles</td></tr>";
        }

        // Cerrar la conexión a la base de datos
        $database->closeConnection();
        ?>
    </table>
    <script src="admin.js"></script>
</body>

<dialog id="actions">
    <form method="post" action="" id="actionForm">
        <label for="actionfolio">Folio:</label>
        <input type="text" id="actionfolio" name="actionfolio" required disenabled>

        <label for=status>Estado:</label>
        <select id="status" name="status" required>
            <option value="0">En Proceso</option>
            <option value="1">Resuelto</option>
            <option value="2">No Resuelto</option>
        </select>

        <label for="verify">Verificar:</label>
        <input type="checkbox" id="verify" name="verify">

        <button type="button" onclick="closeActionsDialog()">Cancelar</button>
        <button type="submit">Ejecutar</button>
    </form>
</dialog>

<dialog id="delete">
    <form method="post" action="" id="deleteForm">
        <label for="deletefolio">Folio:</label>
        <input type="text" id="deletefolio" name="deletefolio" required disenabled>

        <p>¿Estás seguro de que deseas eliminar esta denuncia?</p>

        <button type="button" onclick="closeDeleteDialog()">Cancelar</button>
        <button type="submit">Eliminar</button>
    </form>
</dialog>

<dialog id="imageDialog">
    <img id="imagePreview" src="" alt="Evidencia">
    <button type="button" onclick="closeImageDialog()">Cerrar</button>
</dialog>


</html>