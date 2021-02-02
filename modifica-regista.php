<?php
require_once 'include/connessione.php';

require_once 'include/funzioni.php';

if (isset($_GET['idr'])) {
    $idr = $_GET['idr'];
} else {
    die("Codice regista non trovato");
}

/**
 * Messaggi di errore restituiti in fase di convalida.
 */
$errori = "";

//Se l'utente ha inviato dati, dopo la convalida devo eseguire l'aggiornamento
if ($_POST) {
    $idr1 = $_POST['idr'];
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $nazionalita = $_POST['nazionalita'];
    $data_n = $_POST['data_n'];

    $dati_validi = true;

    if (strlen($idr1) != 6) {
        $dati_validi = false;
        $errori .= "Inserire il codice del regista<br />";
    }

    //se il codice del regista è stato cambiato e se il nuovo codice esiste già
    if ($idr1 != $idr && idr_esiste($idr1)) {
        $dati_validi=false;
        $errori .= "Il regista $idr1 esiste già. Inserire un codice non utilizzato<br />";
    }

    if (strlen($nome) == 0) {
        $dati_validi = false;
        $errori .= "Inserire il nome<br />";
    }

    if (strlen($cognome) == 0) {
        $dati_validi = false;
        $errori .= "Inserire il cognome<br />";
    }

    if (strlen($nazionalita) == 0) {
        $dati_validi = false;
        $errori .= "Inserire la nazionalità<br />";
    }

    if ($dati_validi == true) {
        $query = "UPDATE registi
        SET
          idr='$idr1',
          cognome='$cognome', 
          nome='$nome',
          nazione='$nazionalita',
          data_n = '$data_n'
        WHERE idr='$idr'";
        $comando = $dbconn->prepare($query);
        $esegui = $comando->execute();
        if ($esegui) {
            //se ho eseguito la query modifico la variabile idr con il nuovo valore
            //in modo tale che la select alla riga 57 vada a leggere il codice corretto.
            $idr = $idr1;
        }
    }
}

$query_reg = "SELECT * FROM registi WHERE idr='$idr'";
$comando_reg = $dbconn->prepare($query_reg);
$esegui_reg = $comando_reg->execute();
if ($esegui_reg) {
    $riga = $comando_reg->fetch(PDO::FETCH_ASSOC);
} else {
    die("Codice regista non trovato");
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Modifica regista</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
</head>

<body>
    <h1>Modifica regista</h1>

    <p><a href="elenco-registi.php">Torna all'elenco dei registi</a></p>

    <p style="color:red"><?php echo $errori; ?></p>

    <?php if (isset($esegui) == true && $esegui == true) {
        echo "<p style='color:green'>Modifica andata a buon fine</p>";
    } ?>

    <form action="" method="post">
        <table class="tabella-form">
            <tr>
                <td><label for="idr">Codice</label></td>
                <td><input type="text" name="idr" id="idr" maxlength="6" value="<?php echo $riga['idr']; ?>" /> <button type="button" id="cmd_genera_idr">Genera</button> </td>
            </tr>
            <tr>
                <td><label for="cognome">Cognome</label></td>
                <td><input type="text" name="cognome" id="cognome" value="<?php echo $riga['cognome']; ?>" /></td>
            </tr>
            <tr>
                <td><label for="nome">Nome</label></td>
                <td><input type="text" name="nome" id="nome" value="<?php echo $riga['nome']; ?>" /></td>
            </tr>
            <tr>
                <td><label for="nazionalita">Nazionalità</label></td>
                <td><input type="text" name="nazionalita" id="nazionalita" value="<?php echo $riga['nazione']; ?>" maxlength="3" /></td>
            </tr>
            <tr>
                <td><label for="data_n">Data di nascita</label></td>
                <td><input type="date" name="data_n" id="data_n" value="<?php echo $riga['data_n']; ?>" /></td>
            </tr>
            <tr>
                <td></td>
                <td><button type="submit">Conferma</button></td>
            </tr>
        </table>
    </form>

    <script src="./js/genera_idr.js"></script>
</body>

</html>