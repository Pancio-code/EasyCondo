<?php
//file che cambia la password di un utente che ha dimenticato la propria password
require_once('database.php');
//viene controllata se la form è stata riempita
if (isset($_POST['change'])) {
    $user = $_POST['username'];
    $password = $_POST['password'];
    //la password inserita viene criptata
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    //viene recuperata la password dell'utente
    $query = 'SELECT password FROM users WHERE username = :username';

    $statement = $pdo->prepare($query);
    $statement->bindParam(':username', $user, PDO::PARAM_STR);
    $statement->execute();

    $result = $statement->fetch(PDO::FETCH_ASSOC);

    //se la password inserita è uguale a quella precedente viene avvisato l'utente che ha inserito la stessa password.
    if (password_verify($password, $result['password']) === true) {
        header("location: ../account/same_password.html");
        exit;
    } else {
        //altrimenti elimino la richiesta della nuova password dalla tabella password_change_request
        $query = 'DELETE FROM password_change_request WHERE username = :username';

        $check = $pdo->prepare($query);
        $check->bindParam(':username', $user, PDO::PARAM_STR);
        $check->execute();

        //aggiorno la password dell'utente con la nuova password scelta
        $query = 'UPDATE users SET password = :password WHERE username = :username';

        $statement = $pdo->prepare($query);
        $statement->bindParam(':password', $password_hash, PDO::PARAM_STR);
        $statement->bindParam(':username', $user, PDO::PARAM_STR);
        $statement->execute();
        header("location: ../account/new_password.html");
        exit;
    }
} else {
    header("location: ../account/Riservato.html");
    exit;
}
