<?php
require "connessione.php";
//se il form è stato inviato
if ($_POST) {
    //leggiamo in input i dati in variabili semplici
    $titolo = $_POST['titolo'];
    $anno = intval($_POST['anno']);
    $durata = intval($_POST['durata']);
    $incasso = floatval($_POST['incasso']);
    $idr = $_POST['idr'];

    // qua bisogna eseguire la convalida dei dati
    // prima della query.
    $dati_validi = true;
    $errori = "";

    if (strlen($titolo) == 0) {
        $dati_validi = false;
        $errori .= "Inserire il titolo<br />";
    }

    if(strlen($idr)>0) {
        $idr="'". addslashes($idr) . "'";
    } else {
        $idr='NULL';
    }


    if ($dati_validi == true) {
        // dobbiamo sostituire ' con \'
        //$titolo = str_replace("'", "\'", $titolo);
        //$titolo = str_replace('\'', '\\\'', $titolo);

        $titolo = addslashes($titolo);

        //ora eseguiamo la query sul database
        $query = "INSERT INTO film 
        (titolo, anno, durata, idr) 
        VALUES ('$titolo', $anno, $durata, $idr)";
        echo $query;
        $comando = $dbconn->prepare($query);
        $esegui = $comando->execute();

        if ($esegui == true) {
            //se la chiave primaria è una numerazione automatica
            //ottengo il valore dell'ID generato
            $id_inserito = $dbconn->lastInsertId();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inserimento film</title>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="style.css" />
</head>

<body>

    <div class="contenitore">
        <h1>Inserimento film</h1>
        <p><a href="tabella-film.php">Torna all'elenco film</a></p>
        <form action="" method="post">
            <?php

            if (isset($esegui) && $esegui == true) {
                echo "<p class='messaggio successo'>Il film è stato inserito con ID $id_inserito</p>";
            }

            if (!empty($errori)) {
                echo "<p class='messaggio errore'>$errori</p>";
            }
            ?>

            <table class="tabella-form">

                <tr>
                    <td><label for="titolo">Titolo</label></td>
                    <td><input type="text" name="titolo" id="titolo" maxlength="50" /></td>
                </tr>
                <tr>
                    <td><label for="anno">Anno</label></td>
                    <td><input type="number" name="anno" id="anno" min="1800" /></td>
                </tr>
                <tr>
                    <td><label for="durata">Durata (in minuti)</label></td>
                    <td><input type="number" name="durata" id="durata" min="0" /></td>
                </tr>
                <tr>
                    <td><label for="incasso">Incasso</label></td>
                    <td><input type="number" name="incasso" id="incasso" min="0" /></td>
                </tr>
                <tr>
                    <td><label for="idr">Regista</label><br /></td>
                    <td>
                        <!--<input type="text" name="idr" id="idr" maxlength="6" />-->

                        <select name="idr" id="idr">
                            <option value="">- Non selezionato -</option>
                            <?php
                            $query_registi = "SELECT idr, cognome, nome FROM registi ORDER BY cognome, nome";
                            $comando_registi = $dbconn->prepare($query_registi);
                            $esegui_registi = $comando_registi->execute();
                            if ($esegui_registi == true) {
                                while ($riga = $comando_registi->fetch()) {
                                    $r_idr = $riga['idr'];
                                    $r_cogn = $riga['cognome'];
                                    $r_nome = $riga['nome'];
                                    echo "<option value='$r_idr'>$r_cogn, $r_nome</option>";
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td><button type="submit">Salva</button></td>
                </tr>
            </table>

        </form>
    </div>
</body>

</html>