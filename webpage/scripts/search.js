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
}

// Funcion para validar campos
function validateForm() {
    // Variable de validación
    var valid = true;

    // Limpiar mensajes de error
    clearError();

    // Verificar si el campo 'hechos' está vacío
    if (window.report.hechos.value.length < 10 || !/^[a-zA-Z0-9\s.,]+$/.test(window.report.hechos.value)) {
        showError("ErrorHechos", "Por favor, describa los hechos.");
        valid = false;
    }

    // Verificar si el campo 'fecha' está vacío
    fecha = window.report.fecha.value;
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
    if (window.report.hora.value == "") {
        showError("ErrorHora", "Por favor, seleccione una hora.");
        valid = false;
    }

    // Verificar el campo estado
    if (window.report.estado.valid == "" || window.report.estado.value.length < 5) {
        showError("ErrorEstado", "Por favor, ingrese un estado válido.");
        valid = false;
    }

    // Verificar el campo municipio
    if (window.report.municipio.valid == "" || window.report.municipio.value.length < 5) {
        showError("ErrorMunicipio", "Por favor, ingrese un municipio válido.");
        valid = false;
    }

    // Verificar el campo colonia
    if (window.report.colonia.valid == "" || window.report.colonia.value.length < 5) {
        showError("ErrorColonia", "Por favor, ingrese una colonia válida.");
        valid = false;
    }

    // Verificar el campo calle
    if (window.report.calle.valid == "" || window.report.calle.value.length < 5) {
        showError("ErrorCalle", "Por favor, ingrese una calle válida.");
        valid = false;
    }

    // Verificar si el campo 'nombre' está vacío o tiene menos de 3 caracteres o es un número
    nombre = window.report.nombre.value;
    if (nombre == "" || nombre.length < 3 || !isNaN(nombre)) {
        showError("ErrorNombre", "Por favor, ingrese un nombre válido (mínimo 3 caracteres).");
        valid = false;
    }

    // Verificar si el campo 'curp' es válido
    if (!/^[A-Z]{4}[0-9]{6}[\w]{8}$/.test(window.report.curp.value)) {
        showError("ErrorCurp", "Por favor, ingrese un CURP válido (18 caracteres).");
        valid = false;
    }

    // Verificar si el campo 'correo' está vacío o no tiene un formato de correo válido
    var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    if (window.report.correo.valid == "" || !emailPattern.test(window.report.correo.value)) {
        showError("ErrorCorreo", "Por favor, ingrese un correo electrónico válido.");
        valid = false;
    }

    // Verificar si el campo 'telefono' está vacío o no tiene 10 dígitos
    var phonePattern = /^[0-9]{10}$/;
    if (telefono == "" || !phonePattern.test(window.report.telefono.value)) {
        showError("ErrorTelefono", "Por favor, ingrese un número de teléfono válido (10 dígitos).");
        valid = false;
    }

    // Verificar si el campo 'tipo' está vacío
    if (window.report.tipo.value == "") {
        showError("ErrorTipo", "Por favor, seleccione un tipo de violencia.");
        valid = false;
    }

    // Validar el archivo de evidencia
    let evidencia = window.report.evidencia;
    // Verificar si el campo 'evidencia' está vacío
    if (evidencia.value == "") {
        showError("ErrorEvidencia", "Por favor, suba un archivo de evidencia.");
        valid = false;
        return valid;
    }
    var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif|\.pdf)$/i;
    if (evidencia.value == "" || !allowedExtensions.exec(evidencia.value)) {
        showError("ErrorEvidencia", "Por favor, suba un archivo de evidencia válido (jpg, jpeg, png, gif, pdf).");
        valid = false;
    }

    // Verificar si el campo 'evidencia' tiene un tamaño máximo de 2MB
    if (evidencia.files[0].size > 2 * 1024 * 1024) {
        showError("ErrorEvidencia", "El archivo de evidencia no debe exceder los 2MB.");
        valid = false;
    }

    // Veerifivar el MIME type del archivo
    var mimeType = evidencia.files[0].type;
    var allowedMimeTypes = ["image/jpeg", "image/png", "image/gif", "application/pdf"];
    if (!allowedMimeTypes.includes(mimeType)) {
        showError("ErrorEvidencia", "El archivo de evidencia debe ser una imagen o un PDF.");
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

// Funcion para eliminar una denuncia
function deleteDenuncia(){
    window.modal.close()
    form = document.getElementById("report")
    // Modificar el action del formulario
    form.setAttribute('action','delete-report.php')
    // Enviar el formulario
    form.submit()   
}

// Funcion para actualizar una denuncia
function updateDenuncia(){
    form = document.getElementById("report")
    // Modificar el action del formulario
    form.setAttribute('action','update-report.php')
    if (validateForm()) {
        // Enviar el formulario
        form.submit()
    }
}