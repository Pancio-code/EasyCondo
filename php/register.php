<?php
//file che si occupa della registrazione dell'utente.
session_start();
require_once('database.php');
//viene controllata se la form è stata riempita.
if (isset($_POST['register'])) {
    //vengono recuperati le informazioni della form per registrarsi
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    //viene criptata la password che verrà poi inserita nel database
    $password_hash = password_hash($password, PASSWORD_BCRYPT);
    $ruolo = $_POST['ruolo'];
    $image = 'unknown.png';

    //query per controllare se esite qualche utente con l'username scelto dall'utente
    $query = "
        SELECT id
        FROM users
        WHERE username = :username
    ";

    $check = $pdo->prepare($query);
    $check->bindParam(':username', $username, PDO::PARAM_STR);
    $check->execute();

    $user = $check->fetchAll(PDO::FETCH_ASSOC);

    //se ci sono altri users con lo stesso username nella tabella
    if (count($user) > 0) {
        //l'utente ritorna alla pagina di registrazione in cui verrà mostrata questa stringa d'errore.
        $_SESSION['errors-register'] = array("Purtroppo l'username scelto esiste già.");
        header("location: ../account/register.php");
    } else {
        //query per inserire i dati della registrazione nella tabella users del sito
        $query = "
            INSERT INTO users
            VALUES (0, :username, :email, :password, :ruolo, :image)
        ";
        $check = $pdo->prepare($query);
        $check->bindParam(':username', $username, PDO::PARAM_STR);
        $check->bindParam(':email', $email, PDO::PARAM_STR);
        $check->bindParam(':password', $password_hash, PDO::PARAM_STR);
        $check->bindParam(':ruolo', $ruolo, PDO::PARAM_STR);
        $check->bindParam(':image', $image, PDO::PARAM_STR);
        $check->execute();

        //controllo del corretto inserimento dei dati
        if ($check->rowCount() > 0) {
            //tramite mail viene mandata un email di benvenuto al sito e verrà mostrata una pagina di conferma registrazione.
            $email_subject = 'Messaggio da EasyCondo';
            $email_response = "Benvenuto " . $username . ", la registrazione è avvenuta con successo, buona navigazione!\n\n\n\nEasyCondo";
            $headers = "From: Your-Email\r\n";
            mail($email, $email_subject, $email_response, $headers);
            unset($_SESSION['errors-register']);
            header("location: ../account/Success.html");
        } else {
            //l'utente ritorna alla pagina di registrazione in cui verrà mostrata questa stringa d'errore.
            $_SESSION['errors-register'] = array("Ops, c'è stato un problema,ci scusiamo per il disagio.");
            header("location: ../account/register.php");
        }
    }
}
