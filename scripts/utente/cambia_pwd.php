<?php

require "../funzioni_comuni.php";
//Avvio sessione
session_start();
//Verifico se l'utente è collegato
check_collegato();
if (!isset($_POST['vecchia_pwd']) || empty($_POST['vecchia_pwd']))
    errore("Vecchia password non inviata.");
if (!isset($_POST['nuova_pwd']) || empty($_POST['nuova_pwd']))
    errore("Nuova password non inviata.");
if ($_POST['vecchia_pwd'] == $_POST['nuova_pwd']) {
    errore("La nuova password non può coincidere con la vecchia.");
}
if (strlen($_POST['nuova_pwd']) < 6) {
    errore("La password deve essere almeno di 6 caratteri.");
}
require "../../classi/Utente.php";
require "../config.php";
$conn = connetti();
$utente = new Utente();
try {
    $utente->prelevaDaID($_SESSION['idUtente'], $conn);
} catch (Exception $e) {
    errore("Errore nel prelevare l'utente.");
}
//Prendo vecchio hash
$vecchia_password = $utente->getPassword();
//Verifichiamo se la password vecchia è corretta.
require_once "../../libs-backend/PHPAss/PasswordHash.php";
$t_hasher = new PasswordHash(8, FALSE);
if (!$t_hasher->CheckPassword($_POST['vecchia_pwd'], $vecchia_password)) {
    errore("La vecchia password non è corretta.");
}
//Settiamola nell'utente se tutto ok.
$utente->impostaPassword($_POST['nuova_pwd']);
//Salviamo l'utente aggiornato nel db.
try {
    $utente->memorizza($conn);
} catch (Exception $e) {
    errore("Errore nel salvataggio della password.");
}
require "../inviamail.php";
$oggetto = "Cambio password";
$corpo = "Ciao " . $utente->getUsername() . ",<br />come da te richiesto abbiamo modificato la tua password.<br />Non dimenticarla!";
inviamail($nome_gioco, $nome_gioco, $utente->getEmail(), $oggetto, $corpo);
echo "Password cambiata!Riceverai una mail di conferma";
?>