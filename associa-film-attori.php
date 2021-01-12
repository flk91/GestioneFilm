<?php
require_once 'connessione.php';

if(isset($_GET['idf'])) {
    $idf=$_GET['idf'];
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
                $comando=$dbconn->prepare($query);
                if($esegui=$comando->execute()) {
                    while($riga=$comando->fetch()) {
                        echo "<option value='{$riga['idf']}'>{$riga['titolo']}</option>";
                    }
                }
                ?>
            </select>            
        </form>

        <?php if(isset($idf)) { ?>
            <h4>Attori nel cast</h4>

        <?php } ?>
    </div>
</body>

</html>