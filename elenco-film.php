<?php
require_once 'connessione.php';

//step 1: compongo la stringa della query
$query = "SELECT * FROM film";
//step 2: preparo il comando all'esecuzione
$comando = $dbconn->prepare($query);
//step 3: eseguo il comando
$esegui = $comando->execute();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Elenco film</title>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <h1>Elenco film</h1>

    <p><a href="inserimento-film.php">Inserisci film</a></p>

    <?php
    if($esegui == true) {
        while($riga = $comando->fetch()) {
            $titolo = $riga['titolo'];
            $anno = $riga['anno'];
            $durata = $riga['durata'];
            echo "<p>
                <b>Titolo:</b> $titolo<br />
                <b>Anno:</b> $anno<br />
                <b>Durata: </b> $durata
            </p><hr />";
        }
    } else {
        echo "Si è verificato un errore con la query";
    }
    ?>

</body>
</html>