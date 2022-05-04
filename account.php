<?php
session_start();
require_once('php/database.php');

//verifica se l'utente ha effettuato il login
if (isset($_SESSION['session_id'])) {
    $session_user = htmlspecialchars($_SESSION['session_user'], ENT_QUOTES, 'UTF-8');
    $session_id = htmlspecialchars($_SESSION['session_id']);
    $session_role =  htmlspecialchars($_SESSION['session_role'], ENT_QUOTES, 'UTF-8');
    if ($session_role != "Amministratore") {
        header("location: account/Riservato.html");
        exit;
    }

    //query per ottenere foto profilo dell'user corrente.
    $query = 'SELECT profile_picture,email FROM users WHERE username = :username';

    $statement = $pdo->prepare($query);
    $statement->bindParam(':username', $session_user, PDO::PARAM_STR);
    $statement->execute();

    $result = $statement->fetch(PDO::FETCH_ASSOC);
    $profile_image = $result['profile_picture'];
    $email = $result['email'];
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
    <link rel="stylesheet" href="css/account.css" type="text/css">

    <!--simbolo-->
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
    <script type="text/javascript" src="javascript/exit_account.js"></script>
    <script type="text/javascript" src="javascript/change_password.js"></script>
    <script type="text/javascript" src="javascript/transizione_tra_pagine.js"></script>
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
                        <a id="navAccount" class="nav-link" href="php/amministratore.php">Gruppi</a>
                    </li>
                    <li class="nav-item">
                        <a id="navAccount" class="nav-link active" href="#">Account</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- popup che appare alla pressione del bottone,per eliminare l'account-->
    <div class="overlay" id="overlay-delete">
        <div class="popup">
            <div onclick="CloseModalD()" class="CloseIcon"><i class="fa fa-times" aria-hidden="true"></i></div>
            <div class="section-header">
                <h1 class="display-1 section-heading">Eliminazione dell' account</h1>
                <div class="underline"></div>
            </div>
            <p class="lead">Sei sicuro di voler eliminare il tuo account?</p>
            <div class="clearfix">
                <button type="button" onclick="CloseModalD()" class="cancelbtn btn">Annulla</button>
                <button type="button" id="" onclick="window.location.href='php/cancel_account.php'" class="deletebtn btn">Elimina</button>
            </div>
        </div>
    </div>

    <!-- popup che appare alla pressione del bottone,per effettuare il logout-->
    <div class="overlay" id="overlay-exit">
        <div class="popup">
            <div onclick="CloseModalX()" class="CloseIcon"><i class="fa fa-times" aria-hidden="true"></i></div>
            <div class="section-header">
                <h1 class="display-1 section-heading">Logout</h1>
                <div class="underline"></div>
            </div>
            <p class="lead">Sei sicuro di voler effettuare il logout?</p>
            <div class="clearfix">
                <button type="button" onclick="CloseModalX()" class="cancelbtn btn">Annulla</button>
                <button type="button" id="" onclick="window.location.href='php/logout.php'" class="exitbtn btn">Esci</button>
            </div>
        </div>
    </div>

    <!-- popup che appare alla pressione del bottone,per cambiare password-->
    <div class="overlay" id="overlay-password">
        <div class="popup">
            <div onclick="CloseModalP()" class="CloseIcon"><i class="fa fa-times" aria-hidden="true"></i></div>
            <div class="section-header">
                <h1 class="display-1 section-heading">Nuova Password</h1>
                <div class="underline"></div>
            </div>
            <section class="form-section">
                <div class="form">
                    <form id="form" action="profilo/change_password.php" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="password">
                                <h2>Password</h2>
                            </label>
                            <input type="password" id="password" class="form-control" placeholder="Password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="re_password">
                                <h2>Ripeti Password</h2>
                            </label>
                            <input type="password" id="re_password" class="form-control" placeholder="Confirm password" name="re_password" data-rule-equalTo="#password">
                        </div>
                        <button id="submit" type="submit" class="submit-btn btn btn-info btn-lg" name="create">Invia</button>
                    </form>
                </div>
            </section>
        </div>
    </div>

    <!-- popup che appare alla pressione del bottone,per cambiare foto profilo-->
    <div class="overlay" id="overlay-foto">
        <div class="popup">
            <div onclick="CloseModalF()" class="CloseIcon"><i class="fa fa-times" aria-hidden="true"></i></div>
            <div class="section-header">
                <h1 class="display-1 section-heading">Immagine di profilo</h1>
                <div class="underline"></div>
            </div>
            <section class="form-section">
                <div class="form">
                    <form id="form" action="profilo/upload_pp.php" method="post" enctype="multipart/form-data">
                        <label for="formFile">
                            <h2>Nuova immagine di profilo</h2>
                        </label>
                        <input class="form-control" type="file" id="formFile" name="profileImage" accept="image/x-png,image/jpeg">
                        <button id="submit" type="submit" class="submit-btn btn btn-info btn-lg" name="foto">Invia</button>
                    </form>
                </div>
            </section>
        </div>
    </div>

    <!--card contenenti le informazioni dell'account-->
    <div class="container" id="groups">
        <div class="section-header">
            <h1 class="display-1 section-heading">Account</h1>
            <div class="underline"></div>
        </div>
        <div class="section-account">
            <div class="card mb-3" data-tilt data-tilt-max="5" data-tilt-speed="100">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="profilo/images/<?php echo $profile_image; ?>" class="img-fluid rounded-start" alt="immagine di profilo">
                        <button type="button" class="btn btn-lg btn-labeled btn-success prof" onclick="OpenModalF()">Immagine di profilo <span class="btn-label"><i class="fa fa-pencil"></i></span></button>
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h1 class="card-title"><b>Username:</b></h1>
                            <p class="card-text large"><?php echo $session_user ?></p>
                        </div>
                        <div class="card-body">
                            <h1 class="card-title"><b>Email:</b></h1>
                            <p class="card-text large"><?php echo $email ?></p>
                        </div>
                        <div class="card-body">
                            <h1 class="card-title"><b>Password:</b></h1>
                            <button type="button" class="btn btn-lg btn-labeled btn-success" onclick="OpenModalP()">Cambia password <span class="btn-label"><i class="fa fa-pencil"></i></span></button>
                        </div>
                        <div class="card-body">
                            <h1 class="card-title"><b>Ruolo:</b></h1>
                            <p class="card-text"><?php echo $session_role ?></p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- bottone che gestisce l' eliminazione dell'account e del logout-->
    <div class="btn-group dropup">
        <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <i class=" fa fa-sign-out fa-2x"></i>
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item item-1" href="#" onclick="OpenModalX()"><b>Logout</b></a></li>
            <li><a class="dropdown-item item-2" href="#" onclick="OpenModalD()"><b>Cancella Account</b></a></li>
        </ul>
    </div>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <!--animazione per le card con il movimento del mouse-->
    <script type="text/javascript">
        (function() {
            if (window.innerWidth > 600) {
                var theScript = document.createElement('script');
                theScript.type = 'text/javascript';
                theScript.src = 'javascript/vanilla-tilt.min.js';

                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(theScript, s);
            }
        })();
    </script>
</body>

</html>