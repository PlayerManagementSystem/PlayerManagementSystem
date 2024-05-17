<?php

require "../funzioni_comuni.php";
if (!isset($_POST['oggetto']) || empty($_POST['oggetto']))
    errore("Oggetto mail non inviato.");
if (!isset($_POST['corpo']) || empty($_POST['corpo']))
    errore("Corpo email non inviato.");
//Avvio sessione
session_start();
//Verifico se l'utente è collegato
check_collegato();
require "../../classi/Utente.php";
require "../config.php";
$conn = connetti();
$utente = new Utente();
try {
    $utente->prelevaDaID($_SESSION['idUtente'], $conn);
} catch (Exception $e) {
    errore($e->getMessage());
}
$mittente = $utente->getEmail();
$nome_mittente = $utente->getUsername();
require "../../classi/Master.php";
try {
    $emails = Master::getEmailMasters($conn);
} catch (Exception $e) {
    errore($e->getMessage());
}
require "../inviamail.php";
$oggetto = htmlspecialchars($_POST['oggetto']);
$corpo = htmlspecialchars($_POST['corpo']);
foreach ($emails as $destinatario) {
    inviamail($mittente, $nome_mittente, $destinatario, $oggetto, $corpo);
}
echo "Invio completato.";
?>