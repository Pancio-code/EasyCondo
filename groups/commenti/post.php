<?php
session_start();
require_once('../../php/database.php');

//verifica se l'utente ha effettuato il login
if (isset($_SESSION['session_id'])) {
    $session_user = htmlspecialchars($_SESSION['session_user'], ENT_QUOTES, 'UTF-8');
    $session_post_id = htmlspecialchars($_SESSION['session_post'], ENT_QUOTES, 'UTF-8');
    $session_group = htmlspecialchars($_SESSION['session_group'], ENT_QUOTES, 'UTF-8');
    $session_role =  htmlspecialchars($_SESSION['session_role'], ENT_QUOTES, 'UTF-8');
    $session_group_type = htmlspecialchars($_SESSION['session_type_group'], ENT_QUOTES, 'UTF-8');

    //query per ottenre informazioni del post selezionato dall'utente tramite $session_post_id
    $query = "
        SELECT username, title, condominio, message, dati,num_commenti
        FROM message
        WHERE id = :id
    ";

    $check = $pdo->prepare($query);
    $check->bindParam(':id', $session_post_id, PDO::PARAM_INT);
    $check->execute();

    $post = $check->fetch(PDO::FETCH_ASSOC);

    //array in cui saranno inseriti i commenti del post
    $comments = array();

    //query che recupera tutti i commenti del post
    $query = "
        SELECT id,username,testo,dati
        FROM commenti
        WHERE id_post = :id_post
    ";

    $check = $pdo->prepare($query);
    $check->bindParam(':id_post', $session_post_id, PDO::PARAM_STR);
    $check->execute();

    $result = $check->fetchAll(PDO::FETCH_ASSOC);

    foreach ($result as $row) {
        //query che recupera l'immagine profilo di ogni utente che ha fatto un commento
        $query = "
            SELECT profile_picture
            FROM users
            WHERE username = :username
        ";

        $check = $pdo->prepare($query);
        $check->bindParam(':username', $row['username'], PDO::PARAM_STR);
        $check->execute();

        $result = $check->fetch(PDO::FETCH_ASSOC);

        //salvato il nome dell'immagine del profilo in questa variabile
        $picture = $result['profile_picture'];

        //viene aggiunto in testa all'array dei commenti un nuovo array contenente le informazioni del commento corrente dell'iterazione del ciclo
        array_unshift($comments, array($row['id'], $row['username'], $row['testo'], $row['dati'], $picture));
    }
} else {
    header("location: ../../account/Riservato.html");
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
    <link rel="stylesheet" href="post_style.css" type="text/css">

    <!--simbolo-->
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.js"></script>


    <!-- favicon generator-->
    <link rel="apple-touch-icon" sizes="57x57" href="../../favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="../../favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="../../favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="../../favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="../../favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="../../favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="../../favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="../../favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="../../favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="../../favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../favicon/favicon-16x16.png">
    <link rel="manifest" href="../../favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="../../favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <!-- file javascript per il funzionamento della pagina-->
    <script type="text/javascript" src="../groups.js"></script>
    <script type="text/javascript" src="../post.js"></script>
    <script type="text/javascript" src="reply.js"></script>
    <script type="text/javascript" src="../../javascript/transizione_tra_pagine.js"></script>

    <!-- Magnific Popup core CSS file -->
    <link rel="stylesheet" href="../../css/magnific-popup.css">

    <!-- Magnific Popup core JS file -->
    <script type="text/javascript" src="../../javascript/jquery.magnific-popup.min.js"></script>
</head>

<body>
    <!-- navbar fissa sopra la pagina con bootstrap-->
    <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="../../php/<?php echo lcfirst($session_role) ?>.php">
                <img src="../../favicon/favicon-32x32.png" alt="favicon del sito web,condominio stilizzato" width="30" height="24" class="d-inline-block align-text-top">
                EasyCondo
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a id="navHome" class="nav-link" aria-current="page" href="../../index.html#account">Home</a>
                    </li>
                    <li class="nav-item">
                        <a id="navGroup" class="nav-link" href="../../php/<?php echo lcfirst($session_role) ?>.php">Gruppi</a>
                    </li>
                    <li class="nav-item">
                        <a id="navCond" class="nav-link" href="#" onclick="location.href='../../<?php echo $session_group_type ?>'"><?php echo $session_group ?></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!--popup per gestisce la richiesta della cancellazione di un commento,il contenuto verrà generato da una funzione javascript OpenModalC-->
    <div class="overlay" id="overlay-cancel-comment"></div>

    <!--popup per gestisce la richiesta di rispondere ad un commento,il contenuto verrà generato da una funzione javascript OpenModalR-->
    <div class="overlay" id="overlay-reply-message"></div>

    <!--popup per gestisce la richiesta di cancellare il post,il contenuto verrà generato da una funzione javascript OpenModalCP-->
    <div class="overlay" id="overlay-cancel-post"></div>

    <!--qui troviamo la card del post e tutti i commenti,se ci sono,del post-->
    <div class="container posts" id="posts">
        <div class="section-header">
            <h1 class="display-1 section-heading">Post di <?php echo $post['username']; ?></h1>
            <div class="underline"></div>
        </div>
        <div class="section-middle" id="list-of-comment">
        </div>
    </div>

    <!-- return pagina precedente-->
    <div class="btn-group back" id="back-button">
        <button type="button" class="btn btn-secondary" aria-expanded="false" onclick="location.href='../../<?php echo $session_group_type ?>'">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
            </svg>
        </button>
    </div>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <script>
        let counter = 0;
        let loaded = 0;
        //il contenuto della pagina viene creato dinamicamente da uno script
        function create() {
            //eliminiamo se esiste il bottone che carica i post ,prima di aggiungere nuovi post
            var previus = document.getElementById('load' + loaded);
            if (previus != null) {
                previus.parentNode.removeChild(previus);
            }
            //contenuto del post
            var post_elements = <?php echo json_encode($post); ?>;
            //id del post
            var id_post = <?php echo json_encode($session_post_id); ?>;
            //tutti i commenti del post
            var comments = <?php echo json_encode($comments); ?>;
            //username dell'utente
            var user = <?php echo json_encode($session_user); ?>;
            //numero dei commenti
            var size = post_elements['num_commenti'];
            if (loaded == 0) {
                var link_files = '';
                //se ci sono allegati nel post
                if (post_elements['dati'] != null) {
                    //creato un array contenente i nomi dei file inseriti nel post
                    const myArray = post_elements['dati'].split(" ");
                    var length = myArray.length;
                    for (var j = 0; j < length; j++) {
                        //per ogni file viene creato un link per scaricare il file(split usata per mostrare all'utente il nome vero del file e non quello fittizzio usato nella cartella images)
                        link_files = link_files + '<a class="card-text link-uploads"  href="../uploads/' + myArray[j] + '" download>' + myArray[j].split("_")[1] + '</a>';
                    }
                    link_files = link_files + '<br>';
                }
                //frase generica se non ci sono allegati
                if (link_files == '') {
                    link_files = '<p class="card-text">Nessun allegato</p>';
                }
                //viene creato un elemento div inizialmente vuoto
                var newNode = document.createElement('div');
                //aggiunta la classe card all'elemento div per applicargli le caratteristiche delle card di bootstrap e lo stile legato a post-card
                newNode.className = 'card post-card';
                //se l'utente è l'autore del post
                if (user == post_elements['username']) {
                    newNode.innerHTML = '<div class="card-body message-body"><h1 class="card-title">Titolo:</h1><p class="card-text">' + post_elements['title'] + '</p><h1 class="card-title">Messaggio:</h1><p class="card-text">' + post_elements['message'] + '</p><h1 class="card-title">Allegati:</h1>' + link_files + '</div></div><div id="expand-button" class="comment-section" ><button id="expand" type="button" class="btn btn-secondary" onclick="open_reply();"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-bar-down" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 3.5a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13a.5.5 0 0 1-.5-.5zM8 6a.5.5 0 0 1 .5.5v5.793l2.146-2.147a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 0 1 .708-.708L7.5 12.293V6.5A.5.5 0 0 1 8 6z"></path></svg></button></div><div id="expanded_comment" class="post-header card-header"><div class="post" id="' + id_post + ' ' + post_elements['username'] + '"><div class="form"><form id="form-r" action="comment_v2.php" method="post" enctype="multipart/form-data"><div class="form-group"><label for="ReplyLabel"><h2>Risposta:</h2></label><textarea name="reply" class="form-control form-control-lg" id="ReplyLabel" placeholder="inserisci risposta" rows="1" minlength="3"></textarea></div><div class="form-group"><label for="customFile"><h2>Eventuali Allegati</h2></label><input type="file" name="file[]" class="form-control" id="files" multiple /></div><input type="hidden" id="' + id_post + '"  name="id_post" value="' + id_post + '" /><button type="submit"  name="post-reply" id="rispondi" class="btn btn-lg reply reply-left"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-reply" viewBox="0 0 16 16"><path d="M6.598 5.013a.144.144 0 0 1 .202.134V6.3a.5.5 0 0 0 .5.5c.667 0 2.013.005 3.3.822.984.624 1.99 1.76 2.595 3.876-1.02-.983-2.185-1.516-3.205-1.799a8.74 8.74 0 0 0-1.921-.306 7.404 7.404 0 0 0-.798.008h-.013l-.005.001h-.001L7.3 9.9l-.05-.498a.5.5 0 0 0-.45.498v1.153c0 .108-.11.176-.202.134L2.614 8.254a.503.503 0 0 0-.042-.028.147.147 0 0 1 0-.252.499.499 0 0 0 .042-.028l3.984-2.933zM7.8 10.386c.068 0 .143.003.223.006.434.02 1.034.086 1.7.271 1.326.368 2.896 1.202 3.94 3.08a.5.5 0 0 0 .933-.305c-.464-3.71-1.886-5.662-3.46-6.66-1.245-.79-2.527-.942-3.336-.971v-.66a1.144 1.144 0 0 0-1.767-.96l-3.994 2.94a1.147 1.147 0 0 0 0 1.946l3.994 2.94a1.144 1.144 0 0 0 1.767-.96v-.667z"></path></svg>  Rispondi</button></form></div></div><button type="button" class="btn btn-outline-danger delete-icon delete-icon-left" onclick="OpenModalCP(' + id_post + ');"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"></path><path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"></path></svg></button></div><div id="nonexpand_button" class="comment-section notexpand" ><button id="nonexpand" type="button" class="btn btn-secondary notexpand" onclick="close_reply();"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-bar-up" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 10a.5.5 0 0 0 .5-.5V3.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 3.707V9.5a.5.5 0 0 0 .5.5zm-7 2.5a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13a.5.5 0 0 1-.5-.5z"></path></svg></button></div></div>';
                } else {
                    newNode.innerHTML = '<div class="card-body message-body"><h1 class="card-title">Titolo:</h1><p class="card-text">' + post_elements['title'] + '</p><h1 class="card-title">Messaggio:</h1><p class="card-text">' + post_elements['message'] + '</p><h1 class="card-title">Allegati:</h1>' + link_files + '</div></div><div id="expand-button" class="comment-section" ><button id="expand" type="button" class="btn btn-secondary" onclick="open_reply();"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-bar-down" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 3.5a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13a.5.5 0 0 1-.5-.5zM8 6a.5.5 0 0 1 .5.5v5.793l2.146-2.147a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 0 1 .708-.708L7.5 12.293V6.5A.5.5 0 0 1 8 6z"></path></svg></button></div><div id="expanded_comment" class="card-header post-header"><div class="post" id="' + id_post + ' ' + post_elements['username'] + '"><div class="form"><form id="form-r" action="comment_v2.php" method="post" enctype="multipart/form-data"><div class="form-group"><label for="ReplyLabel"><h2>Risposta:</h2></label><textarea name="reply" class="form-control form-control-lg" id="ReplyLabel" placeholder="inserisci risposta" rows="1" minlength="3"></textarea></div><div class="form-group"><label for="customFile"><h2>Eventuali Allegati</h2></label><input type="file" name="file[]" class="form-control" id="files" multiple /></div><input type="hidden" id="' + id_post + '"  name="id_post" value="' + id_post + '" /><button type="submit"  name="post-reply" id="rispondi" class="btn btn-lg reply reply-left"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-reply" viewBox="0 0 16 16"><path d="M6.598 5.013a.144.144 0 0 1 .202.134V6.3a.5.5 0 0 0 .5.5c.667 0 2.013.005 3.3.822.984.624 1.99 1.76 2.595 3.876-1.02-.983-2.185-1.516-3.205-1.799a8.74 8.74 0 0 0-1.921-.306 7.404 7.404 0 0 0-.798.008h-.013l-.005.001h-.001L7.3 9.9l-.05-.498a.5.5 0 0 0-.45.498v1.153c0 .108-.11.176-.202.134L2.614 8.254a.503.503 0 0 0-.042-.028.147.147 0 0 1 0-.252.499.499 0 0 0 .042-.028l3.984-2.933zM7.8 10.386c.068 0 .143.003.223.006.434.02 1.034.086 1.7.271 1.326.368 2.896 1.202 3.94 3.08a.5.5 0 0 0 .933-.305c-.464-3.71-1.886-5.662-3.46-6.66-1.245-.79-2.527-.942-3.336-.971v-.66a1.144 1.144 0 0 0-1.767-.96l-3.994 2.94a1.147 1.147 0 0 0 0 1.946l3.994 2.94a1.144 1.144 0 0 0 1.767-.96v-.667z"></path></svg>  Rispondi</button></form></div><div id="nonexpand_button" class="comment-section notexpand" ><button id="nonexpand" type="button" class="btn btn-secondary notexpand" onclick="close_reply();"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-bar-up" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 10a.5.5 0 0 0 .5-.5V3.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 3.707V9.5a.5.5 0 0 0 .5.5zm-7 2.5a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13a.5.5 0 0 1-.5-.5z"></path></svg></button></div></div>';
                }
                //div creato per il post viene appeso ad un altro div "list-of-comment".
                document.getElementById('list-of-comment').appendChild(newNode);
            }
            if (size == 0 && loaded == 0) {
                var newNode = document.createElement('div');
                newNode.className = 'nothing';
                newNode.innerHTML = '<h1 class="title-commenti">Commenti:</h1><p class="lead">Ancora non è stato pubblicato nessun commento sotto il post,clicca il bottone rispondi per essere il primo!</p>';
                document.getElementById('list-of-comment').appendChild(newNode);
            } else {
                if (loaded == 0) {
                    var newNode = document.createElement('div');
                    newNode.className = 'filled';
                    newNode.innerHTML = '<h1 class="title-commenti">Commenti:</h1>';
                    document.getElementById('list-of-comment').appendChild(newNode);
                }
                var temp = 0;
                for (var i = counter; i < size && temp < 10; i++) {
                    var link_files = '';
                    if (comments[i][3] != null) {
                        const myArray = comments[i][3].split(" ");
                        var length = myArray.length;
                        for (var j = 0; j < length; j++) {
                            link_files = link_files + '<a class="card-text link-uploads" href="uploads/' + myArray[j] + '" download>' + myArray[j].split("_")[1] + '</a>';
                        }
                        link_files = link_files + '<br>';
                    }
                    newNode = document.createElement('div');
                    newNode.className = 'card post-card comment-card';
                    if (user == comments[i][1]) {
                        newNode.innerHTML = '<div class="card-header comment-header"><div class="post" id="' + id_post + ' ' + comments[i][1] + '"><a class="image-link" href="../../profilo/images/' + comments[i][4] + '"><img src="../../profilo/images/' + comments[i][4] + '" class="img-fluid" alt="foto profilo"></a><p class="user">' + comments[i][1] + '<button type="button" id="rispondi' + i + '" class="btn reply reply-right"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-reply" viewBox="0 0 16 16"><path d="M6.598 5.013a.144.144 0 0 1 .202.134V6.3a.5.5 0 0 0 .5.5c.667 0 2.013.005 3.3.822.984.624 1.99 1.76 2.595 3.876-1.02-.983-2.185-1.516-3.205-1.799a8.74 8.74 0 0 0-1.921-.306 7.404 7.404 0 0 0-.798.008h-.013l-.005.001h-.001L7.3 9.9l-.05-.498a.5.5 0 0 0-.45.498v1.153c0 .108-.11.176-.202.134L2.614 8.254a.503.503 0 0 0-.042-.028.147.147 0 0 1 0-.252.499.499 0 0 0 .042-.028l3.984-2.933zM7.8 10.386c.068 0 .143.003.223.006.434.02 1.034.086 1.7.271 1.326.368 2.896 1.202 3.94 3.08a.5.5 0 0 0 .933-.305c-.464-3.71-1.886-5.662-3.46-6.66-1.245-.79-2.527-.942-3.336-.971v-.66a1.144 1.144 0 0 0-1.767-.96l-3.994 2.94a1.147 1.147 0 0 0 0 1.946l3.994 2.94a1.144 1.144 0 0 0 1.767-.96v-.667z"></path></svg>Reply</button><button type="button" class="btn btn-outline-danger delete-icon delete-icon-right" onclick="OpenModalC(\'' + comments[i][0] + ' ' + id_post + '\');"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"></path><path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"></path></svg></button></p></div></div><div class="card-body message-body"><p class="card-text">' + comments[i][2] + '</p>' + link_files + '</div></div>';
                    } else {
                        newNode.innerHTML = '<div class="card-header comment-header"><div class="post" id="' + id_post + ' ' + comments[i][1] + '"><a class="image-link" href="../../profilo/images/' + comments[i][4] + '"><img src="../../profilo/images/' + comments[i][4] + '" class="img-fluid" alt="foto profilo"></a><p class="user">' + comments[i][1] + '<button type="button" id="rispondi' + i + '" class="btn reply reply-right"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-reply" viewBox="0 0 16 16"><path d="M6.598 5.013a.144.144 0 0 1 .202.134V6.3a.5.5 0 0 0 .5.5c.667 0 2.013.005 3.3.822.984.624 1.99 1.76 2.595 3.876-1.02-.983-2.185-1.516-3.205-1.799a8.74 8.74 0 0 0-1.921-.306 7.404 7.404 0 0 0-.798.008h-.013l-.005.001h-.001L7.3 9.9l-.05-.498a.5.5 0 0 0-.45.498v1.153c0 .108-.11.176-.202.134L2.614 8.254a.503.503 0 0 0-.042-.028.147.147 0 0 1 0-.252.499.499 0 0 0 .042-.028l3.984-2.933zM7.8 10.386c.068 0 .143.003.223.006.434.02 1.034.086 1.7.271 1.326.368 2.896 1.202 3.94 3.08a.5.5 0 0 0 .933-.305c-.464-3.71-1.886-5.662-3.46-6.66-1.245-.79-2.527-.942-3.336-.971v-.66a1.144 1.144 0 0 0-1.767-.96l-3.994 2.94a1.147 1.147 0 0 0 0 1.946l3.994 2.94a1.144 1.144 0 0 0 1.767-.96v-.667z"></path></svg>Reply</button></p></div></div><div class="card-body message-body"><p class="card-text">' + comments[i][2] + '</p>' + link_files + '</div></div>';
                    }
                    document.getElementById('list-of-comment').appendChild(newNode);
                    //se ci sono altri post allora aggiungo un bottone che richiama la funzione stessa per caricare gli atri post
                    if (i + 1 < size && temp + 1 >= 10) {
                        loaded += 1;
                        var newNode = document.createElement('div');
                        //aggiunta la classe nothing al div a cui sarà apllicato dello stile
                        newNode.className = 'nothing-button';
                        newNode.innerHTML = '<button id="load' + loaded + '" type="button" class="btn load" onclick="create();"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/><path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/></svg>Clicca per caricare altri commenti</button>';
                        //appeso il div creato ad un altro div "list-of-posts"
                        document.getElementById('list-of-comment').appendChild(newNode);
                    }
                    counter += 1;
                    temp += 1;
                }
            }
            //funzione che si occupa di creare il popup delle immagini di profilo
            $('.image-link').magnificPopup({
                type: 'image'
            });
            //listener che esegue il contenuto della funzione alla pressione del bottone reply del commento i-esimo
            $(".reply").click(function() {
                //viene recuperato l'id del post del div che contiene il buttone reply cliccato
                var parameter = $(this).closest("div").attr('id').split(" ");
                //viene passato l'uidentificativo del commento e l'identificatico del post a cui si vuole rispondere,e mostrato all'utente un popup per inserire il commento
                OpenModalRP(parameter[0], parameter[1]);
            });
        };
    </script>

    <!-- listener con jquery che gestiscono la pressione del bottone reply su ogni post e la funzione che mostra il tooltip sopra i tre bottoni principali-->
    <script>
        $(document).ready(function() {
            create();
        });
    </script>
</body>

</html>