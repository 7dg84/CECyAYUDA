// Funcion par mostrar el mensaje de error
function showError(idElement, message) {
    document.getElementById(idElement).innerText = message;
}

function validateSearch() {
    // Obtener el folio de la consulta
    var folio = document.getElementById('folio').value;

    // Limpiar el mensaje de error
    showError('ErrorFolio', '');

    // Validar que el folio no esté vacío
    if (folio == "") {
        showError('ErrorFolio', 'El folio no puede estar vacío');
        return false;
    } else {
        // Validar el largo de 64 caracteres
        if (folio.length != 64) {
            showError('ErrorFolio', 'El folio debe tener 64 caracteres');
            return false;
        } else {
            // validar el folio con una expresión regular
            if (/[a-zA-Z0-9]{64}/.test(folio)) {
                return true;
            } else {
                showError('ErrorFolio', 'El folio solo puede contener letras y números');
                return false;
            }
        }
    }
}