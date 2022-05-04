<?php
session_start();
require_once('../php/database.php');

//verifica se l'utente ha effettuato il login
if (isset($_SESSION['session_id'])) {
    $session_group = htmlspecialchars($_SESSION['session_group'], ENT_QUOTES, 'UTF-8');
    $session_group_type = htmlspecialchars($_SESSION['session_type_group'], ENT_QUOTES, 'UTF-8');
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
    //query per ottenere foto profilo dell'user corrente.
    $query = 'SELECT profile_picture FROM users WHERE username = :username';

    $statement = $pdo->prepare($query);
    $statement->bindParam(':username', $amministratore_name, PDO::PARAM_STR);
    $statement->execute();

    $result = $statement->fetch(PDO::FETCH_ASSOC);
    $profile_image = $result['profile_picture'];

    $query = "
        SELECT name,citta,indirizzo,amministratore,codice
        FROM condominio
        WHERE name = :name
    ";

    $check = $pdo->prepare($query);
    $check->bindParam(':name', $session_group, PDO::PARAM_STR);
    $check->execute();

    $info = $check->fetch(PDO::FETCH_ASSOC);

    $group_name = $info['name'];
    $group_indirizzo = $info['indirizzo'];
    $group_citta = $info['citta'];
    $group_code = $info['codice'];
} else {
    header("location: ../account/Riservato.html");
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
    <link rel="stylesheet" href="../css/account.css" type="text/css">

    <!--simbolo-->
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.js"></script>


    <!-- favicon generator-->
    <link rel="apple-touch-icon" sizes="57x57" href="../favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="../favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="../favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="../favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="../favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="../favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="../favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="../favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="../favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="../favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="../favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon/favicon-16x16.png">
    <link rel="manifest" href="../favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="../favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <!-- file javascript per il funzionamento della pagina-->
    <script type="text/javascript" src="../javascript/transizione_tra_pagine.js"></script>
</head>

<body>
    <!-- navbar fissa sopra la pagina con bootstrap-->
    <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="../<?php echo $session_group_type ?>">
                <img src="../favicon/favicon-32x32.png" alt="favicon del sito web,condominio stilizzato" width="30" height="24" class="d-inline-block align-text-top">
                EasyCondo
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a id="navHome" class="nav-link" aria-current="page" href="../index.html#account">Home</a>
                    </li>
                    <li class="nav-item">
                        <a id="navCond" class="nav-link" href="../<?php echo $session_group_type ?>"><?php echo $session_group ?></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!--card contenenti le informazioni dell'account-->
    <div class="container" id="groups">
        <div class="section-header">
            <h1 class="display-1 section-heading">Info Condominio</h1>
            <div class="underline"></div>
        </div>
        <div class="section-account">
            <div class="card mb-3" data-tilt data-tilt-max="5" data-tilt-speed="100">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="../profilo/images/<?php echo $profile_image; ?>" class="img-fluid rounded-start" alt="immagine di profilo dell'amministratore">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h1 class="card-title"><b>Amministratore:</b></h1>
                            <p class="card-text large"><?php echo $amministratore_name ?></p>
                        </div>
                        <div class="card-body">
                            <h1 class="card-title"><b>Nome condominio:</b></h1>
                            <p class="card-text"><?php echo $group_name ?></p>
                        </div>
                        <div class="card-body">
                            <h1 class="card-title"><b>Indirizzo:</b></h1>
                            <p class="card-text"><?php echo $group_indirizzo ?></p>
                        </div>
                        <div class="card-body">
                            <h1 class="card-title"><b>Città:</b></h1>
                            <p class="card-text"><?php echo $group_citta ?></p>
                        </div>
                        <div class="card-body">
                            <h1 class="card-title"><b>Codice:</b></h1>
                            <p class="card-text"><?php echo $group_code ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <!--animazione per le card con il movimento del mouse-->
    <script type="text/javascript">
        (function() {
            if (window.innerWidth > 600) {
                var theScript = document.createElement('script');
                theScript.type = 'text/javascript';
                theScript.src = '../javascript/vanilla-tilt.min.js';

                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(theScript, s);
            }
        })();
    </script>
</body>

</html>