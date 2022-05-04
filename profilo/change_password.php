<?php
//file che si occupa di aggiornare la password dell'utente
session_start();
require_once('../php/database.php');
//viene controllato che l'utente ha i permessi necessari,cioè ha effettuato il login
if (isset($_SESSION['session_id'])) {
    $session_user = htmlspecialchars($_SESSION['session_user'], ENT_QUOTES, 'UTF-8');
    //viene controllata se la form è stata riempita.
    if (isset($_POST['password'])) {
        $password = $_POST['password'];
        //la password viene criptata attravesro la funzione password_hash di php.
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        //query che recupera la password precedente dell'utente
        $query = 'SELECT password FROM users WHERE username = :username';

        $statement = $pdo->prepare($query);
        $statement->bindParam(':username', $session_user, PDO::PARAM_STR);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        //viene usata la funzione password_veify di php per controllare se la vecchia password è uguale a quella inserits dall'utente
        if (password_verify($password, $result['password']) === true) {
            //viene informato l'utente che ha inserito la sua precedente password.
            header("location: same_password.html");
            exit;
        } else {
            //query che aggiorna la password dell'utente
            $query = 'UPDATE users SET password = :password WHERE username = :username';

            $statement = $pdo->prepare($query);
            $statement->bindParam(':password', $password_hash, PDO::PARAM_STR);
            $statement->bindParam(':username', $session_user, PDO::PARAM_STR);
            $statement->execute();
            //aggiornamento andato a buon fine.
            header("location: new_password.html");
            exit;
        }
    }
} else {
    header("location: ../account/Riservato.html");
    exit;
}
