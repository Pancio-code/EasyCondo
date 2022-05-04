<?php
session_start();
require_once('../../php/database.php');
//viene controllato che l'utente ha i permessi necessari,cioè ha effettuato il login
if (isset($_SESSION['session_id'])) {
    //viene controllato se nell'url è presente l'id del post e l'id del commento
    if (isset($_GET['id']) && isset($_GET['id_post'])) {
        $id_comment = $_GET["id"];
        $id_post = $_GET["id_post"];

        //query per recuperare i dati del commento selezionato da id
        $query = 'SELECT dati FROM commenti WHERE id = :id';

        $check = $pdo->prepare($query);
        $check->bindParam(':id', $id_comment, PDO::PARAM_INT);
        $check->execute();

        $message_files = $check->fetch(PDO::FETCH_ASSOC);

        //viene controllato se ci sono effettivamente file allegati al commento
        if (!is_null($message_files['dati'])) {
            //creo un array con il nome dei file del commento(che erano salvati come una stringa unica separati da ' ' nel database)
            $files = explode(' ', $message_files['dati']);
            foreach ($files as $file) {
                //ogni file viene eliminato dalla cartella uploads
                unlink('uploads/' . $file);
            }
        }

        //query che elimina il commento
        $query = 'DELETE FROM commenti WHERE id = :id';

        $statement = $pdo->prepare($query);
        $statement->bindParam(':id', $id_comment, PDO::PARAM_INT);

        $statement->execute();

        //query che decrementa il numero dei commenti del post associato
        $query = "UPDATE message SET num_commenti = num_commenti - 1 WHERE id = :id";
        $check = $pdo->prepare($query);
        $check->bindParam(':id', $id_post, PDO::PARAM_INT);
        $check->execute();

        header("location: post.php");
    }
    exit;
} else {
    header("location: ../account/Riservato.html");
    exit;
}
