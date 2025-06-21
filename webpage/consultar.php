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
  <title>CECyAYUDA - Consultar Reportes</title>
  <meta name="description" content="Plataforma para reportar y encontrar recursos contra la violencia de género. Reportes confidenciales, información y líneas de ayuda." />
  <meta name="author" content="DragonFly Coders" />

  <meta property="og:title" content="CECyAYUDA - Contra la Violencia de Género" />
  <meta property="og:description" content="Plataforma para reportar y encontrar recursos contra la violencia de género. Reportes confidenciales, información y líneas de ayuda." />
  <meta property="og:type" content="website" />

  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <link rel="stylesheet" href="styles/main.css">
  <link rel="stylesheet" href="styles/report.css">
  <link rel="stylesheet" href="styles/consult.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

  <script src="scripts/mobile.js"></script>
  <script src="scripts/consult.js"></script>
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
      <!-- Si se busca un folio -->
      <?php if (checkFolioGet()): ?>

        <!-- Validar el folio -->
        <?php if (validateFolio($_GET['folio'])): ?>
          <!-- Buscar el reporte -->
          <?php $row = search($_GET['folio']);
          if ($row): ?>
            <section class="report-section">
              <div class="report-content">
                <h2 class="section-title">Reporte de Violencia de Género</h2>
                <div class="report-container">
                  <div class="icon">
                    <i class="fa-solid fa-circle-check"></i>
                  </div>
                  <h2 class="section-title">Reporte encontrado</h2>
                </div>
              </div>
            </section>

            <section class="report-section">
              <div class="report-content">
                <form id="Report" method=POST enctype=multipart/form-data action="" onsubmit="return validateForm(this);">
                  <!-- Formulario de denuncia de violencia de género -->
                  <h1 class="section-title">Formulario de denuncia de violencia de Genero </h1>
                  <!-- Folio -->
                  <label for="folio">Folio</label>
                  <p id="folioParraph"><?= $row['Folio'] ?></p><br>
                  <input type="hidden" name="folio" id="folio" value="<?= $row['Folio'] ?>">
                  <button type='button' class="secondary-button" onclick="copy(this, '<?php echo htmlspecialchars($row['Folio']); ?>')">Copiar</button>
                  <!-- Status -->
                  <label>Status</label>
                  <p><?= statusValue($row['Status']) ?></p><br>
                  <!-- Hechos  -->
                  <label for="hechos">Hechos</label>
                  <textarea name="hechos" id="hechos" cols="30" rows="10"><?= $row['Descripcion'] ?></textarea><br>
                  <span id="ErrorHechos" class="error"></span>
                  <div class="date-time-container">
                    <!-- Fecha -->
                    <label for="fecha">Fecha</label><br>
                    <input type="date" name="fecha" id="fecha" value="<?= $row['Fecha'] ?>"><br>
                    <!-- Hora -->
                    <label for="hora">Hora</label><br>
                    <input type="time" name="hora" id="hora" value="<?= substr($row['Hora'], 0, 5) ?>"><br>
                    <br>
                  </div>
                  <span id="ErrorFecha" class="error"></span><br>
                  <span id="ErrorHora" class="error"></span>
                  <!-- Ubicacion -->
                  <label for="ubicacion">Ubicacion</label>
                  <div class="ubicacion-container">
                    <!-- <input type="text" name="estado" id="estado" placeholder="Estado" oninput="soloLetras(this);" value="<?= $row['Estado'] ?>"><br> -->
                    <?php
                    $estados = [
                      'Aguascalientes' => '',
                      'Baja California' => '',
                      'Baja California Sur' => '',
                      'Campeche' => '',
                      'Chiapas' => '',
                      'Chihuahua' => '',
                      'CDMX' => '',
                      'Coahuila' => '',
                      'Colima' => '',
                      'Durango' => '',
                      'Estado de México' => '',
                      'Guanajuato' => '',
                      'Guerrero' => '',
                      'Hidalgo' => '',
                      'Jalisco' => '',
                      'Michoacán' => '',
                      'Morelos' => '',
                      'Nayarit' => '',
                      'Nuevo León' => '',
                      'Oaxaca' => '',
                      'Puebla' => '',
                      'Querétaro' => '',
                      'Quintana Roo' => '',
                      'San Luis Potosí' => '',
                      'Sinaloa' => '',
                      'Sonora' => '',
                      'Tabasco' => '',
                      'Tamaulipas' => '',
                      'Tlaxcala' => '',
                      'Veracruz' => '',
                      'Yucatán' => '',
                      'Zacatecas' => ''
                    ];
                    $estados[$row['Estado']] = "selected";
                    ?>
                    <label for="estado">Estado</label>
                    <select id="estado" name="estado">
                      <option value="Aguascalientes" <?= $estados['Aguascalientes'] ?>>Aguascalientes</option>
                      <option value="Baja California" <?= $estados['Baja California'] ?>>Baja California</option>
                      <option value="Baja California Sur" <?= $estados['Baja California Sur'] ?>>Baja California Sur</option>
                      <option value="Campeche" <?= $estados['Campeche'] ?>>Campeche</option>
                      <option value="Chiapas" <?= $estados['Chiapas'] ?>>Chiapas</option>
                      <option value="Chihuahua" <?= $estados['Chihuahua'] ?>>Chihuahua</option>
                      <option value="CDMX" <?= isset($estados['CDMX']) ? $estados['CDMX'] : '' ?>>Ciudad de México</option>
                      <option value="Coahuila" <?= $estados['Coahuila'] ?>>Coahuila</option>
                      <option value="Colima" <?= $estados['Colima'] ?>>Colima</option>
                      <option value="Durango" <?= $estados['Durango'] ?>>Durango</option>
                      <option value="Estado de México" <?= $estados['Estado de México'] ?>>Estado de México</option>
                      <option value="Guanajuato" <?= $estados['Guanajuato'] ?>>Guanajuato</option>
                      <option value="Guerrero" <?= $estados['Guerrero'] ?>>Guerrero</option>
                      <option value="Hidalgo" <?= $estados['Hidalgo'] ?>>Hidalgo</option>
                      <option value="Jalisco" <?= $estados['Jalisco'] ?>>Jalisco</option>
                      <option value="Michoacán" <?= $estados['Michoacán'] ?>>Michoacán</option>
                      <option value="Morelos" <?= $estados['Morelos'] ?>>Morelos</option>
                      <option value="Nayarit" <?= $estados['Nayarit'] ?>>Nayarit</option>
                      <option value="Nuevo León" <?= $estados['Nuevo León'] ?>>Nuevo León</option>
                      <option value="Oaxaca" <?= $estados['Oaxaca'] ?>>Oaxaca</option>
                      <option value="Puebla" <?= $estados['Puebla'] ?>>Puebla</option>
                      <option value="Querétaro" <?= $estados['Querétaro'] ?>>Querétaro</option>
                      <option value="Quintana Roo" <?= $estados['Quintana Roo'] ?>>Quintana Roo</option>
                      <option value="San Luis Potosí" <?= $estados['San Luis Potosí'] ?>>San Luis Potosí</option>
                      <option value="Sinaloa" <?= $estados['Sinaloa'] ?>>Sinaloa</option>
                      <option value="Sonora" <?= $estados['Sonora'] ?>>Sonora</option>
                      <option value="Tabasco" <?= $estados['Tabasco'] ?>>Tabasco</option>
                      <option value="Tamaulipas" <?= $estados['Tamaulipas'] ?>>Tamaulipas</option>
                      <option value="Tlaxcala" <?= $estados['Tlaxcala'] ?>>Tlaxcala</option>
                      <option value="Veracruz" <?= $estados['Veracruz'] ?>>Veracruz</option>
                      <option value="Yucatán" <?= $estados['Yucatán'] ?>>Yucatán</option>
                      <option value="Zacatecas" <?= $estados['Zacatecas'] ?>>Zacatecas</option>
                    </select>
                    <span id="ErrorEstado" class="error"></span>
                    <input type="text" name="municipio" id="municipio" placeholder="Municipio" oninput="soloLetras(this);" value="<?= $row['Municipio'] ?>"><br>
                    <span id="ErrorMunicipio" class="error"></span>
                    <input type="text" name="colonia" id="colonia" placeholder="Colonia" oninput="soloLetras(this);" value="<?= $row['Colonia'] ?>"><br>
                    <span id="ErrorColonia" class="error"></span>
                    <input type="text" name="calle" id="calle" placeholder="Calle" oninput="soloLetras(this);" value="<?= $row['Calle'] ?>"><br>
                    <span id="ErrorCalle" class="error"></span>
                  </div>
                  <!-- Datos del denunciante -->
                  <label for="nombre">Nombre del denunciante</label>
                  <input name="nombre" id="nombre" type="text" minlength="3" oninput="soloLetras(this);" value="<?= $row['Nombre'] ?>"><br>
                  <span id="ErrorNombre" class="error"></span>
                  <!-- Curp  -->
                  <label for="curp">CURP</label>
                  <input type="text" name="curp" id="curp" minlength="18" maxlength="18" onkeydown="mayusculas(this);" value="<?= $row['CURP'] ?>"><br>
                  <span id="ErrorCurp" class="error"></span>
                  <!-- Correo -->
                  <label for="correo">Correo</label>
                  <input type="email" name="correo" id="correo" value="<?= $row['Correo'] ?>">
                  <span id="ErrorCorreo" class="error"></span>
                  <!-- Telefono -->
                  <label for="telefono">Numero de telefono</label>
                  <input type="tel" name="telefono" id="telefono" minlength="10" maxlength="10" oninput="soloNumeros(this);" value="<?= $row['Numtelefono'] ?>"><br>
                  <span id="ErrorTelefono" class="error"></span>
                  <!-- Tipo de violencia -->
                  <?php $selected = [
                    "Genero" => "",
                    "Familiar" => "",
                    "Psicologica" => "",
                    "Sexual" => "",
                    "Economica" => "",
                    "Patrimonial" => "",
                    "Cibernetica" => ""
                  ];
                  $selected[$row['Tipo']] = "selected";
                  ?>
                  <label for="tipo">Tipo de violencia</label>
                  <select id="tipo" name="tipo">
                    <option value="Genero" <?= $selected['Genero'] ?>>Violencia de Genero</option>
                    <option value="Familiar" <?= $selected['Familiar'] ?>>Violencia Familiar</option>
                    <option value="Psicologica" <?= $selected['Psicologica'] ?>>Violencia Psicologica</option>
                    <option value="Sexual" <?= $selected['Sexual'] ?>>Violencia Sexual</option>
                    <option value="Economica" <?= $selected['Economica'] ?>>Violencia Economica</option>
                    <option value="Patrimonial" <?= $selected['Patrimonial'] ?>>Violencia Patrimonial</option>
                    <option value="Cibernetica" <?= $selected['Cibernetica'] ?>>Violencia Cibernetica</option>
                  </select>
                  <br>
                  <span id="ErrorTipo" class="error"></span>
                  <!-- Evidencia -->
                  <label for="evidencia">
                    Evidencia
                    <br>
                    <p class="secondary-button">Eligir Archivo</p>
                    <br>
                    <p class="file-info" id="fileName"></p>
                    <br>
                  </label>
                  <input type="file" name="evidencia" id="evidencia" accept=".jpg, .jpeg, .png" oninput="updateFileName(this);"><br>
                  <span id="ErrorEvidencia" class="error"></span>
                  <label for="evidencia">Evidencia actual</label>
                  <img src="data:image/png;base64,<?php echo base64_encode($row['Evidencia']); ?>" alt="Evidencia" class="evidencia"">
                  <span id=" ErrorEvidencia" class="error"></span>
                  <!-- Enviar -->
                  <div class="buttons">
                    <!-- delete -->
                    <button type="button" class="primary-button" name="eliminar" id="delete" onclick="window.modal.showModal();">Eliminar</button>
                    <?php if ($row['Status'] == 0 && $row['Verified'] == 1): ?>
                      <!-- update -->
                      <button type="button" class="primary-button" name="modificar" id="update" onclick="updateDenuncia();">Modificar</button>
                    <?php endif; ?>
                  </div>
                  <span id="ErrorEnviar" class="error"></span>

                </form>
              </div>
            </section>
            <!-- Error al buscar el reporte -->
          <?php else: ?>
            <section class="report-section">
              <div class="report-content">
                <div class="report-container">
                  <div class="icon">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                  </div>
                  <h2 class="section-title">Error al buscar el reporte</h2>
                  <p><?php echo htmlspecialchars($errorMsg); ?></p>
                  <p>Por favor, verifique el folio e intente nuevamente.</p>
                </div>
              </div>
            </section>
          <?php endif; ?>

        <?php else: ?>
          <!-- Error al validar el folio -->
          <section class="report-section">
            <div class="report-content">
              <div class="report-container">
                <div class="icon">
                  <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <h2 class="section-title">Error al Validar el Folio</h2>
                <p><?php echo htmlspecialchars($errorMsg); ?></p>
                <p>Por favor, intente nuevamente más tarde.</p>
              </div>
            </div>
          </section>
        <?php endif; ?>

        <!-- Sino mostrar el formulario de busqueda -->
      <?php else: ?>
        <!-- Mensaje -->
        <section class="hero-section">
          <div class="hero-content">
            <div class="search-icon">
              <i class="fa-solid fa-magnifying-glass"></i>
            </div>
            <h1 class="hero-title">Consultar Reportes</h1>
            <p class="hero-text">
              Busca informacion y actualizaciones sobre reportes previos.
            </p>
            <!-- <div class="hero-buttons">
              <a href="reportar.html" class="primary-button">Reportar Incidente</a>
              <a href="recursos.html" class="secondary-button">Acceder a Recursos</a>
            </div> -->
          </div>
        </section>

        <!-- Seccion para buscar -->
        <section class="search-section">
          <div class="search-content">
            <h2 class="section-title">Buscar Reportes</h2>
            <form id="buscar" method=GET action="" onsubmit="return validateSearch(this); " onchange="saveFolio();">
              <input type="text" name="folio" id="folio" placeholder="Ingrese el Folio del reporte" maxlength="64" />
              <button type="submit" class="primary-button">Buscar</button>
              <span class="error" id="ErrorFolio"></span>
              <a href="recuperar.php">¿Olvidaste tu folio?</a>
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
</body>

</html>

<dialog id="modal">
  <h2>¿Desea eliminar esta denuncia?</h2>
  <p>Esta seguro de que desea eliminar esta denuncia</p>
  <div class="buttons">
    <button type="button" class="primary-button" onclick="window.modal.close();">Cancelar</button>
    <button type="button" class="delete-button" onclick="deleteDenuncia();">Eliminar</button>
  </div>
</dialog>