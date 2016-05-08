<?php /*
 * PAGINA CONTATTA MASTER.
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
//Controllo le autorizzazioni
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
		<title>Contatta Master</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Contatta un master">
		<meta name="author" content="Mario Villani">
		<meta charset="utf-8">
		<link rel="stylesheet" href="libs-frontend/bootstrap/css/bootstrap.min.css" media="screen">
		<link href="libs-frontend/jquery.pnotify.default.css" media="all" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="css/comune.css">
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
			<div class="container main-container text-center">
				<h2>Oggetto mail</h2>
				<div class="row">
					<input class="form-control" id="oggetto" placeholder="L'oggetto del messaggio" value="" type="text">
				</div>
				<h2>Testo della mail</h2>
				<div class="row">
					<textarea class="form-control" id="corpo" rows="20" placeholder="Cosa vuoi comunicare ai master?" class="span11"></textarea>
				</div>
				<button style="margin-top:10px;" class="btn btn-primary btn-lg" id="pulsante_invia">
					Invia Email
				</button>
				<?php
				if ($_SESSION['master'] == 0)
					require "componenti/modale_messaggio.php";
				require "componenti/modale_account.php";
				?>
			</div><div id="push"></div>
		</div>
		<?php
			require "componenti/footer.php";
		?>
		<script type="text/javascript" src="libs-frontend/jquery.js"></script>
		<script type="text/javascript" src="libs-frontend/bootstrap/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="libs-frontend/jquery.pnotify.min.js"></script>
		<script type="text/javascript" src="js/account.js"></script>
		<script type="text/javascript" src="js/contatta.js"></script>

</body></html>
