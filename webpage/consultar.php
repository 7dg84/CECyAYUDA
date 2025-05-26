<?php
// Incluir la clase de conexión a la base de datos
include_once 'admin/db.php';

// Variable de error
$errorMSG = "";

// Mostrar errror en caso de que el folio no sea valido
function error($message)
{
  echo "
  <div class=\"report-container\">
  <div class=\"icon\">\n<i class=\"fa-solid fa-triangle-exclamation\"></i>\n</div>
  <h2 class=\"section-title\">Error</h2>
  <h2>$message</h2>
  </div>
  ";
}

// Renderizar el formulario de entrada
function renderInput($type, $name, $placeholder, $value)
{
  return "
      <!-- $placeholder -->
      <label for=\"$name\">$placeholder</label>
      <input type=\"$type\" name=\"$name\" id=\"$name\" value=\"" . htmlspecialchars($value) . "\">
      <span class=\"error\" id=\"Error" . ucfirst($name) . "\"></span>
  ";
}

// Renderizar los hechos
function renderHechos($hechos)
{
  return "
  <!-- Hechos  -->
  <label for=\"hechos\">Hechos</label>
  <textarea name=\"hechos\" id=\"hechos\" cols=\"30\" rows=\"10\" >" .
    $hechos .
    "</textarea><br>
  <span id=\"ErrorHechos\" class=\"error\"></span>
  ";
}

// Renderizar el tipo de violencia
function renderTipo($type)
{
  $selected = [
    "Genero" => "",
    "Familiar" => "",
    "Psicologica" => "",
    "Sexual" => "",
    "Economica" => "",
    "Patrimonial" => "",
    "Cibernetica" => ""
  ];
  $selected[$type] = "selected";
  return "
  <!-- Tipo de violencia -->
  <label for=\"tipo\">Tipo de violencia</label>
  <select id=\"tipo\" name=\"tipo\">
      <option value=\"Genero\" $selected[Genero]>Violencia de Genero</option>
      <option value=\"Familiar\" $selected[Familiar]>Violencia Familiar</option>
      <option value=\"Psicologica\" $selected[Psicologica]>Violencia Psicologica</option>
      <option value=\"Sexual\" $selected[Sexual]>Violencia Sexual</option>
      <option value=\"Economica\" $selected[Economica]>Violencia Economica</option>
      <option value=\"Patrimonial\" $selected[Patrimonial]>Violencia Patrimonial</option>
      <option value=\"Cibernetica\" $selected[Cibernetica]>Violencia Cibernetica</option>
  </select>
  <span id=\"ErrorTipo\" class=\"error\"></span>
  ";
}

// Renderizar los botones
function renderButton($type, $name, $value, $function)
{
  return "
  <!-- $type -->
  <button type=\"button\" class=\"primary-button\"  name=\"$name\" id=\"$type\" onclick=\"$function\">$value</button><br>
  </form>
  ";
}

// obtener valor el Status
function statusValue($value)
{
  $values = [
    0 => "En Proceso",
    1 => "Resuelto",
    2 => "No Resuelto"
  ];
  return $values[$value] ?? "Desconocido";
}

// Buscar el reporte por folio
function search($folio)
{
  $html = "";
  $row = null;
  try {
    $database = new Denuncia();
    $stmt = $database->searchDenuncia($folio);

    if ($stmt->num_rows > 0) {
      $row = $stmt->fetch_assoc();
    } else {
      $html .= error("No se encontraron resultados para el folio proporcionado.");
    }
    $database->closeConnection();
  } catch (Exception $e) {
    $html .= error("Error al buscar el reporte: " . htmlspecialchars($e->getMessage()));
  }
  return $row;
}

// Verificar si se ha enviado el formulario
function checkForm()
{
  return isset($_GET['folio']) && !empty($_GET['folio']);
}

// Validar el folio
function validateFolio($folio)
{
  $regex = "/^[a-f0-9]{64}$/";
  return preg_match($regex, $folio);
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
  <link rel="stylesheet" href="styles/consult.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

  <script src="scripts/mobile.js"></script>
  <script src="scripts/search.js"></script>
  <script src="scripts/form.js"></script>
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
      <?php if (checkForm()): ?>
        <!-- Contenido del reporte -->
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
                <form id="report" method=POST enctype=multipart/form-data action="" onsubmit="return validateForm(this);">
                  <!-- Formulario de denuncia de violencia de género -->
                  <h1 class="section-title">Formulario de denuncia de violencia de Genero </h1>
                  <!-- Folio -->
                  <label for="folio">Folio</label>
                  <p><?= $row['Folio'] ?></p><br>
                  <!-- Status -->
                  <label for="status">Status</label>
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
                    <input type="time" name="hora" id="hora" value="<?= $row['Hora'] ?>"><br>
                    <br>
                  </div>
                  <span id="ErrorFecha" class="error"></span><br>
                  <span id="ErrorHora" class="error"></span>
                  <!-- Ubicacion -->
                  <label for="ubicacion">Ubicacion</label>
                  <div class="ubicacion-container">
                    <input type="text" name="estado" id="estado" placeholder="Estado" oninput="soloLetras(this);" value="<?= $row['Estado'] ?>"><br>
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
                  <label for="evidencia">Evidencia</label>
                  <input type="file" name="evidencia" id="evidencia" accept=".jpg, .jpeg, .png, .pdf" class="secondary-button"><br>
                  <img src="data:image/png;base64,<?php echo base64_encode($row['Evidencia']); ?>" alt="Evidencia" class="evidencia">


                  <span id="ErrorEvidencia" class="error"></span>
                  <!-- Enviar -->
                  <div class="buttons">
                    <?php if ($row['Status'] == 0 && $row['Verified'] == 1): ?>
                      <?= renderButton("update", "modificar", "Modificar", "updateDenuncia();"); ?>
                    <?php endif; ?>
                    <!-- delete -->
                    <button type="button" class="primary-button" name="eliminar" id="delete" onclick="window.modal.showModal();">Eliminar</button><br>
                  </div>
                  <span id="ErrorEnviar" class="error"></span>

                </form>
              </div>
            </section>
          <?php endif; ?>
        <?php else: ?>
          <?php error("El folio proporcionado no es válido."); ?>
        <?php endif; ?>

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
            <form method="get" action="" onsubmit="return validateSearch(this);">
              <input type="text" name="folio" id="folio" placeholder="Ingrese el Folio del reporte" maxlength="64" minlength="64" />
              <button type="submit" class="primary-button">Buscar</button>
              <span class="error" id="ErrorFolio"></span>
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