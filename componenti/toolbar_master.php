<?php
if (!isset($nome_gioco))
	die("Questo componente non può essere richiamato direttamente.");
?>
<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="container-fluid">
		<div class="navbar-header">
		    <a class="navbar-brand" href="#">
        		<img alt="" src="../minilogo.png">
      		</a>
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
		</div>
		<div class="collapse navbar-collapse" id="collapse">
			<ul class="nav navbar-nav">
				<li>
					<a href="personaggi.php" rel="tooltip" title="Gestisci i personaggi">Personaggi</a>
				</li>
				<li class="divider"></li>
				<li>
					<a href="abilita.php" rel="tooltip" title="Crea e modifica le abilità">Abilità</a>
				</li>
				<li class="divider"></li>
				<li>
					<a href="utenti.php" rel="tooltip" title="Crea e modifica gli utenti">Utenti</a>
				</li>
				<li class="divider"></li>
				<li>
					<a href="messaggio.php" rel="tooltip" title="Lascia un messaggio ai giocatori">Messaggio</a>
				</li>
				<li class="divider"></li>
				<li>
					<a href="accessi.php" rel="tooltip" title="Gestisci gli accessi">Accessi</a>
				</li>
				<li class="divider"></li>
				<li>
					<a href="strumenti.php" rel="tooltip" title="Strumenti per master">Strumenti per Master</a>
				</li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li style="background:#2D6CA2;">
					<a rel="tooltip" href="#" title="Stai utilizzando il pannello per master.">
							Master
					</a>
				</li>
				<?php
					require_once dirname(__FILE__) . "/selettore_azioni.php";
				?>
			</ul>
		</div><!--/.nav-collapse -->
	</div>
</div>