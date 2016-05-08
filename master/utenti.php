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
    $utenti = Master::getUtenti($conn);
} catch (Exception $e) {

}
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Gestione Utenti</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Gestione utenti">
        <meta name="author" content="Mario Villani">
        <meta charset="utf-8">
        <link rel="stylesheet" href="../libs-frontend/bootstrap/css/bootstrap.min.css" media="screen" />
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
            <div id="container" class="container main-container text-center">
                <h1>Lista Utenti</h1>
                <div class="thumbnails" id="lista_personaggi">
                    <?php foreach ($utenti as $utente) { ?>
                            <div class="thumbnail clearfix div_utente">
                                <div class="caption" class="pull-left">
                                    <h4><a href="#"><?php echo $utente['username']; ?></a></h4>
                                    <button class="btn-small btn btn-danger hidden pull-right">x</button>
                                    <small class="pull-left"><b>ID : </b><?php echo $utente['id']; ?></small><br />
                                    <small class="pull-left"><b>Email : </b><?php echo $utente['email']; ?></small><br />
                                    <small class="pull-left"><b>Master : </b><?php echo $utente['master']; ?></small>
                                </div>
                            </div>
                        <?php
                    }
                    ?>
                </div>
                <button style="margin-bottom:10px;" class="btn btn-primary btn-large" href="#modale_nuovo_utente" data-toggle="modal">Crea Nuovo Utente</button>
                <?php
                require "../componenti/modale_account.php";
                require "../componenti/modale_nuovo_utente.php";
                ?>
            </div><div id="push"></div></div>
            <?php require "../componenti/footer.php"; ?>
        <script type="text/javascript" src="../libs-frontend/jquery.js"></script>
        <script type="text/javascript" src="../libs-frontend/bootstrap/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="../libs-frontend/jquery.pnotify.min.js"></script>
        <script type="text/javascript" src="../js/account.js"></script>
        <script type="text/javascript" src="../js/utenti.js"></script>

    </body>
</html>
