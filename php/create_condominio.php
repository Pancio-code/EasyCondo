<?php
//file che si occupa di creare un condominio
session_start();
require_once('database.php');
//viene controllato che l'utente ha i permessi necessari,cioè ha effettuato il login
if (isset($_SESSION['session_id'])) {
    //viene controllata se la form è stata riempita.
    if (isset($_POST['create'])) {
        $name = $_POST['name'];
        $citta = $_POST['citta'];
        $indirizzo = $_POST['indirizzo'];
        $session_user = htmlspecialchars($_SESSION['session_user'], ENT_QUOTES, 'UTF-8');
        //viene creato un codice del gruppo criptando il nome del gruppo con una chiave $key
        $key = "linguaggietecnologieperilweb";
        $encoded = openssl_encrypt($name, "AES-256-ECB", $key);

        //query per recuperare dalla tabella condominio i dati del condominio legato al nome fornito
        $query = "
            SELECT id
            FROM condominio
            WHERE name = :name
        ";

        $check = $pdo->prepare($query);
        $check->bindParam(':name', $name, PDO::PARAM_STR);
        $check->execute();

        $user = $check->fetchAll(PDO::FETCH_ASSOC);

        if (count($user) > 0) {
            //se esistono righe nella tabella con quel nome,segnalo all'utente che il nome scelto già esiste,deve cambiarlo
            header("location: ../riservato/duplicate.html");
            exit;
        } else {
            //altrimenti se non esiste già un condominio con quel nome,inserisco i dati forniti nella tabella condominio
            $query = "
                INSERT INTO condominio
                VALUES (0, :name, :indirizzo, :amministratore, :citta, :codice)
            ";

            $check = $pdo->prepare($query);
            $check->bindParam(':name', $name, PDO::PARAM_STR);
            $check->bindParam(':indirizzo', $indirizzo, PDO::PARAM_STR);
            $check->bindParam(':amministratore', $session_user, PDO::PARAM_STR);
            $check->bindParam(':citta', $citta, PDO::PARAM_STR);
            $check->bindParam(':codice', $encoded, PDO::PARAM_STR);
            $check->execute();

            if ($check->rowCount() <= 0) {
                //se ci sono stati problemi con l'inserimento nel database lo segnalo all'utente
                header("location: ../riservato/Error_create.html");
            } else {
                //altrimenti inserisco l'utente,l'amministratore del gruppo,nel condominio
                $query = "
                    INSERT INTO groups
                    VALUES (0, :username, :condominio)
                ";
                $check = $pdo->prepare($query);
                $check->bindParam(':username', $session_user, PDO::PARAM_STR);
                $check->bindParam(':condominio', $name, PDO::PARAM_STR);
                $check->execute();

                //mando un mail all'utente il codice del gruppo con cui inviterà altri condomini
                $email_subject = 'Messaggio da EasyCondo';
                $email_response = "Il codice del gruppo " . $name . " è: " . $encoded . ".\n\n\n\nEasyCondo";
                $headers = "From: Your-Email\r\n";
                $query = "
                    SELECT email
                    FROM users
                    WHERE username = :username
                ";

                $check = $pdo->prepare($query);
                $check->bindParam(':username', $session_user, PDO::PARAM_STR);
                $check->execute();
                $email = $check->fetchAll()[0]['email'];
                mail($email, $email_subject, $email_response, $headers);
                header("location: ../riservato/success.html");
            }
            exit;
        }
    }
} else {
    header("location: ../account/Riservato.html");
    exit;
}
