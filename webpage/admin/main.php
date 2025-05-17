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
$database = new Denuncia();

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
function statusValue($value) {
  $values = [
    0 => "En Proceso",
    1 => "Resuelto",
    2 => "No Resuelto"
  ];
  return $values[$value] ?? "Desconocido";
}

// renderizar boton para modifocar
function renderActionsButton($folio) {
    return "<button type='button' onclick=\"openActionsDialog('$folio')\">Modificar</button>";
}

// renderizar el boton para eliminar
function renderDeleteButton($folio) {
    return "<button type='button' onclick=\"openDeleteDialog('$folio')\">Eliminar</button>";
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
    <h1>Bienvenido al Panel de Administracion</h1>
    <p>Has iniciado sesión correctamente.</p>
    <p>Usuario: <?php echo htmlspecialchars($_SESSION['username']); ?></p>
    <p>Cambiar contraseña: <a href="change_password.php">Cambiar Contraseña</a></p>
    <a href="logout.php">Cerrar Sesión</a>
    <!-- Elije cuantos mostrar -->
    <form method="post" action="">
        <label for="num_records">Número de registros a mostrar:</label>
        <input type="number" id="num_records" name="num_records" min="1" max="100" value="20">
        <button type="submit">Mostrar</button>
    </form>
    <!-- Buscar -->
    <form method="get" action="" id="searchForm" onchange="typeSearch(this)">
        <label for="field">Buscar:</label>
        <select name="field" id="field">
            <option value="" selected>Seleccionar campo</option>
            <option value="Folio">Folio</option>
            <option value="Descripcion">Hechos</option>
            <option value="Fecha">Fecha</option>
            <option value="Hora">Hora</option>
            <option value="Ubicacion">Ubicación</option>
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
            <th>Ubicación</th>
            <th>Nombre</th>
            <th>CURP</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>Tipo</th>
            <th>Verificado</th>
            <th>Status</th>
            <th>Acciones</th>
            <th>Eliminar</th>
        </tr>
        <?php
        $max = isset($_POST['num_records']) ? (int)$_POST['num_records'] : 20;
        // Buscar denuncias
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['field'], $_GET['operator'], $_GET['value'])) {
            $field = $_GET['field'];
            $operator = $_GET['operator'];
            $value = $_GET['value'];

            // Validar y sanitizar los datos
            $field = htmlspecialchars($field);
            $operator = htmlspecialchars($operator);
            $value = htmlspecialchars($value);

            // Buscar la denuncia
            $row = $database->searchDenunciaBy($field, $operator, $value);
        } else {
            // Obtener todas las denuncias si no se busca nada
            $row = $database->getDenuncias($max);
        }

        // Mostrar los registros en la tabla
        if ($row->num_rows > 0) {
            
            while (($record = $row->fetch_assoc()) ) {
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
            echo "<td>" . statusValue($record['Status']) . "</td>";
            echo "<td>". renderActionsButton($record['Folio']) . "</td>";
            echo "<td>". renderDeleteButton($record['Folio']) . "</td>";
            echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='12'>No hay registros disponibles</td></tr>";
        }

        // Cerrar la conexión a la base de datos
        $database->closeConnection();
        ?>
    </table>
    <script>
        let searchFormlastValue = "";

const searchConfig = {
    "Folio": {
        operator: [
            { value: "=", text: "Igual" },
            { value: "LIKE", text: "Contenga" }
        ],
        value: { type: "text", placeholder: "Folio" }
    },
    "Descripcion": {
        operator: [
            { value: "=", text: "Igual" },
            { value: "LIKE", text: "Contenga" }
        ],
        value: { type: "text", placeholder: "Hechos" }
    },
    "Fecha": {
        operator: [
            { value: "=", text: "Igual" },
            { value: "<>", text: "Diferente" },
            { value: ">", text: "Mayor" },
            { value: "<", text: "Menor" },
            { value: ">=", text: "Mayor o Igual" },
            { value: "<=", text: "Menor o Igual" }
        ],
        value: { type: "date" }
    },
    "Hora": {
        operator: [
            { value: "=", text: "Igual" },
            { value: "<>", text: "Diferente" },
            { value: ">", text: "Mayor" },
            { value: "<", text: "Menor" },
            { value: ">=", text: "Mayor o Igual" },
            { value: "<=", text: "Menor o Igual" }
        ],
        value: { type: "time" }
    },
    "Ubicacion": {
        operator: [
            { value: "=", text: "Igual" },
            { value: "LIKE", text: "Contenga" }
        ],
        value: { type: "text", placeholder: "Ubicacion" }
    },
    "Nombre": {
        operator: [
            { value: "=", text: "Igual" },
            { value: "LIKE", text: "Contenga" }
        ],
        value: { type: "text", placeholder: "Nombre" }
    },
    "CURP": {
        operator: [
            { value: "=", text: "Igual" },
            { value: "LIKE", text: "Contenga" }
        ],
        value: { type: "text", placeholder: "CURP", maxlength: 18 }
    },
    "Correo": {
        operator: [
            { value: "=", text: "Igual" },
            { value: "LIKE", text: "Contenga" }
        ],
        value: { type: "text", placeholder: "Correo" }
    },
    "Numtelefono": {
        operator: [
            { value: "=", text: "Igual" },
            { value: "LIKE", text: "Contenga" }
        ],
        value: { type: "tel", placeholder: "Telefono", maxlength: 10 }
    },
    "Tipo": {
        operator: [
            { value: "=", text: "Igual" },
            { value: "<>", text: "Diferente" }
        ],
        value: {
            type: "select",
            options: [
                { value: "", text: "Selecciona un tipo de violencia", disabled: true, selected: true },
                { value: "Genero", text: "Violencia de Genero" },
                { value: "Familiar", text: "Violencia Familiar" },
                { value: "Psicologica", text: "Violencia Psicologica" },
                { value: "Sexual", text: "Violencia Sexual" },
                { value: "Economica", text: "Violencia Economica" },
                { value: "Patrimonial", text: "Violencia Patrimonial" },
                { value: "Cibernetica", text: "Violencia Cibernetica" }
            ]
        }
    },
    "Verified": {
        operator: [
            { value: "=", text: "Igual" },
            { value: "<>", text: "Diferente" }
        ],
        value: {
            type: "select",
            options: [
                { value: "", text: "Selecciona verificación", disabled: true, selected: true },
                { value: "0", text: "No Verificado" },
                { value: "1", text: "Verificado" }
            ]
        }
    },
    "Status": {
        operator: [
            { value: "=", text: "Igual" },
            { value: "<>", text: "Diferente" }
        ],
        value: {
            type: "select",
            options: [
                { value: "", text: "Selecciona un estado", disabled: true, selected: true },
                { value: "0", text: "En Proceso" },
                { value: "1", text: "Resuelto" },
                { value: "2", text: "No Resuelto" }
            ]
        }
    }
};

function typeSearch(form) {
    const field = form.field.value;
    if (searchFormlastValue === field) return;
    searchFormlastValue = field;

    const config = searchConfig[field];
    if (!config) {
        operatorcontainer.innerHTML = "";
        valuecontainer.innerHTML = "";
        return;
    }

    // Render operator select
    operatorcontainer.innerHTML = `
        <label for="operator">Operador:</label>
        <select name="operator" id="operator">
            ${config.operator.map(op => `<option value="${op.value}">${op.text}</option>`).join('')}
        </select>
    `;

    // Render value input/select
    if (config.value.type === "select") {
        valuecontainer.innerHTML = `
            <label for="value">Valor:</label>
            <select name="value" id="value">
                ${config.value.options.map(opt =>
                    `<option value="${opt.value}"${opt.disabled ? " disabled" : ""}${opt.selected ? " selected" : ""}>${opt.text}</option>`
                ).join('')}
            </select>
        `;
    } else {
        let attrs = `type="${config.value.type}" name="value" id="value"`;
        if (config.value.placeholder) attrs += ` placeholder="${config.value.placeholder}"`;
        if (config.value.maxlength) attrs += ` maxlength="${config.value.maxlength}"`;
        valuecontainer.innerHTML = `
            <label for="value">Valor:</label>
            <input ${attrs}>
        `;
    }
}

function openActionsDialog(folio) {
    const dialog = window.actions;
    const folioInput = document.getElementById('actionfolio');
    folioInput.value = folio;
    dialog.showModal();
}
function closeActionsDialog() {
    const dialog = window.actions;
    dialog.close();
}

function openDeleteDialog(folio) {
    const dialog = window.delete;
    const folioInput = document.getElementById('deletefolio');
    folioInput.value = folio;
    dialog.showModal();
}
function closeDeleteDialog() {
    const dialog = window.actions;
    dialog.close();
}
    </script>
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
</html>