// Funcion par mostrar el mensaje de error
function showError(idElement, message) {
    document.getElementById(idElement).innerText = message;
}

// Funcion para limpiar los mensajes de error
function clearError(){
    // Limpiar los mensajes de error de los campos
    document.getElementById("ErrorHechos").innerText = "";
    document.getElementById("ErrorFecha").innerText = "";
    document.getElementById("ErrorHora").innerText = "";
    document.getElementById("ErrorUbicacion").innerText = "";
    document.getElementById("ErrorNombre").innerText = "";
    document.getElementById("ErrorCurp").innerText = "";
    document.getElementById("ErrorCorreo").innerText = "";
    document.getElementById("ErrorTelefono").innerText = "";
    document.getElementById("ErrorTipo").innerText = "";
    document.getElementById("ErrorTerminos").innerText = "";
}

// Funcion para validar campos
function validateForm() {
    // Detener el envío del formulario
    // event.preventDefault();

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
    var terminos = document.getElementById('terminos').checked;

    // Variable de validación
    var valid = true;

    // Limpiar mensajes de error
    clearError();

    // Verificar si el campo 'hechos' está vacío
    if (hechos.length < 10) {
        showError("ErrorHechos", "Por favor, describa los hechos.");
        valid = false;
    }

    // Verificar si el campo 'fecha' está vacío
    if (fecha == "") {
        showError("ErrorFecha", "Por favor, seleccione una fecha.");
        valid = false;
    }

    // Verificar si la fecha es válida
    var fechaActual = new Date();
    var fechaSeleccionada = new Date(fecha);
    if (fechaSeleccionada > fechaActual) {
        showError("ErrorFecha", "La fecha seleccionada no puede ser posterior a la fecha actual.");
        valid = false;
    }

    // Verificar si el campo 'hora' está vacío
    if (hora == "") {
        showError("ErrorHora", "Por favor, seleccione una hora.");
        valid = false;
    }

    // Verificar si el campo 'ubicacion' está vacío
    if (ubicacion == "" || ubicacion.length < 5) {
        showError("ErrorUbicacion", "Por favor, ingrese una ubicación válida.");
        valid = false;
    }

    // Verificar si el campo 'nombre' está vacío o tiene menos de 3 caracteres o es un número
    if (nombre == "" || nombre.length < 3 || !isNaN(nombre)) {
        showError("ErrorNombre", "Por favor, ingrese un nombre válido (mínimo 3 caracteres).");
        valid = false;
    }

    // Verificar si el campo 'curp' es válido
    if (!/^[A-Z]{4}[0-9]{6}[\w]{8}$/.test(curp)) {
        showError("ErrorCurp", "Por favor, ingrese un CURP válido (18 caracteres).");
        valid = false;
    }

    // Verificar si el campo 'correo' está vacío o no tiene un formato de correo válido
    var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    if (correo == "" || !emailPattern.test(correo)) {
        showError("ErrorCorreo", "Por favor, ingrese un correo electrónico válido.");
        valid = false;
    }

    // Verificar si el campo 'telefono' está vacío o no tiene 10 dígitos
    var phonePattern = /^[0-9]{10}$/;
    if (telefono == "" || !phonePattern.test(telefono)) {
        showError("ErrorTelefono", "Por favor, ingrese un número de teléfono válido (10 dígitos).");
        valid = false;
    }

    // Verificar si el campo 'tipo' está vacío
    if (tipo == "") {
        showError("ErrorTipo", "Por favor, seleccione un tipo de violencia.");
        valid = false;
    }

    // Verificar si el checkbox 'terminos' está marcado
    if (!terminos) {
        showError("ErrorTerminos", "Por favor, acepte los términos y condiciones.");
        valid = false;
    }
    
    return valid;
}

// Funcion para pasar a mayusculas el curp
function mayusculas(e) {
    e.value = e.value.toUpperCase();
}

// Funcion para solo escribir letras en el nombre
function soloLetras(e) {
    e.value = e.value.replace(/[^a-zA-Z\s]/g, '');
}

// Funcion para solo escribir numeros en el telefono
function soloNumeros(e) {
    e.value = e.value.replace(/[^0-9]/g, '');
}
