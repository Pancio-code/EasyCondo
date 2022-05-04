<?php
//file che si occupa di aggiornare la password dell'utente
session_start();
require_once('../php/database.php');
//viene controllato che l'utente ha i permessi necessari,cioè ha effettuato il login
if (isset($_SESSION['session_id'])) {
    //viene controllata se è stata fatta una chiamata GET con l'id del post.
    if (isset($_GET['id'])) {
        //viene aggiunta tra le variabili della sessione il post scelto dall'utente
        $_SESSION['session_post'] = $_GET["id"];
        //porto l'utente alla pagina del post
        header("location: commenti/post.php");
        exit;
    }
} else {
    header("location: ../account/Riservato.html");
    exit;
}
