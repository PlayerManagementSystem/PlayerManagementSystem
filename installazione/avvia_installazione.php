<?php
require_once "../scripts/config.php";
if (isset($_POST['install'])) {
    $messaggio = installa_pms();
}

function installa_pms() {
	global $nome_gioco;
    if (!isset($_POST['username']) || empty($_POST['username']))
        return "Username non inviato";
    if (!isset($_POST['email']) || empty($_POST['email']))
        return "Email non inviata";
    if (!isset($_POST['password']) || empty($_POST['password']))
        return "Password non inviata";
    if (strlen($_POST['password']) < 6) {
        return "La password deve essere di almeno 6 caratteri.";
    }
    require_once '../classi/Utente.php';
    $nuovo_utente = new Utente();

    $nuovo_utente->impostaAttivo(1);
    $nuovo_utente->impostaUsername($_POST['username']);
    try {
        $nuovo_utente->impostaEmail($_POST['email']);
    } catch (Exception $e) {
        return $e->getMessage();
    }
    $nuovo_utente->impostaPassword($_POST['password']);
    $nuovo_utente->impostaMaster(1);
	$conn = connetti();
    $file = dirname(__FILE__) . "/pms.sql";
    $fp = file_get_contents($file);
    $var_array = explode(';', $fp);
    $conn->beginTransaction();
    for ($count = 0; $count <= 20; $count++) {
        $conn->exec($var_array[$count]);
    }
    $conn->commit();
    try {
        $nuovo_utente->memorizza($conn);
    } catch (Exception $e) {
        return $e->getMessage();
    }
    require "../scripts/funzioni_comuni.php";
    require "../scripts/inviamail.php";
    $oggetto = "Installazione PMS";
    $url = path_PMS();
    $corpo = "Ciao " . $nuovo_utente->getUsername() . ",<br grazie per aver installato PMS.<br /><br />La piattaforma è ora disponibile all'indirizzo : <a href=\"" . $url . "\">" .$url . "</a><br />Accedi con questi parametri(temporanei)<br /><br />Username :  " .
            $nuovo_utente->getUsername() . " (oppure la tua mail)<br />Password : " . $_POST['password'] . "<br />Potrai cambiare password ed email quando desideri.";
    inviamail($nome_gioco, $nome_gioco, $nuovo_utente->getEmail(), $oggetto, $corpo);
    /*
    * Rimuovere o commentare il codice sotto se non vuoi informarci della installazione
    */
    $corpo_informazione = "PMS è stato installato all'indirizzo : <a href=\"" . $url . "\">" . $url . "</a> da " . $nuovo_utente->getEmail();
    inviamail($nome_gioco, $nome_gioco, "info@pms-platform.com", $oggetto, $corpo_informazione);
    /* Rimuovi fino a qui */
    return "Installazione completata con successo!Ora puoi cancellare la cartella installazione e utilizzare PMS con i dati forniti.";
}
?>
<!doctype html>
<html lang="it"><head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <title>Installazione PMS</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Pagina installazione">
        <meta name="author" content="Mario Villani">
        <link rel="stylesheet" href="../libs-frontend/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="../css/index.css">
        <link rel="stylesheet" href="../css/comune.css">
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
        <script src="../assets/js/html5shiv.js"></script>
        <![endif]-->
    </head>
    <body>
        <div id="wrap">
            <div class="container-fluid text-center">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <img class="img-responsive" id="logo" src="../logo.png">
                    </div>
                </div>
                <div class="row">
                    <form id="installform" class="form-signin" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <h2 class="form-signin-heading">Installazione</h2>
                        <input class="form-control" name="email" placeholder="Email del master" type="text">
                        <input class="form-control" name="username" placeholder="Username del master" type="text">
                        <input class="form-control" name="password" placeholder="Password master" type="text">
                        <input class="form-control" name="install" type="hidden">
                        <?php if (isset($messaggio)) {
                            ?>
                            <div class="alert alert-info alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert">
                                    ×
                                </button>
                                <?php echo $messaggio ?>
                            </div>
                        <?php } ?>
                        <button class="btn btn-primary btn-lg" type="submit" id="installa">
                            Installa
                        </button><br><br>
                    </form>
                </div>
            </div><div id="push"></div></div>
            <?php
            require "../componenti/footer.php";
            ?>
        <script type="text/javascript" src="../libs-frontend/jquery.js"></script>
        <script src="../libs-frontend/bootstrap/js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#installform').submit('click', function() {
                    $("#installa").attr("disabled", true);
                    $("#installa").html("Installazione...");
                });
            });
        </script>
    </body>
</html>
