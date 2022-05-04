<?php
//file che si occupa della risposta ai commenti sotto il post(in realtà verranno aggiunti solamente altri commenti non relazionati effettivamento al commento selezionato)
session_start();
require_once('../../php/database.php');
//viene controllato che l'utente ha i permessi necessari,cioè ha effettuato il login
if (isset($_SESSION['session_id'])) {
    $session_user = htmlspecialchars($_SESSION['session_user'], ENT_QUOTES, 'UTF-8');
    $session_role =  htmlspecialchars($_SESSION['session_role'], ENT_QUOTES, 'UTF-8');
    $session_group = htmlspecialchars($_SESSION['session_group'], ENT_QUOTES, 'UTF-8');
    //viene controllata se la form è stata riempita.
    if (isset($_POST['post-reply'])) {
        $id_post = $_POST['id_post'];
        $reply = $_POST['reply'];
        //viene controllato se ci sono allegati altrimenti viene messo $files=NULL
        $files = empty($_FILES['file']['name'][0]) ? NULL : $_FILES['file'];
        //se $files=NULL viene messo il numero di file=0
        $file_count = $files == NULL ? 0 : count($files['name']);

        $paths = '';

        for ($i = 0; $i < $file_count; $i++) {
            //viene creato un nome fittizio come nome dei file,così da non avere duplicati nella cartella
            $temp_file = time() . '_' . $files['name'][$i];
            //percorso in cui saranno salvati i file
            $target = 'uploads/' . $temp_file;
            //viene fatto l'upload dei file e controllato l'esito dell'operazione
            if (!(move_uploaded_file($files['tmp_name'][$i], $target))) {
                header("location: ../failure_upload.html");
                exit;
            }
            //solamente per il primo file non viene aggiunto ' ',scelta fatta per salvare la stringa nel database
            if ($paths == '') {
                $paths = $temp_file;
            } else {
                $paths = $paths . ' ' . $temp_file;
            }
        }

        //se dopo il ciclo paths è ancora la stringa vuota vuol dire che non c'erano allegati
        if ($paths == '') {
            $paths = NULL;
        }

        //query che inserisce il contenuto del commento nel database
        $query = "
            INSERT INTO commenti
            VALUES (0, :id_post, :username, :testo, :dati)
        ";
        $check = $pdo->prepare($query);
        $check->bindParam(':id_post', $id_post, PDO::PARAM_INT);
        $check->bindParam(':username', $session_user, PDO::PARAM_STR);
        $check->bindParam(':testo', $reply, PDO::PARAM_STR);
        $check->bindParam(':dati', $paths, PDO::PARAM_STR);
        $check->execute();

        //query per incrementare il numero dei commenti del post
        $query = "UPDATE message SET num_commenti = num_commenti + 1 WHERE id = :id";
        $check = $pdo->prepare($query);
        $check->bindParam(':id', $id_post, PDO::PARAM_INT);
        $check->execute();

        $_SESSION['session_post'] = $id_post;

        header("location: post.php");
        exit;
    }
} else {
    header("location: ../../account/Riservato.html");
    exit;
}
