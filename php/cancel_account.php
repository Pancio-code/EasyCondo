<?php
//file che si occupa dell'eliminazione dell'account
session_start();
require_once('database.php');
//viene controllato che l'utente ha i permessi necessari,cioè ha effettuato il login
if (isset($_SESSION['session_id'])) {
    $session_role =  htmlspecialchars($_SESSION['session_role'], ENT_QUOTES, 'UTF-8');
    $session_user = htmlspecialchars($_SESSION['session_user'], ENT_QUOTES, 'UTF-8');

    //query per recuperare l'immagine di profilo dell'utente
    $query = 'SELECT profile_picture FROM users WHERE username = :username';

    $check = $pdo->prepare($query);
    $check->bindParam(':username', $session_user, PDO::PARAM_STR);
    $check->execute();

    $user_image = $check->fetch(PDO::FETCH_ASSOC);

    //salvo il nome del file dell'immagine di profilo
    $image = $user_image['profile_picture'];
    //se l'utente ha un immagine di profilo diversa da quella di default
    if ($image != 'unknown.png') {
        //cancello l'immagine dalla cartella images
        unlink('../profilo/images/' . $image);
    }

    //recupero i file e id_post dei commenti scritti dall'utente in ogni gruppo
    $query = 'SELECT id_post,dati FROM commenti WHERE username = :username';

    $check = $pdo->prepare($query);
    $check->bindParam(':username', $session_user, PDO::PARAM_STR);
    $check->execute();

    $result = $check->fetchAll(PDO::FETCH_ASSOC);

    foreach ($result as $comment_files) {
        //se ci sono allegati al commento i-esimo
        if (!is_null($comment_files['dati'])) {
            //creo un array con il nome dei file del commento(che erano salvati come una stringa unica separati da ' ' nel database)
            $files = explode(' ', $comment_files['dati']);
            foreach ($files as $file) {
                //ogni file viene eliminato dalla cartella uploads
                unlink('../groups/commenti/uploads/' . $file);
            }
        }
        //decremento di 1 per ogni commento che cancellerò il campo num_commenti dei post(id_post).
        $query = "UPDATE message SET num_commenti = num_commenti - 1 WHERE id = :id";
        $check = $pdo->prepare($query);
        $check->bindParam(':id',  $comment_files['id_post'], PDO::PARAM_INT);
        $check->execute();
    }

    //cancello tutti i commenti ora
    $query = 'DELETE FROM commenti WHERE username = :username';

    $statement = $pdo->prepare($query);
    $statement->bindParam(':username', $session_user, PDO::PARAM_STR);
    $statement->execute();

    //recupero i file dei messaggi scritti dall'utente
    $query = 'SELECT dati FROM message WHERE username = :username ';

    $check = $pdo->prepare($query);
    $check->bindParam(':username', $session_user, PDO::PARAM_STR);
    $check->execute();

    $result = $check->fetchAll(PDO::FETCH_ASSOC);

    foreach ($result as $message_files) {
        //se ci sono allegati al messaggio i-esimo
        if (!is_null($message_files['dati'])) {
            //creo un array con il nome dei file del messaggio(che erano salvati come una stringa unica separati da ' ' nel database)
            $files = explode(' ', $message_files['dati']);
            foreach ($files as $file) {
                //ogni file viene eliminato dalla cartella uploads
                unlink('../groups/uploads/' . $file);
            }
        }
    }

    //elimino ogni file scritto dall'utente
    $query = 'DELETE FROM message
    WHERE username = :username ';

    $statement = $pdo->prepare($query);
    $statement->bindParam(':username', $session_user, PDO::PARAM_INT);

    $statement->execute();

    //se l'utente è un amministratore
    if ($session_role == "Amministratore") {
        //recupero il nome dei gruppi amministrati dall'utente
        $query = 'SELECT name FROM condominio WHERE amministratore = :amministratore';

        $statement = $pdo->prepare($query);
        $statement->bindParam(':amministratore', $session_user, PDO::PARAM_STR);
        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $row) {
            //recupero i file dei messaggi scritti nel condominio i-esimo
            $query = 'SELECT id,dati FROM message WHERE condominio = :condominio';

            $check = $pdo->prepare($query);
            $check->bindParam(':condominio', $row['name'], PDO::PARAM_STR);
            $check->execute();

            $result = $check->fetchAll(PDO::FETCH_ASSOC);

            foreach ($result as $message_files) {
                //se ci sono allegati al messaggio i-esimo
                if (!is_null($message_files['dati'])) {
                    //creo un array con il nome dei file del messaggio(che erano salvati come una stringa unica separati da ' ' nel database)
                    $files = explode(' ', $message_files['dati']);
                    foreach ($files as $file) {
                        //ogni file viene eliminato dalla cartella uploads
                        unlink('../groups/uploads/' . $file);
                    }
                }
                //recupero i file dei commenti del messaggio i-esimo
                $query = 'SELECT dati FROM commenti WHERE id_post = :id_post';

                $check = $pdo->prepare($query);
                $check->bindParam(':id_post', $message_files['id'], PDO::PARAM_INT);
                $check->execute();

                $result1 = $check->fetchAll(PDO::FETCH_ASSOC);

                foreach ($result1 as $comment_files) {
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

                //elimino ogni commento del messaggio i-esimo
                $query = 'DELETE FROM commenti WHERE id_post = :id_post';

                $statement = $pdo->prepare($query);
                $statement->bindParam(':id_post', $message_files['id'], PDO::PARAM_INT);

                $statement->execute();
            }

            //elimino tutti i messaggi del condominio i-esimo
            $query = 'DELETE FROM message
            WHERE condominio = :condominio  ';

            $statement = $pdo->prepare($query);
            $statement->bindParam(':condominio', $row['name'], PDO::PARAM_STR);
            $statement->execute();

            //elimino tutte le partecipazioni del condominio i-esimo
            $query = 'DELETE FROM groups
            WHERE condominio = :condominio ';

            $statement = $pdo->prepare($query);
            $statement->bindParam(':condominio', $row['name'], PDO::PARAM_STR);

            $statement->execute();
        }
        //elimino tutti i gruppi amministrati dall'utente
        $query = 'DELETE FROM condominio
                WHERE amministratore = :amministratore';

        $statement = $pdo->prepare($query);
        $statement->bindParam(':amministratore', $session_user, PDO::PARAM_INT);

        $statement->execute();
    }
    //elimino tutte le partecipazioni dell'utente nei vari gruppi
    $query = 'DELETE FROM groups
            WHERE username = :username ';

    $statement = $pdo->prepare($query);
    $statement->bindParam(':username', $session_user, PDO::PARAM_INT);

    $statement->execute();

    //elimino l'utente dagli utenti registrati
    $query = 'DELETE FROM users
    WHERE username = :username ';

    $statement = $pdo->prepare($query);
    $statement->bindParam(':username', $session_user, PDO::PARAM_INT);

    $statement->execute();
    //in caso id successo viene effettuato il logout alla termine.
    header("location: logout.php");
} else {
    header("location: ../account/Riservato.html");
}
exit;
