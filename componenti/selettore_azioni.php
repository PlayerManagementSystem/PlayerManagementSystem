<?php
if (!isset($nome_gioco))
	die("Questo componente non può essere richiamato direttamente.");
?>
<li class="divider"></li>
<li class="dropdown">
	<a data-toggle="dropdown" class="dropdown-toggle" href="#">Azioni <b class="caret"></b></a>
	<ul class="dropdown-menu">
		<li class="">
			<a href="#modale_account" data-toggle="modal">Account</a>
		</li>
		<li class="divider"></li>
		<li class="">
			<a href="<?php echo path_PMS(); ?>scripts/logout.php">Logout</a>
		</li>
	</ul>
</li>