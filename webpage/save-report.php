<?php
$errorMsg = "Error desconocido";
$folio;

// Incluir la logica de la base de datos y verificación
include_once 'logic.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CECyAYUDA - Reporte Guardao</title>
  <meta name="description" content="Plataforma para reportar y encontrar recursos contra la violencia de género. Reportes confidenciales, información y líneas de ayuda." />
  <meta name="author" content="DragonFly Coders" />

  <meta property="og:title" content="CECyAYUDA - Contra la Violencia de Género" />
  <meta property="og:description" content="Plataforma para reportar y encontrar recursos contra la violencia de género. Reportes confidenciales, información y líneas de ayuda." />
  <meta property="og:type" content="website" />

  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <link rel="stylesheet" href="styles/main.css">
  <link rel="stylesheet" href="styles/report.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

  <script>
    // Funcion para copiar el folio
    function copy(event, folio) {
      
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(folio)
          .then(() => event.textContent = '¡Copiado!')
          .catch(err => console.error("Copy failed:", err));
      } else {
        // Fallback for unsupported or insecure contexts
        const textarea = document.createElement("textarea");
        textarea.value = folio;
        textarea.style.position = "fixed"; // avoid scrolling to bottom
        document.body.appendChild(textarea);
        textarea.focus();
        textarea.select();
        try {
          document.execCommand("copy");
          event.textContent = '¡Copiado!';
        } catch (err) {
          console.error("Fallback copy failed:", err);
        }
        document.body.removeChild(textarea);
      }
      setTimeout(function() {
        event.textContent = 'Copiar';
      }, 1500);
    }
  </script>
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
          <a href="consultar.php">Consultar Reportes</a>
          <a href="recursos.html">Recursos</a>
          <a href="sobre-nosotros.html">Sobre Nosotros</a>
          <!-- Boton para modo oscuro -->
          <div id="toggle-theme">
            <i class="fa-solid fa-moon"></i>
          </div>
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
            <!--Validar la informacion -->
            <?php if (!validateData()): ?>
              <!-- Error en la validacion de los datos -->
              <div class="icon">
                <i class="fa-solid fa-triangle-exclamation"></i>
              </div>
              <h2 class="section-title">Error</h2>
              <h2><?= $errorMsg ?></h2>
              <!-- Guardar los Datos -->
            <?php else: ?>
              <?php
              $folio = hash('sha256', $_POST['curp'] . $_POST['correo'] . $_POST['nombre'] . time() . bin2hex(random_bytes(16)));
              if (saveReport(
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
                file_get_contents($_FILES['evidencia']['tmp_name'])
              )): ?>
                <!-- Guardar el reporte en el local storage -->
                <script>
                  localStorage.setItem('folio', '<?php echo htmlspecialchars($folio); ?>');
                </script>
                <audio id="audioElement" src="media/registro.mp3" autoplay style="display: none;"></audio>
                <!-- Guardar el reporte -->
                <div class="icon">
                  <i class="fa-solid fa-circle-check"></i>
                </div>
                <h2 class="section-title">Reporte Guardado</h2>
                <p>Su reporte ha sido guardado exitosamente. A continuación se muestran los detalles de su reporte:</p>
                <div class="report-details">
                  <p><strong>Folio:</strong>
                  <p id="folioParraph"><?php echo htmlspecialchars($folio); ?></p>
                  <button type='button' class="secondary-button" onclick="copy(this, '<?php echo htmlspecialchars($folio); ?>')">Copiar</button>
                  <p><strong>Fecha:</strong></p>
                  <p><?php echo htmlspecialchars($_POST['fecha']); ?></p>
                  <p><strong>Hora:</strong></p>
                  <p><?php echo htmlspecialchars($_POST['hora']); ?></p>
                  <p><strong>Ubicación:</strong></p>
                  <p><?php echo htmlspecialchars($_POST['estado']); ?></p>
                  <p><strong>Municipio:</strong></p>
                  <p><?php echo htmlspecialchars($_POST['municipio']); ?></p>
                  <p><strong>Colonia:</strong></p>
                  <p><?php echo htmlspecialchars($_POST['colonia']); ?></p>
                  <p><strong>Calle:</strong></p>
                  <p><?php echo htmlspecialchars($_POST['calle']); ?></p>
                  <p><strong>Nombre:</strong></p>
                  <p><?php echo htmlspecialchars($_POST['nombre']); ?></p>
                  <p><strong>CURP:</strong></p>
                  <p><?php echo htmlspecialchars($_POST['curp']); ?></p>
                  <p><strong>Correo:</strong></p>
                  <p><?php echo htmlspecialchars($_POST['correo']); ?></p>
                  <p><strong>Teléfono:</strong></p>
                  <p><?php echo htmlspecialchars($_POST['telefono']); ?></p>
                  <p><strong>Tipo de Violencia:</strong></p>
                  <p> <?= htmlspecialchars($_POST['tipo']); ?></p>
                  <p><strong>Archivo de Evidencia:</strong></p>
                  <p><?php echo htmlspecialchars($_FILES['evidencia']['name']); ?></p>
                  <!-- <img src="<?php echo htmlspecialchars($_FILES['evidencia']['tmp_name']); ?>" alt="Evidencia" class="evidence-image"> -->
                </div>
                <!-- Enviar correo electronico -->
                <?php if (sendVerificationEmail()): ?>
                  <div class="icon">
                    <i class="fa-solid fa-envelope"></i>
                  </div>
                  <p>Recibirá un correo electrónico con un enlace para verificar su reporte.</p>
                  <p>Por favor, revise su bandeja de entrada y carpeta de spam.</p>
                <?php else: ?>
                  <!-- Error al enviar el correo -->
                  <div class="icon">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                  </div>
                  <h2 class="section-title">Error</h2>
                  <p>Hubo un problema al enviar el correo de verificación.</p>
                  <p><?= htmlspecialchars($errorMsg); ?></p>
                  <p>Por favor, intente nuevamente más tarde.</p>
                <?php endif; ?>
              <?php else: ?>
                <!-- Error al guardar el reporte -->
                <div class="icon">
                  <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <h2 class="section-title">Error</h2>
                <p><?php echo htmlspecialchars($errorMsg); ?></p>
                <p>Por favor, intente nuevamente más tarde.</p>

              <?php endif; ?>
            <?php endif; ?>
            <div class="buttons">
              <a class="primary-button" href="index.html">Regresar a Inicio</a>
              <a class="primary-button" href="consultar.php">Consultar Reportes</a>
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

  <!-- Ventana para los manuales -->
  <div class="icon-faq" onclick="faq.showModal();">
    <i class="fa-regular fa-circle-question"></i>
  </div>
  <dialog id="faq">
    <h2>Tutorial de Uso</h2>
    <media>
      <video src="media/ManualVideo.mov" controls></video>
    </media>
    <h2>Vea los manuales</h2>
    <a href="resources/ManualUsuarioEsp.pdf" target="_blank" rel="noreferrer" class="secondary-button">Manual en Español</a>
    <a href="resources/ManualUsuarioIng.pdf" target="_blank" rel="noreferrer" class="secondary-button">Manual en Ingles</a>
    <br>
    <button type="button" class="primary-button" onclick="faq.close()">Cerrar</button>
  </dialog>

  <script src="scripts/mobile.js"></script>
</body>

</html>