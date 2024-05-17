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
        <title>Gestione Personaggi</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Gestione personaggi">
        <meta name="author" content="Mario Villani">
        <meta charset="utf-8">
        <link rel="stylesheet" href="../libs-frontend/bootstrap/css/bootstrap.min.css" media="screen" />
        <link rel="stylesheet" href="../libs-frontend/bootstrap-select/css/bootstrap-select.min.css" />
        <link href="../libs-frontend/jquery.pnotify.default.css" media="all" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="../css/comune.css" />
        <style>
            .thumbnail img {
                max-width: 150px;
                max-height: 200px;
                min-height: 150px;
                min-width: 150px;
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
            require "../componenti/toolbar_master.php";
            ?>
            <div class="container main-container text-center">
                <h1>Lista Personaggi</h1>
                <div class="thumbnails" id="lista_personaggi">
                    <?php if ($personaggi != NULL) foreach ($personaggi as $personaggio) { ?>
                                <div class="thumbnail clearfix div_personaggio">
                                    <img src="../avatars/thumbnails/thumb_<?php echo $personaggio['Avatar'] ?>" alt="Avatar di <?php echo $personaggio['Nome']; ?>" class="pull-left span2 clearfix" style='margin-right:10px'>
                                    <div class="caption" class="pull-left">
                                        <h3><a href="../panoramica.php?id=<?php echo $personaggio['ID']; ?>"><?php echo $personaggio['Nome']; ?></a></h3>
                                        <small class="pull-left"><b>Regno: </b><?php echo $personaggio['Regno']; ?></small><br />
                                        <small class="pull-left"><b>Proprietario: </b><?php echo $personaggio['Proprietario']; ?></small>
                                        <button class="btn btn-danger hidden pull-right">x</button>
                                    </div>
                                </div>
                        <?php } ?>
                </div>
                <button style="margin-bottom:10px;" class="btn btn-primary btn-large" href="#modale_nuovo_pg" data-toggle="modal">Crea Nuovo Personaggio</button>
                <?php
                try {
                    $costi = Master::conteggiaCosti($conn);
                ?>
                    <h1>Resoconto abilità </h1>
                    <table class="table table-bordered table-condensed table-hover">
                        <thead>
                        <th>Nome</th>
                        <th>Punti posseduti</th>
                        <th>Punti spesi</th>
                        </thead>
                        <?php
                        foreach ($costi as $lista) {
                            echo "<tr><td>" . $lista["nome"] . "</td><td>" . $lista['punti'] . "</td><td>" . $lista['somma_spesa'] . "</td></tr>";
                        }
                        echo "</table>";
                    } catch (Exception $e) {}
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
