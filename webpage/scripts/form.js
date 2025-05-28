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
    document.getElementById("ErrorEstado").innerText = "";
    document.getElementById("ErrorMunicipio").innerText = "";
    document.getElementById("ErrorColonia").innerText = "";
    document.getElementById("ErrorCalle").innerText = "";
    document.getElementById("ErrorNombre").innerText = "";
    document.getElementById("ErrorCurp").innerText = "";
    document.getElementById("ErrorCorreo").innerText = "";
    document.getElementById("ErrorTelefono").innerText = "";
    document.getElementById("ErrorTipo").innerText = "";
    document.getElementById("ErrorTerminos").innerText = "";
}

// Funcion para validar campos
function validateForm() {
    // Variable de validación
    var valid = true;

    // Limpiar mensajes de error
    clearError();

    // Verificar si el campo 'hechos' está vacío o esta conformado por caracteres alfanumericos
    if (window.Report.hechos.value.length < 10 || !/^[a-zA-Z0-9\s.,]+$/.test(window.Report.hechos.value)) {
        showError("ErrorHechos", "Por favor, describa los hechos.");
        valid = false;
    }

    // Verificar si el campo 'fecha' está vacío
    let fecha = window.Report.fecha.value;
    if (fecha == "") {
        showError("ErrorFecha", "Por favor, seleccione una fecha.");
        valid = false;
    }

    // Verificar si la fecha es anterior a la actual
    var fechaActual = new Date();
    var fechaSeleccionada = new Date(fecha);
    if (fechaSeleccionada > fechaActual) {
        showError("ErrorFecha", "La fecha seleccionada no puede ser posterior a la fecha actual.");
        valid = false;
    }

    // Verificar si el campo 'hora' está vacío
    if (window.Report.hora.value == "") {
        showError("ErrorHora", "Por favor, seleccione una hora.");
        valid = false;
    }

    // Verificar si el campo estado esta vacio o tiene menos de 5 caracteres
    if (window.Report.estado.value == "" || window.Report.estado.value.length < 5) {
        showError("ErrorEstado", "Por favor, ingrese un estado válido.");
        valid = false;
    }

    // Verificar si el campo municipio esta vacio o tiene menos de 5 caracters
    if (window.Report.municipio.value == "" || window.Report.municipio.value.length < 5) {
        showError("ErrorMunicipio", "Por favor, ingrese un municipio válido.");
        valid = false;
    }

    // Verificar si el campo colonia esta vacio o tiene menos de 5 caracteres
    if (window.Report.colonia.value == "" || window.Report.colonia.value.length < 5) {
        showError("ErrorColonia", "Por favor, ingrese una colonia válida.");
        valid = false;
    }

    // Verificar si el campo calle esta vacio o tiene menos de 5 carcteres
    if (window.Report.calle.value == "" || window.Report.calle.value.length < 5) {
        showError("ErrorCalle", "Por favor, ingrese una calle válida.");
        valid = false;
    }
    
    // Verificar si el campo 'nombre' está vacío o tiene menos de 3 caracteres o no es un número
    let nombre = window.Report.nombre.value;
    if (nombre == "" || nombre.length < 3 || !isNaN(Number(nombre))) {
        showError("ErrorNombre", "Por favor, ingrese un nombre válido (mínimo 3 caracteres).");
        valid = false;
    }
    
    // Verificar si el campo 'curp' inicia con 4 letras, luego 6 numeros y despues 8 caracteres alfanumericos
    if (!/^[A-Z]{4}[0-9]{6}[A-Z0-9]{8}$/.test(window.Report.curp.value)) {
        showError("ErrorCurp", "Por favor, ingrese un CURP válido (18 caracteres).");
        valid = false;
    }

    // Verificar si el campo 'correo' está vacío o no tiene un formato de correo válido con caracteres alfanumericos, @, dominio
    var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (window.Report.correo.value == "" || !emailPattern.test(window.Report.correo.value)) {
        showError("ErrorCorreo", "Por favor, ingrese un correo electrónico válido.");
        valid = false;
    }

    // Verificar si el campo 'telefono' está vacío o no tiene 10 dígitos
    var phonePattern = /^[0-9]{10}$/;
    if (window.Report.telefono.value == "" || !phonePattern.test(window.Report.telefono.value)) {
        showError("ErrorTelefono", "Por favor, ingrese un número de teléfono válido (10 dígitos).");
        valid = false;
    }

    // Verificar si el campo 'tipo' está vacío
    if (window.Report.tipo.value == "") {
        showError("ErrorTipo", "Por favor, seleccione un tipo de violencia.");
        valid = false;
    }

    // Verificar si el checkbox 'terminos' está marcado
    if (!window.Report.terminos.checked) {
        showError("ErrorTerminos", "Por favor, acepte los términos y condiciones.");
        valid = false;
    }

    // Validar el archivo de evidencia
    let evidencia = window.Report.evidencia;
    // Verificar si el campo 'evidencia' está vacío
    if (evidencia.value == "") {
        showError("ErrorEvidencia", "Por favor, suba un archivo de evidencia.");
        valid = false;
    }
    
    // Verificar si el campo 'evidencia' tiene un tamaño máximo de 2MB
    if (evidencia.files && evidencia.files[0] && evidencia.files[0].size > 2 * 1024 * 1024) {
        showError("ErrorEvidencia", "El archivo de evidencia no debe exceder los 2MB.");
        valid = false;
        return valid;
    }
    
    // Veerifivar el MIME type del archivo
    var mimeType = evidencia.files && evidencia.files[0] ? evidencia.files[0].type : null;
    var allowedMimeTypes = ["image/jpeg", "image/png", "image/jpg"];
    if (!allowedMimeTypes.includes(mimeType)) {
        showError("ErrorEvidencia", "El archivo de evidencia debe ser una imagen.");
        valid = false;
        return valid;
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
