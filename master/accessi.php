<?php
session_start();
//Controllo sessione ed eventuale ripristino con cookie "ricordami"
require_once "../scripts/funzioni_comuni.php"; //eliminare una volta creato link.
require_once "../scripts/config.php"; //come sopra.
if (!check_visualizza_pagina()) {
    header("Location: ../index.php");
    die('');
}
// Controllo le autorizzazioni
check_is_master();
// Connessione al DB
$conn = connetti();

require_once '../classi/Sessione.php';

$sessione = new Sessione();
if (isset($_POST['username']) && !empty($_POST['username'])) {
    $username = $_POST['username'];
}
$limit = 25;
if (isset($_POST['maxShow']) && is_numeric($_POST['maxShow'])) {
    $limit = $_POST['maxShow'];
}
if (isset($username) && !empty($username))
    $val = $sessione->getByUsername($username, $conn, $limit);
else
    $val = $sessione->getAll($conn, $limit);
?>
<!doctype html>
<html lang="it"><head>
        <title>Gestione Accessi</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Gestione Accessi">
        <meta name="author" content="Mario Villani">
        <meta charset="utf-8">
        <link rel="stylesheet" href="../libs-frontend/bootstrap/css/bootstrap.min.css" media="screen" />
        <link rel="stylesheet" href="../libs-frontend/bootstrap-select/css/bootstrap-select.min.css" />
        <link href="../libs-frontend/jquery.pnotify.default.css" media="all" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="../css/comune.css">
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
            require "../componenti/toolbar_master.php";
            ?>
            <div class="container main-container text-center">
                <h2>Registro degli accessi al sistema</h2>
                <div class="row">
                    <form method="POST" action="accessi.php">
                        Risultati : <select class="selectpicker" id="maxShow" name="maxShow">
                            <option value="25"> 25 </option>
                            <option value="50"> 50 </option>
                            <option value="100"> 100 </option>
                            <option value="200"> 200 </option>
                            </select>
                            <input style="margin-top:10px;margin-bottom:10px" class="form-control" placeholder="Filtra per nome utente" name="username" value="<?php if (isset($username)) echo $username; ?>" type="text" />
                            <button style="margin-top:10px;margin-bottom:10px" type="submit" class="btn btn-primary btn-lg">Filtra</button>
                    </form>
                </div>
                <div class="row">
                    <table class="table table-bordered table-hover tablesorter" id="sessionTable">
                        <thead>
                            <tr class="info">
                                <th><i class=" icon-resize-vertical"></i> # Accesso </th>
                                <th><i class=" icon-resize-vertical"></i> Username </th>
                                <th><i class=" icon-resize-vertical"></i> IP </th>
                                <th><i class=" icon-resize-vertical"></i> Master </th>
                                <th><i class=" icon-resize-vertical"></i> ID Utente </th>
                                <th><i class=" icon-resize-vertical"></i> Data </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($val)) {
                                foreach ($val as $us):
                                    ?>
                                    <tr>
                                        <td> <?php echo $us->getSid(); ?> </td>
                                        <td> <?php echo $us->getUsername(); ?> </td>
                                        <td> <?php echo $us->getAddr(); ?> </td>
                                        <td> <?php echo $us->getMaster() == '1' ? 'Si' : 'No'; ?> </td>
                                        <td> <?php echo $us->getID_Utente(); ?> </td>
                                        <td> <?php echo date('d-m-Y H:i', $us->getTimestamp()); ?></td>
                                    </tr>
                                    <?php
                                endforeach;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <?php
                require "../componenti/modale_account.php";
                ?>
            </div>
            <div id="push"></div></div>
            <?php require "../componenti/footer.php"; ?>
        <script type="text/javascript" src="../libs-frontend/jquery.js"></script>
        <script type="text/javascript">
            document.getElementById('maxShow').value = "<?php echo $limit;?>";
        </script>
        <script type="text/javascript" src="../libs-frontend/bootstrap/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="../libs-frontend/bootstrap-select/js/bootstrap-select.min.js"></script>
        <script type="text/javascript" src="../libs-frontend/jquery.pnotify.min.js"></script>
        <script type="text/javascript" src="../js/account.js"></script>
        <script type="text/javascript" src="../js/accessi.js"></script>
        <script type="text/javascript" src="../libs-frontend/jquery.tablesorter.min.js"></script>

</body></html>
