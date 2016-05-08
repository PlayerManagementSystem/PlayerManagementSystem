<?php
if (!isset($nome_gioco))
	die("Questo componente non può essere richiamato direttamente.");
//Prendiamo le informazioni dall'account corrente;
require_once dirname(__FILE__) . "/../classi/Messaggio.php";
$mess = new Messaggio();
try {
	$messaggio = $mess -> getMessaggio($conn);
} catch (Exception $e) {
	$messaggio = "";
}
?>
<div id="modale_messaggio" class="modal fade">
        <div class="modal-dialog">
        	<div class="modal-content">
   			<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            	×
        	</button>
        	<h3>Messaggio dei master</h3>
   		    </div>
    	<div class="modal-body text-center">
      		 <p><?php echo $messaggio; ?></p>
    	</div>
		</div>
	</div>
</div>