<?php
if (!isset($nome_gioco))
	die("Questo componente non può essere richiamato direttamente.");
?>
<div id="modale_nuovo_utente" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					×
				</button>
				<h3>Crea Nuovo Utente</h3>
			</div>
			<div class="modal-body text-center">
				<p>
					Username :
					<input class="form-control" placeholder="Nome utente" id="username_utente" type="text">
					<br>
					Email :
					<input class="form-control" placeholder="Email utente" id="email_utente" type="email">
					<br>
					Conferma email :
					<input class="form-control" placeholder="Email utente" id="email_utente_conf" type="email">
					<br>
					Password :
					<input class="form-control" placeholder="Password" id="password_utente" type="password">
					<br>
					Conferma password :
					<input class="form-control" placeholder="Password" id="password_utente_conf" type="password">
					<br>
					E' un master?
					<select class="form-control" id="master">
						<option value="0">No</option>
						<option value="1">Si</option>
					</select>
					<br>
					<label>
						<input value="1" id="invia" type="checkbox">
						Invia i dati all'email dell'utente</label>
				</p>
				<div class="well">
					<strong>NOTA:</strong> Dopo la creazione l'utente sarà libero di modificare email e password.
				</div>
				<button class="btn btn-primary" id="pulsante_nuovo">
					Crea Utente
				</button>
				<p></p>
			</div>
		</div>
	</div>
</div>