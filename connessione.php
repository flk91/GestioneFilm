<?php
//definiamo le costanti per la connessione al nostro db
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'film_attori_recita');

//attivo supporto alle sessioni
session_start();

try {
    //data source name (stringa di connessione al DB)
    //host=[nome o ip host mysql]
    //dbname=[nome del database]
    //charset=utf8 //serve a mostrare i caratteri accentati e speciali correttamente
    $dsn = sprintf("mysql:host=%s;dbname=%s;charset=utf8", DB_HOST, DB_NAME);

    //echo $dsn;

    /** @var PDO */
    $dbconn = new PDO($dsn, DB_USER, DB_PASSWORD);

    //attiviamo la visualizzazione dei messaggi di errori
    $dbconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $exception) {
    die("<h1>Impossibile stabilire una connessione con il database</h1>");
} catch (Exception $exception) {
    die("<h1>Qualcosa è andato storto</h1>");
}