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

    echo "reporte eliminado";

    // Redirigir a la página de inicio después de 5 segundos
    echo "<p>Redirigiendo a la página de inicio...</p>";
    echo "<script>setTimeout(function() { window.location.href = 'index.html'; }, 5000);</script>";
?>