<?php
if (!isset($nome_gioco))
	die("Questo componente non può essere richiamato direttamente.");
?>
<div id="modale_nuovo_pg" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					×
				</button>
				<h3>Crea Nuovo Personaggio</h3>
			</div>
			<div class="modal-body text-center">
				<p>
					Nome :
					<input class="form-control" placeholder="Nome personaggio" id="nome_personaggio" type="text">
					<br>
					Proprietario :
					<select class="selectpicker" data-size="15" data-live-search="true" id="id_proprietario">
						<?php
						try {
							$utenti = Master::getUtenti($conn);
						} catch (Exception $e) {
							//Vedere come gestire l'eccezione qui.
						}
						foreach ($utenti as $utente) {
							echo "<option value=\"" . $utente['id'] . "\">" . $utente['username'] . "";
						}
						?>
					</select>
					<br>
					Di quanti punti dispone?(puoi usare anche 0) :
					<input placeholder="Punti" class="span1 form-control" id="punti_personaggio" type="text">
					<br>
				</p>
				<div class="well">
					<strong>NOTA:</strong> Potrai modificare informazioni quali Avatar,Regno aprendo la scheda del personaggio dopo averlo creato da qui.
				</div>
				<button class="btn btn-primary btn-lg" id="pulsante_nuovo">
					Crea Personaggio
				</button>
				<p></p>
			</div>
		</div>
	</div>
</div>