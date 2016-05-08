<?php

require "../funzioni_comuni.php";
//Controllo parametri inviati.
if (!isset($_POST['id']) || !is_numeric($_POST['id']))
    errore("Errore parametro ID");
//Avvio sessione
session_start();
//Verifico se l'utente è collegato
check_collegato();
//Verifico se l'utente collegato può modificare il pg con ID specificato
check_autorizzazioni($_POST['id']);
/*
 * Serie di verifiche per l'avatar caricato
 */
if (empty($_FILES)) {
    errore("Nessun file inviato.");
}
if (!is_uploaded_file($_FILES['nuovo_avatar']['tmp_name'])) {
    errore("File non conforme.");
}

//Se è stato caricato un file
if ($_FILES['nuovo_avatar']['error'] != 0) {
    if ($_FILES['nuovo_avatar']['error'] == 2) {
        // Errore, file troppo grande > 1MB)
        errore("File troppo grande.");
    } else {
        errore("Errore generico nell'upload");
    }
}
//Verifica MIME Type
$ext = pathinfo($_FILES['nuovo_avatar']['name'], PATHINFO_EXTENSION);
$estensioni_consentite = array('gif', 'GIF', 'jpeg', 'JPEG', 'JPE', 'jpe', 'JPG', 'jpg', 'PNG', 'png');
if (!in_array($ext, $estensioni_consentite))
    errore("Estensione non consentita.Puoi caricare solo file immagine.");
/*
 * ---------------------------------------------------------------------
 * Caricamento e verifiche tutto ok, procedo con il salvataggio.
 */
require '../../classi/Personaggio.php';
require '../config.php';
$conn = connetti();
$pg = new PG();
try {
    $pg->prelevaDaID($_POST['id'], $conn);
} catch (Exception $e) {
    errore($e->getMessage());
}
require_once '../../libs-backend/PHPThumbFactory/ThumbLib.inc.php';
try {
    $thumb = PhpThumbFactory::create($_FILES['nuovo_avatar']['tmp_name']);
} catch (Exception $e) {
    errore("Impossibile caricare l'immagine");
}
$thumb->resize(600, 600);
//resizo l'immagine a 600x600 per risparmiare spazio
try {
    $thumb->save("../../avatars/" . $_FILES['nuovo_avatar']['name']);
    $thumb->resize(320, 200);
    $thumb->save("../../avatars/thumbnails/thumb_" . $_FILES['nuovo_avatar']['name']);
} catch (Exception $e) {
    errore("Salvataggio immagine fallito.");
}
$pg->impostaAvatar($_FILES['nuovo_avatar']['name']);
try {
    $pg->memorizza($conn);
} catch (Exception $e) {
    errore($e->getMessage());
}
echo "Avatar modificato!";
?>
