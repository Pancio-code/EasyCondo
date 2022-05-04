<?php
//file che gestisce l'upload della nuova immagine di profilo.
session_start();
require_once('../php/database.php');
//viene controllato che l'utente ha i permessi necessari,cioè ha effettuato il login
if (isset($_SESSION['session_id'])) {
    $session_user = htmlspecialchars($_SESSION['session_user'], ENT_QUOTES, 'UTF-8');
    //viene controllata se la form è stata riempita.
    if (isset($_POST['foto'])) {
        //viene modificato il nome del file inviato aggiungendo il timestamp corrente,così da non permettere l'esistenza di file con lo stesso nome nella cartella images.
        $profileImageName = time() . '_' . $_FILES['profileImage']['name'];

        //viene aggiunto al nome modificato del file il percorso in cui verrà inserita.
        $target = 'images/' . $profileImageName;

        //viene eseguito l'upload del file e controllato se viene eseguito correttamente.
        if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $target)) {
            //query che recupera il nome della vecchia immagine di profilo.
            $query = 'SELECT profile_picture FROM users WHERE username = :username';
            $statement = $pdo->prepare($query);
            $statement->bindParam(':username', $session_user, PDO::PARAM_STR);
            $statement->execute();

            $result = $statement->fetch(PDO::FETCH_ASSOC);

            $old_image = $result['profile_picture'];
            //se l'utente aveva già un immagine profilo,quella precedente viene eliminata dalla cartella images.
            if ($old_image != 'unknown.png') {
                unlink('images/' . $old_image);
            }
            //query che aggiorna il nome del file nella colonna profile_picture dell'utente
            $query = 'UPDATE users SET profile_picture = :profile_picture WHERE username = :username';

            $statement = $pdo->prepare($query);
            $statement->bindParam(':profile_picture', $profileImageName, PDO::PARAM_STR);
            $statement->bindParam(':username', $session_user, PDO::PARAM_STR);
            $statement->execute();

            //non ci sono stati errori,viene informato l'utente.
            header("location: new_image.html");
            exit;
        } else {
            //l'immagine non è stata inserita correttamente nel percorso indicato.
            header("location: failed_image.html");
            exit;
        }
    }
} else {
    header("location: ../account/Riservato.html");
    exit;
}
