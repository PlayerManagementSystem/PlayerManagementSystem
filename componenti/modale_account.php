<?php
if (!isset($nome_gioco))
	die("Questo componente non può essere richiamato direttamente.");
//Prendiamo le informazioni dall'account corrente;
require_once dirname(__FILE__) . "/../classi/Utente.php";
$utente = new Utente();
try {
	$utente -> prelevaDaID($_SESSION['idUtente'], $conn);
} catch (Exception $e) {
	errore("Errore nel prelevare l'utente.");
}
?>
<div id="modale_account" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            ×
        </button>
        <h3>Account di <?php echo $utente -> getUsername(); ?></h3>
    </div>
    <div class="modal-body text-center">
        <p>
            Email : <input class="form-control" placeholder="Email" id="nuova_email" value="<?php echo $utente -> getEmail(); ?>"
 type="email"><br>
<button class="btn btn-primary" id="pulsante_email">
	Cambia email
</button>
<input class="form-control" id="ind_pms" value="<?php echo path_PMS(); ?>" type="hidden">
</p>
<p></p><h2>Modifica Password</h2>
<input class="form-control" id="vecchia_pwd" name="vecchia_pwd" placeholder="Vecchia password" type="password">
<br>
<input class="form-control" id="nuova_pwd" name="nuova_pwd" placeholder="Nuova password" type="password">
<br>
<input class="form-control" id="conferma_pwd" name="conferma_pwd" placeholder="Conferma nuova password" type="password">
<br>
<button class="btn btn-primary" id="pulsante_password">
	Cambia password
</button>
<p></p>
</div>
</div>
</div>
</div>