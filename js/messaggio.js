/*
 * Funzioni client-side per la pagina Gestione Messaggio nel pannello
 */

$(document).ready(function() {
    //Assegno le azioni ai pulsanti
    $('#pulsante_messaggio').bind('click', function() {
        inviaMessaggio();
    });
});

function inviaMessaggio() {
    var corpo = $('#corpo').val();
    var invia = 0;
    if ($('#invia').is(':checked')) {
        invia = 1;
    }
    var notifica = lanciaNotifica("Invio messaggio in corso", "info");
    var opzioni = {
        type: "POST",
        url: "../scripts/messaggio/invia_messaggio.php",
        beforeSend: function(xhr) {
            $('#pulsante_messaggio').attr("disabled", "disabled");
        },
        data: {
            corpo: corpo,
            invia: invia
        },
        success: function(messaggio) {
            lanciaNotifica(messaggio, "success");
            $("#pulsante_messaggio").removeAttr("disabled");
        },
        error: function(XMLHTTP) {
            lanciaNotifica(XMLHTTP.responseText, "error");
            $("#pulsante_messaggio").removeAttr("disabled");
        }
    };
    $.ajax(opzioni);
}