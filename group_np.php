<?php
session_start();
require_once('php/database.php');

//verifica se l'utente ha effettuato il login
if (isset($_SESSION['session_id'])) {
    //verifica se l'utente per accedere alla pagina ha selezionato un gruppo altrimenti gli viene impedito l'accesso
    if (isset($_SESSION['session_codice'])) {
        $_SESSION['session_type_group'] = "group_np.php";
        $session_user = htmlspecialchars($_SESSION['session_user'], ENT_QUOTES, 'UTF-8');
        $session_id = htmlspecialchars($_SESSION['session_id']);
        $session_role =  htmlspecialchars($_SESSION['session_role'], ENT_QUOTES, 'UTF-8');
        $session_group = htmlspecialchars($_SESSION['session_group'], ENT_QUOTES, 'UTF-8');
        $session_code = htmlspecialchars($_SESSION['session_codice'], ENT_QUOTES, 'UTF-8');

        //query per ottenere amministratore del gruppo
        $query = "
            SELECT amministratore
            FROM condominio
            WHERE name = :name
        ";

        $check = $pdo->prepare($query);
        $check->bindParam(':name', $session_group, PDO::PARAM_STR);
        $check->execute();

        $result = $check->fetch(PDO::FETCH_ASSOC);

        $amministratore_name = $result['amministratore'];

        //in people verranno inseriti i membri del gruppo con i propri dati
        $people = array();
        $amministratore = array();
        //in messages verranno salvati i post del gruppo che verranno poi mostrati nel gruppo
        $messages = array();

        //query per ottenere gli username dei membri del gruppo.
        $query = "
            SELECT username
            FROM groups
            WHERE condominio = :condominio
        ";

        $check = $pdo->prepare($query);
        $check->bindParam(':condominio', $session_group, PDO::PARAM_STR);
        $check->execute();

        $result = $check->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $row) {
            //query per ottenere foto profilo del membro i-esimo
            $query = "
                SELECT profile_picture
                FROM users
                WHERE username = :username
            ";

            $check = $pdo->prepare($query);
            $check->bindParam(':username', $row['username'], PDO::PARAM_STR);
            $check->execute();

            $info = $check->fetch(PDO::FETCH_ASSOC);

            //se l'username i-esimo è quello dell' amministratore viene aggiunto nell'array $amministratore il nome e la sua foto profilo,altrimenti viene aggiunto un nuovo elemento in people con l'username e foto profilo del membro.
            if ($amministratore_name != $row['username']) {
                array_push($people, array($row['username'], $info['profile_picture']));
            } else {
                array_push($amministratore, $amministratore_name, $info['profile_picture']);
            }
        }

        //query per ottenere i dati di ogni post del gruppo.
        $query = "
            SELECT id,username,title,message,dati,num_commenti
            FROM message
            WHERE condominio = :condominio
        ";

        $check = $pdo->prepare($query);
        $check->bindParam(':condominio', $session_group, PDO::PARAM_STR);
        $check->execute();

        $result = $check->fetchAll(PDO::FETCH_ASSOC);

        //per ogni post del gruppo
        foreach ($result as $row) {
            //query per ottenere la foto profilo del membro del post
            $query = "
                SELECT profile_picture
                FROM users
                WHERE username = :username
            ";

            $check = $pdo->prepare($query);
            $check->bindParam(':username', $row['username'], PDO::PARAM_STR);
            $check->execute();

            $result = $check->fetch(PDO::FETCH_ASSOC);

            $picture = $result['profile_picture'];

            //viene aggiunto in coda all'array u altro array contenente le informazioni del post i-esimo: identificativo,username del creatore,titolo e testo del post,il nome degli eventuali allegati e infine il numero di commenti
            array_unshift($messages, array($row['id'], $row['username'], $row['title'], $row['message'], $row['dati'], $picture, $row['num_commenti']));
        }
    } else {
        header("location: account/Riservato.html");
        exit;
    }
} else {
    header("location: account/Riservato.html");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Andrea Panceri & Lorenzo Cirone">
    <meta name="GENERATOR" content="Visual Studio Code">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>EasyCondo</title>
    <link rel="stylesheet" href="css/group.css" type="text/css">

    <!--simbolo x-->
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.js"></script>


    <!-- favicon generator-->
    <link rel="apple-touch-icon" sizes="57x57" href="favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
    <link rel="manifest" href="favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <!-- file javascript per il funzionamento della pagina-->
    <script type="text/javascript" src="javascript/enter.js"></script>
    <script type="text/javascript" src="javascript/post.js"></script>
    <script type="text/javascript" src="groups/groups.js"></script>
    <script type="text/javascript" src="groups/post.js"></script>
    <script type="text/javascript" src="groups/commenti/reply.js"></script>
    <script type="text/javascript" src="javascript/transizione_tra_pagine.js"></script>
    <script type="text/javascript" src="javascript/componenti_groups.js"></script>

    <!-- Magnific Popup core CSS file -->
    <link rel="stylesheet" href="css/magnific-popup.css">

    <!-- Magnific Popup core JS file -->
    <script src="javascript/jquery.magnific-popup.min.js"></script>
</head>

<body>
    <!-- navbar fissa sopra la pagina con bootstrap-->
    <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="php/<?php echo lcfirst($session_role) ?>.php">
                <img src="favicon/favicon-32x32.png" alt="favicon del sito web,condominio stilizzato" width="30" height="24" class="d-inline-block align-text-top">
                EasyCondo
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a id="navHome" class="nav-link" aria-current="page" href="index.html#account">Home</a>
                    </li>
                    <li class="nav-item">
                        <a id="navAccount" class="nav-link" href="php/<?php echo lcfirst($session_role) ?>.php">Gruppi</a>
                    </li>
                    <li class="nav-item">
                        <a id="navAccount" class="nav-link" href="account_np.php">Account</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- popup che appare alla pressione del bottone legato alla creazione di un nuovo post-->
    <div class="overlay popup-post" id="overlay-post">
        <div class="popup">
            <div onclick="CloseModalP()" class="CloseIcon"><i class="fa fa-times" aria-hidden="true"></i></div>
            <div class="section-header">
                <h1 class="display-1 section-heading">Crea post</h1>
                <div class="underline"></div>
            </div>
            <section class="condominio">
                <div class="form">
                    <form id="form" action="groups/create_post.php" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="TitleLabel">
                                <h2>Titolo</h2>
                            </label>
                            <input type="text" id="title" class="form-control" placeholder="Inserisci titolo" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="NameLabel">
                                <h2>Testo</h2>
                            </label>
                            <textarea name="message" class="form-control form-control-lg" id="MessageLabel" placeholder="inserisci messaggio" rows="3" minlength="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="customFile">
                                <h2>Eventuali Allegati</h2>
                            </label>
                            <input type="file" name="file[]" class="form-control" id="files" multiple />
                        </div>
                        <button id="submit" type="submit" class="submit-btn btn btn-info btn-lg" name="post-create">Crea</button>
                    </form>
                </div>
            </section>
        </div>
    </div>

    <!-- popup che appare alla pressione del bottone per invitare tramite mail un utente-->
    <div class="overlay popup-enter" id="overlay-enter">
        <div class="popup">
            <div onclick="CloseModalE()" class="CloseIcon"><i class="fa fa-times" aria-hidden="true"></i></div>
            <div class="section-header">
                <h1 class="display-1 section-heading">Invita condomino</h1>
                <div class="underline"></div>
            </div>
            <section class="condominio">
                <div class="form">
                    <form id="form1" action="php/invite.php" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="email">
                                <h2>Email</h2>
                            </label>
                            <input name="email" type="email" class="form-control" id="email" placeholder="Inserisci Email" required>
                        </div>
                        <button id="submit" type="submit" class="submit-btn btn btn-info btn-lg" name="send">Invita</button>
                    </form>
                </div>
            </section>
        </div>
    </div>

    <!--creatore del post può cancellare il suo post-->
    <div class="overlay" id="overlay-cancel-message"></div>

    <!--popup per rispondere ai post degli altri membri-->
    <div class="overlay" id="overlay-reply-message"></div>

    <!--alert che verrà mostrao se l'utente preme il bottone copia-->
    <div class="alert alert-success" id="success-alert">
        <strong>Successo! </strong> Codice copiato correttemente.
    </div>

    <!--sezione superiore in cui ci sono i tre bottoni:
    1. bacheca in cui ci sono i post degli utenti e il bottone per creare un post.
    2. copia codice del gruppo.
    3. membri in cui c'è l'elenco dei membri del gruppo e il bottono per invitare altri utenti.
    -->
    <div class="container posts" id="posts">
        <div class="section-header">
            <!--se si clicca sul nome del gruppo nella sezione superiore della pagina si potrà accedere alla pagina delle info del gruppo-->
            <h1 id="name-link" onclick="javascript:location.href='groups/info_group.php'" class="display-1 section-heading"><?php echo $session_group ?></h1>
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-info btn-lg bacheca-b" data-toggle="tooltip" data-placement="top" title="Bacheca"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-mailbox" viewBox="0 0 16 16">
                        <path d="M4 4a3 3 0 0 0-3 3v6h6V7a3 3 0 0 0-3-3zm0-1h8a4 4 0 0 1 4 4v6a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V7a4 4 0 0 1 4-4zm2.646 1A3.99 3.99 0 0 1 8 7v6h7V7a3 3 0 0 0-3-3H6.646z" />
                        <path d="M11.793 8.5H9v-1h5a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.354-.146l-.853-.854zM5 7c0 .552-.448 0-1 0s-1 .552-1 0a1 1 0 0 1 2 0z" />
                    </svg></button>
                <button type="button" class="btn btn-warning btn-lg copy-b" onclick="copyString();" data-toggle="tooltip" data-placement="top" title="Copia codice"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-files" viewBox="0 0 16 16">
                        <path d="M13 0H6a2 2 0 0 0-2 2 2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h7a2 2 0 0 0 2-2 2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm0 13V4a2 2 0 0 0-2-2H5a1 1 0 0 1 1-1h7a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1zM3 4a1 1 0 0 1 1-1h7a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V4z" />
                    </svg></button>
                <button type="button" class="btn btn-outline-info btn-lg persone-b" onclick="persone();" data-toggle="tooltip" data-placement="top" title="Persone"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16">
                        <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816zM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275zM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4z" />
                    </svg></button>
            </div>
        </div>

        <!--attraverso uno script javascript vengono create le card dei post del gruppo in modo dinamico,le informazioni vengono prese dalla variabile php messages-->
        <div class="section-middle" id="list-of-posts"></div>
    </div>

    <!--sezione superiore in cui ci sono i tre bottoni:
    1. bacheca in cui ci sono i post degli utenti e il bottone per creare un post.
    2. copia codice del gruppo.
    3. membri in cui c'è l'elenco dei membri del gruppo e il bottono per invitare altri utenti.
    -->
    <div class="container people" id="people">
        <div class="section-header">
            <!--se si clicca sul nome del gruppo nella sezione superiore della pagina si potrà accedere alla pagina delle info del gruppo-->
            <h1 id="name-link" onclick="javascript:location.href='groups/info_group.php'" class="display-1 section-heading"><?php echo $session_group ?></h1>
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-outline-info btn-lg bacheca-b" onclick="stream();" data-toggle="tooltip" data-placement="top" title="Bacheca"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-mailbox" viewBox="0 0 16 16">
                        <path d="M4 4a3 3 0 0 0-3 3v6h6V7a3 3 0 0 0-3-3zm0-1h8a4 4 0 0 1 4 4v6a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V7a4 4 0 0 1 4-4zm2.646 1A3.99 3.99 0 0 1 8 7v6h7V7a3 3 0 0 0-3-3H6.646z" />
                        <path d="M11.793 8.5H9v-1h5a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.354-.146l-.853-.854zM5 7c0 .552-.448 0-1 0s-1 .552-1 0a1 1 0 0 1 2 0z" />
                    </svg></button>
                <button type="button" class="btn btn-warning btn-lg copy-b" onclick="copyString();" data-toggle="tooltip" data-placement="top" title="Copia codice"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-files" viewBox="0 0 16 16">
                        <path d="M13 0H6a2 2 0 0 0-2 2 2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h7a2 2 0 0 0 2-2 2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm0 13V4a2 2 0 0 0-2-2H5a1 1 0 0 1 1-1h7a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1zM3 4a1 1 0 0 1 1-1h7a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V4z" />
                    </svg></button>
                <button type="button" class="btn btn-info btn-lg persone-b" data-toggle="tooltip" data-placement="top" title="Persone"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16">
                        <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816zM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275zM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4z" />
                    </svg></button>
            </div>
        </div>

        <!--attraverso uno script javascript vengono create le card dei membri del gruppo in modo dinamico,le informazioni vengono prese dalla variabile php people-->
        <div class="section-middle" id="list-of-person"></div>
        <script>
            (function() {
                //vengono recuperati i membri del gruppo.
                var people = <?php echo json_encode($people); ?>;
                //viene recuperato l'amministratore del gruppo.
                var amministratore = <?php echo json_encode($amministratore); ?>;
                //numero dei membri del gruppo,incluso l'amministratore
                var size = people.length;
                //viene creato un elemento div inizialmente vuoto
                var newNode = document.createElement('div');
                //aggiunta la classe card all'elemento div per applicargli le caratteristiche delle card di bootstrap
                newNode.className = 'card mb-3';
                //aggiunto al div l'amministratore del gruppo con l'immagine profilo.
                newNode.innerHTML = '<div class="person"><a class="image-link" href="profilo/images/' + amministratore[1] + '"><img src="profilo/images/' + amministratore[1] + '" class="img-fluid" alt="foto profilo"></a><p>' + amministratore[0] + ' <b>(Amministratore)</p></div>';
                //appeso il div creato ad un altro div "list-of-person"
                document.getElementById('list-of-person').appendChild(newNode);
                for (var i = 0; i < size; i++) {
                    //viene creato un elemento div inizialmente vuoto
                    var newNode = document.createElement('div');
                    //aggiunta la classe card all'elemento div per applicargli le caratteristiche delle card di bootstrap
                    newNode.className = 'card mb-3';
                    //aggiunto al div gli utenti del gruppo con la loro immagine profilo.
                    newNode.innerHTML = '<div class="person"><a class="image-link" href="profilo/images/' + people[i][1] + '"><img src="profilo/images/' + people[i][1] + '" class="img-fluid" alt="foto profilo"></a><p>' + people[i][0] + '</p></div>';
                    //Ogni div creato per l'i-esimo membro viene appeso ad un altro div "list-of-person".
                    document.getElementById('list-of-person').appendChild(newNode);
                }
            })();
        </script>
    </div>

    <!-- bottone che gestisce l'apertura del form per creare un post-->
    <div class="btn-group dropup new-post" id="new-post">
        <button type="button" class="btn btn-secondary" aria-expanded="false" onclick="OpenModalP()">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
            </svg>
        </button>
    </div>


    <!-- bottone che gestisce l'apertura del form per invitare nuovi membri-->
    <div class="btn-group dropup new-member" id="new-member">
        <button type="button" class="btn btn-secondary" aria-expanded="false" onclick="OpenModalE()">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-person-plus" viewBox="0 0 16 16">
                <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z" />
                <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z" />
            </svg>
        </button>
    </div>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!-- copia codice del gruppo alla pressione del bottone centrale-->
    <script>
        function copyString() {
            //viene creata una text-area fittizia
            var el = document.createElement('textarea');
            //nella text-area viene inserito il codice del gruppo.
            el.value = <?php echo json_encode($session_code) ?>;
            //viene settata in sola lettura la text-area.
            el.setAttribute('readonly', '');
            //viene posizionata in un area non visibile all'utente anche se questa scomparirà quasi istantaneamente
            el.style = {
                position: 'absolute',
                left: '-9999px'
            };
            //viene appesa al body,selezionata e eseguita la copia del contenuto della text-area alla clipboard e infine rimossa la text-area.
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
        }
    </script>

    <script>
        let counter = 0;
        let loaded = 0;

        //funzione che crea dinamicamnete i post
        function create() {
            //eliminiamo se esiste il bottone che carica i post ,prima di aggiungere nuovi post
            var previus = document.getElementById('load' + loaded);
            if (previus != null) {
                previus.parentNode.removeChild(previus);
            }
            //vengono recuperati i post degli utenti del gruppo
            var messages = <?php echo json_encode($messages); ?>;
            //viene recuperato l'username dell'utente
            var user = <?php echo json_encode($session_user); ?>;
            var size = messages.length;
            //se il numero dei post è uguale a zero
            if (size == 0) {
                //viene creato un elemento div inizialmente vuoto
                var newNode = document.createElement('div');
                //aggiunta la classe nothing al div a cui sarà apllicato dello stile
                newNode.className = 'nothing';
                newNode.innerHTML = '<p class="lead">Ancora non è stato pubblicato nessun post,clicca il bottone in basso per essere il primo!</p>';
                //appeso il div creato ad un altro div "list-of-posts"
                document.getElementById('list-of-posts').appendChild(newNode);
            } else {
                var temp = 0;
                for (var i = counter; i < size && temp < 10; i++) {
                    var link_files = '';
                    //se il campo dati del post non è vuoto
                    if (messages[i][4] != null) {
                        //creato un array contenente i nomi dei file inseriti nel post
                        const myArray = messages[i][4].split(" ");
                        var length = myArray.length;
                        for (var j = 0; j < length; j++) {
                            //per ogni file viene creato un link per scaricare il file(split usata per mostrare all'utente il nome vero del file e non quello fittizzio usato nella cartella images)
                            link_files = link_files + '<a class="link-uploads" href="groups/uploads/' + myArray[j] + '" download>' + myArray[j].split("_")[1] + '</a>';
                        }
                        link_files = link_files + '<br>';
                    }
                    //viene creato un elemento div inizialmente vuoto
                    var newNode = document.createElement('div');
                    //aggiunta la classe card all'elemento div per applicargli le caratteristiche delle card di bootstrap e lo stile legato a post-card
                    newNode.className = 'card post-card';
                    //se il post è stato scritto dall'utente attuale verrà eseguito il codice dell'if altrimenti quello dell'else
                    if (user == messages[i][1]) {
                        //viene creato un post con l'immagine del profilo dell'utente che l'ha creato(quello corrente visto che stiamo nell'if) e due bottoni: reply che attraverso il listener che si trova il fondo alla pagina permette di commentare il post e delete che permette al creatore del post di cancellarlo tramite OpenModalC. Nel body della card ci saranno il titolo,contenuto e gli allegati del post, e anche il numero di commenti con un link cliccabile per vederli.
                        newNode.innerHTML = '<div class="card-header"><div class="post" id="' + messages[i][0] + ' ' + messages[i][1] + '"><a class="image-link" href="profilo/images/' + messages[i][5] + '"><img src="profilo/images/' + messages[i][5] + '" class="img-fluid" alt="foto profilo"></a><p class="user">' + messages[i][1] + '<button type="button" id="rispondi' + i + '" class="btn reply"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-reply" viewBox="0 0 16 16"><path d="M6.598 5.013a.144.144 0 0 1 .202.134V6.3a.5.5 0 0 0 .5.5c.667 0 2.013.005 3.3.822.984.624 1.99 1.76 2.595 3.876-1.02-.983-2.185-1.516-3.205-1.799a8.74 8.74 0 0 0-1.921-.306 7.404 7.404 0 0 0-.798.008h-.013l-.005.001h-.001L7.3 9.9l-.05-.498a.5.5 0 0 0-.45.498v1.153c0 .108-.11.176-.202.134L2.614 8.254a.503.503 0 0 0-.042-.028.147.147 0 0 1 0-.252.499.499 0 0 0 .042-.028l3.984-2.933zM7.8 10.386c.068 0 .143.003.223.006.434.02 1.034.086 1.7.271 1.326.368 2.896 1.202 3.94 3.08a.5.5 0 0 0 .933-.305c-.464-3.71-1.886-5.662-3.46-6.66-1.245-.79-2.527-.942-3.336-.971v-.66a1.144 1.144 0 0 0-1.767-.96l-3.994 2.94a1.147 1.147 0 0 0 0 1.946l3.994 2.94a1.144 1.144 0 0 0 1.767-.96v-.667z"></path></svg>Reply</button><button type="button" class="btn btn-outline-danger delete-icon" onclick="OpenModalC(' + messages[i][0] + ');"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"></path><path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"></path></svg></button></p></div></div><div class="card-body message-body"><h3 class="card-title">' + messages[i][2] + '</h3><p class="card-text">' + messages[i][3] + '</p>' + link_files + '<button type="button" class="btn btn-link btn-lg comment" id="' + messages[i][0] + '" onclick="enter_post(' + messages[i][0] + ');">Commenti (' + messages[i][6] + ')</button></div></div>';
                    } else {
                        //viene creato un post con l'immagine del profilo dell'utente che l'ha creato e un bottone reply che attraverso il listener che si trova il fondo alla pagina permette di commentare il post. Nel body della card ci saranno il titolo,contenuto e gli allegati del post, e anche il numero di commenti con un link cliccabile per vederli.
                        newNode.innerHTML = '<div class="card-header"><div class="post" id="' + messages[i][0] + ' ' + messages[i][1] + '"><a class="image-link" href="profilo/images/' + messages[i][5] + '"><img src="profilo/images/' + messages[i][5] + '" class="img-fluid" alt="foto profilo"></a><p class="user">' + messages[i][1] + '<button type="button" id="rispondi' + i + '" class="btn reply"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-reply" viewBox="0 0 16 16"><path d="M6.598 5.013a.144.144 0 0 1 .202.134V6.3a.5.5 0 0 0 .5.5c.667 0 2.013.005 3.3.822.984.624 1.99 1.76 2.595 3.876-1.02-.983-2.185-1.516-3.205-1.799a8.74 8.74 0 0 0-1.921-.306 7.404 7.404 0 0 0-.798.008h-.013l-.005.001h-.001L7.3 9.9l-.05-.498a.5.5 0 0 0-.45.498v1.153c0 .108-.11.176-.202.134L2.614 8.254a.503.503 0 0 0-.042-.028.147.147 0 0 1 0-.252.499.499 0 0 0 .042-.028l3.984-2.933zM7.8 10.386c.068 0 .143.003.223.006.434.02 1.034.086 1.7.271 1.326.368 2.896 1.202 3.94 3.08a.5.5 0 0 0 .933-.305c-.464-3.71-1.886-5.662-3.46-6.66-1.245-.79-2.527-.942-3.336-.971v-.66a1.144 1.144 0 0 0-1.767-.96l-3.994 2.94a1.147 1.147 0 0 0 0 1.946l3.994 2.94a1.144 1.144 0 0 0 1.767-.96v-.667z"></path></svg>Reply</button></p></div></div><div class="card-body message-body"><h3 class="card-title">' + messages[i][2] + '</h3><p class="card-text">' + messages[i][3] + '</p>' + link_files + '<button type="button" class="btn btn-link btn-lg comment" id = "' + messages[i][0] + '" onclick="enter_post(' + messages[i][0] + ');">Commenti (' + messages[i][6] + ')</button></div></div>';
                    }
                    //Ogni div creato per l'i-esimo post viene appeso ad un altro div "list-of-posts".
                    document.getElementById('list-of-posts').appendChild(newNode);
                    //se ci sono altri post allora aggiungo un bottone che richiama la funzione stessa per caricare gli atri post
                    if (i + 1 < size && temp + 1 >= 10) {
                        loaded += 1;
                        var newNode = document.createElement('div');
                        //aggiunta la classe nothing al div a cui sarà apllicato dello stile
                        newNode.className = 'nothing';
                        newNode.innerHTML = '<button id="load' + loaded + '" type="button" class="btn load" onclick="create();"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/><path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/></svg>Clicca per caricare altri post</button>';
                        //appeso il div creato ad un altro div "list-of-posts"
                        document.getElementById('list-of-posts').appendChild(newNode);
                    }
                    counter += 1;
                    temp += 1;
                }
            }
            //funzione che si occupa di creare il popup delle immagini di profilo
            $('.image-link').magnificPopup({
                type: 'image'
            });
            $(".reply").click(function() {
                //viene recuperato l'id del post del div che contiene il buttone reply cliccato
                var parameter = $(this).closest("div").attr('id').split(" ");
                //viene passato l'username e identificatico del post a cui si vuole rispondere,e mostrato all'utente un popup per inserire il commento
                OpenModalR(parameter[0], parameter[1])
            });
        };
    </script>
</body>

</html>