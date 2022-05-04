<?php
require_once('../php/database.php');

//viene controllato se è stato passato il codice di recupero nell'indirizzo della pagaina
if (isset($_GET['code'])) {
    $code_ws  = $_GET['code'];

    //query per ottenere username e codice di recupero delle richieste fatte
    $query = "
        SELECT username,code
        FROM password_change_request
    ";

    $check = $pdo->prepare($query);
    $check->execute();

    $requestAll = $check->fetchAll(PDO::FETCH_ASSOC);

    //ciclo per controllare se esiste una richiesta con questo codice e se esiste controlla se rientra nel tempo limite di 30 minuti dall'invio
    $exist = false;
    foreach ($requestAll as $request) {
        //controlla se il codice della richiesta i-esima non è null
        if (!is_null($request['code'])) {
            //controlla se il codice della richiesta i-esima è uguale al codice del link usato dall'utente,in caso affermativo significa che la richiesta corrente esiste
            if (password_verify($code_ws, $request['code'])) {
                $user = $request['username'];
                //viene calcolata la differenza di tempo dall'invio della richiesta al tempo corrente.
                $query = "SELECT TIMESTAMPDIFF(MINUTE, time, NOW()) as differenza FROM password_change_request";
                $check = $pdo->prepare($query);
                $check->execute();
                $diff = $check->fetch(PDO::FETCH_ASSOC);
                //se la richiesta è stata fatta da più di 30 minuti viene negato l'accesso alla pagina
                if ($diff['differenza'] > 30) {
                    header("location: time_limit.html");
                    exit;
                }
                //richiesta esiste e soddisfa le richieste temporali
                $exist = true;
            }
        }
    }

    //se non è stata trovata nessuna richiesta o viola il timer,l'accesso è negato
    if (!$exist) {
        header("location: Riservato.html");
        exit;
    }
} else {
    header("location: Riservato.html");
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
    <link rel="stylesheet" href="../css/styleform.css" type="text/css">

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
    <script type="text/javascript" src="../javascript/change_password.js"></script>
    <script type="text/javascript" src="../javascript/transizione_tra_pagine.js"></script>
</head>

<body>
    <!-- navbar fissa sopra la pagina con bootstrap-->
    <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.html#account">
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
                        <a id="navAccount" class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a id="navAccount" class="nav-link" href="register.php">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- form in cui viene chiesta la nuova password per il proprio account,apply_change_password.php farà alcuni controlli e in caso di successo avviserà l'utente-->
    <div class="container">
        <div class="section-header">
            <h1 class="display-1 section-heading">Inserisci nuova password</h1>
            <div class="underline"></div>
        </div>
        <section class="forgot_password">
            <div class="form">
                <form id="form" action="../php/apply_change_password.php" method="post" enctype="multipart/form-data">
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
                        <input type="password" id="re_password" class="form-control" placeholder="Confirm password" name="re_password" data-rule-equalTo="#password" data-msg-equalTo="Inserisci la stessa password">
                    </div>
                    <input type="hidden" id="username" name="username" value="<?php echo $user ?>" />
                    <button id="submit" type="submit" class="submit-btn btn btn-info btn-lg" name="change">Invia</button>
                </form>
            </div>
        </section>
    </div>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>

</html>