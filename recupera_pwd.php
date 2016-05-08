<?php
require_once 'scripts/config.php';
if (isset($_POST['recupera'])) {
	if (!isset($_POST['email']) || empty($_POST['email']))
		$messaggio = "Email/Username non immesso.";
	else {
		$messaggio = richiedi_pwd();
	}
}

function richiedi_pwd() {
	require "classi/Utente.php";
	require_once 'scripts/funzioni_comuni.php';
	$conn = connetti();
	$utente = new Utente();
	try {
		$utente -> prelevaDaUsername($_POST['email'], $conn);
	} catch (Exception $e) {
		return "Utente non trovato.";
	}
	require "classi/Attivazione.php";
	$attivazione = new Attivazione();
	try {
		$attivazione -> impostaEmail($utente -> getEmail());
		$attivazione -> impostaID($utente -> getID());
		$codice = stringa_casuale(15);
		$attivazione -> impostaCodice($codice);
	} catch (Exception $e) {
		return $e -> getMessage();
	}
	try {
		$attivazione -> memorizza($conn);
	} catch (Exception $e) {
		return $e -> getMessage();
	}
	//Mandiamo una mail per cambiare la password
	require "scripts/inviamail.php";
	$url_attivazione = path_PMS() . "conferma.php?id=" . $utente -> getID() . "&codice=" . $codice . "&resetpwd=reset";
	$oggetto = "Cambio password";
	$corpo = "Ciao " . $utente -> getUsername() . ",<br />clicca qui per ripristinare la tua password : <a href=\"" . $url_attivazione . "\">" . $url_attivazione . "</a><br />Riceverai una nuova password nella tua casella email.";
	inviamail($nome_gioco, $nome_gioco, $utente -> getEmail(), $oggetto, $corpo);
	return "Mail di ripristino password inviata con successo!";
}
?>
<!doctype html>
<html lang="it"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<title>Recupera password</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Recupera password">
<meta name="author" content="Mario Villani">
<link rel="stylesheet" href="libs-frontend/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="css/index.css">
<link rel="stylesheet" href="css/comune.css">
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
<img id="logo" class="img-responsive" src="logo.png">
</div>
</div>
<div class="row">
<form class="form-signin" method="post">
<h2 class="form-signin-heading">Recupera password</h2>
<input class="form-control" name="email" placeholder="Email/Username" type="text">
<input class="form-control" name="recupera" type="hidden">
<?php
if (isset($messaggio)) {
?>
<div class="alert alert-info alert-dismissable">
<button type="button" class="close" data-dismiss="alert">
×
</button>
<?php echo $messaggio
?>
</div>
<?php } ?>
<button class="btn btn-primary btn-lg" type="submit">
Recupera
</button>
</form>
</div>
</div><div id="push"></div></div>
<?php
require "componenti/footer.php";
?>
<!-- /container -->
<script type="text/javascript" src="libs-frontend/jquery.js"></script>
<script src="libs-frontend/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
