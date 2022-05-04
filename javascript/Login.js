//validazione della form della pagina login.php

/*Dalla documentazione di Jquery:
$( document ).ready()
A page can't be manipulated safely until the document is "ready." jQuery detects this state of readiness for you. Code included inside $( document ).ready() will only run once the page Document Object Model (DOM) is ready for JavaScript code to execute. Code included inside $( window ).on( "load", function() { ... }) will run once the entire page (images or iframes), not just the DOM, is ready.*/
$(document).ready(function() {
    //viene creato un controllo custom per il testo dell'username "regex".La funzione prende una espressione regolare "regexpr",viene fatto regexpr.test(value); per verificare se il testo rientra nel valori ammissibili di regexpr.In caso di failure viene stampato un messaggio d'errore. 
    $.validator.addMethod("regex", function(value, element, regexpr) {          
        return regexpr.test(value);
      }, "Username devono avere caratteri alfanumerici ed eventualmente underscore(come separatore)."); 
    //validate è un tool offerto da Jquery che facilita la validazione delle form
    $("#form").validate({
        //viene aggiunta la classe errorClass all'elemento corrispondente in caso di valori inseriti errati, a cui è stato legato dello stile tramite il css.
        errorClass: "error fail-alert",
        //viene aggiunta la classe validClass all'elemento corrispondente in caso di valori inseriti corretti, a cui è stato legato dello stile tramite il css.
        validClass: "valid success-alert",
        //regole che deve rispettare la form
        rules: {
            //username è richiesto,ha una lunghezza minima,massima e deve soddisfare la validazione custom regex.
            username : {
                required: true,
                minlength: 3,
                maxlength: 20,
                ///espressione regolare che accetta solo valori alfanumerici separati eventualmente da underscore
                regex: /^[a-zA-Z0-9]([_](?![_])|[a-zA-Z0-9]){1,18}[a-zA-Z0-9]$/
            },
            //La password è richiesta e deve avere almeno 8 caratteri e massimo 20
            password : {
                required: true,
                minlength:8,
                maxlength:20
            }
        },
        //messaggi d'errore per ogni singolo controllo.
        messages : {
            username: {
                required: "Questo campo è richiesto",
                minlength: "username deve avere almeno 3 caratteri",
                maxlength: "username deve avere massimo 20 caratteri"
            },
            password: {
                required: "Questo campo è richiesto",
                minlength: "Lunghezza minima password 8 caratteri",
                maxlength: "Lunghezza massima password 20 caratteri"
            },
        }
    });
});