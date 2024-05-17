<?php

require "../funzioni_comuni.php";
//Verifica parametri inviati
if (!isset($_POST['id']) || !is_numeric($_POST['id']))
    errore("Errore parametro ID");
if (!isset($_POST['nome']) || empty($_POST['nome'])) {
    errore("Nome non inviato.");
}
if (!isset($_POST['regno']) || empty($_POST['regno'])) {
    errore("Regno non inviato.");
}
if (!isset($_POST['punti']) || !is_numeric(($_POST['punti']))) {
    errore("Errore numero punti");
}
//-------------//
//Avvio sessione
session_start();
require "../config.php";
require "../../classi/Personaggio.php";
//Verifico se un utente collegato sta effettuando l'operazione
check_collegato();
//Verifico se sono master
check_is_master();
//Creo la classe personaggio per effettuare le modifiche.
$pg_da_modificare = new PG();
$conn = connetti();
try {
    $pg_da_modificare->prelevaDaID($_POST['id'], $conn);
} catch (Exception $e) {
    errore($e->getMessage());
}
$pg_da_modificare->impostaNome($_POST['nome']);
$pg_da_modificare->impostaRegno($_POST['regno']);
$pg_da_modificare->impostaRazza($_POST['razza']);
$pg_da_modificare->impostaPunti($_POST['punti']);
if (isset($_POST['nota']) && !empty($_POST['nota']))
    $pg_da_modificare->impostaNota($_POST['nota']);
try {
    $pg_da_modificare->memorizza($conn);
} catch (Exception $e) {
    errore($e->getMessage());
}
echo "Modifica effettuata con successo!";
?>