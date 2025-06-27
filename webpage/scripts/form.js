// Funcion par mostrar el mensaje de error
function showError(idElement, message) {
    document.getElementById(idElement).innerText = message;
}

// Funcion para limpiar los mensajes de error
function clearError() {
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
    document.getElementById("ErrorEvidencia").innerText = "";
    document.getElementById("ErrorTerminos").innerText = "";
    document.getElementById("ErrorCP").innerText = "";
}

// Funcion para validar campos
function validateForm() {
    // Variable de validación
    var valid = true;

    // Limpiar mensajes de error
    clearError();

    // Verificar si el campo 'hechos' está vacío o esta conformado por caracteres alfanumericos
    if (window.Report.hechos.value.length < 10 || !/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑüÜ\s.,;:¡!¿?\-'"()]+$/.test(window.Report.hechos.value)) {
        showError("ErrorHechos", "Por favor, describa los hechos. (10 caracteres mínimos, se permiten acentos y puntuación básica)");
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

    // Validar el campo `cp`
    if (!/^\d{5}$/.test(window.Report.cp.value)) {
        showError("ErrorCP", "Código postal no válido");
        valid = false;
    }

    // Verificar si el campo estado esta en los estados de la republica mexicana
    let estados = [
        'Aguascalientes',
        'Baja California',
        'Baja California Sur',
        'Campeche',
        'Chiapas',
        'Chihuahua',
        'CDMX',
        'Coahuila',
        'Colima',
        'Durango',
        'México',
        'Guanajuato',
        'Guerrero',
        'Hidalgo',
        'Jalisco',
        'Michoacán',
        'Morelos',
        'Nayarit',
        'Nuevo León',
        'Oaxaca',
        'Puebla',
        'Querétaro',
        'Quintana Roo',
        'San Luis Potosí',
        'Sinaloa',
        'Sonora',
        'Tabasco',
        'Tamaulipas',
        'Tlaxcala',
        'Veracruz',
        'Yucatán',
        'Zacatecas'
    ];
    if (!estados.includes(window.Report.estado.value)) {
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

// Funcion para actualizar el nombre del archivo de evidencia
function updateFileName(input) {
    // Obtener el nombre del archivo seleccionado
    var fileName = input.files && input.files[0] ? input.files[0].name : "";

    // Actualizar el texto del elemento con el ID 'fileName'
    document.getElementById("fileName").innerText = fileName ? "Archivo seleccionado: " + fileName : "No se ha seleccionado ningún archivo.";
}

// Funcion para buscar en la API SEPOMEX los datos de el CP
function getColonias(e) {
    showError('ErrorCP', '');
    let cp = e.value.trim();
    // Validar: debe ser numérico y de 5 dígitos
    if (!/^\d{5}$/.test(cp)) {
        showError("ErrorCP", "Código postal no válido");
        return false;
    }
    // Request a la API de SEPOMEX
    fetch('https://sepomex.icalialabs.com/api/v1/zip_codes?per_page=200&zip_code=' + cp + '')
        .then(response => response.json())
        .then(data => {
            // Si se devolvio informacion
            if (data && data.zip_codes && data.zip_codes.length > 0) {
                // Variables para almacenar los datos
                const meta = data.meta;
                var estados = [];
                var municipios = [];
                var colonias = []

                // Guardar Datos
                data.zip_codes.forEach(colonia => {
                    // Guardar el estado de cada una de las colonias
                    if (!estados.includes(colonia.d_estado)) {
                        estados.push(colonia.d_estado)
                    }
                    // Guardar el municipio de cada una de las colonias
                    if (!municipios.includes(colonia.d_ciudad)) {
                        municipios.push(colonia.d_ciudad)
                    }
                    // Guardar cada colonia
                    colonias.push(colonia.d_asenta)
                });
                // Agregar Estados
                if (estados.length >= 1) {
                    document.getElementById('estado').innerHTML = '';
                    const optionEstado = document.getElementById('estado');
                    estados.forEach(estado => {
                        const option = document.createElement("option");
                        option.value = estado;
                        option.textContent = estado;
                        optionEstado.appendChild(option);
                    })
                }
                // Agregar Municipio
                if (municipios.length >= 1) {
                    document.getElementById('municipio').outerHTML = "<select id='municipio' name='municipio'>";
                    const optionMun = document.getElementById('municipio');
                    municipios.forEach(municipio => {
                        const option = document.createElement("option");
                        option.value = municipio;
                        option.textContent = municipio;
                        optionMun.appendChild(option);
                    })
                }
                // Agregar colonias
                if (colonias.length >= 1) {
                    document.getElementById("colonia").outerHTML = "<select id='colonia' name='colonia'>"; // Cambiar el input por select
                    // Select antes creado
                    const select = document.getElementById("colonia");
                    // Campo Seleccione una
                    const optionColonia = document.createElement("option");
                    optionColonia.value = "";
                    optionColonia.textContent = "Seleccione Una";
                    select.appendChild(optionColonia);
                    // Agregar todas las colonias
                    colonias.forEach(colonia => {
                        const option = document.createElement("option");
                        option.value = colonia;
                        option.textContent = colonia;
                        select.appendChild(option);
                    })
                }
            } else {
                showError("ErrorCP", "No se encontraron colonias para este código postal.");
            }
        })
        .catch(() => {
            showError("ErrorCP", "Error al consultar el código postal.");
        });
    document.getElementById('estado').innerHTML = `
                    <option value="no">Seleccione uno...</option>
                    <option value="Aguascalientes">Aguascalientes</option>
                    <option value="Baja California">Baja California</option>
                    <option value="Baja California Sur">Baja California Sur</option>
                    <option value="Campeche">Campeche</option>
                    <option value="Chiapas">Chiapas</option>
                    <option value="Chihuahua">Chihuahua</option>
                    <option value="CDMX">Ciudad de México</option>
                    <option value="Coahuila">Coahuila</option>
                    <option value="Colima">Colima</option>
                    <option value="Durango">Durango</option>
                    <option value="México">Estado de México</option>
                    <option value="Guanajuato">Guanajuato</option>
                    <option value="Guerrero">Guerrero</option>
                    <option value="Hidalgo">Hidalgo</option>
                    <option value="Jalisco">Jalisco</option>
                    <option value="Michoacán">Michoacán</option>
                    <option value="Morelos">Morelos</option>
                    <option value="Nayarit">Nayarit</option>
                    <option value="Nuevo León">Nuevo León</option>
                    <option value="Oaxaca">Oaxaca</option>
                    <option value="Puebla">Puebla</option>
                    <option value="Querétaro">Querétaro</option>
                    <option value="Quintana Roo">Quintana Roo</option>
                    <option value="San Luis Potosí">San Luis Potosí</option>
                    <option value="Sinaloa">Sinaloa</option>
                    <option value="Sonora">Sonora</option>
                    <option value="Tabasco">Tabasco</option>
                    <option value="Tamaulipas">Tamaulipas</option>
                    <option value="Tlaxcala">Tlaxcala</option>
                    <option value="Veracruz">Veracruz</option>
                    <option value="Yucatán">Yucatán</option>
                    <option value="Zacatecas">Zacatecas</option>`;

    document.getElementById('municipio').outerHTML = `<input type="text" name="municipio" id="municipio" placeholder="Municipio" oninput="soloLetras(this);">`;

    document.getElementById("colonia").outerHTML = `<input type="text" name="colonia" id="colonia" placeholder="Colonia" oninput="soloLetras(this);">`;
}