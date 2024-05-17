/* Funzioni client side per modifica impostazioni account.
 * Contiene anche funzioni per la gestione delle notifiche.
 */
var url_pms = $('#ind_pms').val();

$(document).ready(function() {
    //Assegno le azioni ai pulsanti
    $('#pulsante_email').bind('click', function() {
        cambiaEmail();
    });
    $('#pulsante_password').bind('click', function() {
        cambiaPassword();
    });
    //Tooltip del tastino Messaggio master.
    $('[rel=tooltip]').tooltip({
        placement: "bottom"
    });
});

function cambiaEmail() {
    var email = $("#nuova_email").val();
    var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (!filter.test(email)) {
        lanciaNotifica("Formato email non valido", "error");
        return;
    }
    $.ajax({
        type: "POST",
        url: url_pms + "scripts/utente/cambia_email.php",
        data: {
            nuova_email: email,
        },
        beforeSend: function(xhr) {
            $('#pulsante_email').attr("disabled", "disabled");
            lanciaNotifica("Richiesta in corso...", "info");
        },
        success: function(richiesta) {
            lanciaNotifica(richiesta, "success");
            $("#pulsante_email").removeAttr("disabled");
        },
        error: function(XMLHTTP) {
            lanciaNotifica(XMLHTTP.responseText, "error");
            $("#pulsante_email").removeAttr("disabled");
        }
    });
}

function cambiaPassword() {
    var vecchiaPass = $("#vecchia_pwd").val();
    var nuovaPass = $("#nuova_pwd").val();
    var confPass = $("#conferma_pwd").val();
    if ((vecchiaPass == "") || (nuovaPass == "") || (confPass == "")) {
        lanciaNotifica("Compilare tutti i campi.", "error");
        return;
    } else if ((nuovaPass != confPass)) {
        lanciaNotifica("I campi nuova password e conferma password non coincidono.", "error");
        return;
    } else if (nuovaPass == vecchiaPass) {
        lanciaNotifica("La nuova password è uguale alla vecchia.", "error");
        return;
    }
    $.ajax({
        type: "POST",
        url: url_pms + "scripts/utente/cambia_pwd.php",
        data: {
            nuova_pwd: nuovaPass,
            vecchia_pwd: vecchiaPass
        },
        beforeSend: function(xhr) {
            $('#pulsante_password').attr("disabled", "disabled");
            lanciaNotifica("Richiesta in corso...", "info");

        },
        success: function(richiesta) {
            lanciaNotifica(richiesta, "success");
            $("#pulsante_password").removeAttr("disabled");

        },
        error: function(XMLHTTP) {
            lanciaNotifica(XMLHTTP.responseText, "error");
            $("#pulsante_password").removeAttr("disabled");
        }
    });
}

function lanciaNotifica(testo, tipo) {
    new PNotify({
        styling: "bootstrap3",
        text: testo,
        type: tipo
    });
}