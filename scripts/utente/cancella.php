<?php

require "../funzioni_comuni.php";
if (!isset($_POST['id']) || !is_numeric($_POST['id']))
    errore("ID Abilità non valido");
//Avvio sessione
session_start();
//Verifico se l'utente è collegato
check_collegato();
//Verifico se l'utente collegato è master
check_is_master();
//Controllo parametri inviati.

$id = $_POST['id'];
require '../../classi/Utente.php';
require '../config.php';
if ($_SESSION['idUtente'] == $_POST['id'])
    errore("Non puoi cancellare te stesso.");
$conn = connetti();
try {
    $pers = new Utente();
    $pers->prelevaDaID($id, $conn);
    $pers->cancella($conn);
} catch (Exception $e) {
    errore($e->getMessage());
}
echo "Utente cancellato con successo.";
?>