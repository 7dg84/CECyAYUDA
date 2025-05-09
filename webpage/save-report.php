<?php
// Incluir la clase de conexión a la base de datos
include_once 'packages/db.php';

// Mostrar errror en caso de que el folio no sea valido
function error($message) {
  echo "
  <div class=\"icon\">\n<i class=\"fa-solid fa-triangle-exclamation\"></i>\n</div>
  <h2 class=\"section-title\">Error</h2>
  <h2>$message</h2>
  ";
}


// Guardar el reporte en la base de datos
function saveReport($folio, $hechos, $fecha, $hora, $ubicacion, $nombre, $curp, $correo, $telefono, $tipo) {
  try {
      // Crear una instancia de la clase Database
      $database = new Database();
      // Insertar la denuncia en la base de datos
      $database->insertDenuncia($folio, $hechos, $fecha, $hora, $ubicacion, $nombre, $curp, $correo, $telefono, $tipo);
      echo "<div class=\"icon\">\n<i class=\"fa-solid fa-circle-check\"></i>\n</div>";
      echo "<h2 class=\"section-title\">Reporte Guardado</h2>
      <p>Su reporte ha sido guardado exitosamente. A continuación se muestran los detalles de su reporte:</p>";
      echo "
      <div class=\"report-details\">
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
      ";
      // Cerrar la conexión a la base de datos
      $database->closeConnection();
  } catch (Exception $e) {  
      error(htmlspecialchars($e->getMessage()));
  }
}
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
                if (preg_match("/^[a-zA-Z0-9\s]+$/", $hechos) && preg_match("/^[a-zA-Z0-9\s]+$/", $ubicacion) && preg_match("/^[a-zA-Z\s]+$/", $nombre) && preg_match("/^[a-zA-Z0-9]{18}$/", $curp) && filter_var($correo, FILTER_VALIDATE_EMAIL) && preg_match("/^\d{10}$/", $telefono)) {
                  // Generar un folio único
                  $folio = hash('sha256', $curp . $correo . $nombre . time() . rand(0, 1000));
    
                  // Guardar el reporte en la base de datos
                  saveReport($folio, $hechos, $fecha, $hora, $ubicacion, $nombre, $curp, $correo, $telefono, $tipo);
                } else {
                  error("Por favor, completa todos los campos del formulario correctamente.");
                }
              } else {
                error("Por favor, completa todos los campos del formulario.");
              }

            ?>
            <div class="buttons">
              <a class="primary-button" href="index.html">Regresar a Inicio</a>
              <a class="primary-button" href="consultar.html">Consultar Reportes</a>
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