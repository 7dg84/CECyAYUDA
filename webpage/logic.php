<?php
// Incluir la clase de conexión a la base de datos
include_once 'admin/db.php';
// Incluir la clase de verificación de token
include_once 'admin/verify.php';

/*
    
*/

// Enviar el correo de verificacion
function sendVerificationEmail()
{
    global $errorMsg, $folio;
    try {
        // Enviar el correo de verificación
        sendEmail($_POST['nombre'], $folio, $_POST['curp'], $_POST['correo']);
        return true;
    } catch (Exception $e) {
        $errorMsg = "Error al enviar el correo de verificación: " . htmlspecialchars($e->getMessage());
        return false;
    }
}


// Guardar el reporte en la base de datos
function saveReport($folio, $hechos, $fecha, $hora, $cp, $estado, $municipio, $colonia, $calle, $nombre, $curp, $correo, $telefono, $tipo, $file)
{
    global $errorMsg;
    try {
        // Crear una instancia de la clase Database
        $database = new Denuncia();
        // Insertar la denuncia en la base de datos
        $database->insertDenuncia($folio, $hechos, $fecha, $hora, $cp, $estado, strtolower($municipio), strtolower($colonia), strtolower($calle), $nombre, $curp, $correo, $telefono, $tipo, $file);
        // Cerrar la conexión a la base de datos
        $database->closeConnection();
        return true;
    } catch (Exception $e) {
        $errorMsg = htmlspecialchars($e->getMessage()) . '\n' . $e->getMessage();
        return false;
    }
}

// Funcion para validar los datos del formulario
function validate($field, $pattern = null)
{
    if (empty($field)) {
        return false;
    }
    if ($pattern != null) {
        return preg_match($pattern, $field);
    }
}

// Funcion para validar los datos del formulario
function validateData($requiereFile = true)
{
    global $errorMsg;
    // Verificar metodo de envio
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $errorMsg = "Método de envío no permitido.";
        return false;
    }

    // Validar los campos del formulario
    if (!validate($_POST['hechos'], "/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑüÜ\s.,]+$/")) {
        $errorMsg = ("Campo 'Hechos' inválido.");
        return false;
    }
    if (!validate($_POST['fecha'], "/^\d{4}-\d{2}-\d{2}$/")) {
        $errorMsg = ("Campo 'Fecha' inválido.");
        return false;
    }
    if (date('Y-m-d', strtotime($_POST['fecha'])) > date('Y-m-d')) {
        $errorMsg = ("La fecha no puede ser posterior a la actual.");
        return false;
    }
    if (!validate($_POST['hora'], "/^\d{2}:\d{2}$/")) {
        $errorMsg = ("Campo 'Hora' inválido.") . $_POST['hora'];
        return false;
    }
    if(!validate($_POST['cp'], "/^\d{5}$/")) {
        $errorMsg = "Campo 'CP' inválido.";
        return false;
    }
    $estados = [
        'Aguascalientes',
        'Baja California',
        'Baja California Sur',
        'Campeche',
        'Chiapas',
        'Chihuahua',
        'CDMX',
        'Coahuila',
        'Colima',
        'Durango',
        'México',
        'Guanajuato',
        'Guerrero',
        'Hidalgo',
        'Jalisco',
        'Michoacán',
        'Morelos',
        'Nayarit',
        'Nuevo León',
        'Oaxaca',
        'Puebla',
        'Querétaro',
        'Quintana Roo',
        'San Luis Potosí',
        'Sinaloa',
        'Sonora',
        'Tabasco',
        'Tamaulipas',
        'Tlaxcala',
        'Veracruz',
        'Yucatán',
        'Zacatecas'
    ];
    if (!in_array($_POST['estado'], $estados)) {
        $errorMsg = ("Campo 'Estado' inválido.");
        return false;
    }
    if (!validate($_POST['municipio'], "/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑüÜ\s,.\-]+$/")) {
        $errorMsg = ("Campo 'Municipio' inválido.");
        return false;
    }
    if (!validate($_POST['colonia'], "/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑüÜ\s,.\-]+$/")) {
        $errorMsg = ("Campo 'Colonia' inválido.");
        return false;
    }
    if (!validate($_POST['calle'], "/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑüÜ\s,.\-]+$/")) {
        $errorMsg = ("Campo 'Calle' inválido.");
        return false;
    }
    if (!validate($_POST['nombre'], "/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/")) {
        $errorMsg = ("Campo 'Nombre' inválido.");
        return false;
    }
    if (!validate($_POST['curp'], "/^[A-Z]{4}[0-9]{6}[\w]{8}$/")) {
        $errorMsg = ("Campo 'CURP' inválido.");
        return false;
    }
    if (!validate($_POST['correo'], "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/")) {
        $errorMsg = ("Campo 'Correo' inválido.");
        return false;
    }
    if (!validate($_POST['telefono'], "/^\d{10}$/")) {
        $errorMsg = ("Campo 'Teléfono' inválido.");
        return false;
    }
    if (!validate($_POST['tipo'], "/^[a-zA-Z\s]+$/")) {
        $errorMsg = ("Campo 'Tipo de Reporte' inválido.");
        return false;
    }

    // Validar el campo de archivo solo si es requerido
    $file = $_FILES['evidencia'] ?? null;
    if ($requiereFile) {
        if (empty($file['name'])) {
            $errorMsg = ("Campo 'Evidencia' no puede estar vacío.");
            return false;
        }
    }

    // Si se proporcionó archivo, valida tipo y tamaño
    if ($file && $file['error'] == 0 && !empty($file['name'])) {
        $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
        if (!in_array($file['type'], $allowedTypes)) {
            $errorMsg = ("Tipo de archivo no permitido. Solo se permiten imágenes JPEG, PNG y archivos PDF.");
            return false;
        }
        $maxFileSize = 2 * 1024 * 1024; // 2 MB
        if ($file['size'] > $maxFileSize) {
            $errorMsg = ("El tamaño del archivo excede el límite permitido de 2 MB.");
            return false;
        }
    }

    // Si todos los campos son válidos, retornar true
    return true;
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

