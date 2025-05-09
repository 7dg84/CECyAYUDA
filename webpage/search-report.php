<?php
// Incluir la clase de conexión a la base de datos
include_once 'db.php';

$folio = $_GET['folio'];

// Mostrar errror en caso de que el folio no sea valido
function error($message) {
  echo "<div class=\"report-container\">";
  echo "<h2 class=\"section-title\">Consulta de Reporte</h2>";
  echo "<h2>$message</h2>";
  echo "</div>";
}

// Renderizar el formulario de entrada
function renderInput($type, $name, $placeholder, $value) {
  return "
      <!-- $placeholder -->
      <label for=\"$name\">$placeholder</label>
      <input type=\"$type\" name=\"$name\" id=\"$name\" value=\"" . htmlspecialchars($value) . "\">
      <span class=\"error\"></span>
  ";
}

// Renderizar el tipo de violencia
function renderTipo($type) {
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
  <select id=\"tipo\" name=\"tipo\">
    <option value=\"Genero\" $selected[$type]>Violencia de Genero</option>
    <option value=\"Familiar\" $selected[$type]>Violencia Familiar</option>
    <option value=\"Psicologica\" $selected[$type]>Violencia Psicologica</option>
    <option value=\"Sexual\" $selected[$type]>Violencia Sexual</option>
    <option value=\"Economica\" $selected[$type]>Violencia Economica</option>
    <option value=\"Patrimonial\" $selected[$type]>Violencia Patrimonial</option>
    <option value=\"Cibernetica\" $selected[$type]>Violencia Cibernetica</option>
  </select>
  ";
}

// Renderizar los hechos
function renderHechos($hechos) {
  return "
  <!-- Hechos  -->
  <label for=\"hechos\">Hechos</label>
  <textarea name=\"hechos\" id=\"hechos\" cols=\"30\" rows=\"10\" >".
  $hechos.
  "</textarea><br>
  <span id=\"ErrorHechos\" class=\"error\"></span>
  ";
}

// Renderizar los botones
function renderButton($type, $name, $value, $function,) {
  return "
            <!-- $type -->
            <button class=\"primary-button\" type=\"submit\" name=\"$name\" id=\"$type\" onclick=\"$function\">$value</button><br>
            <span id=\"ErrorEnviar\" class=\"error\"></span>
            </form>
            ";
}

// Buscar el reporte por folio
function search($folio) {
  try {
    // Crear una instancia de la clase de conexión
    $database = new Database();

    // Buscar la denuncia por folio
    $stmt = $database->searchDenuncia($folio);
    
    if ($stmt->num_rows > 0) {
        // Mostrar los resultados
        while ($row = $stmt->fetch_assoc()) {

            echo "
            <form id=\"report\" method=\"post\" onsubmit=\"return validateForm(this);\" >
            <!-- Formulario de denuncia de violencia de género -->
            <h1 class=\"section-title\">Formulario de denuncia de violencia de Genero </h1>"
            . "<p><strong>Folio:</strong> " . htmlspecialchars($row['Folio']) . "</p>"."
              <p><strong>Status:</strong> " . htmlspecialchars($row['Status']) . "</p>"."
            ";
        
            echo renderHechos($row['Descripcion']);
            echo "<div class=\"date-time-container\">";
            echo renderInput("date", "fecha", "Fecha", $row['Fecha']);
            echo renderInput("time", "hora", "Hora", $row['Hora']);
            echo "</div>";
            echo renderInput("text", "ubicacion", "Ubicación", $row['Ubicacion']);
            echo renderInput("text", "nombre", "Nombre del denunciante", $row['Nombre']);
            echo renderInput("text", "curp", "CURP", $row['CURP']);
            echo renderInput("email", "correo", "Correo", $row['Correo']);
            echo renderInput("tel", "telefono", "Número de teléfono", $row['Numtelefono']);
            echo renderTipo($row['Tipo']);
            
            echo "<div class=\"buttons\">";
            echo renderButton("modify", "modificar", "Modificar", "deleteDenuncia();");
            echo renderButton("delete", "eliminar", "Eliminar", "");
            echo "</div>";
        }
    } else {
        error("No se encontraron resultados para el folio proporcionado.");
    }
} catch (Exception $e) {
    error("Error al buscar el reporte: " . htmlspecialchars($e->getMessage()));
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

    <script src="scripts/mobile.js"></script>
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
            <a href="consultar.html" class="active">Consultar Reportes</a>
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
      <!-- Contenido del reporte -->
       <section class="report-section">
        <div class="report-content">
          <?php
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
            ?>
        </div>
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