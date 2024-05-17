<?php

require "../funzioni_comuni.php";

if (!isset($_POST['id']) || !is_numeric($_POST['id']))
    errore("ID Abilità non valido");

session_start();
check_collegato();
//Verifico se l'utente collegato è master
check_is_master();
require_once '../../classi/Abilita.php';
require "../config.php";
$conn = connetti();

// Aggiungere controlli su global POST.

$id = $_POST['id'];

$ab = new Abilita();

try {
    $ab->prelevaDaId($id, $conn);
} catch (Exception $e) {
    errore($e->getMessage());
}

try {
    $ab->delete($conn);
} catch (Exception $e) {
    errore($e->getMessage());
}

echo "Abilità eliminata con successo.";

?>