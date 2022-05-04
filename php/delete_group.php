<?php
session_start();
require_once('database.php');

//viene controllato che l'utente ha i permessi necessari,cioè ha effettuato il login
if (isset($_SESSION['session_id'])) {
        //viene controllato se nell'url è presente il nome del gruppo che si vuole eliminare
        if (isset($_GET['name_group'])) {
                $name_group = $_GET["name_group"];

                //query per ottenere id e file dei messaggi del condominio
                $query = 'SELECT id,dati FROM message WHERE condominio = :condominio';

                $check = $pdo->prepare($query);
                $check->bindParam(':condominio', $name_group, PDO::PARAM_STR);
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

                        //vengono recuperati i file dei commenti del messaggio i-esimo(id_post)
                        $query = 'SELECT dati FROM commenti WHERE id_post = :id';

                        $check = $pdo->prepare($query);
                        $check->bindParam(':id', $message_files['id'], PDO::PARAM_INT);
                        $check->execute();

                        $result1 = $check->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($result1 as $comment_files) {
                                //se ci sono allegati al commento i-esimo
                                if (!is_null($comment_files['dati'])) {
                                        //creo un array con il nome dei file del commento(che erano salvati come una stringa unica separati da ' ' nel database)
                                        $files1 = explode(' ', $comment_files['dati']);
                                        foreach ($files1 as $file1) {
                                                //ogni file viene eliminato dalla cartella uploads
                                                unlink('../groups/commenti/uploads/' . $file1);
                                        }
                                }
                        }

                        //vengono eliminati i commenti del post i-esimo
                        $query = 'DELETE FROM commenti WHERE id_post = :id';

                        $statement = $pdo->prepare($query);
                        $statement->bindParam(':id', $message_files['id'], PDO::PARAM_INT);

                        $statement->execute();
                }

                //vengono eliminati i messaggi del gruppo
                $query = 'DELETE FROM message
                        WHERE condominio = :condominio ';

                $statement = $pdo->prepare($query);
                $statement->bindParam(':condominio', $name_group, PDO::PARAM_STR);

                $statement->execute();

                //vengono eliminate le partecipazione dei membri dalla tabella groups
                $query = 'DELETE FROM groups
                        WHERE condominio = :condominio ';

                $statement = $pdo->prepare($query);
                $statement->bindParam(':condominio', $name_group, PDO::PARAM_INT);

                $statement->execute();

                //viene infine eliminato il condominio
                $query = 'DELETE FROM condominio
                        WHERE name = :condominio ';

                $statement = $pdo->prepare($query);
                $statement->bindParam(':condominio', $name_group, PDO::PARAM_INT);

                $statement->execute();
                header("location: amministratore.php");
                exit;
        }
} else {
        header("location: ../account/Riservato.html");
        exit;
}
