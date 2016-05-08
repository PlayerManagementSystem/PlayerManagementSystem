/*
 * Funzioni client side per la sezione Modifica nel pannello.
 */
$(document).ready(function() {
    //Assegno le azioni ai pulsanti
    $('#pulsante_salva').bind('click', function() {
        salvaInformazioni();
    });
    $('#pulsante_salva_master').bind('click', function() {
        salvaInformazioniMaster();
    });
    $('#pulsante_avatar').bind('click', function() {
        caricaAvatar();
    });
});
/*
 * Funzione di caricamento avatar.
 */
function caricaAvatar() {
    //Prelevo ID personaggio, la foto è inviata dal tag form.
    var id_personaggio = $('#id_pg').attr("pg");
    var notifica = lanciaNotifica("Caricamento in corso...", "info");
    var opzioni = {
        beforeSend: function(xhr) {
            $('#pulsante_avatar').attr("disabled", "disabled");
        },
        data: {
            id: id_personaggio
        },
        success: function(messaggio) {
            lanciaNotifica(messaggio, "success");
            $("#pulsante_avatar").removeAttr("disabled");
        },
        error: function(XMLHTTP) {
            lanciaNotifica(XMLHTTP.responseText, "error");
            $("#pulsante_avatar").removeAttr("disabled");
        }
    };
    $("#form_immagine").ajaxForm(opzioni).submit();
}

/*
 * Funzione per memorizzazione background e descrizione.
 */
function salvaInformazioni() {
    //Prelevo bg,descrizione e id_personaggio
    var background = $('#background').val();
    var descrizione = $('#descrizione').val();
    var notifica = lanciaNotifica("Invio informazioni...", "info");
    var id_personaggio = $('#id_pg').attr("pg");
    var opzioni = {
        type: "POST",
        beforeSend: function(xhr) {
            $('#pulsante_salva').attr("disabled", "disabled");
        },
        url: "scripts/personaggio/salva_bg_descrizione.php",
        data: {
            id: id_personaggio,
            background: background,
            descrizione: descrizione
        },
        success: function(messaggio) {
            lanciaNotifica(messaggio, "success");
            $("#pulsante_salva").removeAttr("disabled");
        },
        error: function(XMLHTTP) {
            lanciaNotifica(XMLHTTP.responseText, "error");
            $("#pulsante_salva").removeAttr("disabled");
        }
    };
    $.ajax(opzioni);
}

/*
 * Memorizza nome,regno e punti.
 */
function salvaInformazioniMaster() {
    //Prelevo bg,descrizione e id_personaggio
    var nome = $('#nome_pg').val();
    var regno = $('#regno_pg').val();
    var punti = $('#punti_pg').val();
    var nota = $('#nota').val();
    var razza = $('#razza_pg').val();
    var notifica = lanciaNotifica("Invio informazioni...", "info");
    var id_personaggio = $('#id_pg').attr("pg");
    var opzioni = {
        type: "POST",
        beforeSend: function(xhr) {
            $('#pulsante_salva_master').attr("disabled", "disabled");
        },
        url: "scripts/personaggio/salva_master.php",
        data: {
            id: id_personaggio,
            nome: nome,
            regno: regno,
            punti: punti,
            razza: razza,
            nota: nota
        },
        success: function(messaggio) {
            lanciaNotifica(messaggio, "success");
            $("#pulsante_salva_master").removeAttr("disabled");
        },
        error: function(XMLHTTP) {
            lanciaNotifica(XMLHTTP.responseText, "error");
            $("#pulsante_salva_master").removeAttr("disabled");
        }
    };
    $.ajax(opzioni);
}