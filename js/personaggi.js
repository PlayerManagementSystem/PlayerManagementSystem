/*
 * Funzioni client side per la sezione Gestione Personaggi nel pannello master.
 */
$(document).ready(function() {
    //Assegno le azioni ai pulsanti
    $('#pulsante_nuovo').bind('click', function() {
        creaNuovoPersonaggio();
    });

    $('div .div_personaggio').mouseenter(function() {
        $(this).find('button').removeClass('hidden');
    });

    $('div .div_personaggio').mouseleave(function() {
        $(this).find('button').addClass('hidden');
    });

    $('div .div_personaggio button').on('click', cancella);
});
/*
 * Funzione di creazione nuovo personaggio.
 */
function creaNuovoPersonaggio() {
    var id_proprietario = $('#id_proprietario').val();
    var proprietario = $('#id_proprietario option:selected').text();
    var nome = $('#nome_personaggio').val();
    var punti = $('#punti_personaggio').val();
    var notifica = lanciaNotifica("Caricamento in corso...", "info");
    var opzioni = {
        type: "POST",
        dataType: 'json',
        beforeSend: function(xhr) {
            $('#pulsante_nuovo').attr("disabled", "disabled");
        },
        url: "../scripts/personaggio/crea_nuovo.php",
        data: {
            id_proprietario: id_proprietario,
            nome: nome,
            punti: punti
        },
        success: function(risposta) {
            $('#modale_nuovo_pg').modal('hide');
            lanciaNotifica(risposta.mess, "success");
            costruisciBoxPg(risposta.id, nome, proprietario);
            $("#pulsante_nuovo").removeAttr("disabled");
        },
        error: function(XMLHTTP) {
            lanciaNotifica(XMLHTTP.responseText, "error");
            $("#pulsante_nuovo").removeAttr("disabled");
        }
    };
    $.ajax(opzioni);
}

function cancella() {
    var id = $(this).parent().find('a')[0].href;
    id = id.substr(id.indexOf('=') + 1);
    var r = confirm("Vuoi davvero cancellare questo personaggio?");
    if (r == true) {
        var cont = $(this).parent().parent();
        $.ajax({
            type: "POST",
            url: "../scripts/personaggio/cancella.php",
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

/*
 * Crea il box con le info del nuovo personaggio. Viene invocata quando il server dà l'ok sulla creazione del nuovo pg.
 */
function costruisciBoxPg(id_personaggio, nome_personaggio, proprietario) {
    var markup = '<div class="thumbnail clearfix"><img style="margin-right:10px" class="pull-left span2 clearfix" alt="Avatar di ' + nome_personaggio + '" src="../avatars/thumbnails/thumb_default.jpg"><div class="caption"><h3><a href="../panoramica.php?id='+id_personaggio+'">' + nome_personaggio + '</a></h3><small class="pull-left"><b>Regno: </b>Nessuno</small><br /><small class="pull-left"><b>Proprietario: </b>' + proprietario + '</small><button class="btn btn-danger hidden pull-right">x</button></div></div>';

    var btn = $("#lista_personaggi").append(markup).find('button:last');

    btn.parent().parent().mouseenter(function() {
        btn.removeClass('hidden');
    });

    btn.parent().parent().mouseleave(function() {
        btn.addClass('hidden');
    });

    btn.on('click', cancella);

}