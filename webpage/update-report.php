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

    // Funcion para actualizar el reporte
    function updateReport($folio, $hechos, $fecha, $hora, $ubicacion, $nombre, $curp, $correo, $telefono, $tipo) {
        try {
            // Crear una instancia de la clase Database
            $database = new Database();
            // Actualizar la denuncia en la base de datos
            $database->updateDenuncia($folio, $hechos, $fecha, $hora, $ubicacion, $nombre, $curp, $correo, $telefono, $tipo);
            echo "<div class=\"icon\">\n<i class=\"fa-solid fa-circle-check\"></i>\n</div>";
            echo "<h2 class=\"section-title\">Reporte Actualizado</h2>
            <p>Su reporte ha sido actualizado exitosamente. A continuación se muestran los detalles de su reporte:</p>";
            echo "
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
        search($folio);
    } else {
            error("Folio Invalido, Por favor, proporciona un folio válido.");
        }
    } else {
        error("Por favor, proporciona un folio para buscar el reporte.");
    }

    // Redirigir a la página de inicio después de 5 segundos
    echo "<p>Redirigiendo a la página de inicio...</p>";
    echo "<script>setTimeout(function() { window.location.href = 'index.html'; }, 5000);</script>";
?>