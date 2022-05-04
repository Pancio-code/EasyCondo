<?php
//file che si occupa di eliminare un post
session_start();
require_once('../php/database.php');
//viene controllato che l'utente ha i permessi necessari,cioè ha effettuato il login
if (isset($_SESSION['session_id'])) {
    $session_role =  htmlspecialchars($_SESSION['session_role'], ENT_QUOTES, 'UTF-8');
    //viene controllato se nell'url c'è anche l'id del messaggio da eliminare.
    if (isset($_GET['id'])) {
        $id_message = $_GET["id"];

        //query per recuperare i file dei commenti del post
        $query = 'SELECT dati FROM commenti WHERE id_post = :id_post';

        $check = $pdo->prepare($query);
        $check->bindParam(':id_post', $id_message, PDO::PARAM_INT);
        $check->execute();


        $result = $check->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $comment_files) {
            //se ci sono allegati al commento i-esimo
            if (!is_null($comment_files['dati'])) {
                //creo un array con il nome dei file del commento(che erano salvati come una stringa unica separati da ' ' nel database)
                $files = explode(' ', $comment_files['dati']);
                foreach ($files as $file) {
                    //ogni file viene eliminato dalla cartella uploads
                    unlink('commenti/uploads/' . $file);
                }
            }
        }

        //query che elimina tutti i commenti del post selezionato
        $query = 'DELETE FROM commenti WHERE id_post = :id_post';

        $statement = $pdo->prepare($query);
        $statement->bindParam(':id_post', $id_message, PDO::PARAM_INT);

        $statement->execute();

        //query che recupera tutti i file del post
        $query = 'SELECT dati FROM message WHERE id = :id';

        $check = $pdo->prepare($query);
        $check->bindParam(':id', $id_message, PDO::PARAM_INT);
        $check->execute();

        $message_files = $check->fetch(PDO::FETCH_ASSOC);
        //se ci sono allegati nel post
        if (!is_null($message_files['dati'])) {
            //creo un array con il nome dei file del post(che erano salvati come una stringa unica separati da ' ' nel database)
            $files = explode(' ', $message_files['dati']);
            foreach ($files as $file) {
                //ogni file viene eliminato dalla cartella uploads
                unlink('uploads/' . $file);
            }
        }

        //query che elimina il post con quell'id
        $query = 'DELETE FROM message WHERE id = :id';

        $statement = $pdo->prepare($query);
        $statement->bindParam(':id', $id_message, PDO::PARAM_INT);

        $statement->execute();

        if ($session_role == "Amministratore") {
            header("location: ../group.php");
        } else {
            header("location: ../group_np.php");
        }
    }
    exit;
} else {
    header("location: ../account/Riservato.html");
    exit;
}
