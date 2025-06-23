<?php
// Incluir la logica de la base de datos y verificación
include_once 'logic.php';

// Variable de error
$errorMsg = "Error desconocido";
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CECyAYUDA - Recuperar Folio</title>
  <meta name="description" content="Plataforma para reportar y encontrar recursos contra la violencia de género. Reportes confidenciales, información y líneas de ayuda." />
  <meta name="author" content="DragonFly Coders" />

  <meta property="og:title" content="CECyAYUDA - Contra la Violencia de Género" />
  <meta property="og:description" content="Plataforma para reportar y encontrar recursos contra la violencia de género. Reportes confidenciales, información y líneas de ayuda." />
  <meta property="og:type" content="website" />

  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <link rel="stylesheet" href="styles/main.css">
  <link rel="stylesheet" href="styles/recovery.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

  <script src="scripts/form.js"></script>
  <script src="scripts/recovery.js"></script>
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
          <a href="index.html">Inicio</a>
          <a href="info.html">Informacion</a>
          <a href="reportar.html">Reportar</a>
          <a href="consultar.php" class="active">Consultar Reportes</a>
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
      <!-- Si se proporcionan los datos -->
      <?php if (ckeckDataRecovery()): ?>
        <section class="recovery-section">
          <div class="recovery-content">
            <!-- Validar la informacion -->
            <?php if (validateRecoveryData()): ?>
              <!-- Buscar el reporte -->
              <?php if (searchReport()): ?>
                <!-- Si se encuentra el reporte enviar el correo-->
                <div class="recovery-container">
                  <div class="icon">
                    <i class="fa-solid fa-circle-check"></i>
                  </div>
                  <h2 class="section-title">Folio Recuperado</h2>
                  <p>Enviamos un correo a la dirección proporcionada con el folio de tu reporte.</p>
                  <p>Si no recibiste el correo, verifica tu bandeja de entrada o carpeta de spam.</p>
                  <p>Recuerda que puedes consultar el estado de tu reporte en cualquier momento.</p>
                </div>
              <?php else: ?>
                <!-- Si no se encuentra el reporte -->
                <div class="recovery-container">
                  <div class="icon">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                  </div>
                  <h2 class="section-title">Error Folio no encontrado</h2>
                  <?= $errorMsg ?>
                  <p>Por favor, verifica los datos ingresados e intenta nuevamente.</p>
                </div>
              <?php endif; ?>

            <?php else: ?>
              <!-- Informacion no valida -->
              <div class="recovery-container">
                <div class="icon">
                  <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <h2 class="section-title">Error Informacion no valida</h2>
                <p><?php echo $errorMsg; ?></p>
                <p>Por favor, verifica los datos ingresados e intenta nuevamente.</p>
              </div>
            <?php endif; ?>
          </div>
        </section>
      <?php else: ?>
        <!-- Sino mostar el formulario -->
        <!-- Seccion para el formulario -->
        <section class="recovery-section">
          <div class="recovery-content">
            <form id="Recuperar" method=POST action="" onsubmit="return validateRecovery()">
              <h2 class="section-title">Recuperar Folio</h2>
              <!-- Datos del denunciante -->
              <label for="nombre">Nombre del denunciante</label>
              <input name="nombre" id="nombre" type="text" minlength="3" placeholder="Nombre completo del denunciante."
                oninput="soloLetras(this);"><br>
              <span id="ErrorNombre" class="error"></span>
              <!-- Curp  -->
              <label for="curp">CURP</label>
              <input type="text" name="curp" id="curp" minlength="18" maxlength="18" placeholder="XXXX000000XXXXXX00"
                onkeydown="mayusculas(this);"><br>
              <span id="ErrorCurp" class="error"></span>
              <!-- Correo -->
              <label for="correo">Correo</label>
              <input type="email" name="correo" placeholder="tu@ejemplo.com" id="correo">
              <span id="ErrorCorreo" class="error"></span>
              <!-- Telefono -->
              <label for="telefono">Numero de telefono</label>
              <input type="tel" name="telefono" id="telefono" minlength="10" maxlength="10"
                placeholder="Numero telefonico." oninput="soloNumeros(this);"><br>
              <span id="ErrorTelefono" class="error"></span>
              <!-- Boton de enviar -->
              <button type="submit" class="primary-button">Recuperar Folio</button>
            </form>
          </div>
        </section>
      <?php endif; ?>
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
          <p>contacto@cecyayuda.org</p>
          <p>Línea de ayuda: 0800-999-1234</p>
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
    <h2>Vea los manuales</h2>
    
    <a href="resources/ManualUsuarioEsp.pdf" target="_blank"  class="secondary-button">Manual en Español</a>
    <a href="resources/ManualUsuarioIng.pdf" target="_blank"  class="secondary-button">Manual en Ingles</a>
    <br>
    <button type="button" class="primary-button" onclick="faq.close()">Cerrar</button>
  </dialog>

  <!-- Script para el menú móvil -->
  <script src="scripts/mobile.js"></script>
</body>

</html>