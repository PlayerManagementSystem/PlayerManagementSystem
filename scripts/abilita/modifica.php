<?php

require_once '../../classi/Abilita.php';
require "../config.php";
require "../funzioni_comuni.php";

if (!isset($_POST['name']) || empty($_POST['name']))
    errore("Nome parametro non inviato");
if (!isset($_POST['value']))
    errore("Valore parametro non inviato");
if (!isset($_POST['pk']) || !is_numeric($_POST['pk']))
    errore("ID Abilità non inviato");
session_start();
check_collegato();
//Verifico se l'utente collegato è master
check_is_master();
$conn = connetti();

$name = $_POST['name'];
$val = $_POST['value'];
$id = $_POST['pk'];

$ab = new Abilita();
try {
    $ab->prelevaDaID($id, $conn);
} catch (Exception $e) {
    errore($e->getMessage());
}
try {
    if ($name === 'nome')
        $ab->impostaNome($val);
    else if ($name === 'descrizione')
        $ab->impostaDescrizione($val);
    else if ($name === 'costo')
        $ab->impostaCosto($val);
    else if ($name === 'note')
        $ab->impostaNote($val);
    $ab->memorizza($conn);
} catch (Exception $e) {
    errore($e->getMessage());
}
?>