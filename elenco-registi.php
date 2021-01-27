<?php
require_once 'include/connessione.php';

$query = "SELECT * FROM registi";
$comando = $dbconn->prepare($query);
$esegui = $comando->execute();

?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elenco registi</title>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <div class="contenitore">
        <h1>Elenco registi</h1>

        <p><a href="index.php">Torna alla home</a> | <a href="inserimento-regista.php">Inserisci un regista</a></p>

        <table class="tabella-dati">
            <thead>
                <tr>
                    <th>Cognome</th>
                    <th>Nome</th>
                    <th>Nazione</th>
                    <th>Data nascita</th>
                    <th colspan="2">Operazioni</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($riga = $comando->fetch()) { ?>
                    <tr>
                        <td><?php echo $riga['cognome']; ?></td>
                        <td><?php echo $riga['nome']; ?></td>
                        <td><?php echo $riga['nazione']; ?></td>
                        <td><?php echo date_format(date_create($riga['data_n']), 'd/m/Y'); ?></td>
                        <td>
                            <a href="modifica-regista.php?idr=<?php echo $riga['idr']; ?>">Modifica</a>
                        </td>
                        <td>
                            <a href="elimina-regista.php?idr=<?php echo $riga['idr']; ?>">Elimina</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>

</html>