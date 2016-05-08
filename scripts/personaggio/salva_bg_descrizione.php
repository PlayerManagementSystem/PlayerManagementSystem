<?php

require "../funzioni_comuni.php";
//Verifica parametri inviati
if (!isset($_POST['id']) || !is_numeric($_POST['id']))
    errore("Errore parametro ID");
if (!isset($_POST['background']) || empty($_POST['background'])) {
    errore("Background non inviato.");
}
if (!isset($_POST['descrizione']) || empty($_POST['descrizione'])) {
    errore("Descrizione non inviata.");
}
//-------------//
//Avvio sessione
session_start();
require "../config.php";
require "../../classi/Personaggio.php";
//Verifico se un utente collegato sta effettuando l'operazione
check_collegato();
//Verifico se posso modificare il personaggio
check_autorizzazioni($_POST['id']);
//Creo la classe personaggio per effettuare le modifiche.
$pg_da_modificare = new PG();
$conn = connetti();
try {
    $pg_da_modificare->prelevaDaID($_POST['id'], $conn);
} catch (Exception $e) {
    errore($e->getMessage());
}
$pg_da_modificare->impostaBackground($_POST['background']);
$pg_da_modificare->impostaDescrizione($_POST['descrizione']);
try {
    $pg_da_modificare->memorizza($conn);
} catch (Exception $e) {
    errore($e->getMessage());
}
echo "Modifica effettuata con successo!";
?>