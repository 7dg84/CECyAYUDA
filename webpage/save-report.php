<?php
// Incluir la clase de conexión a la base de datos
include_once 'admin/db.php';
// Incluir la clase de verificación de token
include_once 'admin/verify.php';

$errorMsg = "Error desconocido";
$folio;

// Enviar el correo de verificacion
function email() {
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
function saveReport($folio, $hechos, $fecha, $hora, $estado, $municipio, $colonia, $calle, $nombre, $curp, $correo, $telefono, $tipo, $file) {
  global $errorMsg;
  try {
      // Crear una instancia de la clase Database
      $database = new Denuncia();
      // Insertar la denuncia en la base de datos
      $database->insertDenuncia($folio, $hechos, $fecha, $hora, $estado, $municipio, $colonia, $calle, $nombre, $curp, $correo, $telefono, $tipo, $file);
      // Cerrar la conexión a la base de datos
      $database->closeConnection();
      return true;
  } catch (Exception $e) {  
      $errorMsg = htmlspecialchars($e->getMessage()) .'\n'. $e->getMessage();
      return false;
  }
}

// Funcion para validar los datos del formulario
function validateData() {
  global $errorMsg, $folio;
  // Funcion para validar los datos del formulario
  function validate($field, $pattern=null) {
    if (empty($field)) {
      return false;
    }
    if ($pattern != null) {
      return preg_match($pattern, $field);
    }
  }
  // Verificar metodo de envio
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $errorMsg = "Método de envío no permitido.";
    return false;
  }

  // Validar los campos del formulario
  if (!validate($_POST['hechos'], "/^[a-zA-Z0-9\s.,]+$/")) 
    {$errorMsg = ("Campo 'Hechos' inválido."); return false;}
  if (!validate($_POST['fecha'], "/^\d{4}-\d{2}-\d{2}$/")) 
    {$errorMsg = ("Campo 'Fecha' inválido."); return false;}
  if (date('Y-m-d', strtotime($_POST['fecha'])) > date('Y-m-d')) 
    {$errorMsg = ("La fecha no puede ser posterior a la actual."); return false;}
  if (!validate($_POST['hora'], "/^\d{2}:\d{2}$/")) 
    {$errorMsg = ("Campo 'Hora' inválido."); return false;}
  if (!validate($_POST['estado'], "/^[a-zA-Z0-9\s,.\-]+$/")) 
    {$errorMsg = ("Campo 'Estado' inválido."); return false;}
  if (!validate($_POST['municipio'], "/^[a-zA-Z0-9\s,.\-]+$/")) 
    {$errorMsg = ("Campo 'Municipio' inválido."); return false;}
  if (!validate($_POST['colonia'], "/^[a-zA-Z0-9\s,.\-]+$/")) 
    {$errorMsg = ("Campo 'Colonia' inválido."); return false;}
  if (!validate($_POST['calle'], "/^[a-zA-Z0-9\s,.\-]+$/")) 
    {$errorMsg = ("Campo 'Calle' inválido."); return false;}
  if (!validate($_POST['nombre'], "/^[a-zA-Z\s]+$/")) 
    {$errorMsg = ("Campo 'Nombre' inválido."); return false;}
  if (!validate($_POST['curp'], "/^[A-Z]{4}[0-9]{6}[\w]{8}$/")) 
    {$errorMsg = ("Campo 'CURP' inválido."); return false;}
  if (!validate($_POST['correo'], "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/")) 
    {$errorMsg = ("Campo 'Correo' inválido."); return false;}
  if (!validate($_POST['telefono'], "/^\d{10}$/")) 
    {$errorMsg = ("Campo 'Teléfono' inválido."); return false;}
  if (!validate($_POST['tipo'], "/^[a-zA-Z\s]+$/")) 
    {$errorMsg = ("Campo 'Tipo de Reporte' inválido."); return false;}

  // Validar el campo de archivo
  if (empty($_FILES['evidencia']['name'])) {
    $errorMsg = ("Campo 'Evidencia' no puede estar vacío.");
    return false;
  }

  // Validar el tipo de archivo
  $file = $_FILES['evidencia'];
  if ($file && $file['error'] == 0) {
    $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
    if (!in_array($file['type'], $allowedTypes)) {
      $errorMsg = ("Tipo de archivo no permitido. Solo se permiten imágenes JPEG, PNG y archivos PDF.");
      return false;
    }
  }
  // Validar el tamaño del archivo
  if ($file && $file['error'] == 0) {
    $maxFileSize = 2 * 1024 * 1024; // 2 MB
    if ($file['size'] > $maxFileSize) {
      $errorMsg = ("El tamaño del archivo excede el límite permitido de 2 MB.");
      return false;
    }
  }
  $folio = hash('sha256', $_POST['curp'] . $_POST['correo'] . $_POST['nombre'] . time() . bin2hex(random_bytes(16)));
  // Si todos los campos son válidos, retornar true
  return true;
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
            <a href="consultar.php">Consultar Reportes</a>
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
            <!--Validar la informacion -->
            <?php if (!validateData()): ?>
              <!-- Error en la validacion de los datos -->
              <div class=\"icon\">
                <i class=\"fa-solid fa-triangle-exclamation\"></i>
              </div>
              <h2 class=\"section-title\">Error</h2>
              <h2><?= $errorMsg?></h2>
            <?php else: ?>
              <!-- Guardar los Datos -->
               <?php if (saveReport(
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
              <!-- Guardar el reporte -->
              <div class="icon">
                <i class="fa-solid fa-circle-check"></i>
              </div>
              <h2 class="section-title">Reporte Guardado</h2>
              <p>Su reporte ha sido guardado exitosamente. A continuación se muestran los detalles de su reporte:</p>
              <div class="report-details">
                <p><strong>Folio:</strong> <p><?php echo htmlspecialchars($folio); ?></p>
                <button type='button' class="secondary-button" onclick="
                  navigator.clipboard.writeText('<?php echo htmlspecialchars($folio); ?>').then(function() {
                    event.target.textContent = '¡Copiado!';
                    setTimeout(function() {
                      event.target.textContent = 'Copiar';
                    }, 1500);
                  });
                ;">Copiar</button>
                <p><strong>Fecha:</strong></p> <p><?php echo htmlspecialchars($_POST['fecha']); ?></p>
                <p><strong>Hora:</strong></p> <p><?php echo htmlspecialchars($_POST['hora']); ?></p>
                <p><strong>Ubicación:</strong></p> <p><?php echo htmlspecialchars($_POST['estado']); ?></p>
                <p><strong>Municipio:</strong></p> <p><?php echo htmlspecialchars($_POST['municipio']); ?></p>
                <p><strong>Colonia:</strong></p> <p><?php echo htmlspecialchars($_POST['colonia']); ?></p>
                <p><strong>Calle:</strong></p> <p><?php echo htmlspecialchars($_POST['calle']); ?></p>
                <p><strong>Nombre:</strong></p> <p><?php echo htmlspecialchars($_POST['nombre']); ?></p>
                <p><strong>CURP:</strong></p> <p><?php echo htmlspecialchars($_POST['curp']); ?></p>
                <p><strong>Correo:</strong></p> <p><?php echo htmlspecialchars($_POST['correo']); ?></p>
                <p><strong>Teléfono:</strong></p> <p><?php echo htmlspecialchars($_POST['telefono']); ?></p>
                <p><strong>Tipo de Violencia:</strong></p> <p> <?= htmlspecialchars($_POST['tipo']); ?></p>
                <p><strong>Archivo de Evidencia:</strong></p> <p><?php echo htmlspecialchars($_FILES['evidencia']['name']); ?></p>
                <!-- <img src="<?php echo htmlspecialchars($_FILES['evidencia']['tmp_name']); ?>" alt="Evidencia" class="evidence-image"> -->
              </div>
              <!-- Enviar correo electronico -->
               <?php if (email()): ?>
                <p>Recibirá un correo electrónico con un enlace para verificar su reporte.</p>
                <p>Por favor, revise su bandeja de entrada y carpeta de spam.</p>
              <?php else: ?>
                <!-- Error al enviar el correo -->
                <div class="icon">
                  <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <h2 class="section-title">Error</h2>
                <p><?php echo htmlspecialchars($errorMsg); ?></p>
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