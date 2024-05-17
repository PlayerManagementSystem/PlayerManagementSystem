$(document).ready(function() {
    $('#pulsante_assegna').on('click', assegna);
    $('#abilityTable td button').on('click', remove);
    $.fn.editable.defaults.mode = 'inline';
    $('#abilityTable').tablesorter();
    $('#abilityTable th').on('click', function() {
        var cl = $(this).attr('class');
        var i = $(this).find('i');

        $('#abilityTable th i').attr('class', 'icon-resize-vertical');
        if (cl.indexOf('headerSortDown') != -1) {
            i.attr('class', 'icon-chevron-down');
        } else if (cl.indexOf('headerSortUp') != -1) {
            i.attr('class', 'icon-chevron-up');
        } else if (cl === 'header') {
            i.attr('class', 'icon-chevron-up');
        }
    });
});

function makeRow(id_abilita, nome_abilita, costo, note_abilita) {
    $('#abilityTable tr:last').after('<tr id=' + id_abilita + '><td>' + nome_abilita + '</td><td>' + costo + '</td><td>' + note_abilita + '</td><td><button class=\'btn btn-small btn-danger\'>x</button></td></tr>');
    $('#abilityTable td button').on('click', remove);
}

function remove() {
    var row = $(this).parent().parent();
    var id_abilita = row.attr('id');
    var id_personaggio = $('#id_pg').attr("pg");
    var ab = row.find('td')[0].textContent;
    var costo = row.find('td')[1].textContent;
    if (confirm("Sicuro di voler rimuovere l'abilità: '" + $.trim(ab) + "' ?")) {
        $.ajax({
            type: "POST",
            url: "scripts/personaggio/rimuovi_abilita.php",
            data: {
                id_ab: id_abilita,
                id_pg: id_personaggio
            },
            beforeSend: function(xhr) {
                lanciaNotifica("Rimozione in corso...", "info");
            },
            success: function(risposta) {
                lanciaNotifica(risposta, "success");
                var punti_spesi = document.getElementById("punti_spesi");
                var somma = parseFloat(punti_spesi.innerHTML) - costo;
                punti_spesi.innerHTML = somma.toFixed(1);
                row.remove();
            },
            error: function(XMLHTTP) {
                lanciaNotifica(XMLHTTP.responseText, "error");
            }
        });
    }
}

;

function assegna() {
    var id_personaggio = $('#id_pg').attr("pg");
    var id_abilita = $('#lista_abilita').val();
    var notifica = lanciaNotifica("Assegnazione in corso...", "info");
    var opzioni = {
        type: "POST",
        dataType: 'json',
        beforeSend: function(xhr) {
            $('#pulsante_nuovo').attr("disabled", "disabled");
        },
        url: "scripts/personaggio/assegna_abilita.php",
        data: {
            id_pg: id_personaggio,
            id_ab: id_abilita,
        },
        success: function(risposta) {
            lanciaNotifica(risposta.mess, "success");
            var punti_spesi = document.getElementById("punti_spesi");
            var costo_abilita = parseFloat(risposta.costo);
            var somma = parseFloat(punti_spesi.innerHTML) + costo_abilita;
            punti_spesi.innerHTML = somma.toFixed(1);
            makeRow(id_abilita, risposta.nome, costo_abilita, risposta.note);
        },
        error: function(XMLHTTP) {
            lanciaNotifica(XMLHTTP.responseText, "error");
        },
        complete: function() {
            $("#pulsante_nuovo").removeAttr("disabled");
        }
    };
    $.ajax(opzioni);
}
