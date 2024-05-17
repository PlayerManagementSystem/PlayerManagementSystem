$(document).ready(function() {
    $('#tasto_aggiungi').bind('click', function() {
        creaAbilita();
    });

    $('#abilityTable td button').on('click', remove);

    $.fn.editable.defaults.mode = 'inline';
    $.fn.editable.defaults.error = function(data) {
        lanciaNotifica(data.responseText, "error");
    }
    $.fn.editable.defaults.success = function(data) {
        lanciaNotifica("Modifica effettuata.", "success");
    }
    $('td > span').editable();
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

function makeRow(id, nome, desc, costo, note) {
    $('#abilityTable tr:first').after('<tr data-pk="' + id + '"><td><span data-name="nome" data-pk="' + id + '" data-url="../scripts/abilita/modifica.php" data-type="text">' + nome + '</span></td><td><span data-name="descrizione" data-pk="' + id + '" data-url="../scripts/abilita/modifica.php" data-type="text">' + desc + '</span></td><td><span data-name="costo" data-pk="' + id + '" data-url="../scripts/abilita/modifica.php" data-type="text">' + costo + '</span></td><td><span data-name="note" data-pk="' + id + '" data-url="../scripts/abilita/modifica.php" data-type="text">' + note + '</span></td><td><button class=\'btn btn-small btn-danger\'>x</button></td></tr>');
    $('td > span').editable();
    $('#abilityTable td button').on('click', remove);
}

function creaAbilita() {
    var nome = document.getElementById("nome_abilita").value;
    var descrizione = document.getElementById("desc_abilita").value;
    var costo = document.getElementById("costo_abilita").value;
    var note = document.getElementById("note_abilita").value;
    $.ajax({
        type: "POST",
        url: "../scripts/abilita/aggiungi.php",
        dataType: 'json',
        data: {
            nome: nome,
            desc: descrizione,
            costo: costo,
            note: note
        },
        beforeSend: function(xhr) {
            lanciaNotifica("Creazione in corso...", "info");
        },
        success: function(risposta) {
            lanciaNotifica(risposta.mess, "success");
            $('#modale_nuova_ab').modal('hide');
            makeRow(risposta.id, nome, descrizione, costo, note);
        },
        error: function(XMLHTTP) {
            lanciaNotifica(XMLHTTP.responseText, "error");
        }
    });
}

function remove() {
    var row = $(this).parent().parent();

    var id = row.attr("data-pk");
    var ab = row.find('span')[0].textContent;
    if (confirm("Sicuro di voler cancellare l'abilità: '" + $.trim(ab) + "' ?")) {
        $.ajax({
            type: "POST",
            url: "../scripts/abilita/cancella.php",
            data: {
                id: id
            },
            beforeSend: function(xhr) {
                lanciaNotifica("Cancellazione in corso...", "info");
            },
            success: function(risposta) {
                lanciaNotifica(risposta, "success");
                row.remove();
            },
            error: function(XMLHTTP) {
                lanciaNotifica(XMLHTTP.responseText, "error");
            }
        });
    }
}