//validazione della form delle pagine: account.php,account_np.php e change_password.php

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
            //La password è richiesta e deve avere almeno 8 caratteri e massimo 20
            password : {
                required: true,
                minlength:8,
                maxlength:20
            },
            //La password di conferma è richiesta e deve avere almeno 8 caratteri e massimo 20, il controllo di uguaglianza tra password e re_password si trova all'internno del tag html.
            re_password: {
                minlength:8,
                maxlength:20,
            }
        },
        //messaggi d'errore per ogni singolo controllo.
        messages : {
            password: {
                required: "Questo campo è richiesto.",
                minlength: "Lunghezza minima password 8 caratteri",
                maxlength: "Lunghezza massima password 20 caratteri"
            },
            re_password: {
                minlength: "Lunghezza minima password 8 caratteri",
                maxlength: "Lunghezza massima password 20 caratteri",
            }
        }
    });
});