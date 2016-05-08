<?php

//Ripristino la sessione
session_start();

if (isset($_SESSION['idUtente']) || isset($_SESSION['master']) || isset($_SESSION['idPersonaggi'])) {
    require_once dirname(__FILE__) . '/../classi/Sessione.php';
    require_once dirname(__FILE__) . '/config.php';
    $conn = connetti();

    $old_sess = new Sessione(); //Prendiamo la vecchia sessione
    try {
        $old_sess->getBySID($_SESSION['sid'], $conn);
		//Invalidiamo la vecchia sessione se tutto ok
		$old_sess->setHash(sha1(microtime(true) . mt_rand(10000, 90000)));
		$old_sess->save($conn);
    } catch (Exception $e) {} //Se non esiste la sessione, fa nulla!
    session_unset();
    session_destroy();
    if (isset($_COOKIE['auth'])) {
        setcookie('auth', false, time() - 60 * 100000, '/');
    }
    header("Location: ../index.php");
    die('');
}
else
    die("Non sei collegato");
?>