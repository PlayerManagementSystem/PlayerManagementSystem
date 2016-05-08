<?php

require_once '../../classi/Competenza.php';
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
$competenza = new Competenza();
$competenza->impostaPG($_POST['id_pg']);
$competenza->impostaAbilita($_POST['id_ab']);
try {
    $competenza->delete($conn);
} catch (Exception $e) {
    errore($e->getMessage());
}
echo "Competenza rimossa.";
?>