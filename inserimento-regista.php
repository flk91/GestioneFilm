<?php
require_once 'include/connessione.php';

/**
 * Messaggi di errore restituiti in fase di convalida.
 */
$errori = "";

if ($_POST) {
    $idr = $_POST['idr'];
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $nazionalita = $_POST['nazionalita'];
    $data_n = $_POST['data_n'];

    $dati_validi = true;

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
        $query = "INSERT INTO registi(idr, cognome, nome, nazione, data_n) 
        VALUES ('$idr', '$cognome', '$nome', '$nazionalita', '$data_n')";
        $comando = $dbconn->prepare($query);
        $esegui = $comando->execute();
    }
}

?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inserimento regista</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
</head>

<body>
    <h1>Inserimento regista</h1>

    <p><a href="elenco-registi.php">Torna all'elenco dei registi</a></p>

    <p style="color:red"><?php echo $errori; ?></p>

    <?php if (isset($esegui) == true && $esegui == true) {
        echo "<p style='color:green'>Inserimento andato a buon fine</p>";
    } ?>

    <form action="" method="post">
        <table>
            <tr>
                <td><label for="idr">Codice</label></td>
                <td><input type="text" name="idr" id="idr" maxlength="6" /> <button type="button" id="cmd_genera_idr">Genera</button> </td>
            </tr>
            <tr>
                <td><label for="cognome">Cognome</label></td>
                <td><input type="text" name="cognome" id="cognome" /></td>
            </tr>
            <tr>
                <td><label for="nome">Nome</label></td>
                <td><input type="text" name="nome" id="nome" /></td>
            </tr>
            <tr>
                <td><label for="nazionalita">Nazionalità</label></td>
                <td><input type="text" name="nazionalita" id="nazionalita" maxlength="3" /></td>
            </tr>
            <tr>
                <td><label for="data_n">Data di nascita</label></td>
                <td><input type="date" name="data_n" id="data_n" /></td>
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