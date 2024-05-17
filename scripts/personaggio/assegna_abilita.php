<?php

require_once '../../classi/Competenza.php';
require_once '../../classi/Abilita.php';
require_once '../../classi/Personaggio.php';
require "../config.php";
require "../funzioni_comuni.php";

if (!isset($_POST['id_ab']) || !is_numeric($_POST['id_ab']))
    errore("ID Abilità non inviato");
if (!isset($_POST['id_pg']) || !is_numeric($_POST['id_pg']))
    errore("ID Personaggio non inviato");
session_start();
check_collegato();
//Verifico se l'utente collegato è master
check_is_master();
$conn = connetti();
$pg = new PG();
$ab = new Abilita();
try {
    $ab->prelevaDaID($_POST['id_ab'], $conn);
} catch (Exception $e) {
    errore($e->getMessage());
}
try {
    $pg->prelevaDaID($_POST['id_pg'], $conn);
} catch (Exception $e) {
    errore($e->getMessage());
}
try {
    $pg->prelevaAbilita($conn);
} catch (Exception $e) {
    //Non faccio niente...se non ha abilità non fa niente!
}
if ($pg->getPuntiSpesi() + $ab->getCosto() > $pg->getPunti()) {
    errore("Il personaggio non ha abbastanza punti per l'abilita.");
}
$competenza = new Competenza();
$competenza->impostaPG($_POST['id_pg']);
$competenza->impostaAbilita($_POST['id_ab']);
try {
    $competenza->memorizza($conn);
} catch (Exception $e) {
    errore($e->getMessage());
}
header('Content-Type: application/json');
$json_res['mess'] = "Abilità aggiunta con successo";
$json_res['note'] = $ab->getNote();
$json_res['nome'] = $ab->getNome();
$json_res['costo'] = $ab->getCosto();
echo json_encode($json_res);
?>