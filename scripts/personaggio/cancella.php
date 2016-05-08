<?php

require "../funzioni_comuni.php";
if (!isset($_POST['id']) || !is_numeric($_POST['id']))
    errore("ID Personaggio non valido");
//Avvio sessione
session_start();
//Verifico se l'utente è collegato
check_collegato();
//Verifico se l'utente collegato è master
check_is_master();
//Controllo parametri inviati.

$id = $_POST['id'];

require '../../classi/Personaggio.php';
require '../config.php';
$conn = connetti();
try {
    $pers = new PG();
    $pers->prelevaDaID($id, $conn);
    $pers->cancella($conn);
} catch (Exception $e) {
    errore($e->getMessage());
}
echo "Personaggio cancellato con successo.";
?>