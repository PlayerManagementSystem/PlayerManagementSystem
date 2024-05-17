<?php

require "../funzioni_comuni.php";
if (!isset($_POST['corpo']) || empty($_POST['corpo']))
    errore("Corpo messaggio non inviato.");
if (!isset($_POST['invia']) || ($_POST['invia'] > 1) || ($_POST['invia'] < 0))
    errore("Parametro invia errato.");
//Avvio sessione
session_start();
//Verifico se l'utente è collegato
check_collegato();
check_is_master();
require "../config.php";
$conn = connetti();
require "../../classi/Messaggio.php";
$mex = new Messaggio();
$corpo = htmlspecialchars($_POST['corpo']);
$mex->impostaMessaggio($corpo);
require "../../classi/Master.php";
try {
    $mex->memorizza($conn);
} catch (Exception $e) {
    errore($e->getMessage());
}
try {
    $utenti = Master::getUtenti($conn);
} catch (Exception $e) {
    errore($e->getMessage());
}
if ($_POST['invia'] == 1) {
    require "../inviamail.php";
    $url = path_PMS();
    foreach ($utenti as $destinatario) {
        $messaggio = "Ciao " . $destinatario['username'] . ",<br />un master ha comunicato : <br />" . $corpo . "<br /><br />Accedi subito all'indirizzo : <a href=\"".  $url ."\">".$url ."</a>";
        inviamail($nome_gioco, $nome_gioco, $destinatario['email'], "Messaggio dai master", $messaggio);
    }
}
echo "Invio completato.";
?>