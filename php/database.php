<?php
$config = [
    'db_engine' => 'mysql',
    'db_host' => '127.0.0.1',
    'db_name' => 'serverdatabase',
    'db_user' => 'root',
    'db_password' => '',
];

//Alla riga 2 definiamo un array associativo contenente i dati per la connessione al database.

$db_config = $config['db_engine'] . ":host=" . $config['db_host'] . ";dbname=" . $config['db_name'];

try {
    $pdo = new PDO($db_config, $config['db_user'], $config['db_password'], [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
    ]);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    exit("Impossibile connettersi al Server database: " . $e->getMessage());
}

//Alla riga 14 proviamo ad effettuare una connessione al database. Se questa non va a buon fine viene lanciata un eccezione di tipo PDOException.
