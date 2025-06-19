document.addEventListener('DOMContentLoaded', function () {
    let mapa = document.getElementById('mapa');
    if (window.innerWidth < 768) {
        mapa.setAttribute('rows', "358%,100%");
    } else {
        mapa.setAttribute('cols', "358%,100%");
    }
})