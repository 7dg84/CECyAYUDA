<?php
    include_once 'packages/db.php';

    // Mostrar errror en caso de que el folio no sea valido
    function error($message) {
    echo "
    <div class=\"report-container\">
    <div class=\"icon\">\n<i class=\"fa-solid fa-triangle-exclamation\"></i>\n</div>
    <h2 class=\"section-title\">Error</h2>
    <h2>$message</h2>
    </div>
    ";
    }

    function renderReport($folio, $hechos, $fecha, $hora, $ubicacion, $nombre, $curp, $correo, $telefono, $tipo) {
        return "
        <div class=\"icon\">\n<i class=\"fa-solid fa-circle-check\"></i>\n</div>
        <h2 class=\"section-title\">Reporte Actualizado</h2>
        <p>Su reporte ha sido actualizado exitosamente. A continuación se muestran los detalles de su reporte:</p>
        <div class=\"report-details\">
        <p><strong>Folio:</strong> ".htmlspecialchars($folio)."</p>
        <p><strong>Fecha:</strong> ".htmlspecialchars($fecha)."</p>
        <p><strong>Hora:</strong> ".htmlspecialchars($hora)."</p>
        <p><strong>Ubicación:</strong> ".htmlspecialchars($ubicacion)."</p>
        <p><strong>Nombre:</strong> ".htmlspecialchars($nombre)."</p>
        <p><strong>CURP:</strong> ".htmlspecialchars($curp)."</p>
        <p><strong>Correo:</strong> ".htmlspecialchars($correo)."</p>
        <p><strong>Teléfono:</strong> ".htmlspecialchars($telefono)."</p>
        <p><strong>Tipo de Reporte:</strong> ".htmlspecialchars($tipo)."</p>
        </div>
        ";
    }

    // Funcion para actualizar el reporte
    function updateReport($folio, $hechos, $fecha, $hora, $ubicacion, $nombre, $curp, $correo, $telefono, $tipo) {
        try {
            // Crear una instancia de la clase Database
            $database = new Database();
            // Actualizar la denuncia en la base de datos
            $database->updateDenuncia($folio, $hechos, $fecha, $hora, $ubicacion, $nombre, $curp, $correo, $telefono, $tipo);
            // Cerrar la conexión a la base de datos
            $database->closeConnection();
        } catch (Exception $e) {  
            error(htmlspecialchars($e->getMessage()));
        }
    }

    // Obtener el folio del formulario
    $folio = isset($_POST['folio']) ? $_POST['folio'] : '';
    // Validar el folio
    if (!empty($folio)) {
    $regex = "/^[a-f0-9]{64}$/";
    if (preg_match($regex, $folio)) {
        // Obtener los datos del formulario
        $hechos = $_POST['hechos'];
        $fecha = $_POST['fecha'];
        $hora = $_POST['hora'];
        $ubicacion = $_POST['ubicacion'];
        $nombre = $_POST['nombre'];
        $curp = $_POST['curp'];
        $correo = $_POST['correo'];
        $telefono = $_POST['telefono'];
        $tipo = $_POST['tipo'];
        
        // Validar los datos
        if (!(empty($hechos) || empty($fecha) || empty($hora) || empty($ubicacion) || empty($nombre) || empty($curp) || empty($correo) || empty($telefono) || empty($tipo))) {
        if (!(preg_match("/^[a-zA-Z0-9\s]+$/", $hechos) && preg_match("/^[a-zA-Z0-9\s]+$/", $ubicacion) && preg_match("/^[a-zA-Z\s]+$/", $nombre) && preg_match("/^[a-zA-Z0-9]{18}$/", $curp) && filter_var($correo, FILTER_VALIDATE_EMAIL) && preg_match("/^\d{10}$/", $telefono))) {
            // Actualizar el reporte en la base de datos
            echo updateReport(
                $folio,
                $hechos,
                $fecha,
                $hora,
                $ubicacion,
                $nombre,
                $curp,
                $correo,
                $telefono,
                $tipo
            );
            echo "<script>window.location.href = 'search-report.php?folio=$folio';</script>";
            // echo renderReport(
            //     $folio,
            //     $hechos,
            //     $fecha,
            //     $hora,
            //     $ubicacion,
            //     $nombre,
            //     $curp,
            //     $correo,
            //     $telefono,
            //     $tipo
            // );
        } else {
            error("Por favor, completa todos los campos del formulario correctamente.");
        }
        } else {
        error("Por favor, completa todos los campos del formulario.");
        }
    } else {
            error("Folio Invalido, Por favor, proporciona un folio válido.");
        }
    } else {
        error("Por favor, proporciona un folio para buscar el reporte.");
    }
?>