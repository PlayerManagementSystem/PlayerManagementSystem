<?php

//Configurazione personalizzata
require_once "../scripts/config.php";
//Utility spesso usate
require_once "../scripts/funzioni_comuni.php";
//Avvio sessione
session_start();
//Vedo se son stati passati gli ID
controlla_parametri();
//Controllo sessione ed eventuale ripristino con cookie "ricordami"
if (!check_visualizza_pagina()) {
	header("Location: ../index.php");
	die('');
}
//Controllo le autorizzazioni
check_autorizzazioni($_GET['id']);
//Connessione al DB
$conn = connetti();
//Caricamento della classe Personaggio
require_once ("../classi/Personaggio.php");
$personaggio = new PG();
try {
	$personaggio -> prelevaDaID($_GET['id'], $conn);
} catch (Exception $e) {
	errore($e -> getMessage());
}
try {
	$lista_abilita = $personaggio -> prelevaAbilita($conn);
} catch (Exception $e) {
	$lista_abilita = array();
}
$nome = utf8_decode($personaggio ->getNome());
$razza = utf8_decode($personaggio ->getRazza());
$regno = utf8_decode($personaggio ->getRegno());
$punti = utf8_decode($personaggio -> getPunti());
$puntiSpesi = utf8_decode($personaggio -> getPuntiSpesi());
require_once('../libs-backend/FPDI/fpdf.php');
require_once('../libs-backend/FPDI/fpdi.php');
// initiate FPDI
$pdf = new FPDI();
// add a page
$pdf->AddPage();
// set the source file
$pdf->setSourceFile("Template.pdf");
// import page 1
$tplIdx = $pdf->importPage(1);
// use the imported page and place it at point 10,10 with a width of 100 mm
$pdf->useTemplate($tplIdx);

$titolo = utf8_decode($nome_gioco . " - " . $nome);
$pdf->SetTitle($titolo);
$pdf->SetFont('Helvetica','B');
$pdf->SetFontSize(10);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(50, 26);
$pdf->Write(0, $nome);
$pdf->SetXY(50, 30.5);
$pdf->Write(0, $razza);
$pdf->SetXY(50, 35);
$pdf->Write(0, $regno);
$pdf->SetFontSize(14);
$pdf->SetXY(122, 45);
$pdf->Write(2, $punti);
$pdf->SetXY(150, 45);
$pdf->Write(2, $puntiSpesi);
$pdf->SetFontSize(10);
$y_offset = 66;
$pdf->SetXY(101.5, $y_offset);
foreach ($lista_abilita as $abilita) {
	$pdf->Write(0,utf8_decode($abilita['Nome']));
	$pdf->SetXY(189, $y_offset);
	$pdf->Write(0,utf8_decode($abilita['Costo']));
	$y_offset = $y_offset + 4;
	$pdf->SetXY(101.5, $y_offset);
}
$pdf->Output($titolo . '.pdf','I');
?>