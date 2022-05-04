<?php
//pagina che gestisce la creazione dei post nei vari gruupi
session_start();
require_once('../php/database.php');
//viene controllato che l'utente ha i permessi necessari,cioè ha effettuato il login
if (isset($_SESSION['session_id'])) {
    $session_user = htmlspecialchars($_SESSION['session_user'], ENT_QUOTES, 'UTF-8');
    $session_role =  htmlspecialchars($_SESSION['session_role'], ENT_QUOTES, 'UTF-8');
    $session_group = htmlspecialchars($_SESSION['session_group'], ENT_QUOTES, 'UTF-8');
    //viene controllata se la form è stata riempita.
    if (isset($_POST['post-create'])) {
        $title = $_POST['title'];
        $message = $_POST['message'];
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
                header("location: failure_upload.html");
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

        //query che inserisce il contenuto del messaggio nel database
        $query = "
            INSERT INTO message
            VALUES (0, :username, :title, :condominio, :message, :dati,0)
        ";
        $check = $pdo->prepare($query);
        $check->bindParam(':username', $session_user, PDO::PARAM_STR);
        $check->bindParam(':title', $title, PDO::PARAM_STR);
        $check->bindParam(':condominio', $session_group, PDO::PARAM_STR);
        $check->bindParam(':message', $message, PDO::PARAM_STR);
        $check->bindParam(':dati', $paths, PDO::PARAM_STR);
        $check->execute();

        if ($session_role == 'Amministratore') {
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
