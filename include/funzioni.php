<?php
/*

Questo file contiene le funzioni condivise tra più pagine.
Abituarsi a creare funzioni permette di ottenere i seguenti vantaggi:

 1. il codice sorgente risulta più leggibile.
    viene infatti separato il codice delle pagine web da quello che esegue le operazioni sul db.
    i nomi delle funzioni e i loro parametri aiutano a rendere più organizzato il codice.
 2. possibilità di riutilizzare la stessa funzione in più pagine web senza ripetere il codice.
 3. le variabili locali esistono solo nell'ambito della funzione in cui sono eseguite.
    E' quindi possibile usare gli stessi nomi di variabile in funzioni diverse senza correre 
    il rischio di sovrascrittura dei dati.
    Esempio: posso usare $query, $comando, $esegui in funzioni diverse che eseguono una sola query.

*/

require_once __DIR__ . '/connessione.php';

/**
 * Preso in input un codice regista, guarda se esiste già nel database
 * @param string $idr codice regista da verificare
 */
function idr_esiste($idr)
{
    //in PHP, per poter accedere alle variabili globali all'interno di una funzione,
    //devo "importarle" anteponendo al nome della variabile il prefisso `$global`
    global $dbconn;

    $idr = addslashes($idr); //pulizia
    $query = "SELECT COUNT(idr) FROM registi WHERE idr='$idr'";
    $comando = $dbconn->prepare($query);
    $esegui = $comando->execute();

    if ($esegui == true) {
        //la query restituisce una sola riga e una sola colonna.
        //con fetchColumn ottengo direttamente il suo valore.
        $risultato = $comando->fetchColumn();
        //viene restituito true se risultato > 0, false in caso contrario
        return $risultato > 0;
    } else {
        return false;
    }
}


/**
 * Associa un film ad un attore che vi recita all'interno
 * @param int $idf identificativo film
 * @param int $ida identificativo attore
 */
function associa_film_attore(int $idf, int $ida)
{
    global $dbconn;

    $query = "INSERT INTO recita (idF, idA) VALUES ($idf, $ida)";
    $comando = $dbconn->prepare($query);
    $esegui = $comando->execute();
    return $esegui;
}

/**
 * Ottiene l'elenco degli attori che recitano in un determinato film
 * @param int $idf Identificativo film
 * @return array righe della tabella film di film dell'attore
 */
function getAttoriFilm($idf)
{
    global $dbconn;
    $comando = $dbconn->prepare("SELECT * FROM attori a, recita r WHERE r.ida=a.ida AND r.idf=$idf");
    if ($comando->execute()) {
        $dati = $comando->fetchAll(PDO::FETCH_ASSOC);
        //var_dump($dati);
        return $dati;
    } else {
        //se la query non va a buon fine restituisco un array vuoto
        return [];
    }
}

/**
 * Restituisce l'elenco di tutti gli attori
 * @return array
 */
function getElencoAttori() {
    global $dbconn;

    $query = "SELECT * FROM attori ORDER BY cognome, nome";
    $comando=$dbconn->prepare($query);
    $esegui = $comando->execute();

    if($esegui==true) {
        return $comando->fetchAll(PDO::FETCH_ASSOC);
    } else {
        return [];
    }

}



function dissociaAttore($idf, $ida)
{
    global $dbconn;
    $comando = $dbconn->prepare("DELETE FROM recita WHERE idF=$idf AND idA=$ida");
    $esegui = $comando->execute();
    return $esegui;
}
