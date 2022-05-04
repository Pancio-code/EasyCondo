//usiamo jquery e allo scroll della pagina calcoliamo l'altezza dall'inizio pagina con $(document).scrollTop(); e per ogni nav-link controlliamo se la zona indirizzata "href" è quella dove l'utente si trova attualmente
$(document).ready(function () {
    $(window).scroll(function (event) {
        var scrollPos = $(document).scrollTop();
        if (scrollPos === 0)
        {
            $('a[href^="#container"]').addClass('active');
            return;
        }    
        $('.nav-link').each(function () {
            var currLink = $(this);
            var refElement = $(currLink.attr("href"));
            if (refElement.position().top - ($('section').innerHeight()/2) <= scrollPos ) {
                $('.nav-link').removeClass("active");
                currLink.addClass("active");
            } else {
                currLink.removeClass("active");
            }
        });    
    })    
});

//PRIMO METODO SENZA SCROLL
/*
//funzione eseguita alla pressione di Home sulla navbar di index.html
function clickHome(){
    //viene preso l'elemento associato all'id navHome e viene aggiunto alle classi dell'elemento active, cambiando di colore il testo
    document.getElementById("navHome").classList.add('active');
    //viene preso l'elemento associato all'id navAccount e viene rimosso alle classi dell'elemento active, cambiando di colore il testo
    document.getElementById("navAccount").classList.remove('active');
    //viene preso l'elemento associato all'id navAuthors e viene rimosso alle classi dell'elemento active, cambiando di colore il testo
    document.getElementById("navAuthors").classList.remove('active');
    //viene preso l'elemento associato all'id navContact e viene rimosso alle classi dell'elemento active, cambiando di colore il testo
    document.getElementById("navContact").classList.remove('active');
}

//funzione eseguita alla pressione di Account sulla navbar di index.html
function clickAccount(){
    //viene preso l'elemento associato all'id navHome e viene rimosso alle classi dell'elemento active, cambiando di colore il testo
    document.getElementById("navHome").classList.remove('active');
    //viene preso l'elemento associato all'id navAccount e viene aggiunto alle classi dell'elemento active, cambiando di colore il testo
    document.getElementById("navAccount").classList.add('active');
    //viene preso l'elemento associato all'id navAuthors e viene rimosso alle classi dell'elemento active, cambiando di colore il testo
    document.getElementById("navAuthors").classList.remove('active');
    //viene preso l'elemento associato all'id navContact e viene rimosso alle classi dell'elemento active, cambiando di colore il testo
    document.getElementById("navContact").classList.remove('active');
}

//funzione eseguita alla pressione di Authors sulla navbar di index.html
function clickAuthors(){
    //viene preso l'elemento associato all'id navHome e viene rimosso alle classi dell'elemento active, cambiando di colore il testo
    document.getElementById("navHome").classList.remove('active');
    //viene preso l'elemento associato all'id navAccount e viene rimosso alle classi dell'elemento active, cambiando di colore il testo
    document.getElementById("navAccount").classList.remove('active');
    //viene preso l'elemento associato all'id navAuthors e viene aggiunto alle classi dell'elemento active, cambiando di colore il testo
    document.getElementById("navAuthors").classList.add('active');
    //viene preso l'elemento associato all'id navContact e viene rimosso alle classi dell'elemento active, cambiando di colore il testo
    document.getElementById("navContact").classList.remove('active');
}

//funzione eseguita alla pressione di Contact sulla navbar di index.html
function clickContact(){
    //viene preso l'elemento associato all'id navHome e viene aggiunto alle classi dell'elemento active, cambiando di colore il testo
    document.getElementById("navHome").classList.remove('active');
    //viene preso l'elemento associato all'id navAccount e viene aggiunto alle classi dell'elemento active, cambiando di colore il testo
    document.getElementById("navAccount").classList.remove('active');
    //viene preso l'elemento associato all'id navAuthors e viene aggiunto alle classi dell'elemento active, cambiando di colore il testo
    document.getElementById("navAuthors").classList.remove('active');
    //viene preso l'elemento associato all'id navContact e viene aggiunto alle classi dell'elemento active, cambiando di colore il testo
    document.getElementById("navContact").classList.add('active');
}
*/