<?php
require_once 'connessione.php';

$query = "SELECT f.idf, f.titolo, f.anno, f.durata, r.cognome, r.nome, f.idr AS f_idr
    FROM film AS f
    LEFT JOIN registi AS r ON f.idr=r.idr
    ORDER BY anno ASC";
$comando = $dbconn->prepare($query);
$esegui = $comando->execute();
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Elenco di film (tabella)</title>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <div class="contenitore">
        <h1>Film</h1>

        <p><a href="index.php">Home</a> | <a href="inserimento-film.php"><img src="icons/film_add.png" alt="Aggiungi film" /> Inserisci un film</a></p>

        <?php
        if ($esegui == true) {
            //apertura della tabella, thead e riga di intestazione
            echo "
        <table class='tabella-dati'>
            <thead>
                <tr>
                    <th>Titolo</th>
                    <th>Anno</th>
                    <th>Durata</th>
                    <th>Regista</th>
                    <th colspan='2'>Operazioni</th>
                </tr>
            </thead>
            <tbody>
        ";

            while ($riga = $comando->fetch()) {
                $idf = $riga['idf'];
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
                <td style='text-align:center'> 
                    <a href='modifica-film.php?idf=$idf'><img src='icons/film_edit.png' alt='Modifica film' title='Modifica film' /></a>
                </td>
                <td style='text-align:center'>
                    <a href='elimina-film.php?idf=$idf'><img src='icons/film_delete.png' alt='Elimina film' title='Elimina film' /></a> 
                </td>
            </tr>
            ";
            }

            //chiusura della tabella
            echo "</tbody></table>";
        }
        ?>
    </div>
</body>

</html>