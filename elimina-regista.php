<?php
require_once 'connessione.php';
if (!isset($_GET['idr'])) {
    die("Codice regista non fornito in input");
}
$idr = intval($_GET['idr']);
$eliminato = false;
if ($_POST && $_POST['conferma']) {
    $query = "DELETE FROM registi WHERE idr=$idr";
    $comando = $dbconn->prepare($query);
    $esegui = $comando->execute();
    $eliminato = true;
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Elimina film</title>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <div class="contenitore">
        <h1>Eliminazione film</h1>

        <?php if ($eliminato == false) { ?>
            <form action="" method="post">
                <p>Stai per eliminare il regista con codice <?php echo $_GET['idr']; ?>. Confermi?</p>

                <p><button name="conferma" value="1" type="submit">Conferma eliminazione</button></p>
            </form>
        <?php } else { ?>
            <h2 style="color: red">Film eliminato correttamente.</h2>
        <?php } ?>
    </div>
</body>

</html>