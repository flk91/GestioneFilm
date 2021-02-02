<?php
require_once 'include/connessione.php';
require_once 'include/funzioni.php';

if (isset($_GET['idf'])) {
    $idf = $_GET['idf'];
}

if (isset($_POST['dissocia'])) {
    //quando il name di un elemento ha le parentesi quadre alla fine, vuol dire che viene inviato come un array.
    //ida[], in questo caso, è un vettore contenente gli ID degli attori a cui è stata messa la spunta.
    $ida_attori = $_POST['ida'];

    foreach ($ida_attori as $ida) {
        dissociaAttore($idf, $ida);
    }
}

if (isset($_POST['associa'])) {
}

?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Associa film a attori</title>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <div class="contenitore">
        <h1>Associa film a attori</h1>

        <p><a href="index.php">Home</a> | <a href="inserimento-film.php"><img src="icons/film_add.png" alt="Aggiungi film" /> Inserisci un film</a></p>

        <form action="" method="get">
            <label for="idf">Seleziona un film</label><br />

            <select name="idf" id="idf" onchange="form.submit();">
                <option value="">Selezionare...</option>
                <?php
                $query = "SELECT * FROM film ORDER BY titolo";
                $comando = $dbconn->prepare($query);
                if ($esegui = $comando->execute()) {
                    while ($riga = $comando->fetch()) {
                        $selezionato = '';
                        if ($idf == $riga['idF']) {
                            $selezionato = ' selected ';
                        }
                        echo "<option value='{$riga['idF']}' $selezionato >{$riga['titolo']}</option>";
                    }
                }
                ?>
            </select>
        </form>
        <p>&nbsp;</p>

        <?php
        if (isset($idf)) {
            $attori = getAttoriFilm($idf);
            ?>
            <form action="" method="post">
                <fieldset>
                    <legend>Attori nel cast</legend>
                    <?php if (count($attori) > 0) { ?>
                        <p>
                            <?php foreach ($attori as $attore) { ?>
                                <label><input type="checkbox" name="idA[]" value="<?php echo $attore['idA']; ?>"> <?php echo $attore['nome'] . ' ' . $attore['cognome']; ?></label><br />
                            <?php } ?>
                        </p>
                        <button type="submit" name="dissocia" value="1">Dissocia attori selezionati</button>
                    <?php } else { ?>
                        <p><strong>Non sono ancora presenti attori</strong></p>
                    <?php } ?>
                </fieldset>
            </form>

            <p>&nbsp;</p>

            <form action="" method="post">
                <fieldset>
                    <legend>Associa un attore a questo film</legend>
                    <table class="tabella-form">
                        <tr>
                            <td>Attore</td>
                            <td>
                                <select name="ida" id="ida_associa">
                                <?php
                                $attori = getElencoAttori();
                                foreach($attori as $attore) {
                                    $ida = $attore['ida'];
                                    $nome = $attore['nome'];
                                    $cognome = $attore['cognome'];
                                    echo "<option value='$ida'>$cognome, $nome</option>";
                                }
                                ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Ruolo</td>
                            <td><input type="text" name="ruolo" id="ruolo" /></td>
                        </tr>
                        <tr>
                            <td>Cachet</td>
                            <td><input type="number" name="cachet" id="cachet" /></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><button type="submit" name="associa" value="1">Associa</button></td>
                        </tr>
                    </table>
                </fieldset>
            </form>

        <?php } ?>
    </div>
</body>

</html>