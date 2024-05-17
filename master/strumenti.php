<?php
/*
 * PAGINA DI PANORAMICA PERSONAGGIO.
 */
//Configurazione personalizzata
require_once "../scripts/config.php";
//Funzioni utilità
require_once "../scripts/funzioni_comuni.php";
//Avvio sessione
session_start();
//Controllo sessione ed eventuale ripristino con cookie "ricordami"
if (!check_visualizza_pagina()) {
    header("Location: ../index.php");
    die('');
}
//Controllo le autorizzazioni
check_is_master();
//Connessione al DB
$conn = connetti();
//Caricamento della classe Personaggio
require_once ("../classi/Master.php");
try {
    $personaggi = Master::getPersonaggi($conn);
} catch (Exception $e) {
    $personaggi = NULL;
}
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Strumenti per Master</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Strumenti per Master">
        <meta name="author" content="Mario Villani">
        <meta charset="utf-8">
        <link rel="stylesheet" href="../libs-frontend/bootstrap/css/bootstrap.min.css" media="screen" />
        <link rel="stylesheet" href="../libs-frontend/bootstrap-select/css/bootstrap-select.min.css" />
        <link href="../libs-frontend/jquery.pnotify.default.css" media="all" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="../css/comune.css" />
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
        <script src="../assets/js/html5shiv.js"></script>
        <![endif]-->
    </head>
    <body>
        <div id="wrap">
            <?php
            require "../componenti/toolbar_master.php";
            ?>
            <div class="container main-container text-center">
                    <h1>Stampa tutte le schede</h1>
                    <h3>Otterrai un file ZIP contenente tutte le schede dei personaggi sulla piattaforma.</h3>
                    <a href="../printSheet/printAllSheets.php"><button style="margin-bottom:10px;" class="btn btn-primary btn-large">Stampa tutte le schede</button></a>
                    <?php
                        require "../componenti/modale_account.php";
                        require "../componenti/modale_nuovo_pg.php";
                    ?>
            </div><div id="push"></div>
        </div>
        <?php require "../componenti/footer.php"; ?>
        <script type="text/javascript" src="../libs-frontend/jquery.js"></script>
        <script type="text/javascript" src="../libs-frontend/bootstrap/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="../libs-frontend/bootstrap-select/js/bootstrap-select.min.js"></script>
        <script type="text/javascript" src="../libs-frontend/jquery.pnotify.min.js"></script>
        <script type="text/javascript" src="../js/account.js"></script>
        <script type="text/javascript" src="../js/personaggi.js"></script>
    </body>
</html>
