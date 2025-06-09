// Funcion par mostrar el mensaje de error
function showError(idElement, message) {
    document.getElementById(idElement).innerText = message;
}

// Validar el formulario
document.getElementById('violenciaForm').addEventListener('submit', function (e) {
    e.preventDefault(); // Evita el envío automático

    let respuestas = { a: 0, b: 0, c: 0, d: 0 };
    let respondidas = 0;

    for (let i = 1; i <= 5; i++) {
        let opciones = document.getElementsByName('q' + i);
        let respondida = false;
        for (let opcion of opciones) {
            if (opcion.checked) {
                respuestas[opcion.value]++;
                respondida = true;
                break;
            }
        }
        showError('ErrorQ' + i, respondida ? "" : "Por favor conteste la Pregunta: " + i);
        if (respondida) respondidas++;
    }

    if (respondidas < 5) return;

    // Encuentra la respuesta con mayor cantidad
    let max = Math.max(...Object.values(respuestas));
    let mayores = Object.keys(respuestas).filter(k => respuestas[k] === max);
    let mayor = mayores[0]; // Si hay empate, toma la primera

    var radios = document.getElementById('violenciaForm')
    // Desmarcar todos los botones de radio
    for (var i = 0; i < radios.length; i++) {
        radios[i].checked = false;
    }

    let title = document.getElementById('title');
    let text = document.getElementById('contenttext');
    switch (mayor) {
        case 'a':
            title.innerHTML = ('No sufres Violencia');
            text.innerHTML = ('No se detectan señales claras de violencia de género. Las personas a tu alrededor parecen respetar tu integridad y libertad.');
            break;
        case 'b':
            title.innerHTML = ('Signos leves de violencia');
            text.innerHTML = ('Existen signos leves o disfrazados de violencia. Aunque parezcan inofensivos, pueden escalar con el tiempo. No los ignores.');
            break;
        case 'c':
            title.innerHTML = ('Señales moderadas de Violencia');
            text.innerHTML = ('Hay señales evidentes de violencia. Tu libertad o dignidad están siendo afectadas. Habla con alguien y busca ayuda.');
            break;
        case 'd':
            title.innerHTML = ('Situacion grave de Violencia');
            text.innerHTML = ('Situación grave de violencia de género. Tu bienestar está en riesgo. Es urgente que busques apoyo inmediato.');
            break;
    }

    document.getElementById('modal').showModal();

});