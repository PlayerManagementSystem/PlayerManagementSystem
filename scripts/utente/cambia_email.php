<?php

require "../funzioni_comuni.php";
//Avvio sessione
session_start();
//Verifico se l'utente è collegato
check_collegato();
if (!isset($_POST['nuova_email']) || empty($_POST['nuova_email']))
    errore("Nuova email non inviata.");
require "../../classi/Attivazione.php";
$attivazione = new Attivazione();
try {
    $attivazione->impostaEmail($_POST['nuova_email']);
} catch (Exception $e) {
    errore($e->getMessage());
}
require "../config.php";
require "../../classi/Utente.php";
$conn = connetti();
//Prendiamo l'utente attuale
$utente = new Utente();
try {
    $utente->prelevaDaID($_SESSION['idUtente'], $conn);
} catch (Exception $e) {
    errore($e->getMessage());
}
//Verifichiamo se la mail immessa è identica a quella attuale.
$vecchia_email = $utente->getEmail();
if ($vecchia_email == $_POST['nuova_email']) {
    errore("La nuova email &egrave; uguale a quella impostata attualmente.");
}
//Prepariamo l'inserimento delle informazioni per la nuova attivazione
$attivazione->impostaID($utente->getID());
$codice = stringa_casuale(15);
$attivazione->impostaCodice($codice);
try {
    $attivazione->memorizza($conn);
} catch (Exception $e) {
    errore($e->getMessage());
}
//Mandiamo una mail al nuovo indirizzo comunicato per poi attivarlo.
require "../inviamail.php";
$url_attivazione = path_PMS() . "conferma.php?id=" . $utente->getID() . "&codice=" . $codice;
$oggetto = "Cambio email";
$corpo = "Ciao " . $utente->getUsername() . ",<br />clicca qui per confermare questo indirizzo email : <a href=\"" . $url_attivazione . "\">". $url_attivazione . "</a><br />Fino a che non lo confermerai, le comunicazioni da parte di PMS verranno inviate sul tuo vecchio indirizzo e dovrai accedere con il vecchio indirizzo. ";
inviamail($nome_gioco, $nome_gioco, $_POST['nuova_email'], $oggetto, $corpo);
echo "L'email deve essere confermata.Hai ricevuto un messaggio sull'indirizzo da te specificato.";
?>