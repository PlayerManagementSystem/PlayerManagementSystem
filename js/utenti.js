/*
 * Funzioni client side per la sezione Gestione Utenti nel pannello master.
 */
$(document).ready(function() {
    //Assegno le azioni ai pulsanti
    $('#pulsante_nuovo').bind('click', function() {
        creaNuovoUtente();
    });

    $('div .div_utente').mouseenter(function() {
        $(this).find('button').removeClass('hidden');
    });

    $('div .div_utente').mouseleave(function() {
        $(this).find('button').addClass('hidden');
    });

    $('div .div_utente button').on('click', cancella);
});
/*
 * Funzione di creazione nuovo utente.
 */
function creaNuovoUtente() {
    var username = $('#username_utente').val();
    var email = $('#email_utente').val();
    var email_conf = $('#email_utente_conf').val();
    var password = $('#password_utente').val();
    var password_conf = $('#password_utente_conf').val();
    var master = $('#master').val();
    var invia = 0;
    if ($('#invia').is(':checked')) {
        invia = 1;
    }
    if ((username == "") || (email == "") || (email_conf == "") || (password == "") || (password_conf == "")) {
        lanciaNotifica("Compila tutti i campi!", "error");
        return;
    }
    var pattern = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    if (!pattern.test(email)) {
        lanciaNotifica("Formato mail non valido", "error");
        return;
    }
    if (email != email_conf) {
        lanciaNotifica("Mail e conferma mail non corrispondono", "error");
        return;
    }
    if (password != password_conf) {
        lanciaNotifica("Password e conferma password non corrispondono", "error");
        return;
    }
    var notifica = lanciaNotifica("Creazione in corso...", "info");
    var opzioni = {
        type: "POST",
        dataType: 'json',
        beforeSend: function(xhr) {
            $('#pulsante_nuovo').attr("disabled", "disabled");
        },
        url: "../scripts/utente/crea_nuovo.php",
        data: {
            username: username,
            email: email,
            password: password,
            master: master,
            invia: invia
        },
        success: function(risposta) {
            $('#modale_nuovo_utente').modal('hide');
            lanciaNotifica(risposta.mess, "success");
            setTimeout("location.reload(true);", 2000);
        },
        error: function(XMLHTTP) {
            lanciaNotifica(XMLHTTP.responseText, "error");
            $("#pulsante_nuovo").removeAttr("disabled");
        }
    };
    $.ajax(opzioni);
}

function cancella() {
    var id = $(this).parent().find('small:first').text();
    id = id.substr(id.indexOf(': ') + 1);
    var r = confirm("Vuoi davvero cancellare questo utente?");
    if (r == true) {
        var cont = $(this).parent().parent();
        $.ajax({
            type: "POST",
            url: "../scripts/utente/cancella.php",
            data: {
                id: id
            },
            beforeSend: function(xhr) {
                lanciaNotifica("Cancellazione in corso...", "info");
            },
            success: function(risposta) {
                lanciaNotifica(risposta, "success");
                cont.remove();
            },
            error: function(XMLHTTP) {
                lanciaNotifica(XMLHTTP.responseText, "error");
            }
        });
    }
}