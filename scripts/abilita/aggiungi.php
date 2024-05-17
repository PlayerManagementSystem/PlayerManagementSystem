<?php

require_once '../../classi/Abilita.php';
require "../config.php";
require "../funzioni_comuni.php";

if (!isset($_POST['nome']) || empty($_POST['nome']))
    errore("Nome non inviato");
if (!isset($_POST['desc']) || empty($_POST['desc']))
    errore("Descrizione non inviata");
if (!isset($_POST['costo']) || !is_numeric($_POST['costo']))
    errore("Costo non inviato");
header('Content-Type: application/json');
session_start();
check_collegato();
//Verifico se l'utente collegato è master
check_is_master();
$conn = connetti();

$nome = $_POST['nome'];
$desc = $_POST['desc'];
$costo = $_POST['costo'];
$note = $_POST['note'];

$newAb = new Abilita();

$newAb->impostaNome($nome);
$newAb->impostaDescrizione($desc);
try {
$newAb->impostaCosto($costo);
$newAb->impostaNote($note);
}
catch(Exception $e) {
    errore($e->getMessage());
}

try {
    $id = $newAb->memorizza($conn);
} catch (Exception $e) {
    errore($e->getMessage());
}

$json_res['id'] = $id;
$json_res['mess'] = "Abilità aggiunta con successo";

echo json_encode($json_res);
?>