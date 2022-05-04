<?php
//file che gestisce la richiesta di partecipazione ad un gruppo
session_start();
require_once('database.php');
//viene controllato che l'utente ha i permessi necessari,cioè ha effettuato il login
if (isset($_SESSION['session_id'])) {
    //viene controllata se la form è stata riempita.
    if (isset($_POST['create'])) {
        $encoded = $_POST['codice'];
        $session_user = htmlspecialchars($_SESSION['session_user'], ENT_QUOTES, 'UTF-8');

        //query che recupera il nome del condominio legato al codice inserito
        $query = "
            SELECT name
            FROM condominio
            WHERE codice = :codice
        ";

        $check = $pdo->prepare($query);
        $check->bindParam(':codice', $encoded, PDO::PARAM_STR);
        $check->execute();

        $result = $check->fetchAll(PDO::FETCH_ASSOC);

        if (count($result) != 1) {
            //se il numero di righe trovate non è 1,allora il codice è errato
            header("location: ../riservato/not_exist.html");
        } else {
            //se esiste una riga nella tabella condominio con quel codice allora viene inserito l'utente nel gruppo
            $query = "
                INSERT INTO groups
                VALUES (0, :username, :condominio)
            ";
            $check = $pdo->prepare($query);
            $check->bindParam(':username', $session_user, PDO::PARAM_STR);
            $check->bindParam(':condominio', $result[0]['name'], PDO::PARAM_STR);
            //si prova ad inserire l'utente nel gruppo
            try {
                $check->execute();
            } catch (Exception $var) {
                //se viene generata un eccezione significa che l'utente già era nel gruppo
                header("location: ../riservato/re_enter.html");
                exit;
            }

            //viene avvisato l'utente che ora è un membro effettivo del gruppo
            header("location: ../riservato/enter.html");
            exit;
        }
    }
} else {
    header("location: ../account/Riservato.html");
    exit;
}
