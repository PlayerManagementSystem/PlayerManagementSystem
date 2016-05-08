<?php
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
// Controllo le autorizzazioni(solo per master)
check_is_master();
require_once "scripts/config.php";
require_once ("classi/Master.php");
require_once ("classi/Personaggio.php");
// Connessione al DB
$conn = connetti();
$personaggio = new PG();
try {
	$personaggio -> prelevaDaID($_GET['id'], $conn);
} catch (Exception $e) {
	errore($e -> getMessage());
}
//Se non ci sono abilità, metto NULL come flag
try {
	$abilita_pg = $personaggio -> prelevaAbilita($conn);
} catch (Exception $e) {
	$abilita_pg = NULL;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title> Abilità <?php $personaggio -> getNome(); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Editor Abilità Personaggio">
        <meta name="author" content="Mario Villani">
        <meta charset="utf-8">
        <link rel="stylesheet" href="libs-frontend/bootstrap/css/bootstrap.min.css" />
        <link rel="stylesheet" href="libs-frontend/bootstrap-select/css/bootstrap-select.min.css" />
        <link href="libs-frontend/jquery.pnotify.default.css" media="all" rel="stylesheet" type="text/css" />
        <link href="libs-frontend/bootstrap-editable/css/bootstrap-editable.css" rel="stylesheet" />
        <link rel="stylesheet" href="css/comune.css" />
        <style>
			.table thead tr.info th {
				background-color: #d9edf7;
				cursor: pointer;
			}
        </style>
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
        <script src="../assets/js/html5shiv.js"></script>
        <![endif]-->
    </head>
    <body>
        <div id="wrap">
            <?php
			require_once "componenti/toolbar_utente.php";
            ?>
            <div class="container main-container text-center">
                <h1>Gestione Abilità di <?php echo $personaggio -> getNome(); ?></h1>
                <h3>Punti totali : <?php echo $personaggio -> getPunti(); ?></h3>
				<h3>Punti spesi : <span id="punti_spesi"><?php echo $personaggio -> getPuntiSpesi(); ?></span></h3>
                <div class="alert alert-info">
                    Queste sono le competenze attuali del personaggio.
                </div>
                <div class="row" style="margin-top: 10px;">
                    <table class="table table-bordered table-hover tablesorter" id="abilityTable">
                        <thead>
                            <tr class="info">
                                <th><i class=" icon-resize-vertical"> </i> Nome </th>
                                <th><i class=" icon-resize-vertical"> </i> Costo </th>
                                <th><i class=" icon-resize-vertical"> </i> Note </th>
                                <th><i class=" icon-resize-vertical"> </i> Rimuovi abilità </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($abilita_pg == NULL)
                                echo "<td>Nessuna abilità assegnata.</td>";
                            else
                                foreach ($abilita_pg as $ab):
                                    ?>
                                    <tr id=<?php echo $ab['ID_Abilita']; ?>>
                                        <td><?php echo $ab['Nome'] ?></td>
                                        <td><?php echo $ab['Costo']; ?></td>
                                        <td><?php echo $ab['Note']; ?></td>
                                        <td>
                                            <button class="btn btn-small btn-danger">
                                                x
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <h2>Assegna abilità</h2>		<div class="alert alert-info">
                    Seleziona ed assegna al personaggio una delle abilità disponibili.
                </div>
                Nome abilità :
                <?php
                try {
                    $lista_abilita = Master::getAbilita($conn);
                    echo "<select class=\"selectpicker\" id=\"lista_abilita\" data-size=\"15\" data-live-search=\"true\">";
                    foreach ($lista_abilita as $abilita) {
                        echo "<option value=\"" . $abilita['id'] . "\" >" . $abilita['nome'] . " (" . $abilita['costo'] . ")</option>";
                    }
                    ?>
                    </select><br /><br />
                    <button id="pulsante_assegna" style="margin-bottom:10px;" class="btn btn-primary btn-large">Assegna abilità</button>
                    <?php } catch (Exception $e) {
					echo "Non hai creato nessuna abilità.";
					}
                ?>
                <?php
					require "componenti/modale_account.php";
 ?>
                <span id="id_pg" pg="<?php echo $_GET['id'] ?>"></span>
            </div><div id="push"></div></div>
            <?php
				require "componenti/footer.php";
 ?>
        <script type="text/javascript" src="libs-frontend/jquery.js"></script>
        <script type="text/javascript" src="libs-frontend/bootstrap/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="libs-frontend/bootstrap-select/js/bootstrap-select.min.js"></script>
        <script type="text/javascript" src="libs-frontend/jquery.pnotify.min.js"></script>
        <script type="text/javascript" src="js/abilita_pg.js"></script>
        <script type="text/javascript" src="js/account.js"></script>
        <script src="libs-frontend/bootstrap-editable/js/bootstrap-editable.min.js"></script>
        <script type="text/javascript" src="libs-frontend/jquery.tablesorter.min.js"></script>
    </body>
</html>
