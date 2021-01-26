<?php
require_once 'connessione.php';
if (!isset($_GET['idr'])) {
    die("Codice regista non fornito in input");
}
$idr = $_GET['idr'];

$blocca = false; //flag blocco eliminazione
$eliminato = false; //flag avvenuta eliminazione


//se è stata confermata l'eliminazione
if ($_POST && $_POST['conferma']) {
    //leggo il campo azione film (radio)
    $azione_film = $_POST['azione_film'];
    //scelgo cosa fare in base al valore
    switch ($azione_film) {
        case 'blocca':
            //se ci sono film, impedisco l'eliminazione
            $query_contafilm = "SELECT count(*) FROM film WHERE idr='$idr'";
            $comando_contafilm = $dbconn->prepare($query_contafilm);
            if ($comando_contafilm->execute() == true && $comando_contafilm->fetchColumn() > 0) {
                $blocca = true;
            }
            break;
        case 'dissocia':
            //eseguo una query per dissociare i film dal regista
            $query_dissocia = "UPDATE film SET idr=NULL WHERE idr='$idr'";
            $comando_dissocia = $dbconn->prepare($query_dissocia);
            $esegui_dissocia = $comando_dissocia->execute(true);
            break;
        case 'elimina':
            //elimino i film del regista
            $query_eliminaf = "DELETE FROM film WHERE idr='$idr'";
            $comando_eliminaf = $dbconn->prepare($query_dissocia);
            $esegui_eliminaf = $comando_eliminaf->execute(true);
            break;
    }

    //se l'eliminazione non è stata bloccata
    if ($blocca == false) {
        //elimino il regista
        $query = "DELETE FROM registi WHERE idr='$idr'";
        $comando = $dbconn->prepare($query);
        $esegui = $comando->execute();
        $eliminato = true;
    }
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
        <h1>Eliminazione regista</h1>

        <?php if ($eliminato == false) { ?>
            <form action="" method="post">
                <p>Stai per eliminare il regista con codice <?php echo $_GET['idr']; ?>.</p>

                <fieldset>
                    <legend>Cosa vuoi fare dei suoi film?</legend>

                    <p>
                        <label for="azione_film_blocca"><input type="radio" name="azione_film" id="azione_film_blocca" value="blocca" checked="checked" /> Non consentire eliminazione in presenza di film</label><br />
                        <label for="azione_film_dissocia"><input type="radio" name="azione_film" id="azione_film_dissocia" value="dissocia" /> Dissocia regista</label><br />
                        <label for="azione_film_elimina"><input type="radio" name="azione_film" id="azione_film_elimina" value="elimina" /> Elimina i film</label>
                    </p>
                </fieldset>

                <p><button name="conferma" value="1" type="submit">Conferma eliminazione</button></p>
            </form>
        <?php } else { ?>
            <h2 style="color: red">Film eliminato correttamente.</h2>
        <?php } ?>
    </div>
</body>

</html>