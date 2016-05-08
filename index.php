<?php
session_start();
//Tentativo di login
require_once 'scripts/funzioni_comuni.php';
require_once 'scripts/config.php';

if (!isset($_SESSION['idUtente']) && isset($_COOKIE['auth']))//Ripristino eventualmente la sessione se non c'è
	autologin();

if (isset($_SESSION['idUtente'])) {//Se c'è una sessione attiva
	if ($_SESSION['master'] == 1) {//Se è master, reindirizzo al pannello Master sezione personaggi
		header("Location: master/personaggi.php");
		die('');
	} else {//Se non è un master, reindirizzo alla panoramica del primo pg caricato in sessione
		//Estensione possibile : mantenere in sessione l'ID dell'ultimo pg visualizzato e caricarlo qui.
		header("Location: panoramica.php?id=" . reset($_SESSION['idPersonaggi']));
		die('');
	}
}
if (isset($_POST['login'])) {
	$risposta = "";
	if (!isset($_POST['email']) || !isset($_POST['password'])) {
		$risposta = "Campi vuoti.";
	} else if (!isset($_POST['email']) || empty($_POST['email'])) {// controllo che il campo email non sia vuoto
		$risposta = "Campo email vuoto!";
	} else if (!isset($_POST['password']) || empty($_POST['password'])) {// controllo che il solo campo password non sia vuoto
		$risposta = "Campo password vuoto!";
	}
	$remember = false;
	if (isset($_POST['remember-me']) && $_POST['remember-me'] == "autologin") {
		$remember = true;
	}
	//Faccio il login se ci sono tutti i parametri.
	if ($risposta == "") {
		$conn = connetti();
		$risposta = login($_POST['email'], $_POST['password'], $conn);
		if (($risposta == 2) || ($risposta == 4)) {
			require_once 'classi/Sessione.php';
			//Avviamo nuova sessione
			Sessione::startSession($remember,$conn);
			if ($risposta == "2") {
				header("Location: master/personaggi.php");
				die('');
			}//se invece è un semplice utente,apro il pannello del primo pg assegnatogli
			else if ($risposta == "4") {
				header("Location: panoramica.php?id=" . reset($_SESSION['idPersonaggi']));
				die('');
			}
		}
	}
}

/* Funzione per autenticazione utente */

function login($user, $password, $conn) {
	/*
	 * Valori restituiti
	 * 0 : login fallito
	 * 1 : account non attivo
	 * 2 : account master
	 * 3 : nessun pg attivo(utente normale)
	 * 4 : account ordinario
	 */
	require_once 'classi/Utente.php';
	$utente = new Utente();
	//Verifichiamo se esiste l'utente
	try {
		$utente -> prelevaDaUsername($user, $conn);
	} catch (Exception $e) {
		return "0";
	}
	//Login fallito se password non corretta
	require "libs-backend/PHPAss/PasswordHash.php";
	$t_hasher = new PasswordHash(8, FALSE);
	if (!$t_hasher -> CheckPassword($password, $utente -> getPassword())) {
		return "0";
	}
	if ($utente -> isAttivo() == 0)
		return "1";
	//Restituiamo il valore di populateCharacters
	require_once 'classi/Sessione.php';
	return Sessione::populateCharacters($utente,$conn);
}
?>
<!doctype html>
<html lang="it">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<title>Login</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Pagina login">
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
						<h2 class="form-signin-heading">Login</h2>
						<input class="form-control" name="email" placeholder="Email/Username" type="text">
						<input class="form-control" name="password" placeholder="Password" type="password">
						<input class="form-control" name="login" type="hidden">
						<label>
							<input name="remember-me" value="autologin" type="checkbox">
							Ricordami</label>
						<br>
						<?php
						if (isset($risposta)) {
						switch ($risposta) {
						case "0":
						$risposta = "Login fallito";
						break;
						case "1":
						$risposta = "Il tuo account non è attivo. Controlla la tua email.";
						break;
						case "3":
						$risposta = "Non hai personaggi assegnati.Contatta un master.";
						break;
						}
						?>
						<div class="alert alert-danger alert-dismissable">
							<button type="button" class="close" data-dismiss="alert">
								×
							</button>
							<strong>Attenzione! </strong><?php echo $risposta
							?>
						</div>
						<?php } ?>
						<button class="btn btn-primary btn-lg" type="submit">
							Entra
						</button>
						<br>
						<br>
						<a href="recupera_pwd.php">Ho dimenticato la mia password</a>
					</form>
				</div>
			</div><div id="push"></div>
		</div>
		<?php
		require "componenti/footer.php";
		?>
		<script type="text/javascript" src="libs-frontend/jquery.js"></script>
		<script src="libs-frontend/bootstrap/js/bootstrap.min.js"></script>

	</body>
</html>