// Buscar el reporte por folio
function search($folio)
{
    global $errorMsg;
    $row = null;
    try {
        $database = new Denuncia();
        $stmt = $database->searchDenuncia($folio);

        if ($stmt->num_rows > 0) {
            $row = $stmt->fetch_assoc();
        } else {
            $errorMsg = "No se encontraron resultados para el folio proporcionado.";
        }
        $database->closeConnection();
    } catch (Exception $e) {
        $errorMsg = "Error al buscar el reporte: " . htmlspecialchars($e->getMessage());
    }
    return $row;
}

// Verificar si se ha enviado el formulario
function checkFolioGet()
{
    return isset($_GET['folio']) && !empty($_GET['folio']);
}

function checkFolioPost()
{
    return isset($_POST['folio']) && !empty($_POST['folio']);
}

// Validar el folio
function validateFolio($folio)
{
    $regex = "/^[a-f0-9]{64}$/";
    return preg_match($regex, $folio);
}

// Validar el token de verificación
function validateToken($token)
{
    global $errorMsg;
    // Verificar si el token no está vacío
    if (empty($token)) {
        // Mostrar mensaje de error si el token está vacío
        $errorMsg = "No se ha enviado el token.";
        return false; // El token está vacío   
    }
    // Validar el formato del token (Base64)
    if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $token)) {
        // Mostrar mensaje de error si el formato del token no es válido
        $errorMsg = "El formato del token es inválido.";
        return false; // El formato del token es inválido
    }
    return true; // El formato del token es válido
}

// Eliminar un reporte
function deleteReport($folio)
{
    global $errorMsg;
    try {
        // Crear una instancia de la clase Database
        $database = new Denuncia();
        // Eliminar la denuncia en la base de datos
        $database->deleteDenuncia($folio);
        // Cerrar la conexión a la base de datos
        $database->closeConnection();
        return true;
    } catch (Exception $e) {
        $errorMsg = "Error al eliminar el folio" . htmlspecialchars($e->getMessage());
        return false;
    }
}

