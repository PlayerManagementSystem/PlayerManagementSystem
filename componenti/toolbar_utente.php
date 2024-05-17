<?php
if (!isset($nome_gioco))
	die("Questo componente non può essere richiamato direttamente.");
?>
<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="container-fluid">
		<div class="navbar-header">
            <a class="navbar-brand" href="#">
                <img alt="" src="minilogo.png">
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
                        <a href="panoramica.php?id=<?php echo $personaggio->getID() ?>" rel="tooltip" data-placement="bottom" title="Visualizza scheda del personaggio">Panoramica PG</a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="modifica.php?id=<?php echo $personaggio->getID() ?>" rel="tooltip" data-placement="bottom" title="Modifica informazioni del personaggio">Modifica Informazioni PG</a>
                    </li>
                    <li class="divider"></li>
                    <?php
//Contatta master è visualizzabile solo dai giocatori.
                    if ($_SESSION['master'] == 0) {
                        ?>
                        <li>
                            <a href="contatta.php?id=<?php echo $personaggio->getID() ?>" rel="tooltip" data-placement="bottom" title="Manda un messaggio ai master">Contatta Master</a>
                        </li>
                        <li class="divider"></li>
<?php } else { ?>
                        <li>
                            <a href="abilita_pg.php?id=<?php echo $personaggio -> getID(); ?>" rel="tooltip" data-placement="bottom" title="Modifica abilità personaggio">Modifica Abilità PG</a>
                        </li>
                        <li class="divider"></li>
                        <?php }
							//Il tastino è visualizzabile solo dai giocatori.
							if ($_SESSION['master'] == 0) {
		?>
		<li>
		<a href="#modale_messaggio" data-toggle="modal" rel="tooltip" data-placement="bottom" title="Visualizza messaggio lasciato dai master" >
		Comunicazioni</a>
		</li><li class="divider"></li>

		<?php } ?>
		</ul>
                <ul class="nav navbar-nav navbar-right">
        <?php
                //Visualizziamo il selettore dei personaggi se l'utente corrente è un giocatore.
                if ($_SESSION['master'] == 0) {
                    ?>
                    <li class="dropdown" style="background:#2D6CA2;">
                            <a class="dropdown-toggle" href="#" rel="tooltip" data-placement="bottom" title="Personaggio selezionato" data-toggle="dropdown"> <?php echo substr($personaggio -> getNome(), 0, 24); ?>
                                <span class="caret"></span> </a>
                            <ul class="dropdown-menu" role="menu">
                                <?php $num_pg = count($_SESSION['idPersonaggi']);
                                if ($num_pg > 1) {
                                    foreach ($_SESSION['idPersonaggi'] as $nome_pg => $id_pg) {
                                        if ($id_pg != $id_attuale)
                                            echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="panoramica.php?id=' . $id_pg . '">' . $nome_pg . '</a></li>';
                                    }
                                } else {
                                    echo "<li><a>Nessun altro pg disponibile.</a></li>";
                                }
                                ?>
                            </ul>
                    </li>
                    <?php }
                    //Se non lo è, mostriamo un link per tornare al pannello master.
                    else {
                    ?>
                    <li>
                        <a href="master/personaggi.php">Torna al pannello master</a>
                    </li>
<?php } ?>
<?php
    require_once dirname(__FILE__) . "/selettore_azioni.php";
 ?>
            </ul>
		</div><!--/.nav-collapse -->
	</div>
</div>