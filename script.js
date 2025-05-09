// Funcion par mostrar el mensaje de error
function showError(idElement, message) {
    document.getElementById(idElement).innerText = message;
}

// Funcion para validar campos
function validateForm(object) {
    // Detener el envío del formulario
    object.preventDefault();

    // Obtener valores de los campos
    var hechos = document.getElementById('hechos').value;
    var fecha = document.getElementById('fecha').value;
    var hora = document.getElementById('hora').value;
    var ubicacion = document.getElementById('ubicacion').value;
    var nombre = document.getElementById('nombre').value;
    var curp = document.getElementById('curp').value;
    var correo = document.getElementById('correo').value;
    var telefono = document.getElementById('telefono').value;
    var tipo = document.getElementById('tipo').value;

    // Verificar si el campo 'hechos' está vacío
    if (hechos.length < 10) {
        showError("ErrorHechos" ,"Por favor, describa los hechos.");
        return false;
    }

    // Verificar si el campo 'fecha' está vacío
    if (fecha == "") {
        showError("ErrorFecha","Por favor, seleccione una fecha.");
        return false;
    }

    // Verificar si el campo 'hora' está vacío
    if (hora == "") {
        showError("ErrorHora","Por favor, seleccione una hora.");
        return false;
    }

    // Verificar si el campo 'ubicacion' está vacío
    if (ubicacion == "") {
        showError("ErrorUbicacion","Por favor, ingrese la ubicación.");
        return false;
    }

    // Verficar si el campo 'nombre' está vacío o tiene menos de 3 caracteres o es un número
    if (nombre == "" || nombre.length < 3 || !isNaN(nombre)) {
        showError("ErrorNombre","Por favor, ingrese un nombre válido (mínimo 3 caracteres).");
        return false;
    }

    // Verificar si el campo 'curp' está vacío o no tiene 18 caracteres
    if (curp == "" || curp.length != 18) {
        showError("ErrorCurp","Por favor, ingrese un CURP válido (18 caracteres).");
        return false;
    }

    // Verificar si el campo 'correo' está vacío o no tiene un formato de correo válido
    var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    if (correo == "" || !emailPattern.test(correo)) {
        showError("ErrorCorreo","Por favor, ingrese un correo electrónico válido.");
        return false;
    }

    // Verificar si el campo 'telefono' está vacío o no tiene 10 dígitos
    var phonePattern = /^[0-9]{10}$/;
    if (telefono == "" || !phonePattern.test(telefono)) {
        showError("ErrorTelefono","Por favor, ingrese un número de teléfono válido (10 dígitos).");
        return false;
    }

    // Verificar si el campo 'tipo' está vacío
    if (tipo == "") {
        showError("ErrorTipo","Por favor, seleccione un tipo de violencia.");
        return false;
    }

    // Devolver 'true' si todos los campos son válidos
    alert("Formulario enviado correctamente.");
    object.submit(); // Enviar el formulario si todo es correcto
    return true;
}