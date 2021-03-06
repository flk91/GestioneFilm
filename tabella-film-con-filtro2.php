<?php
require_once 'include/connessione.php';

$anno_inizio = 0;
$anno_fine = 0;

if (isset($_GET['anno_inizio'])) {
    $anno_inizio = intval($_GET['anno_inizio']);
}
if (isset($_GET['anno_fine'])) {
    $anno_fine = intval($_GET['anno_fine']);
}

$filtro = "";

if ($anno_inizio > 0 && $anno_fine > $anno_inizio) {
    $filtro = "anno BETWEEN $anno_inizio AND $anno_fine";
} elseif ($anno_inizio>0) {
    $filtro = "anno > $anno_inizio";
} elseif ($anno_fine > 0) {
    $filtro = "anno < $anno_fine";
}

//prima parte della query
$query = "SELECT f.titolo, f.anno, f.durata, r.cognome, r.nome, f.idr AS f_idr
    FROM film AS f
    LEFT JOIN registi AS r ON f.idr=r.idr";
//se il filtro non è vuoto aggiungo la where
if(!empty($filtro)) {
    //ricorda: .= significa "appendi"
    $query .= " WHERE $filtro";
}
//aggiungo ultima parte della query con order by
$query .= " ORDER BY anno ASC";

$comando = $dbconn->prepare($query);
$esegui = $comando->execute();
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Elenco di film (tabella)</title>
</head>

<body>
    <h1>La nostra videoteca</h1>

    <form action="" method="get">
        <fieldset>
            <legend>Filtri disponibili</legend>
            <p>
                <label for="anno_inizio">Anno da: </label>
                <input type="number" name="anno_inizio" id="anno_inizio" /> --
                <label for="anno_fine">Anno a: </label>
                <input type="number" name="anno_fine" id="anno_fine" />
            </p>
            <p><button type="submit">Applica filtro</button></p>
        </fieldset>
    </form>
    <br />
    <?php
    if ($esegui == true) {
        //apertura della tabella, thead e riga di intestazione
        echo "
        <table border='1' cellpadding='4'>
            <thead>
                <tr>
                    <th>Titolo</th>
                    <th>Anno</th>
                    <th>Durata</th>
                    <th>Regista</th>
                </tr>
            </thead>
            <tbody>
        ";

        while ($riga = $comando->fetch()) {
            $titolo = $riga['titolo'];
            $anno = $riga['anno'];
            $durata = $riga['durata'];
            $nome = $riga['nome'];
            $cognome = $riga['cognome'];

            echo "
            <tr>
                <td>$titolo</td>
                <td>$anno</td>
                <td>$durata</td>
                <td>$nome $cognome</td>
            </tr>
            ";
        }

        //chiusura della tabella
        echo "</tbody></table>";
    }
    ?>

</body>

</html>