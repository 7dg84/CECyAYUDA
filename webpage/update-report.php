<?php
include_once 'logic.php';

$errorMsg = "Error desconocido";

// Mostrar errror en caso de que el folio no sea valido
function error($message)
{
    // alert
    echo "<script>alert('$message');</script>";
    // redirigir al inicio
    header("Location: index.html");
}

// Obtener el folio del formulario
$folio = isset($_POST['folio']) ? $_POST['folio'] : '';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CECyAYUDA - Actualizar Reporte</title>
    <meta name="description" content="Plataforma para reportar y encontrar recursos contra la violencia de género. Reportes confidenciales, información y líneas de ayuda." />
    <meta name="author" content="DragonFly Coders" />

    <meta property="og:title" content="CECyAYUDA - Contra la Violencia de Género" />
    <meta property="og:description" content="Plataforma para reportar y encontrar recursos contra la violencia de género. Reportes confidenciales, información y líneas de ayuda." />
    <meta property="og:type" content="website" />

    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="styles/main.css">
</head>

<body>
    <!-- Validar el folio -->
    <?php if (validateFolio($folio)): ?>

        <!-- Validar los datos -->
        <?php if (validateData(requiereFile:false)): ?>
            <!-- Actualizar el reporte en la base de datos -->
            <?php if (updateReport(
                $folio,
                $_POST['hechos'],
                $_POST['fecha'],
                $_POST['hora'],
                $_POST['estado'],
                $_POST['municipio'],
                $_POST['colonia'],
                $_POST['calle'],
                $_POST['nombre'],
                $_POST['curp'],
                $_POST['correo'],
                $_POST['telefono'],
                $_POST['tipo'],
                $_FILES['evidencia']
            )): ?>
                <script>
                    window.location.href = "consultar.php?folio=<?= $folio; ?>";
                </script>
            <?php else: ?>
                <script>
                    alert("Error al actualizar la denuncia.\n<?= $errorMsg; ?>");
                    window.location.href = "consultar.php?folio=<?= $folio; ?>";
                </script>
            <?php endif ?>
            
        <!-- Si los datos no son validos -->
            <?php else: ?>
            <script>
                window.location.href = "consultar.php?folio=<?= $folio; ?>";
                alert("<?= $errorMsg; ?>");
            </script>
        <?php endif ?>
    <!-- Si el folio no es valido -->
    <?php else: ?>
    <script>
        alert("Folio Invalido, Por favor, proporciona un folio válido.");
        window.location.href = "consultar.php?folio=<?= $folio; ?>";
    </script>
    <?php endif; ?>
</body>

</html>