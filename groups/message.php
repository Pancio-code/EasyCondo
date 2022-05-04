<?php
//file che si occupa di mandare un email ad un singolo membro
session_start();
require_once('../php/database.php');
//viene controllato che l'utente ha i permessi necessari,cioè ha effettuato il login
if (isset($_SESSION['session_id'])) {
    $session_user = htmlspecialchars($_SESSION['session_user'], ENT_QUOTES, 'UTF-8');
    $session_group = htmlspecialchars($_SESSION['session_group'], ENT_QUOTES, 'UTF-8');
    //viene controllata se la form è stata riempita.
    if (isset($_POST['send'])) {
        $message = $_POST['messaggio'];
        $nome = $_POST['nome'];

        //query che recupera l'email dell'utente scelto dall'amministratore
        $query = "
            SELECT email
            FROM users
            WHERE username = :username
        ";

        $check = $pdo->prepare($query);
        $check->bindParam(':username', $nome, PDO::PARAM_STR);
        $check->execute();

        $result = $check->fetch(PDO::FETCH_ASSOC);
        $mail = $result['email'];

        //viene mandata una mail con il testo scritto a quell'utente del gruppo
        $email_from = 'EasyCondo';
        $email_subject = $session_group;
        $email_body = "Messaggio da: " . $session_user . ".\n" . $message . ".";
        $headers = "From: Your-Email\r\n";

        mail($mail, $email_subject, $email_body, $headers);

        header("location: ../group.php");
        exit;
    }
} else {
    header("location: ../account/Riservato.html");
    exit;
}
