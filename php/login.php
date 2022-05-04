<?php
//file che si occupa del login dell'utente.
session_start();
require_once('database.php');
//viene controllata se la form è stata riempita.
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    //query per ottenere i dati corrispondenti all'username inserito
    $query = "
        SELECT username, password, ruolo
        FROM users
        WHERE username = :username
    ";

    $check = $pdo->prepare($query);
    $check->bindParam(':username', $username, PDO::PARAM_STR);
    $check->execute();

    $user = $check->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        //se l'username non esiste,l'utente torna alla pagina di login in cui verrà avvisato che l'username inserito non esiste
        $_SESSION['errors'] = array("L'username è inesistente.");
        header('Location: ../account/login.php');
    } elseif (password_verify($password, $user['password']) === false) {
        //se l'usernameesiste ma la password non corrisponde a quella criptata nel database,l'utente torna alla pagina di login in cui verrà avvisato che la password è errata
        $_SESSION['errors'] = array("Password errata.");
        header('Location: ../account/login.php');
    } else {
        //altrimenti viene creata un sessione con alcune variabili
        session_regenerate_id();
        $_SESSION['session_id'] = session_id();
        $_SESSION['session_user'] = $user['username'];
        $_SESSION['session_role'] = $user['ruolo'];
        unset($_SESSION['errors']);

        if ($user['ruolo'] == 'Amministratore') {
            header('Location: amministratore.php');
        } else {
            header('Location: condomino.php');
        }
        exit;
    }
}
