//alla pressione del copy button viene mostrato un alert di conferma e il tooltip viene momentaneamente nascosto

//jquery quando il documento sarà caricato eseguirà queste funzioni
$(document).ready(function() {
    create();
    //quando il documento è caricato viene impostata la bacheca come homepage
    stream();
    //tutti gli elementi che hanno data-toggle="tooltip" al trigger dell'hover del mouse o al click mostreranno un tooltip,un piccolo messaggio popup vicino l'elemento.
    $('[data-toggle="tooltip"]').tooltip({
        trigger: 'hover'
    })
    //al click del bottone copy viene eseguita la funzione showAlert
    $(".copy-b").click(function showAlert() {
        //il tooltip del bottone copy viene nascosto
        $('.copy-b').tooltip('hide');
        //viene mostrato il messaggio di conferma della copia del codice ma solo per un breve lasso di tempo grazie a fadeTo e slideUp
        $("#success-alert").fadeTo(3000, 500).slideUp(500, function() {
            $("#success-alert").slideUp(500);
        });
    });
    //funzione che si occupa di creare il popup delle immagini di profilo
    $('.image-link').magnificPopup({
        type: 'image'
    });
});