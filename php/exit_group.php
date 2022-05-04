<?php
//il file si occupa dell'eliminazione del gruppo scelto tra quelli a cui partecipa l'utente.
session_start();
require_once('database.php');
//viene controllato che l'utente ha i permessi necessari,cioè ha effettuato il login
if (isset($_SESSION['session_id'])) {
        //viene controllato se nell'url è presente il nome del gruppo da cui si vuole uscire
        if (isset($_GET['name_group'])) {
                $name_group = $_GET["name_group"];
                $username = htmlspecialchars($_SESSION['session_user'], ENT_QUOTES, 'UTF-8');
                $session_role =  htmlspecialchars($_SESSION['session_role'], ENT_QUOTES, 'UTF-8');

                //query che recupera i file dei messaggi scritti dall'utente nel gruppo
                $query = 'SELECT dati FROM message WHERE condominio = :condominio and username = :username ';

                $check = $pdo->prepare($query);
                $check->bindParam(':condominio', $name_group, PDO::PARAM_STR);
                $check->bindParam(':username', $username, PDO::PARAM_STR);
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

                //vengoni eliminati tutti i messaggi scritti dall'utente
                $query = 'DELETE FROM message
                        WHERE condominio = :condominio and username = :username';

                $statement = $pdo->prepare($query);
                $statement->bindParam(':condominio', $name_group, PDO::PARAM_INT);
                $statement->bindParam(':username', $username, PDO::PARAM_STR);

                $statement->execute();

                //viene eliminata la partecipazione del membro dal gruppo
                $query = 'DELETE FROM groups
                        WHERE username = :username and condominio = :condominio ';

                $statement = $pdo->prepare($query);
                $statement->bindParam(':username', $username, PDO::PARAM_STR);
                $statement->bindParam(':condominio', $name_group, PDO::PARAM_STR);

                $statement->execute();

                if ($session_role != 'Amministratore') {
                        header("location: condomino.php");
                } else {
                        header("location: amministratore.php");
                }
                exit;
        }
} else {
        header("location: ../account/Riservato.html");
        exit;
}
