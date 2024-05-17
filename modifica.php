<?php /*
 * PAGINA DI PANORAMICA PERSONAGGIO.
 */
//Configurazione personalizzata
require_once "scripts/config.php";
//Funzioni utilità
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
} catch (exception $e) {
	errore($e -> getMessage());
}
?>
<!doctype html>
<html lang="it"><head>
        <title><?php echo "Modifica Informazioni " . $personaggio ->getNome(); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Gestione">
        <meta name="author" content="Mario Villani">
        <meta charset="utf-8">
        <link rel="stylesheet" href="libs-frontend/bootstrap/css/bootstrap.min.css" media="screen">
        <link rel="stylesheet" href="libs-frontend/jasny-bootstrap.min.css">
        <link href="libs-frontend/jquery.pnotify.default.css" media="all" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="css/comune.css">
        <style>
            #avatar {
                max-height: 200px;
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
            require "componenti/toolbar_utente.php";
            ?>
            <div id="container" class="container main-container text-center">
                <h2>Avatar di <?php echo $personaggio -> getNome(); ?></h2>
                <form id="form_immagine" method="post" enctype="multipart/form-data" action="scripts/personaggio/carica_avatar.php">
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="fileinput-new thumbnail" style="max-width: 200px; max-height: 200px;"><img id="avatar" src="avatars/<?php echo $personaggio -> getAvatar(); ?>">
                        </div>
                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px; line-height: 20px;"></div>
                        <div>
                            <span class="btn btn-file btn-primary"><span class="fileinput-new ">Seleziona avatar</span><span class="fileinput-exists">Cambia</span>
                                <input class="form-control" name="nuovo_avatar" accept="image/*" type="file">
                            </span>
                            <a href="#" class="btn fileinput-exists btn-default" data-dismiss="fileinput">Rimuovi</a>
                        </div>
                    </div>
                </form>
                <button class="btn btn-primary btn-lg" id="pulsante_avatar">Aggiorna Avatar</button>
                <hr>
                <h2>Modifica Background</h2>
                <div class="alert alert-info">
                    &lt;b&gt;tuotesto&lt;/b&gt; : evidenzia il testo in grassetto <br>
                    &lt;p&gt;contenuto&lt;/p&gt; : paragrafo <br>
                    &lt;br /&gt; alla fine del testo : a capo<br>
                </div>
                <div class="row">
                    <textarea id="background" class="form-control" placeholder="Scrivi qui il tuo background." rows="20" class="span11"><?php echo $personaggio -> getBackground(); ?></textarea>
                </div>
                <h2>Descrizione</h2>
                <div class="row">
                    <input class="form-control" id="descrizione" placeholder="Breve descrizione personaggio." value="<?php echo $personaggio -> getDescrizione(); ?>" type="text">
                </div>
                <button style="margin-top:10px;" class="btn btn-primary btn-lg" id="pulsante_salva">Salva</button>
                <hr>
                <?php
                /*
                 * Informazioni modificabili solo dal master
                 */
                if ($_SESSION['master'] == 1) {
                    ?>
                    <h1>Sezione per Master</h1>
                    <h2>Nome personaggio</h2>
                    <div class="row">
                        <input class="form-control" id="nome_pg" placeholder="Nome personaggio." value="<?php echo $personaggio -> getNome(); ?>" type="text">
                    </div>
                    <h2>Regno</h2>
                    <div class="row">
                        <input class="form-control" id="regno_pg" placeholder="Regno" value="<?php echo $personaggio -> getRegno(); ?>" type="text">
                    </div>
                    <h2>Razza</h2>
                    <div class="row">
                        <input class="form-control" id="razza_pg" placeholder="Razza" value="<?php echo $personaggio -> getRazza(); ?>" type="text">
                    </div>
                    <h2>Punti</h2>
                    <div class="row">
                        <input class="form-control" id="punti_pg" placeholder="Punti" value="<?php echo $personaggio -> getPunti(); ?>" type="text">
                    </div>
                    <h2>Note del master</h2>
                    <div class="alert alert-info">
                        &lt;b&gt;tuotesto&lt;/b&gt; : evidenzia il testo in grassetto <br>
                        &lt;p&gt;contenuto&lt;/p&gt; : paragrafo <br>
                        &lt;br /&gt; alla fine del testo : a capo<br>
                    </div>
                    <div class="row">
                        <textarea class="form-control" id="nota" rows="5" class="span4"><?php echo $personaggio -> getNota(); ?></textarea>
                    </div>
                    <button style="margin-top:10px;margin-bottom:10px;" class="btn btn-primary btn-lg" id="pulsante_salva_master">Salva</button>
                    <?php } if ($_SESSION['master'] == 0)
                    require "componenti/modale_messaggio.php";
                    require "componenti/modale_account.php";
                ?>
                <span id="id_pg" pg="<?php echo $_GET['id'] ?>"></span></div>
            <div id="push"></div></div>
            <?php
                require "componenti/footer.php";
            ?>
        <script type="text/javascript" src="libs-frontend/jquery.js"></script>
        <script type="text/javascript" src="libs-frontend/bootstrap/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="libs-frontend/jasny-bootstrap.min.js"></script>
        <script type="text/javascript" src="libs-frontend/jquery.form.min.js"></script>
        <script type="text/javascript" src="libs-frontend/jquery.pnotify.min.js"></script>
        <script type="text/javascript" src="js/account.js"></script>
        <script type="text/javascript" src="js/modifica.js"></script>

</body></html>
