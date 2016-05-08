<?php
/* Parametri da modificare */
$nome_gioco = "NomeGioco";
$nome_cartella = "nomecartella";
/* Parametri per database */
$db_host = "localhost";
$db_username = "username_db";
$db_password = 'password_db';
$db_nome = 'nome_database';
/* Non modificare oltre questo punto!*/

function connetti() {
	global $db_host,$db_username,$db_password,$db_nome;
    try {
        $conn = new PDO("mysql:host=$db_host;dbname=$db_nome", $db_username, $db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        die("DB Error: " . $e->getMessage());
    }
}
?>