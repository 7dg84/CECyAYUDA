<?php
include_once 'logic.php';
include_once 'admin/verify.php';

$errorMsg = "Error desconocido";
// Mostrar errror en caso de que el folio no sea valido
function error($message)
{
  echo "
  <div class=\"icon\">\n<i class=\"fa-solid fa-triangle-exclamation\"></i>\n</div>
  <h2 class=\"section-title\">Error</h2>
  <h2>$message</h2>
  ";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CECyAYUDA - Verifica tu Correo Electronico</title>
  <meta name="description" content="Plataforma para reportar y encontrar recursos contra la violencia de género. Reportes confidenciales, información y líneas de ayuda." />
  <meta name="author" content="DragonFly Coders" />

  <meta property="og:title" content="CECyAYUDA - Contra la Violencia de Género" />
  <meta property="og:description" content="Plataforma para reportar y encontrar recursos contra la violencia de género. Reportes confidenciales, información y líneas de ayuda." />
  <meta property="og:type" content="website" />

  <link rel="icon" type="image/x-icon" href="favicon.ico">
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
          <a href=" info.html">Informacion</a>
          <a href=" reportar.html" class="active">Reportar</a>
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
            // Verificar método de envío
            if ($_SERVER['REQUEST_METHOD'] == 'GET'): ?>
              <!-- // Obtener el token de la URL -->
              <?php $token = $_GET['token'] ?? '';
              // Validar el token
              // Token valido
              if (validateToken($token)): ?>
                <!-- Verificar el correo -->
                <?php if (verifyToken($token)): ?>
                  <div class="icon">
                    <i class="fa-solid fa-circle-check"></i>
                  </div>
                  <h2 class="section-title">Éxito</h2>
                  <p>Tu correo ha sido verificado exitosamente. Gracias por tu reporte.</p>
                <?php else: ?>
                  <!-- Error al verificar el correo -->
                  <div class="icon">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                  </div>
                  <h2 class="section-title">Error</h2>
                  <p>Error al verificar el correo.</p>
                  <p><?= $errorMsg ?></p>
                <?php endif; ?>
                <!-- Token Invalido -->
              <?php else: ?>
                <div class="icon">
                  <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <h2 class="section-title">Error</h2>
                <p><?= $errorMsg ?></p>
              <?php endif; ?>

              <!-- Metodo de envio no permitido -->
            <?php else: ?>
              <div class="icon">
                <i class="fa-solid fa-triangle-exclamation"></i>
              </div>
              <h2 class="section-title">Error</h2>
              <p><?= $errorMsg ?></p>
            <?php endif; ?>
            <div class="buttons">
              <a class="primary-button" href="index.html">Regresar a Inicio</a>
              <a class="primary-button" href="reportar.html">Realizar Otro reporte</a>
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
            <li><a href="info.html">Información</a></li>
            <li><a href="reportar.html">Reportar</a></li>
            <li><a href="consultar.html">Consultar Reportes</a></li>
            <li><a href="recursos.html">Recursos</a></li>
            <li><a href="sobre-nosotros.html">Sobre Nosotros</a></li>
          </ul>
        </div>
        <div class="footer-section">
          <h3>Contacto</h3>
          <a href="mailto:dragonflycodes5@gmail.com">
            <p>dragonflycodes5@gmail.com</p>
          </a>
          <a href="https://www.youtube.com/@DragonFlyCodes">
            <p>Canal DragonFlyCodes</p>
          </a>
          <h3>Administracion</h3>
          <a href="admin/login.php">
            <p>Panel de Administracion</p>
          </a>
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