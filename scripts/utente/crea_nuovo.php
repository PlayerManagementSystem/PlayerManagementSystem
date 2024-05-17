<?php

require_once '../../classi/Utente.php';
require "../config.php";
require "../funzioni_comuni.php";

if (!isset($_POST['username']) || empty($_POST['username']))
    errore("Username non inviato");
if (!isset($_POST['email']) || empty($_POST['email']))
    errore("Email non inviata");
if (!isset($_POST['password']) || empty($_POST['password']))
    errore("Password non inviata");
if (!isset($_POST['master']) || ($_POST['master'] > 1) || ($_POST['master'] < 0)) {
    errore("Parametro master errato.");
}
if (!isset($_POST['invia']) || ($_POST['invia'] > 1) || ($_POST['invia'] < 0)) {
    errore("Parametro invia errato.");
}
header('Content-Type: application/json');
session_start();
check_collegato();
//Verifico se l'utente collegato è master
check_is_master();
$conn = connetti();

$nuovo_utente = new Utente();

$nuovo_utente->impostaAttivo(1);
$nuovo_utente->impostaUsername($_POST['username']);
try {
    $nuovo_utente->impostaEmail($_POST['email']);
} catch (Exception $e) {
    errore($e->getMessage());
}
$nuovo_utente->impostaPassword($_POST['password']);
$nuovo_utente->impostaMaster($_POST['master']);
try {
    $id = $nuovo_utente->memorizza($conn);
} catch (Exception $e) {
    errore($e->getMessage());
}
if ($_POST['invia'] == 1) {
//Mandiamo una mail al nuovo indirizzo comunicato per poi attivarlo.
    require "../inviamail.php";
    $url = path_PMS();
    $oggetto = "Registrazione " . $nome_gioco;
    $corpo = "Ciao " . $nuovo_utente->getUsername() . ",<br />ti è stato assegnato un account qui : <a href=\"" . $url . "\">". $url ."</a><br />Accedi con questi parametri(temporanei)<br /><br />Username :  " .
            $nuovo_utente->getUsername() . " (oppure la tua mail)<br />Password : " . $_POST['password'] . "<br />Potrai cambiare password ed email quando desideri.";
    inviamail($nome_gioco, $nome_gioco, $nuovo_utente->getEmail(), $oggetto, $corpo);
}
$json_res['id'] = $id;
$json_res['username'] = $_POST['username'];
$json_res['email'] = $_POST['email'];
$json_res['master'] = $_POST['master'];
$json_res['mess'] = "Nuovo utente aggiunto con successo";

echo json_encode($json_res);
?>