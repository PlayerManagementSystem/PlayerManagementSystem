<?php

/*
 * Questo file contiene funzioni comuni a molti scripts
 */

//Verifica se l'utente è collegato.La uso solo per gli scripts.
//NON recupera la sessione, non ha senso farlo per gli scripts ma bensì per le pagine(che son quelle che l'utente salva nel browser)
function check_collegato() {
    if (!isset($_SESSION['idUtente'])) {
        errore('Non sei collegato.');
    }
}

//Verifica se sono stati passati gli ID per visualizzare i personaggi.
//Viene verificato in modifica.php,panoramica.php,contatta.php e abilità_pg.php che sono quelli riguardanti il singolo personaggio.Stesso vale per printPGSheet.php(stampa scheda)
function controlla_parametri() {
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        errore("Parametri mancanti");
    }
}

/*
 * Verifica se è possibile visualizzare il pannello.
 * Ripristina eventualmente la vecchia sessione del "Ricordami"
 * Viene verificato in tutte le pagine del sito ad eccezione degli scripts.
 */

function check_visualizza_pagina() {
    if (!isset($_SESSION['idUtente'])) {
        return autologin();
    }
    return true;
}

//Verifica se si è proprietari del personaggio da modificare.
//Se si è master, viene autorizzata la modifica in ogni caso.
//Questa funzione va eseguita dopo aver verificato o ripristinato la sessione.
function check_autorizzazioni($id) {
    if ($_SESSION['master'] != 1) {//Verifico se sono master
        if (!in_array($id, $_SESSION['idPersonaggi'])) {//verifico se posso visualizzare le info/effettuare l'operazione
            errore("Non sei autorizzato a visualizzare/modificare il contenuto.");
        }
    }
}

//Restrizione della funzione di sopra : l'operazione è concessa solo ad un master.
//Come sopra, ma più restrittivo.
function check_is_master() {
    if ($_SESSION['master'] != 1)
        errore("Non sei un master...non puoi effettuare l'operazione.");
}

/*
 * Uccide lo script mandando errore al client e il messaggio dell'errore.
 */

function errore($messaggio) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    die($messaggio);
}
/*
* Restituisce il path a PMS
*/
function path_PMS() {
    global $nome_cartella;
    $path = $nome_cartella;
    if(!empty($path)) $path = $nome_cartella ."/";
    return "http://" . $_SERVER['SERVER_NAME'] . "/".$path;
}

/* Uno scriptino per generare codici casuali.
  La funzione stringa_casuale genera una stringa casuale della $lenght prefissata.
 */

function stringa_casuale($length) {
    $caratterivalidi = "abcdefghijklmnopqrstuxyvwzABCDEFGHIJKLMNOPQRSTUXYVWZ0123456789";
    $num_car_validi = strlen($caratterivalidi);
    $risultato = "";
    for ($i = 0; $i < $length; $i++) {
        $index = mt_rand(0, $num_car_validi - 1);
        $risultato .= $caratterivalidi[$index];
    }
    return $risultato;
}

// Se l'utente ha cliccato su "Ricordami" viene usata questa funzione...
function autologin() {
    if (!isset($_COOKIE['auth'])) //Se non c'è il cookie
        return false;

    $ck = unserialize($_COOKIE['auth']);

    if (empty($ck)) //Se il cookie è vuoto
        return false;

    require_once dirname(__FILE__) . '/../classi/Sessione.php';
    require_once dirname(__FILE__) . '/config.php';
    $conn = connetti();

    $old_sess = new Sessione(); //Prendiamo la sessione
    try {
        $old_sess->getSessione($ck['s'], $ck['h'], $conn);
    } catch (Exception $e) { //Se non esiste la sessione
        return false;
    }
    //Invalidiamo la vecchia sessione se tutto ok
    $old_sess->setHash(sha1(microtime(true) . mt_rand(10000, 90000)));
    $old_sess->save($conn);
    /* Carichiamo le informazioni dell'utente */
    require_once dirname(__FILE__) . '/../classi/Utente.php';

    $usr = new Utente();
    try {
        /* Nota : poco sicuro fare il preleva ID usando il campo u nel cookie!
          //Basta avere una sessione valida, cambiare ad arte il valore 'u' nel cookie e si può impersonare chiunque!
         */
        $usr->prelevaDaID($old_sess->getUid(), $conn);
    } catch (Exception $e) {
        return false;
    }
    if(Sessione::populateCharacters($usr,$conn) == "3" ) return false; //si verifica se c'è stato errore nel fetching dei personaggi.
    Sessione::startSession(true,$conn); //avviamo sessione con impostazione "Ricordami" attiva.
}
?>