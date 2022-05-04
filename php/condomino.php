<?php
session_start();
require_once('database.php');
//verifica se l'utente ha effettuato il login
if (isset($_SESSION['session_id'])) {
    $session_user = htmlspecialchars($_SESSION['session_user'], ENT_QUOTES, 'UTF-8');
    $session_id = htmlspecialchars($_SESSION['session_id']);
    $session_role =  htmlspecialchars($_SESSION['session_role'], ENT_QUOTES, 'UTF-8');
    //verifica se l'utente è un ammministratore,nel caso lo rimanda alla sua dashboard
    if ($session_role == "Amministratore") {
        header("location: amministratore.php");
        exit;
    }
} else {
    header("location: ../account/Riservato.html");
    exit;
}
//array che conterrà i gruppi a cui è iscritto l'utente
$groups = array();

//query per recuperare i gruppi dell'utente
$query = "
    SELECT condominio
    FROM groups
    WHERE username = :username
";

$check = $pdo->prepare($query);
$check->bindParam(':username', $session_user, PDO::PARAM_STR);
$check->execute();

$result = $check->fetchAll(PDO::FETCH_ASSOC);

foreach ($result as $row) {
    //vengono recuperate le informazioni del conominio i-esimo
    $query = "
        SELECT name,citta,indirizzo,amministratore,codice
        FROM condominio
        WHERE name = :name
    ";

    $check = $pdo->prepare($query);
    $check->bindParam(':name', $row['condominio'], PDO::PARAM_STR);
    $check->execute();

    $info = $check->fetch(PDO::FETCH_ASSOC);

    //viene aggiunto all'array groups un array contenente le informazioni del condominio i-esimo
    array_push($groups, array($info['name'], $info['citta'], $info['indirizzo'], $info['amministratore'], $info['codice']));
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
    <link rel="stylesheet" href="../css/dashboard.css">

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
    <script type="text/javascript" src="../javascript/exit_group.js"></script>
    <script type="text/javascript" src="../javascript/reg-cond.js"></script>
    <script type="text/javascript" src="../javascript/enter.js"></script>
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
                        <a id="navAccount" class="nav-link active" href="../php/condomino.php">Gruppi</a>
                    </li>
                    <li class="nav-item">
                        <a id="navAccount" class="nav-link" href="../account_np.php">Account</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!--popup che permette all'utente di inserire un codice di un gruppo e quindi entrare in un condominio.La gestione del codice verrà fatta da enter.php-->
    <div class="overlay" id="overlay-enter">
        <div class="popup">
            <div onclick="CloseModalE()" class="CloseIcon"><i class="fa fa-times" aria-hidden="true"></i></div>
            <div class="section-header">
                <h1 class="display-1 section-heading">Entra in un condominio</h1>
                <div class="underline"></div>
            </div>
            <section class="condominio">
                <div class="form">
                    <form id="form1" action="enter.php" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="CodiceLabel">
                                <h2>Codice</h2>
                            </label>
                            <input name="codice" type="text" class="form-control" id="CodiceLabel" placeholder="Inserisci codice" required>
                        </div>
                        <button id="submit" type="submit" class="submit-btn btn btn-info btn-lg" name="create">Entra</button>
                    </form>
                </div>
            </section>
        </div>
    </div>

    <!--popup che gestisce l'uscita da un gruppo.Viene mostrato dopo la pressione del bottone exit-->
    <div class="overlay" id="overlay-exit">
        <div class="popup">
            <div onclick="CloseModalX()" class="CloseIcon"><i class="fa fa-times" aria-hidden="true"></i></div>
            <div class="section-header">
                <h1 class="display-1 section-heading">Esci dal condominio</h1>
                <div class="underline"></div>
            </div>
            <p class="lead">Sei sicuro di voler uscire dal condominio?</p>
            <div class="clearfix">
                <button type="button" onclick="CloseModalX()" class="cancelbtn btn">Annulla</button>
                <button type="button" id="" onclick="exit_group(this.id);" class="exitbtn btn">Esci</button>
            </div>
        </div>
    </div>

    <div class="container" id="groups">
        <div class="section-header">
            <!--se si clicca sul proprio username nella sezione superiore della pagina si potrà accedere alla pagina del proprio account(pensato per i telefoni,per i computer si può usare la navbar direttamente)-->
            <h1 id="name-link" onclick="javascript:location.href='../account_np.php'" class="display-1 section-heading"><?php echo $session_user ?></h1>
            <div class="underline"></div>
        </div>
        <script>
            (function() {
                //vengono recuperati i gruppi a cui è iscritto l'utente
                var groups = <?php echo json_encode($groups); ?>;
                var size = groups.length;
                //se l'utente non è iscritto a nessun gruppo
                if (size == 0) {
                    //viene creato un elemento div inizialmente vuoto
                    var newNode = document.createElement('div');
                    //aggiunta la classe nothing al div a cui sarà apllicato dello stile
                    newNode.className = 'nothing';
                    newNode.innerHTML = '<p class="lead">Non partecipi a nessun gruppo.clicca sul pulsante in basso a destra!</p>';
                    //appeso il div creato ad un altro div "groups"
                    document.getElementById('groups').appendChild(newNode);
                } else {
                    //si vuole creare un layout a due colonne per i grandi schermi,per fare ciò inseriamo finchè possiamo coppie di gruppi in due colonne all'interno del div row,mentre il elemento rimanente verrà inserito in una singola colonna.Negli schermi più piccoli grazie all'uso di row e cols di bootstrap i gruppi verranno mostrati come una singola colonna.
                    for (var i = 0; i + 1 < size; i += 2) {
                        //viene creato un elemento div inizialmente vuoto
                        var newNode = document.createElement('div');
                        //aggiunta la classe row all'elemento div per creare una riga avente le carattesristiche responsive di bootstrap
                        newNode.className = 'row';
                        //2 <div class="col-6"> in cui vengono inserite delle card di bootstrap,nel card-body verranno mostrati il titolo,città,via del condominio e il nome dell'amministratore.Inoltre ci sarà un bottone per entrare nel gruppo e un altro bottone per uscire visto che l'utente è un semplice condomino.
                        newNode.innerHTML = '<div class="col-6"><div class="card text-center align-middle" data-tilt data-tilt-max="5" data-tilt-speed="100"><div class="card-body"><h5 class="card-title"><b>' + groups[i][0] + '</b></h5><p class="card-text">' + groups[i][1] + ' ,' + groups[i][2] + '.</p><p class="card-text">Di: ' + groups[i][3] + '</p><a id="' + groups[i][0] + " " + groups[i][4] + '" href="#" class="btn btn-primary" onclick="enter_group(this.id)">Entra</a><button type="button" class="btn btn-outline-danger delete-icon" onclick="OpenModalX(\'' + groups[i][0] + '\')"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"></path><path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"></path></svg></button></div></div></div>' + '<div class="col-6"><div class="card text-center" data-tilt data-tilt-max="5" data-tilt-speed="100"><div class="card-body"><h5 class="card-title"><b>' + groups[i + 1][0] + '</b></h5><p class="card-text">' + groups[i + 1][1] + ' ,' + groups[i + 1][2] + '.</p><p class="card-text">Di: ' + groups[i + 1][3] + '</p><a id="' + groups[i + 1][0] + " " + groups[i + 1][4] + '" href="#" class="btn btn-primary" onclick="enter_group(this.id)">Entra</a><button type="button" class="btn btn-outline-danger delete-icon" onclick="OpenModalX(\'' + groups[i][0] + '\')"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"></path><path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"></path></svg></button></div></div></div>';
                        //Ogni div creato per la coppia i-esima di gruppi viene appesa ad un altro div "groups".
                        document.getElementById('groups').appendChild(newNode);
                    }
                    //elemento restante che verrà mostrato in una singola colonna
                    for (; i < size; i++) {
                        //viene creato un elemento div inizialmente vuoto
                        var newNode = document.createElement('div');
                        //aggiunta la classe row all'elemento div per creare una riga avente le carattesristiche responsive di bootstrap
                        newNode.className = 'row';
                        //un <div class="col-6"> in cui viene inserita una card di bootstrap,nel card-body verranno mostrati il titolo,città,via del condominio e il nome dell'amministratore.Inoltre ci sarà un bottone per entrare nel gruppo e un altro bottone per uscire visto che l'utente è un semplice condomino.
                        newNode.innerHTML = '<div class="col-6"><div class="card text-center" data-tilt data-tilt-max="5" data-tilt-speed="100"><div class="card-body"><h5 class="card-title"><b>' + groups[i][0] + '</b></h5><p class="card-text">' + groups[i][1] + ' ,' + groups[i][2] + '.</p><p class="card-text">Di: ' + groups[i][3] + '</p><a id="' + groups[i][0] + " " + groups[i][4] + '" href="#" class="btn btn-primary" onclick="enter_group(this.id)">Entra</a><button type="button" class="btn btn-outline-danger delete-icon" onclick="OpenModalX(\'' + groups[i][0] + '\')"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"></path><path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"></path></svg></button></div></div></div>';
                        //Ogni div creato per l'i-esimo gruppo viene appeso ad un altro div "groups".
                        document.getElementById('groups').appendChild(newNode);
                    }
                }
            })();
        </script>
    </div>

    <!--al click di questo bottone verrà mostrato il popup per entrare in un gruppo-->
    <div class="btn-group dropup">
        <button type="button" class="btn btn-secondary" aria-expanded="false" onclick="OpenModalE()">
            <i class="fa fa-plus"></i>
        </button>
    </div>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <!--animazione per le card con il movimento del mouse-->
    <script type="text/javascript" src="../javascript/vanilla-tilt.min.js"></script>
</body>

</html>