// Funcion para actualizar el reporte
function updateReport($folio, $hechos, $fecha, $hora, $cp, $estado, $municipio, $colonia, $calle, $nombre, $curp, $correo, $telefono, $tipo, $file)
{
    global $errorMsg;
    try {
        // Crear una instancia de la clase Database
        $database = new Denuncia();
        // Verificar si el email de la denuncia esta verificado
        if (!$database->isEmailVerified($folio)) {
            // Si el email no se ha verificado, no se puede actualizar la denuncia
            $errorMsg = "El correo electrónico no ha sido verificado. No se puede actualizar la denuncia.";
            return false;
        }
        // Actualizar la denuncia en la base de datos
        if (!empty($file['name'])) {
            // Si se proporciona un archivo, actualizar la denuncia con el archivo
            $database->updateDenunciaWithFile($folio, $hechos, $fecha, $hora, $cp, $estado, $municipio, $colonia, $calle, $nombre, $curp, $correo, $telefono, $tipo, file_get_contents($file['tmp_name']));
        } else {
            // Si no se proporciona un archivo, actualizar la denuncia sin el archivo
            $database->updateDenunciaWithoutFile($folio, $hechos, $fecha, $hora, $cp, $estado, $municipio, $colonia, $calle, $nombre, $curp, $correo, $telefono, $tipo);
        }
        // Cerrar la conexión a la base de datos
        $database->closeConnection();
        return true;
    } catch (Exception $e) {
        $errorMsg = "Error\n" . htmlspecialchars($e->getMessage());
        return false;
    }
}


// Verificar si se han proporcionado los datos de recuperación
function ckeckDataRecovery()
{
    // Verificar si se han enviado los datos del formulario
    return $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre'], $_POST['curp'], $_POST['correo'], $_POST['telefono']);
}

function validateRecoveryData()
{
    global $errorMsg;
    // Verificar metodo de envio
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $errorMsg = "Método de envío no permitido.";
        return false;
    }
    // Validar los campos del formulario de recuperación
    if (!validate($_POST['nombre'], "/^[a-zA-Z\s]+$/")) {
        $errorMsg = ("Campo 'Nombre' inválido.");
        return false;
    }
    if (!validate($_POST['curp'], "/^[A-Z]{4}[0-9]{6}[\w]{8}$/")) {
        $errorMsg = ("Campo 'CURP' inválido.");
        return false;
    }
    if (!validate($_POST['correo'], "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/")) {
        $errorMsg = ("Campo 'Correo' inválido.");
        return false;
    }
    if (!validate($_POST['telefono'], "/^\d{10}$/")) {
        $errorMsg = ("Campo 'Teléfono' inválido.");
        return false;
    }
    return true;
}

// Buscar el folio en la base de datos
function searchReport()
{
    global $errorMsg;
    try {
        // Crear una instancia de la clase Database
        $database = new Denuncia();
        // Buscar el folio en la base de datos
        $result = $database->searchFolio($_POST['nombre'], $_POST['curp'], $_POST['correo'], $_POST['telefono']);
        // Cerrar la conexión a la base de datos
        $database->closeConnection();
        // Verificar si se encontró el folio
        if (!$result) {
            // Si no se encontró el folio, retornar null
            $errorMsg = "No se encontró un reporte con los datos proporcionados.";
            return false;
        }
        // Si se encontró el folio, retornar el folio
        $folios = [];
        while ($row = $result->fetch_assoc()) {
            // Verificar si el correo esta verificado
            if ($row['Verified'] == 1) {
                // Si el correo esta verificado, agregar el folio al array
                $folios[] = $row;
            }
        }
        // Si no se encontraron folios verificados, retornar false
        if (empty($folios)) {
            // Si el correo no esta verificado, retornar false
            $errorMsg = "El correo electrónico no ha sido verificado. No se puede recuperar el folio.";
        }
        // Enviar el correo con los filios encontrados
        if (!sendRecoveryEmail($folios)) {
            // Si no se pudo enviar el correo de verificación, retornar false
            return false;
        }
        return true;
    } catch (Exception $e) {
        $errorMsg = "Error al buscar el folio: " . htmlspecialchars($e->getMessage());
        return false;
    }
}

// Enviar el correo con el folio
function sendRecoveryEmail($folios)
{
    global $errorMsg;
    try {
        // Enviar el correo con el folio
        foreach ($folios as $folio) {
            sendFolioEmail($folio['Nombre'], $folio['Folio'], $folio['Correo']);
        }
        return true;
    } catch (Exception $e) {
        $errorMsg = "Error al enviar el correo con el folio: " . htmlspecialchars($e->getMessage());
        return false;
    }
}