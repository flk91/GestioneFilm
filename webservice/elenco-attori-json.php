<?php

/* 
questo file restituisce l'elenco di tutti gli attori in formato JSON 
(JavaScript Object Notation).
Il formato JSON permette di codificare informazioni strutturate e di renderle 
leggibili a sistemi diversi tra loro. Un documento JSON può infatti essere letto 
e scritto da diversi linguaggi, tra cui PHP, JavaScript, ecc...
*/

require_once 'include/connessione.php';

$query = "SELECT * FROM attori ORDER BY cognome, nome";
$comando = $dbconn->prepare($query);
$esegui = $comando->execute();

//PDOStatement::fetchAll() restituisce tutte le righe prodotte dalla query
//in un unico array di array.
//PDO::FETCH_ASSOC serve a farci restituire come indici solo i nomi delle colonne
$dataset = $comando->fetchAll(PDO::FETCH_ASSOC);

//se voglio vedere in anteprima i dati nella variabile $dataset posso usare:
//var_dump($dataset);
//(va poi tolto, altrimenti non viene prodotto codice JSON valido).

//impostiamo il tipo di file restituito (application/json)
header("Content-type: application/json");

//la funzione json_encode prende in input una variabile (array, oggetto)
//e restituisce la rappresentazione in formato JSON del suo contenuto.
$json = json_encode($dataset);

//inviamo il codice json così generato in output
echo $json;

//terminiamo il caricamento della pagina (non è indispensabile, ma in alcuni casi potrebbe essere necessario)
exit();
