<?php
if (!isset($nome_gioco))
	die("Questo componente non può essere richiamato direttamente.");
?>
<div id="modale_nuova_ab" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					×
				</button>
				<h3>Crea Abilità</h3>
			</div>
			<div class="modal-body text-center">
				<p>
					Nome :
					<input class="form-control" placeholder="Nome abilità" id="nome_abilita" type="text">
					<br>
					Descrizione :
					<input class="form-control" placeholder="Descrizione" id="desc_abilita" type="text">
					<br>
					Costo(puoi usare anche 0) :
					<input class="form-control" placeholder="Costo" id="costo_abilita" type="text">
					<br>
					Note:
					<input class="form-control" placeholder="Note" id="note_abilita" type="text">
					<br>
					<button class="btn btn-primary btn-lg" id="tasto_aggiungi">
						Crea Abilità
					</button>
				</p>
			</div>
		</div>
	</div>
</div>