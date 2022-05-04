//validazione della form per creare un post nel gruppo

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
            //title è richiesto,ha una lunghezza minima.
            title : {
                required: true,
                minlength: 3
            },
            //message è richiesto,ha una lunghezza minima.
            message : {
                required: true,
                minlength:3
            }
        },
        //messaggi d'errore per ogni singolo controllo.
        messages : {
            title: {
                minlength: "title deve avere almeno 3 caratteri"
            },
            message: {
                minlength: "Lunghezza minima messaggio 3 caratteri"
            }
        }
    });
    //validazione della form per invitare un nuovo membro(pulsante fisso in basso a destra)
    $("#form1").validate({
        errorClass: "error fail-alert",
        validClass: "valid success-alert",
        rules: {
            email : {
                required:true
            }
        },
        //messaggi d'errore per ogni singolo controllo.
        messages : {
            email: {
                required: "email richiesta",
                email: "Inserisci una mail valida: nome_mail@domain.com"
            }
        }
    });
});
