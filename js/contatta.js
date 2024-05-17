/*
 * Funzioni client-side per la pagina Contatta nel pannello
 */

$(document).ready(function() {
    //Assegno le azioni ai pulsanti
    $('#pulsante_invia').bind('click', function() {
        inviaMail();
    });
});

function inviaMail() {
    var oggetto = $('#oggetto').val();
    var corpo = $('#corpo').val();
    var notifica = lanciaNotifica("Invio della mail in corso", "info");
    var opzioni = {
        type: "POST",
        url: "scripts/utente/contatta_master.php",
        beforeSend: function(xhr) {
            $('#pulsante_invia').attr("disabled", "disabled");
        },
        data: {
            oggetto: oggetto,
            corpo: corpo
        },
        success: function(messaggio) {
            lanciaNotifica(messaggio, "success");
            $("#pulsante_invia").removeAttr("disabled");
        },
        error: function(XMLHTTP) {
            lanciaNotifica(XMLHTTP.responseText, "error");
            $("#pulsante_invia").removeAttr("disabled");
        }
    };
    $.ajax(opzioni);
}