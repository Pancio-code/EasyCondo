<?php
//file che si occupa di selezionare e mostrare il gruppo in cui vuole entrare l'utente
session_start();
require_once('database.php');
//viene controllato che l'utente ha i permessi necessari,cioè ha effettuato il login
if (isset($_SESSION['session_id'])) {
    //viene controllato se nell'url c'è pure il nome e codice del gruppo in cui si vuole entrare.
    if (isset($_GET['name']) && isset($_GET['code'])) {
        $_SESSION['session_group'] = $_GET["name"];
        $_SESSION['session_codice'] = str_replace(" ", "+", $_GET["code"]);

        //query per ottenere username dell'amministratore del gruppo
        $query = "
            SELECT amministratore
            FROM condominio
            WHERE name = :name
        ";

        $check = $pdo->prepare($query);
        $check->bindParam(':name', $_SESSION['session_group'], PDO::PARAM_STR);
        $check->execute();

        $user = $check->fetch(PDO::FETCH_ASSOC);

        $session_user = htmlspecialchars($_SESSION['session_user'], ENT_QUOTES, 'UTF-8');
        //se l'utente è l'amministratore del gruppo gli verrà mostrata la pagina con privilegi,altrimenti quella senza
        if ($user['amministratore'] == $session_user) {
            header("location: ../group.php");
        } else {
            header("location: ../group_np.php");
        }
        exit;
    }
} else {
    header("location: ../account/Riservato.html");
    exit;
}
