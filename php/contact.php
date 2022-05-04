<?php
//file che gestisce il form contact-us
//viene controllata se la form è stata riempita
if (isset($_POST['contact-us'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    $email_from = 'EasyCondo';
    $email_subject = 'Messaggio da EasyCondo';
    $email_body = "Name: $name.\n" .
        "Email: $email.\n" .
        "Messaggio: $message.\n";
    $to = "panceri.1884749@studenti.uniroma1.it";
    $headers = "From: Your-Email\r\n";
    $email_response = "Grazie per il tuo feedback, il tuo contributo aiuta lo sviluppo del sito!";

    //viene mandata un email al proprietario del sito con il contenuto del contact-us
    mail($to, $email_subject, $email_body, $headers);

    //viene mandata una mail di ringraziamento all'utente che ha inviato la form
    mail($email, $email_subject, $email_response, $headers);

    header("location: ../index.html#contact");
    exit;
}
