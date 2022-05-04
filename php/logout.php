<?php
//file che si occupa di effettuare il logout dal sito
session_start();
//viene controllato che l'utente ha i permessi necessari,cioè ha effettuato il login
if (isset($_SESSION['session_id'])) {
    unset($_SESSION['session_id']);
    unset($_SESSION['session_user']);
    unset($_SESSION['session_role']);
    unset($_SESSION['session_group']);
    unset($_SESSION['session_codice']);
    unset($_SESSION['session_type_group']);
    unset($_SESSION['errors-register']);
    unset($_SESSION['errors-forgot']);
    unset($_SESSION['errors']);
    unset($_SESSION['session_post']);
}
header('Location: ../account/login.php');
exit;
