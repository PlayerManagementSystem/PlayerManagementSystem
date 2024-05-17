<?php
require_once ("../classi/Master.php");
require_once "../scripts/funzioni_comuni.php";

require_once "../scripts/config.php";
session_start();
//Controllo sessione ed eventuale ripristino con cookie "ricordami"
if (!check_visualizza_pagina()) {
    header("Location: ../index.php");
    die('');
}
// Controllo le autorizzazioni
check_is_master();
// Connessione al DB
$conn = connetti();
try {
    $abilita = Master::getAbilita($conn);
} catch (Exception $e) {
    $abilita = NULL;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title> Gestione Abilità </title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Editor Abilità">
        <meta name="author" content="Tubelli Santolo">
        <meta charset="utf-8">
        <link rel="stylesheet" href="../libs-frontend/bootstrap/css/bootstrap.min.css" media="screen" />
        <link href="../libs-frontend/jquery.pnotify.default.css" media="all" rel="stylesheet" type="text/css" />
        <link href="../libs-frontend/bootstrap-editable/css/bootstrap-editable.css" rel="stylesheet" />
        <link rel="stylesheet" href="../css/comune.css" />
        <style>
            .table thead tr.info th {
                background-color: #d9edf7;
                cursor:pointer;
            }
        </style>
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
        <script src="../assets/js/html5shiv.js"></script>
        <![endif]-->
    </head>
    <body>
        <div id="wrap">
            <?php
            require_once "../componenti/toolbar_master.php";
            ?>
            <div class="container main-container text-center">
                <h1>Gestione Abilità</h1>
                <div class="alert alert-info">
                    Clicca sulle voci della tabella per modificarle oppure il tasto Cancella per eliminare una abilità.
                    Altrimenti, creane nuove cliccando sul tasto Aggiungi Abilità.
                </div>
                <div class="row" >
                    <button class="btn btn-primary btn-large" id ="aggiungi" href="#modale_nuova_ab" data-toggle="modal">
                        Aggiungi Abilità
                    </button>
                </div>
                <div class="row" style="margin-top:5px;">
                    <table class="table table-bordered table-hover tablesorter" id="abilityTable">
                        <thead>
                            <tr class="info">
                                <th><i class=" icon-resize-vertical"></i> Nome </th>
                                <th><i class=" icon-resize-vertical"></i> Descrizione </th>
                                <th><i class=" icon-resize-vertical"></i> Costo </th>
                                <th><i class=" icon-resize-vertical"></i> Note </th>
                                <th><i class=" icon-resize-vertical"></i> Cancella </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($abilita == NULL)
                                echo "<td>Nessuna abilità creata.</td>";
                            else
                                foreach ($abilita as $ab):
                                    ?>
                                    <tr data-pk="<?= $ab['id'] ?>">
                                        <td><span data-name="nome" data-pk="<?= $ab['id'] ?>" data-url="../scripts/abilita/modifica.php" data-type="text"> <?= $ab['nome'] ?> </span></td>
                                        <td><span data-name="descrizione" data-pk="<?= $ab['id'] ?>" data-url="../scripts/abilita/modifica.php" data-type="text"> <?= $ab['descrizione'] ?> </span></td>
                                        <td><span data-name="costo" data-pk="<?= $ab['id'] ?>" data-url="../scripts/abilita/modifica.php" data-type="text"> <?= $ab['costo'] ?> </span></td>
                                        <td><span data-name="note" data-pk="<?= $ab['id'] ?>" data-url="../scripts/abilita/modifica.php" data-type="text"> <?= $ab['note'] ?> </span></td>
                                        <td>
                                            <button class="btn btn-small btn-danger">
                                                x
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php require_once "../componenti/modale_account.php"; ?>
                <?php require_once "../componenti/modale_nuova_abilita.php"; ?>
            </div><div id="push"></div></div>
            <?php require "../componenti/footer.php"; ?>
        <script type="text/javascript" src="../libs-frontend/jquery.js"></script>
        <script type="text/javascript" src="../libs-frontend/bootstrap/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="../libs-frontend/jquery.pnotify.min.js"></script>
        <script type="text/javascript" src="../js/abilita.js"></script>
        <script type="text/javascript" src="../js/account.js"></script>
        <script src="../libs-frontend/bootstrap-editable/js/bootstrap-editable.min.js"></script>
        <script type="text/javascript" src="../libs-frontend/jquery.tablesorter.min.js"></script>
    </body>
</html>
