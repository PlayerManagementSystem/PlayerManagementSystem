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
//Caricamento della classe Messaggio
require_once ("../classi/Messaggio.php");
$messaggio = new Messaggio();
try {
    $messaggio = $messaggio->getMessaggio($conn);
} catch (Exception $e) {
    errore($e->getMessage());
}
?>
<!doctype html>
<html lang="it"><head>
        <title>Messaggio per i Giocatori</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Messaggio per i giocatori">
        <meta name="author" content="Mario Villani">
        <meta charset="utf-8">
        <link rel="stylesheet" href="../libs-frontend/bootstrap/css/bootstrap.min.css" media="screen">
        <link href="../libs-frontend/jquery.pnotify.default.css" media="all" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="../css/comune.css">
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
                <h1>Messaggio per i giocatori</h1>
                <h3>Cosa vuoi dire ai giocatori?
                    <br>
                    <small>Il messaggio sarà visualizzabile nel pannello dei giocatori.E' persistente e non cambierà finché non lo modifichi.</small></h3>
                <textarea class="form-control span7" rows="4" id="corpo"><?php echo $messaggio; ?></textarea>
                <br />
                <label><input value="1" id="invia" type="checkbox"> Invia il messaggio a tutti gli utenti via mail</label><br />
                <button style="margin-bottom:10px;" class="btn btn-primary btn-lg" id="pulsante_messaggio">
                    Invia
                </button>
                <?php
                require "../componenti/modale_account.php";
                ?>
            </div><div id="push"></div></div>
            <?php require "../componenti/footer.php"; ?>
        <script type="text/javascript" src="../libs-frontend/jquery.js"></script>
        <script type="text/javascript" src="../libs-frontend/bootstrap/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="../libs-frontend/jquery.pnotify.min.js"></script>
        <script type="text/javascript" src="../js/account.js"></script>
        <script type="text/javascript" src="../js/messaggio.js"></script>

</body></html>
