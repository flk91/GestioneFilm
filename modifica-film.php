<?php
require "connessione.php";

if (isset($_GET['idf'])) {
    $idf = intval($_GET['idf']);

    //se il form è stato inviato
    if ($_POST) {
        //leggiamo in input i dati in variabili semplici
        $titolo = $_POST['titolo'];
        $anno = intval($_POST['anno']);
        $durata = intval($_POST['durata']);
        $idr = $_POST['idr'];

        // qua bisognerebbe eseguire la convalida dei dati
        // prima della query.

        // dobbiamo sostituire ' con \'
        //$titolo = str_replace("'", "\'", $titolo);
        //$titolo = str_replace('\'', '\\\'', $titolo);

        $titolo = addslashes($titolo);

        //ora eseguiamo la query sul database
        $query = "UPDATE film SET
        titolo = '$titolo',
        anno = $anno,
        durata = $durata,
        idr = '$idr'
    WHERE idf=$idf";
        echo $query;
        $comando = $dbconn->prepare($query);
        $esegui = $comando->execute();

        if ($esegui == true) {
            //se la chiave primaria è una numerazione automatica
            //ottengo il valore dell'ID generato
            $id_inserito = $dbconn->lastInsertId();
        }
    }

    // lettura dei dati della riga (per popolare i campi del form)
    $query_get_film = "SELECT * FROM film WHERE idf = $idf";
    $comando_get_film = $dbconn->prepare($query_get_film);
    $esegui_get_film = $comando_get_film->execute();

    if ($esegui_get_film == true) {
        $dati_film = $comando_get_film->fetch();
        if ($dati_film == false) {
            die("Film non trovato");
        }
    }
} else {
    die("Codice film non fornito in input");
}



?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Modifica film</title>
</head>

<body>

    <?php
    if (isset($esegui)) {
        if ($esegui == true) {
            echo "<p style='color:green'>Il film è stato modificato correttamente</p>";
        } else {
            echo "<p style='color:red'>Si è verificato un errore</p>";
        }
    }
    ?>

    <form action="" method="post">
        <h1>Modifica film</h1>
        <p>
            <label for="titolo">Titolo</label><br />
            <input type="text" name="titolo" id="titolo" value="<?php echo htmlentities($dati_film['titolo']); ?>" maxlength="50" />
        </p>
        <p>
            <label for="anno">Anno</label><br />
            <input type="number" name="anno" id="anno" min="1800" value="<?php echo $dati_film['anno']; ?>" />
        </p>
        <p>
            <label for="durata">Durata (in minuti)</label><br />
            <input type="number" name="durata" id="durata" min="0" value="<?php echo $dati_film['durata']; ?>" />
        </p>
        <p>
            <label for="idr">ID Regista</label><br />
            <!--<input type="text" name="idr" id="idr" maxlength="6" />-->

            <select name="idr" id="idr">
                <?php
                $query_registi = "SELECT idr, cognome, nome FROM registi ORDER BY cognome, nome";
                $comando_registi = $dbconn->prepare($query_registi);
                $esegui_registi = $comando_registi->execute();
                if ($esegui_registi == true) {
                    while ($riga = $comando_registi->fetch()) {
                        $r_idr = $riga['idr'];
                        $r_cogn = $riga['cognome'];
                        $r_nome = $riga['nome'];

                        $selezionato = '';
                        if ($r_idr == $dati_film['idr']) {
                            $selezionato = ' selected ';
                        }

                        echo "<option value='$r_idr' $selezionato>$r_cogn, $r_nome</option>";
                    }
                }
                ?>
            </select>

        </p>
        <p>
            <button type="submit">Salva</button>
        </p>
    </form>
</body>

</html>