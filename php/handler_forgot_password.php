<?php
//file che gestisce la richiesta del cambio password in caso essa è stata dimenticata
session_start();
require_once('database.php');
//viene controllata se la form è stata riempita.
if (isset($_POST['recupero'])) {
    $username = $_POST['username'];

    //query per recuperare l'email legata all'username
    $query = "
        SELECT email
        FROM users
        WHERE username = :username
    ";

    $check = $pdo->prepare($query);
    $check->bindParam(':username', $username, PDO::PARAM_STR);
    $check->execute();

    $user = $check->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        //se l'username non esiste,l'utente torna alla pagina per la richiest dell nuova password e viene mostrato questo messaggio d'errore.
        $_SESSION['errors-forgot'] = array("Purtroppo l'username inserito non esiste.");
        header("location: ../account/forgot_password.php");
    } else {
        //se esiste l'username vengono cancellate eventuali richieste precedenti
        $query = 'DELETE FROM password_change_request WHERE username = :username';

        $check = $pdo->prepare($query);
        $check->bindParam(':username', $username, PDO::PARAM_STR);
        $check->execute();

        //viene creato un codice composto dall'username e la data corrente
        $code = $username . date("D M d, Y G:i");
        //vengono eliminati gli spazi nella data
        $code_ws = str_replace(' ', '', $code);
        //il codice viene criptato come fosse una password per maggiore sicurezza
        $code_crypted = password_hash($code_ws, PASSWORD_BCRYPT);

        //viene inserita la richiesta nella tabella password_change_request,con la data in cui è stata fatta tramite CURRENT_TIMESTAMP().
        $query = "
            INSERT INTO password_change_request
            VALUES (0, :code, :username, CURRENT_TIMESTAMP())
        ";
        $check = $pdo->prepare($query);
        $check->bindParam(':code', $code_crypted, PDO::PARAM_STR);
        $check->bindParam(':username', $username, PDO::PARAM_STR);
        $check->execute();

        if ($check->rowCount() > 0) {
            //se l'inserimento è andato a buon fine viene mandato per email un link per cambiare la password.
            $email = $user['email'];
            $email_subject = 'Recupero Password da EasyCondo';
            $email_response = $username . " clicca su questo link entro 30 minuti per cambiare la password: " . "http://localhost/Site%20LTW/account/change_password.php?code=" . $code_ws . " \n\n\n\nEasyCondo";
            $headers = "From: Your-Email\r\n";
            mail($email, $email_subject, $email_response, $headers);
            unset($_SESSION['errors-forgot']);
            header("location: ../account/success_reply.html");
        } else {
            //se ci sono stati problemi nell'inserimento viene avvisato l'utente con questo messaggio d'errore.
            $_SESSION['errors-forgot'] = array("Ops, c'è stato un problema,ci scusiamo per il disagio.");
            header("location: ../account/forgot_password.php");
        }
    }
}
