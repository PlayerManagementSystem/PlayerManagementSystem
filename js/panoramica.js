/*
 * Funzioni client-side per la Panoramica del pannello.
 */

$(document).ready(function() {
    $('#pulsante_qr').bind('click', function() {
        apriQR();
    });
    $('#stampa_qr').bind('click', function() {
        stampaQR();
    });
});

function apriQR() {
    document.getElementById("testo_qr").innerHTML = "";
    new QRCode(document.getElementById("testo_qr"), {
        text: document.URL
    });
    $("#modaleQR").modal('toggle');
}

function stampaQR() {
    var testo_qr = document.getElementById("testo_qr").innerHTML;
    var popupWin = window.open('', '_blank', 'width=450,height=500,scrollbars=1');
    popupWin.document.write('<!DOCTYPE html><html><head>' + '<title>Codice QR</title>' + '</head><body onload="window.print()">' + testo_qr + '</body></html>');
    popupWin.document.close();
}