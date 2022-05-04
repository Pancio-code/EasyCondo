<?php
//file che gestisce l'invito dei membri in un gruppo.
session_start();
//viene controllato che l'utente ha i permessi necessari,cioè ha effettuato il login
if (isset($_SESSION['session_id'])) {
    $session_user = htmlspecialchars($_SESSION['session_user'], ENT_QUOTES, 'UTF-8');
    $session_group_type = htmlspecialchars($_SESSION['session_type_group'], ENT_QUOTES, 'UTF-8');
    $session_group = htmlspecialchars($_SESSION['session_group'], ENT_QUOTES, 'UTF-8');
    $session_code = htmlspecialchars($_SESSION['session_codice'], ENT_QUOTES, 'UTF-8');

    //viene controllata se la form è stata riempita.
    if (isset($_POST['email'])) {
        //viene creata una mail con il codice per far entrare il destinatario della mail nel gruppo.
        $to = $_POST['email'];

        $email_from = 'EasyCondo';
        $email_subject = 'Codice d\'invito';
        $email_body = "Sei stato invitato al gruppo: " . $session_group . ", da " . $session_user . ".\nIl codice è il seguente: " . $session_code . ".";
        $headers = "From: Your-Email\r\n";

        mail($to, $email_subject, $email_body, $headers);

        if ($session_group_type == "group.php") {
            header("location: ../group.php");
        } else {
            header("location: ../group_np.php");
        }
    }
} else {
    header("location: ../account/Riservato.html");
    exit;
}
