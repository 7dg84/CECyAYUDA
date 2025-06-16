// Funcion para validar campos
function validateRecovery() {
    // Variable de validación
    var valid = true;

    // Limpiar mensajes de error
    showError("ErrorNombre", "");
    showError("ErrorCurp", "");
    showError("ErrorCorreo", "");
    showError("ErrorTelefono", "");
   
    // Verificar si el campo 'nombre' está vacío o tiene menos de 3 caracteres o no es un número
    let nombre = window.Recuperar.nombre.value;
    if (nombre == "" || nombre.length < 3 || !isNaN(Number(nombre))) {
        showError("ErrorNombre", "Por favor, ingrese un nombre válido (mínimo 3 caracteres).");
        valid = false;
    }
    
    // Verificar si el campo 'curp' inicia con 4 letras, luego 6 numeros y despues 8 caracteres alfanumericos
    if (!/^[A-Z]{4}[0-9]{6}[A-Z0-9]{8}$/.test(window.Recuperar.curp.value)) {
        showError("ErrorCurp", "Por favor, ingrese un CURP válido (18 caracteres).");
        valid = false;
    }

    // Verificar si el campo 'correo' está vacío o no tiene un formato de correo válido con caracteres alfanumericos, @, dominio
    var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (window.Recuperar.correo.value == "" || !emailPattern.test(window.Recuperar.correo.value)) {
        showError("ErrorCorreo", "Por favor, ingrese un correo electrónico válido.");
        valid = false;
    }

    // Verificar si el campo 'telefono' está vacío o no tiene 10 dígitos
    var phonePattern = /^[0-9]{10}$/;
    if (window.Recuperar.telefono.value == "" || !phonePattern.test(window.Recuperar.telefono.value)) {
        showError("ErrorTelefono", "Por favor, ingrese un número de teléfono válido (10 dígitos).");
        valid = false;
    }

    return valid;
}