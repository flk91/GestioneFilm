<?php
require_once 'connessione.php';

$query = "SELECT * FROM attori";
$comando = $dbconn->prepare($query);
$esegui = $comando->execute();

?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elenco attori</title>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <div class="contenitore">
        <h1>Elenco attori</h1>

        <p><a href="inserimento-attore.php">Inserisci un attore</a></p>

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
                        <td><?php echo date_format(date_create($riga['dataNas']), 'd/m/Y'); ?></td>
                        <td>
                            <a href="modifica-attore.php?idr=<?php echo $riga['ida']; ?>">Modifica</a>
                        </td>
                        <td>
                            <a href="elimina-attore.php?idr=<?php echo $riga['ida']; ?>">Elimina</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>

</html>