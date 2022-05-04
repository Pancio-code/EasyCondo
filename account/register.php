<?php
session_start();
//elimina error alert eventualmente settati precedentemente
unset($_SESSION['errors']);
unset($_SESSION['errors-forgot']);
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
  <script type="text/javascript" src="../javascript/register.js"></script>
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
        </ul>
      </div>
    </div>
  </nav>

  <!--form che si occupa di registrare un nuovo utente, sono richiesti:username,email,password e il ruolo(condomino/amministratore).-->
  <div class="container">
    <div class="section-header">
      <h1 class="display-1 section-heading">Register</h1>
      <div class="underline"></div>
    </div>
    <section class="register">
      <div class="form">
        <!--In caso di username già esistente viene mostrato un messaggio d'errore.-->
        <?php if (isset($_SESSION['errors-register'])) : ?>
          <div class="form-errors">
            <?php foreach ($_SESSION['errors-register'] as $error) : ?>
              <p><?php echo $error ?></p>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
        <form id="form2" action="../php/register.php" method="post" enctype="multipart/form-data">
          <div class="form-group">
            <label for="username">
              <h2>Username</h2>
            </label>
            <input type="text" id="username" class="form-control" placeholder="Username" name="username" maxlength="20" required autofocus>
          </div>
          <div class="form-group">
            <label for="EmailLabel">
              <h2>Email</h2>
            </label>
            <input name="email" type="email" class="form-control" id="EmailLabel" placeholder="Inserisci Email" required>
          </div>
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
          <div class="form-group">
            <label for="floatingSelect">
              <h2>Tipo Utente</h2>
            </label>
            <select name="ruolo" class="form-select" id="floatingSelect" required>
              <option value="Amministratore">Amministratore</option>
              <option value="Condomino">Condomino</option>
            </select>
          </div>
          <button id="submit" type="submit" class="submit-btn btn btn-info btn-lg" name="register">Registrati</button>
        </form>
      </div>
    </section>
  </div>

  <!-- Option 1: Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>