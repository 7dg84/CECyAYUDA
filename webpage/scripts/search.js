// Funcion para eliminar una denuncia
function deleteDenuncia(){
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
    // Enviar el formulario
    form.submit()
}