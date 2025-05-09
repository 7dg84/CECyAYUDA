<?php
// Incluir la clase de conexión a la base de datos
include_once 'db.php';

$hechos = $_POST['hechos'];
$fecha = $_POST['fecha'];
$hora = $_POST['hora'];
$ubicacion = $_POST['ubicacion'];
$nombre = $_POST['nombre'];
$curp = $_POST['curp'];
$correo = $_POST['correo'];
$telefono = $_POST['telefono'];
$tipo = $_POST['tipo'];

// Generar un folio único
$folio = hash('sha256', $curp . $correo . $nombre . time() . rand(0, 1000));

// Redirigir a la página de inicio después de 5 segundos
// echo "<p>Redirigiendo a la página de inicio...</p>";
// echo "<script>setTimeout(function() { window.location.href = 'index.html'; }, 5000);</script>";

?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CECyAYUDA - Contra la Violencia de Género</title>
    <meta name="description" content="Plataforma para reportar y encontrar recursos contra la violencia de género. Reportes confidenciales, información y líneas de ayuda." />
    <meta name="author" content="DragonFly Coders" />

    <meta property="og:title" content="CECyAYUDA - Contra la Violencia de Género" />
    <meta property="og:description" content="Plataforma para reportar y encontrar recursos contra la violencia de género. Reportes confidenciales, información y líneas de ayuda." />
    <meta property="og:type" content="website" />
    
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/report.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  </head>

  <body>
    <div class="container">
      <!-- Barra de navegación -->
      <header class="navbar">
        <div class="navbar-container">
          <a href="index.html" class="navbar-logo">
            <strong>CECyAYUDA</strong>
          </a>
          <nav class="navbar-links">
            <a href="index.html"">Inicio</a>
            <a href="reportar.html" class="active">Reportar</a>
            <a href="consultar.html">Consultar Reportes</a>
            <a href="recursos.html">Recursos</a>
            <a href="sobre-nosotros.html">Sobre Nosotros</a>
          </nav>
          <!-- Boton para dispositivos Moviles -->
          <button class="mobile-menu-btn" title="Abrir menú de navegación">
            <i class="fas fa-bars"></i>
          </button>
        </div>
      </header>

      <main>
      <!-- Mensaje de guardado -->
       <section class="report-section">
        <div class="report-content">
          <div class="report-container">
          <?php
            // Crear una instancia de la clase Database
            $database = new Database();
            // Insertar la denuncia en la base de datos
            try {
                $database->insertDenuncia($folio, $hechos, $fecha, $hora, $ubicacion, $nombre, $curp, $correo, $telefono, $tipo);
                echo "<div class=\"icon\">\n<i class=\"fa-solid fa-circle-check\"></i>\n</div>";
                echo "<h2 class=\"section-title\">Reporte Guardado</h1>\n<p>Su reporte ha sido guardado exitosamente. A continuación se muestran los detalles de su reporte:</p>";
            } catch (Exception $e) {
                echo "<div class=\"icon\">\n<i class=\"fa-solid fa-triangle-exclamation\"></i>\n</div>";
                echo "<h2 class=\"section-title\">Error al guardar el reporte: </h2>";
                echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
            } finally {
                // Cerrar la conexión a la base de datos
                $database->closeConnection();
            }
          ?>
            <div class="report-details">
              <p><strong>Folio:</strong> <?php echo htmlspecialchars($folio); ?></p>
              <p><strong>Fecha:</strong> <?php echo htmlspecialchars($fecha); ?></p>
              <p><strong>Hora:</strong> <?php echo htmlspecialchars($hora); ?></p>
              <p><strong>Ubicación:</strong> <?php echo htmlspecialchars($ubicacion); ?></p>
              <p><strong>Nombre:</strong> <?php echo htmlspecialchars($nombre); ?></p>
              <p><strong>CURP:</strong> <?php echo htmlspecialchars($curp); ?></p>
              <p><strong>Correo:</strong> <?php echo htmlspecialchars($correo); ?></p>
              <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($telefono); ?></p>
              <p><strong>Tipo de Reporte:</strong> <?php echo htmlspecialchars($tipo); ?></p>
            </div>
            <div class="buttons">
              <button class="primary-button" onclick="window.location.href='index.html'">Regresar a Inicio</button>
              <button class="secondary-button" onclick="window.location.href='consultar.html'">Consultar Reportes</button>
            </div>
          </div>
        </div>
    </section>
</main>
<!-- Pie de página -->
<footer class="footer">
        <div class="footer-container">
          <div class="footer-section">
            <h3>CECyAYUDA</h3>
            <p>Trabajando juntos contra la violencia de género</p>
          </div>
          <div class="footer-section">
            <h3>Enlaces rápidos</h3>
            <ul>
              <li><a href="index.html">Inicio</a></li>
              <li><a href="reportar.html">Reportar</a></li>
              <li><a href="consultar.html">Consultar Reportes</a></li>
              <li><a href="recursos.html">Recursos</a></li>
              <li><a href="sobre-nosotros.html">Sobre Nosotros</a></li>
            </ul>
          </div>
          <div class="footer-section">
            <h3>Contacto</h3>
            <p>contacto@cecyayuda.org</p>
            <p>Línea de ayuda: 0800-999-1234</p>
          </div>
        </div>
        <div class="footer-bottom">
          <p>&copy; 2025 Voz Contra El Silencio. Todos los derechos reservados.</p>
        </div>
      </footer>
    </div>

    <script src="scripts/mobile.js"></script>
  </body>
</html>