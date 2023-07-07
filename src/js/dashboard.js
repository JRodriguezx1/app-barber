document.addEventListener('DOMContentLoaded', function() {scrolnav(); });

function scrolnav() {
    const enlaces = document.querySelectorAll('.dashboard__menu a');  //selecicona el elemento nav y sus enelaces 
    enlaces.forEach( function(enlace){  //me recorre los enlaces del elemento NAV seleccionado 
        if(enlace.classList.contains('dashboard__enlace--actual')){
            const seccion = document.querySelector('.'+enlace.firstElementChild.nextElementSibling.textContent);
            seccion.scrollIntoView({behavior: 'smooth'});
        } 
    });
}