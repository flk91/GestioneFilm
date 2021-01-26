<?php
require_once 'connessione.php';

$query="SELECT * FROM attori ORDER BY cognome, nome";
$comando=$dbconn->prepare($query);
$esegui = $comando->execute();

$dataset = $comando->fetchAll(PDO::FETCH_ASSOC);

//var_dump($dataset);
header("Content-type: text/json");
echo(json_encode($dataset));
exit();