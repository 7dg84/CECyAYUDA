let searchFormlastValue = "";

const searchConfig = {
    "Folio": {
        operator: [
            { value: "=", text: "Igual" },
            { value: "LIKE", text: "Contenga" }
        ],
        value: { type: "text", placeholder: "Folio" }
    },
    "Descripcion": {
        operator: [
            { value: "=", text: "Igual" },
            { value: "LIKE", text: "Contenga" }
        ],
        value: { type: "text", placeholder: "Hechos" }
    },
    "Fecha": {
        operator: [
            { value: "=", text: "Igual" },
            { value: "<>", text: "Diferente" },
            { value: ">", text: "Mayor" },
            { value: "<", text: "Menor" },
            { value: ">=", text: "Mayor o Igual" },
            { value: "<=", text: "Menor o Igual" }
        ],
        value: { type: "date" }
    },
    "Hora": {
        operator: [
            { value: "=", text: "Igual" },
            { value: "<>", text: "Diferente" },
            { value: ">", text: "Mayor" },
            { value: "<", text: "Menor" },
            { value: ">=", text: "Mayor o Igual" },
            { value: "<=", text: "Menor o Igual" }
        ],
        value: { type: "time" }
    },
    "Estado": {
        operator: [
            { value: "=", text: "Igual" },
            { value: "<>", text: "Diferente" }
        ],
        value: {
            type: "select",
            options: [
                { value: "", text: "Seleccione uno...", disabled: true, selected: true },
                { value: "Aguascalientes", text: "Aguascalientes" },
                { value: "Baja California", text: "Baja California" },
                { value: "Baja California Sur", text: "Baja California Sur" },
                { value: "Campeche", text: "Campeche" },
                { value: "Chiapas", text: "Chiapas" },
                { value: "Chihuahua", text: "Chihuahua" },
                { value: "CDMX", text: "Ciudad de México" },
                { value: "Coahuila", text: "Coahuila" },
                { value: "Colima", text: "Colima" },
                { value: "Durango", text: "Durango" },
                { value: "Estado de México", text: "Estado de México" },
                { value: "Guanajuato", text: "Guanajuato" },
                { value: "Guerrero", text: "Guerrero" },
                { value: "Hidalgo", text: "Hidalgo" },
                { value: "Jalisco", text: "Jalisco" },
                { value: "Michoacán", text: "Michoacán" },
                { value: "Morelos", text: "Morelos" },
                { value: "Nayarit", text: "Nayarit" },
                { value: "Nuevo León", text: "Nuevo León" },
                { value: "Oaxaca", text: "Oaxaca" },
                { value: "Puebla", text: "Puebla" },
                { value: "Querétaro", text: "Querétaro" },
                { value: "Quintana Roo", text: "Quintana Roo" },
                { value: "San Luis Potosí", text: "San Luis Potosí" },
                { value: "Sinaloa", text: "Sinaloa" },
                { value: "Sonora", text: "Sonora" },
                { value: "Tabasco", text: "Tabasco" },
                { value: "Tamaulipas", text: "Tamaulipas" },
                { value: "Tlaxcala", text: "Tlaxcala" },
                { value: "Veracruz", text: "Veracruz" },
                { value: "Yucatán", text: "Yucatán" },
                { value: "Zacatecas", text: "Zacatecas" }
            ]
        }
    },
    "Municipio": {
        operator: [
            { value: "=", text: "Igual" },
            { value: "LIKE", text: "Contenga" }
        ],
        value: { type: "text", placeholder: "Municipio" }
    },
    "Nombre": {
        operator: [
            { value: "=", text: "Igual" },
            { value: "LIKE", text: "Contenga" }
        ],
        value: { type: "text", placeholder: "Nombre" }
    },
    "CURP": {
        operator: [
            { value: "=", text: "Igual" },
            { value: "LIKE", text: "Contenga" }
        ],
        value: { type: "text", placeholder: "CURP", maxlength: 18 }
    },
    "Correo": {
        operator: [
            { value: "=", text: "Igual" },
            { value: "LIKE", text: "Contenga" }
        ],
        value: { type: "text", placeholder: "Correo" }
    },
    "Numtelefono": {
        operator: [
            { value: "=", text: "Igual" },
            { value: "LIKE", text: "Contenga" }
        ],
        value: { type: "tel", placeholder: "Telefono", maxlength: 10 }
    },
    "Tipo": {
        operator: [
            { value: "=", text: "Igual" },
            { value: "<>", text: "Diferente" }
        ],
        value: {
            type: "select",
            options: [
                { value: "", text: "Selecciona un tipo de violencia", disabled: true, selected: true },
                { value: "Genero", text: "Violencia de Genero" },
                { value: "Familiar", text: "Violencia Familiar" },
                { value: "Psicologica", text: "Violencia Psicologica" },
                { value: "Sexual", text: "Violencia Sexual" },
                { value: "Economica", text: "Violencia Economica" },
                { value: "Patrimonial", text: "Violencia Patrimonial" },
                { value: "Cibernetica", text: "Violencia Cibernetica" }
            ]
        }
    },
    "Verified": {
        operator: [
            { value: "=", text: "Igual" },
            { value: "<>", text: "Diferente" }
        ],
        value: {
            type: "select",
            options: [
                { value: "", text: "Selecciona verificación", disabled: true, selected: true },
                { value: "0", text: "No Verificado" },
                { value: "1", text: "Verificado" }
            ]
        }
    },
    "Status": {
        operator: [
            { value: "=", text: "Igual" },
            { value: "<>", text: "Diferente" }
        ],
        value: {
            type: "select",
            options: [
                { value: "", text: "Selecciona un estado", disabled: true, selected: true },
                { value: "0", text: "En Proceso" },
                { value: "1", text: "Resuelto" },
                { value: "2", text: "No Resuelto" }
            ]
        }
    }
};

