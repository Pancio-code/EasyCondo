<?php
session_start();
//elimina error alert eventualmente settati precedentemente
unset($_SESSION['errors-register']);
unset($_SESSION['errors-forgot']);
//in caso di login già fatto nella sessione viene saltato nuovamente il processo,se si vuole ripetere il login basta fare logout dall'account
if (isset($_SESSION['session_id'])) {
  $session_role =  htmlspecialchars($_SESSION['session_role'], ENT_QUOTES, 'UTF-8');
  if ($session_role == "Amministratore") {
    header('Location: ../php/amministratore.php');
  } else {
    header('Location: ../php/condomino.php');
  }
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
  <script type="text/javascript" src="../javascript/Login.js"></script>
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
            <a id="navAccount" class="nav-link" href="register.php">Register</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!--form che si occupa di manadare al file login.php l'username e password inserite nei campi appositi dall'utente.-->
  <div class="container">
    <div class="section-header">
      <h1 class="display-1 section-heading">Login</h1>
      <div class="underline"></div>
    </div>
    <!--nome classe di section register per usare stesso css per entrambe le pagine register e login -->
    <section class="register">
      <div class="form">
        <form id="form" action="../php/login.php" method="post" enctype="multipart/form-data" name="myForm">
          <div class="form-group">
            <label for="username">
              <h2>Username</h2>
            </label>
            <input type="text" id="username" class="form-control" placeholder="Username" name="username" maxlength="20" required autofocus>
          </div>
          <div class="form-group">
            <label for="password">
              <h2>Password</h2>
            </label>
            <input type="password" id="password" class="form-control" placeholder="Password" name="password" required>
          </div>
          <button id="submit" type="submit" class="submit-btn btn btn-info btn-lg" name="login">Accedi</button>
        </form>
        <!--In caso di username o password errata vengono mostrati dei messaggi d'errore.-->
        <?php if (isset($_SESSION['errors'])) : ?>
          <div class="form-errors">
            <?php foreach ($_SESSION['errors'] as $error) : ?>
              <p><?php echo $error ?></p>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
        <!--In caso di password dimenticata.-->
        <a href="forgot_password.php">Hai dimenticato la Password?</a>
      </div>
    </section>
  </div>

  <!-- Option 1: Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>