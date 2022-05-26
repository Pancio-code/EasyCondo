//prima "validate" legata alle form crea condominio della pagina amministratore.php
//seconda "validate" legata alle form entra in un condominio delle pagine:amministratore.php e condomino.php

/*Dalla documentazione di Jquery:
$( document ).ready()
A page can't be manipulated safely until the document is "ready." jQuery detects this state of readiness for you. Code included inside $( document ).ready() will only run once the page Document Object Model (DOM) is ready for JavaScript code to execute. Code included inside $( window ).on( "load", function() { ... }) will run once the entire page (images or iframes), not just the DOM, is ready.*/
$(document).ready(function() {
    //viene creato un controllo custom per il testo dell'username "regex".La funzione prende una espressione regolare "regexpr",viene fatto regexpr.test(value); per verificare se il testo rientra nel valori ammissibili di regexpr.In caso di failure viene stampato un messaggio d'errore. 
    $.validator.addMethod("regex", function(value, element, regexpr) {          
        return regexpr.test(value);
    }, "Sono ammessi solamente caratteri alfanumerici e l\'underscore(come separatore).");
    //viene creato un controllo custom per il testo dell'indirizzo "regex_via".La funzione prende una espressione regolare "regexpr",viene fatto regexpr.test(value); per verificare se il testo rientra nel valori ammissibili di regexpr.In caso di failure viene stampato un messaggio d'errore. 
    $.validator.addMethod("regex_via", function(value, element, regexpr) {          
        return regexpr.test(value);
    }, "Inserire un indirizzo nella forma via + civico. (EX: Via Roma 18).");
    //validate è un tool offerto da Jquery che facilita la validazione delle form
    $("#form").validate({
        //viene aggiunta la classe errorClass all'elemento corrispondente in caso di valori inseriti errati, a cui è stato legato dello stile tramite il css.
        errorClass: "error fail-alert",
        //viene aggiunta la classe validClass all'elemento corrispondente in caso di valori inseriti corretti, a cui è stato legato dello stile tramite il css.
        validClass: "valid success-alert",
        //regole che deve rispettare la form
        rules: {
            //name è richiesto,ha una lunghezza minima,massima e deve soddisfare la validazione custom regex.
            name : {
                required: true,
                minlength: 3,
                maxlength: 20,
                ///espressione regolare che accetta solo valori alfanumerici separati eventualmente da underscore
                regex: /^[a-zA-Z0-9]([_](?![_])|[a-zA-Z0-9]){1,18}[a-zA-Z0-9]$/
            },
            //città è richiesto,ha una lunghezza minima,massima-.
            citta: {
                required: true,
                minlength: 3,
                maxlength: 30
            },
            //name è richiesto e deve soddisfare la validazione custom regex_via.
            indirizzo: {
                required: true,
                ///espressione regolare che accetta solo parole che iniziano con via/corso/viale/piazza uno spazio e altre parole(almeno 1) separate da spazi,e al termine un numero civico
                regex_via: /^(via|corso|viale|piazza|Via|Corso|Viale|Piazza)[ ][a-zA-Z]([ ](?![ ])|[a-zA-Z]){1,}[ ][0-9]{1,}$/
            },
        },
        //messaggi d'errore per ogni singolo controllo.
        messages : {
            name: {
                required: "Questo campo è richiesto",
                minlength: "nome deve avere almeno 3 caratteri",
                maxlength: "nome deve avere massimo 20 caratteri"
            },
            citta: {
                required: "Questo campo è richiesto",
                minlength: "città deve avere almeno 3 caratteri",
                maxlength: "città deve avere massimo 30 caratteri",
            }
        }
    });
    $("#form1").validate({
        errorClass: "error fail-alert",
        validClass: "valid success-alert",
        rules: {
            //codice è richiesto.
            codice:{
                required:true
            }
        },
        //messaggi d'errore per ogni singolo controllo.
        messages : {
            codice: {
                required: "Questo campo è richiesto",
            }
        }
    });
});