<?php

require "../funzioni_comuni.php";
//Avvio sessione
session_start();
//Verifico se l'utente è collegato
check_collegato();
//Verifico se l'utente collegato è master
check_is_master();
//Controllo parametri inviati.
if (!isset($_POST['id_proprietario']) || !is_numeric($_POST['id_proprietario']))
    errore("Errore parametro ID");
if (!isset($_POST['nome']) || empty($_POST['nome']))
    errore("Errore invio nome");
if (!isset($_POST['punti']) || !is_numeric($_POST['punti'])) {
    errore("Errore punti.");
}
require '../../classi/Personaggio.php';
require '../config.php';
$conn = connetti();
$pg = new PG();
$pg->impostaNome($_POST['nome']);
$pg->impostaProprietario($_POST['id_proprietario']);
$pg->impostaPunti($_POST['punti']);
try {
    $id = $pg->memorizza($conn);
} catch (Exception $e) {
    errore($e->getMessage());
}
header('Content-Type: application/json');
$json_res['id'] = $id;
$json_res['mess'] = "Creazione personaggio completata!";
echo json_encode($json_res);
?>