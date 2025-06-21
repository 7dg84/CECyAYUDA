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
    return "<button type='button' class='editButton' onclick=\"openActionsDialog('$folio', $verify)\">Modificar</button>";
}

// renderizar el boton para eliminar
function renderDeleteButton($folio)
{
    return "<button type='button' class='deleteButton' onclick=\"openDeleteDialog('$folio')\">Eliminar</button>";
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
    <style>
        table {
            width: 98%;
            margin: 1.5rem auto 2rem auto;
            border-collapse: collapse;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }

        th,
        td {
            padding: 0.7rem 0.5rem;
            text-align: center;
            border-bottom: 1px solid #e0e0e0;
        }

        th {
            background: #11decdbc;
            color: #fff;
            font-weight: bold;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover {
            background: #f0faff;
        }

        .evidencia-img {
            max-width: 80px;
            max-height: 80px;
            border-radius: 8px;
            cursor: pointer;
            transition: box-shadow 0.2s;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
        }

        .evidencia-img:hover {
            box-shadow: 0 4px 16px rgba(17, 222, 205, 0.15);
        }

        form#searchForm {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: center;
            align-items: flex-end;
            background: #fff;
            padding: 1.2rem 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
            margin: 1.5rem auto 2rem auto;
            width: 95%;
            max-width: 1200px;
        }

        form#searchForm label {
            font-weight: bold;
            color: #333;
            margin-right: 0.3rem;
        }

        form#searchForm input,
        form#searchForm select {
            padding: 0.5rem 0.7rem;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1rem;
        }

        form#searchForm button {
            background: #11decdbc;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 0.5rem 1.2rem;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
        }

        form#searchForm button:hover {
            background: #0bb8a9;
        }

        .buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 1.5rem;
        }

        dialog {
            border-radius: 12px;
            padding: 2rem;
            border: 2px solid #11decdbc;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.10);
            width: 100%;
            max-width: 600px;
            left: 70%;
            top: 50%;
            transform: translate(-50%, -50%);
        }

        dialog form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        dialog button {
            background: #11decdbc;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 0.5rem 1.2rem;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 0.5rem;
        }

        dialog button[type="button"] {
            background: #ccc;
            color: #333;
        }

        dialog img {
            max-width: 100%;
            max-height: 400px;
            border-radius: 8px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
        }

        dialog input,
        dialog select {
            padding: 0.5rem 0.7rem;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1rem;
        }

        @media (max-width: 900px) {

            form#searchForm,
            table {
                width: 100%;
                padding: 0.5rem;
            }

            header {
                padding: 1rem;
            }
        }

        @media (max-width: 600px) {
            form#searchForm {
                flex-direction: column;
                align-items: stretch;
                padding: 0.5rem;
            }

            table,
            th,
            td {
                font-size: 0.9rem;
            }

            header h1 {
                font-size: 1.3rem;
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
    <h1>Busca Registros</h1>

    <!-- Buscar -->
    <form method="get" action="" id="searchForm" onchange="typeSearch(this); saveConfigSearch(this)">
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
            <option value="Numtelefono">Teléfono</option>
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
            <option value="Numtelefono">Teléfono</option>
            <option value="Tipo">Tipo</option>
            <option value="Verified">Verificado</option>
            <option value="Status">Status</option>
            <option value="Created">Fecha de Creación</option>
        </select>
        <br>

        <button type="submit">Buscar</button>
        <button type="reset" onclick="resetSearchConfig(); searchFormlastValue = ''; window.location.href = 'main.php';">Limpiar</button>

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
        // Si no se ha definido $_SESSION['last_search'], inicializarla
        if (!isset($_SESSION['last_search']) || !is_array($_SESSION['last_search'])) {
            $_SESSION['last_search'] = [
            'field' => 'Default',
            'operator' => '',
            'value' => '',
            'num_records' => $max,
            'order' => 'Folio'
        ];
        }
        $last_search = $_SESSION['last_search'];

        // Buscar denuncias usando switch para evaluar los casos posibles
        switch (true) {
            // Caso: búsqueda avanzada con campo, operador, valor, num_records y order
            case ($_SERVER['REQUEST_METHOD'] == 'GET'
                && isset($_GET['field'], $_GET['operator'], $_GET['value'], $_GET['num_records'], $_GET['order'])
                && $_GET['field'] != 'Default'):
                $field = htmlspecialchars($_GET['field']);
                $operator = ($_GET['operator']);
                $value = ($_GET['value']);
                $num_records = max(1, min((int)$_GET['num_records'], 100));
                $order = ($_GET['order']);
                $row = $database->searchDenunciaBy($field, $operator, $value, $order, $num_records, 0);
                $_SESSION['last_search'] = [
                    'field' => $field,
                    'operator' => $operator,
                    'value' => $value,
                    'num_records' => $num_records,
                    'order' => $order
                ];
                break;

            // Caso: solo num_records y order (ordenar sin búsqueda)
            case ($_SERVER['REQUEST_METHOD'] == 'GET'
                && isset($_GET['num_records'], $_GET['order'])
                && (!isset($_GET['field']) || $_GET['field'] == 'Default')):
                $num_records = max(1, min((int)$_GET['num_records'], 100));
                $order = htmlspecialchars($_GET['order']);
                $row = $database->getDenunciasWithOrder($num_records, $order);
                $_SESSION['last_search'] = [
                    'num_records' => $num_records,
                    'order' => $order
                ];
                break;

            // Caso: solo num_records (sin búsqueda ni orden)
            case ($_SERVER['REQUEST_METHOD'] == 'GET'
                && isset($_GET['num_records'])
                && (!isset($_GET['order']) || empty($_GET['order']))
                && (!isset($_GET['field']) || $_GET['field'] == 'Default')):
                $num_records = max(1, min((int)$_GET['num_records'], 100));
                $row = $database->getDenuncias($num_records);
                $_SESSION['last_search'] = [
                    'num_records' => $num_records
                ];
                break;
            
            // Caso: ultima búsqueda guardada avanzada
            case ($last_search['field'] != 'Default' && isset($last_search['field'], $last_search['operator'], $last_search['value'], $last_search['num_records'], $last_search['order'])):
                $field = htmlspecialchars($last_search['field']);
                $operator = ($last_search['operator']);
                $value = ($last_search['value']);
                $num_records = max(1, min((int)$last_search['num_records'], 100));
                $order = ($last_search['order']);
                $row = $database->searchDenunciaBy($field, $operator, $value, $order, $num_records, 0);
                break;

            // Caso: última búsqueda guardada sin campo (solo num_records y order)
            case ($last_search['field'] === 'Default' && isset($last_search['num_records'], $last_search['order'])
                && (!isset($last_search['field']) || $last_search['field'] == 'Default')):
                $num_records = max(1, min((int)$last_search['num_records'], 100));
                $order = htmlspecialchars($last_search['order']);
                $row = $database->getDenunciasWithOrder($num_records, $order);
                break;
            // Caso por defecto: mostrar 20 registros
            default:
                $row = $database->getDenuncias(20);
                break;
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
                echo '<td><img src="data:image/png;base64,' . base64_encode($record['Evidencia']) . '" alt="Evidencia" class="evidencia-img" onclick="openImageDialog(\'' . $record['Folio'] . '\')"></td>';
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
        <input type="text" id="actionfolio" name="actionfolio" required>

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
        <button type="submit" class="deleteButton">Eliminar</button>
    </form>
</dialog>

<dialog id="imageDialog">
    <img id="imagePreview" src="" alt="Evidencia">
    <button type="button" onclick="closeImageDialog()">Cerrar</button>
</dialog>


</html>