(function(){
    if(document.querySelector('.headerapp__menu')){
        const btnmenu = document.querySelector('#btnmenu');
        const navmenu = document.querySelector('.headerapp__navmenu');
        btnmenu.addEventListener('click', ()=>{
            navmenu.classList.toggle('mostrar');
        });
    }

})();