<?php
//file che si occupa di espellere un membro del gruppo
session_start();
require_once('../php/database.php');
//viene controllato che l'utente ha i permessi necessari,cioè ha effettuato il login
if (isset($_SESSION['session_id'])) {
        $session_group = htmlspecialchars($_SESSION['session_group'], ENT_QUOTES, 'UTF-8');
        //viene controllato se nell'url c'è anche l'username del membro da espellere.
        if (isset($_GET['name'])) {
                $name = $_GET["name"];

                //query che recupera tutti i file del post
                $query = 'SELECT id,dati FROM message WHERE condominio = :condominio and username = :username';

                $check = $pdo->prepare($query);
                $check->bindParam(':condominio', $session_group, PDO::PARAM_STR);
                $check->bindParam(':username', $name, PDO::PARAM_STR);
                $check->execute();
                $result = $check->fetchAll(PDO::FETCH_ASSOC);

                foreach ($result as $message_files) {
                        //se ci sono allegati nel post
                        if (!is_null($message_files['dati'])) {
                                //creo un array con il nome dei file del post(che erano salvati come una stringa unica separati da ' ' nel database)
                                $files = explode(' ', $message_files['dati']);
                                foreach ($files as $file) {
                                        //ogni file viene eliminato dalla cartella uploads
                                        unlink('uploads/' . $file);
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
                                                unlink('commenti/uploads/' . $file1);
                                        }
                                }
                        }

                        //vengono eliminati i commenti del post i-esimo
                        $query = 'DELETE FROM commenti WHERE id_post = :id';

                        $statement = $pdo->prepare($query);
                        $statement->bindParam(':id', $message_files['id'], PDO::PARAM_INT);
                        $statement->execute();
                }

                //query per eliminare i messaggi del membro dal gruppo
                $query = 'DELETE FROM message
                        WHERE condominio = :condominio and username = :username';

                $statement = $pdo->prepare($query);
                $statement->bindParam(':condominio', $session_group, PDO::PARAM_STR);
                $statement->bindParam(':username', $name, PDO::PARAM_STR);

                $statement->execute();

                //query che elimina la partecipazione del membro dal gruppo.
                $query = 'DELETE FROM groups
                        WHERE condominio = :condominio and username = :username';

                $statement = $pdo->prepare($query);
                $statement->bindParam(':condominio', $session_group, PDO::PARAM_STR);
                $statement->bindParam(':username', $name, PDO::PARAM_STR);

                $statement->execute();

                header("location: ../group.php");
                exit;
        }
} else {
        header("location: ../account/Riservato.html");
        exit;
}
