<?php

require "scripts/funzioni_comuni.php";
if (!isset($_GET['id']) || !is_numeric($_GET['id']))
    errore("ID da attivare non inviato.");
if (!isset($_GET['codice']) || empty($_GET['codice']))
    errore("Codice non inviato.");
require "classi/Attivazione.php";
require "scripts/config.php";
$conn = connetti();
$attivazione = new Attivazione();
try {
    $attivazione->prelevaDaID($_GET['id'], $conn);
} catch (Exception $e) {
    errore($e->getMessage());
}
if ($attivazione->getCodice() != $_GET['codice']) {
    errore("Codice d'attivazione non valido");
}
require_once "classi/Utente.php";
$utente = new Utente();
try {
    $utente->prelevaDaID($_GET['id'], $conn);
} catch (Exception $e) {
    errore($e->getMessage());
}
if (isset($_GET['resetpwd']) && $_GET['resetpwd'] == "reset") {
    $nuova_pwd = stringa_casuale(10);
    $utente->impostaPassword($nuova_pwd);
    require "scripts/inviamail.php";
    $oggetto = "Recupero password " . $nome_gioco;
    $corpo = "Ciao " . $utente->getUsername() . ",<br />ecco la tua nuova password : " . $nuova_pwd . "<br />";
    inviamail($nome_gioco, $nome_gioco, $utente->getEmail(), $oggetto, $corpo);
} else {
    $utente->impostaAttivo(1);
    $utente->impostaEmail($attivazione->getEmail());
}
try {
    $utente->memorizza($conn);
    $attivazione->distruggi($conn);
} catch (Exception $e) {
    errore($e->getMessage());
}
header('Refresh: 5;url=' . path_PMS());
if (isset($_GET['resetpwd']) && $_GET['resetpwd'] == "reset") {
    echo "Password ripristinata con successo.E' stata generata una nuova password.Controlla la tua email!<br />";
}
else
    echo "Attivazione completata con successo.<br />";
echo "Verrai reindirizzato alla home entro 5 secondi."
?>