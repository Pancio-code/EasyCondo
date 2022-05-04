//validazione della form contact-us in index.html

/*Dalla documentazione di Jquery:
$( document ).ready()
A page can't be manipulated safely until the document is "ready." jQuery detects this state of readiness for you. Code included inside $( document ).ready() will only run once the page Document Object Model (DOM) is ready for JavaScript code to execute. Code included inside $( window ).on( "load", function() { ... }) will run once the entire page (images or iframes), not just the DOM, is ready.*/
$(document).ready(function() {
    //validate è un tool offerto da Jquery che facilita la validazione delle form
    $("#form").validate({
        //viene aggiunta la classe errorClass all'elemento corrispondente in caso di valori inseriti errati, a cui è stato legato dello stile tramite il css.
        errorClass: "error fail-alert",
        //viene aggiunta la classe validClass all'elemento corrispondente in caso di valori inseriti corretti, a cui è stato legato dello stile tramite il css.
        validClass: "valid success-alert",
        //regole che deve rispettare la form
        rules: {
            //name è richiesto e deve avere almeno 3 caratteri.
            name : {
                required: true,
                minlength: 3
            },
            //email è richiesta,e deve essere una email valida: nome@nome1.nome3.
            email: {
                required: true,
                email: true
            },
            //message è richiesto.
            message : {
                required: true,
            }
        },
        //messaggi d'errore per ogni singolo controllo.
        messages : {
            name: {
                minlength: "nome deve avere almeno 3 caratteri"
            },
            email: {
                email: "l'email deve avere il seguente formato: abc@domain.tld"
            },
        }
    });
});

