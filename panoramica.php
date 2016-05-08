<?php /*
 * PAGINA DI PANORAMICA PERSONAGGIO.
 */
//Configurazione personalizzata
require_once "scripts/config.php";
//Utility spesso usate
require_once "scripts/funzioni_comuni.php";
//Avvio sessione
session_start();
//Vedo se son stati passati gli ID
controlla_parametri();
//Controllo sessione ed eventuale ripristino con cookie "ricordami"
if (!check_visualizza_pagina()) {
	header("Location: index.php");
	die('');
}
//Controllo le autorizzazioni
check_autorizzazioni($_GET['id']);
//Connessione al DB
$conn = connetti();
//Caricamento della classe Personaggio
require_once ("classi/Personaggio.php");
$personaggio = new PG();
try {
	$personaggio -> prelevaDaID($_GET['id'], $conn);
} catch (Exception $e) {
	errore($e -> getMessage());
}
try {
	$lista_abilita = $personaggio -> prelevaAbilita($conn);
} catch (Exception $e) {
	$lista_abilita = array();
}
?>
<!doctype html>
<html lang="it"><head>
        <title><?php echo "Panoramica di " . $personaggio->getNome(); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Panoramica personaggio">
        <meta name="author" content="Mario Villani">
        <meta charset="utf-8">
        <link rel="stylesheet" href="libs-frontend/bootstrap/css/bootstrap.min.css" media="screen">
        <link rel="stylesheet" href="css/comune.css">
        <link href="libs-frontend/jquery.pnotify.default.css" media="all" rel="stylesheet" type="text/css">
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
        <script src="../assets/js/html5shiv.js"></script>
        <![endif]-->
        <style>
            #testo_qr img {
                display: inline !important;
            }
            #avatar {
                max-height: 255px;
            }
            .page-header {
                margin: -15px 0 30px;
            }
        </style>
    </head>
    <body>
        <div id="wrap">
            <?php
            require "componenti/toolbar_utente.php";
            ?>
            <div class="container main-container">
                <div class="row">
                    <div class="col-sm-4 col-md-4">
                        <div class="thumbnails">
                                <a class="thumbnail" href="#">
                                    <img id="avatar" class="img-responsive" src="avatars/<?php echo $personaggio -> getAvatar(); ?>">
                                </a>
                        </div>
                    </div>
                    <div class="col-sm-8 col-md-8">
                        <div class="page-header">
                            <h1 id="nome"><?php echo $personaggio->getNome(); ?></h1>
                            <p class="lead" id="desc"><?php echo $personaggio->getDescrizione(); ?></p>
                            <p class="lead"><span style="color:#004ACC">Regno : </span><span id="regno"><?php echo $personaggio->getRegno(); ?></span></p>
                            <p class="lead"><span style="color:#004ACC">Razza : </span><span id="regno"><?php echo $personaggio->getRazza(); ?></span></p>
                            <p class="lead"><span style="color:#004ACC">Punti totali : </span><span id="punti"><?php echo $personaggio -> getPunti(); ?></span></p>
                            <p class="lead"><span style="color:#004ACC">Punti spesi : </span><span id="punti_s"><?php echo $personaggio -> getPuntiSpesi(); ?></span></p>
                            <button id="pulsante_qr" class="btn btn-primary btn-lg" type="button">Codice QR</button>
                            <button id="pulsante_qr" onclick="window.open('printSheet/printPGSheet.php?id=<?php echo $personaggio->getID();?>','_blank');" class="btn btn-primary btn-lg" type="button">Stampa Scheda</button>
                        </div>
                    </div>
                </div>
                <div class="panel-group" id="accordion">
                <div class="panel panel-default" id="accordion1">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion1" href="#collapseOne">
                                Abilità
                            </a>
                            </h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse in">
                            <div class="panel-body">
                                <?php
                                if (empty($lista_abilita))
                                    echo "Nessuna abilità presente";
                                else {
                                    echo "<table class=\"table table-hover table-bordered \" id=\"abilita\"><thead><th>Abilità</th><th>Descrizione</th><th>Costo</th><th>Note</th></thead>";
                                    foreach ($lista_abilita as $abilita) {
                                        echo "<tr><td>" . $abilita['Nome'] . "</td><td>" . $abilita['Descrizione'] . "</td><td>" . $abilita['Costo'] . "</td><td>" .$abilita['Note'] . "</td></tr>";
                                    }
                                    echo "<br>";
                                }
                                ?>
                                </table>
                            </div>
                        </div>
                </div>
                <div class="panel panel-default" id="accordion2">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
                                Background
                            </a>
                            </h4>
                        </div>
                        <div id="collapseTwo" class="panel-collapse collapse in">
                            <div class="panel-body">
                                <?php echo $personaggio->getBackground(); ?>
                            </div>
                        </div>
                </div>
                <div class="panel panel-default" id="accordion3">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion3" href="#collapseThree">
                                Note del master
                            </a>
                            </h4>
                        </div>
                        <div id="collapseThree" class="panel-collapse collapse in">
                            <div class="panel-body" id="note">
                                <?php echo $personaggio->getNota(); ?>
                            </div>
                        </div>
                </div>
            </div>
                <!-- Modal -->
                <div id="modaleQR" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
            <div class="modal-content">
                    <div class="modal-header">
                        <h3 id="myModalLabel">Codice QR per <?php echo $personaggio -> getNome(); ?></h3>
                    </div>
                    <div class="modal-body text-center">
                        <div id="testo_qr" class="text-center"><!--Contenuto QR --></div>
                        <button style="margin-top:10px;" class="btn btn-primary" id="stampa_qr">Stampa QR</button>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Chiudi</button>
                    </div>
                </div>
                </div>
                </div>
                <?php
                if ($_SESSION['master'] == 0)
                    require "componenti/modale_messaggio.php";
                require "componenti/modale_account.php";
                ?>
            </div>
            <div id="push"></div></div>
            <?php
                require "componenti/footer.php";
            ?>
        <script type="text/javascript" src="libs-frontend/jquery.js"></script>
        <script type="text/javascript" src="libs-frontend/bootstrap/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="libs-frontend/qrcode.min.js"></script>
        <script type="text/javascript" src="libs-frontend/jquery.pnotify.min.js"></script>
        <script type="text/javascript" src="js/account.js"></script>
        <script type="text/javascript" src="js/panoramica.js"></script>
</body></html>
