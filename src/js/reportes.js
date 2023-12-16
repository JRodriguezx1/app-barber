(function(){

    if(document.querySelector('.reportes')){
        const reportes__btns = document.querySelectorAll('.reportes__btn');
        const dialogo = document.getElementById("miDialogo");
        reportes__btns.forEach(reportebtn => {
            reportebtn.addEventListener('click', ()=>{
                dialogo.showModal();
                document.addEventListener("click", cerrarDialogoExterno);
            });
        });

        function cerrarDialogoExterno(event) {
            console.log(event.target);
            if (event.target === dialogo) {
              dialogo.close();
              document.removeEventListener("click", cerrarDialogoExterno);
            }
        }
    }
})();