function typeSearch(form) {
    const field = form.field.value;
    if (searchFormlastValue === field) return;
    searchFormlastValue = field;

    const config = searchConfig[field];
    if (!config) {
        operatorcontainer.innerHTML = "";
        valuecontainer.innerHTML = "";
        return;
    }

    // Render operator select
    operatorcontainer.innerHTML = `
        <label for="operator">Operador:</label>
        <select name="operator" id="operator">
            ${config.operator.map(op => `<option value="${op.value}">${op.text}</option>`).join('')}
        </select>
    `;

    // Render value input/select
    if (config.value.type === "select") {
        valuecontainer.innerHTML = `
            <label for="value">Valor:</label>
            <select name="value" id="value">
                ${config.value.options.map(opt =>
            `<option value="${opt.value}"${opt.disabled ? " disabled" : ""}${opt.selected ? " selected" : ""}>${opt.text}</option>`
        ).join('')}
            </select>
        `;
    } else {
        let attrs = `type="${config.value.type}" name="value" id="value"`;
        if (config.value.placeholder) attrs += ` placeholder="${config.value.placeholder}"`;
        if (config.value.maxlength) attrs += ` maxlength="${config.value.maxlength}"`;
        valuecontainer.innerHTML = `
            <label for="value">Valor:</label>
            <input ${attrs}>
        `;
    }
}

function openActionsDialog(folio, verify) {
    const dialog = window.actions;
    const folioInput = document.getElementById('actionfolio');
    document.getElementById('verify').checked = verify;
    folioInput.value = folio;
    dialog.showModal();
}
function closeActionsDialog() {
    const folioInput = document.getElementById('actionfolio');
    document.getElementById('verify').checked = false;
    folioInput.value = '';
    const dialog = window.actions;
    dialog.close();
}

function openDeleteDialog(folio) {
    const dialog = window.delete;
    const folioInput = document.getElementById('deletefolio');
    folioInput.value = folio;
    dialog.showModal();
}
function closeDeleteDialog() {
    const dialog = window.delete;
    dialog.close();
}

function openImageDialog(folio) {
    const dialog = window.imageDialog;
    console.log(folio);
    fetch('api.php?action=getImage&folio=' + folio)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const image = document.getElementById('imagePreview');
                image.src = "data:image/png;base64, " + data.value;
            } else {
                alert('Error al obtener la imagen: ' + data.message);
            }
        }).catch(error => {
            console.error('Error fetching image:', error);
            alert('Error al obtener la imagen.');
        });
    dialog.showModal();
}

function closeImageDialog() {
    const image = document.getElementById('imagePreview');
    image.src = "";
    image.alt = "No hay imagen disponible";
    const dialog = window.imageDialog;
    dialog.close();
}