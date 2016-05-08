$(document).ready(function() {
    $('#sessionTable').tablesorter();
    $('#sessionTable th').on('click', function() {
        var cl = $(this).attr('class');
        var i = $(this).find('i');

        $('#sessionTable th i').attr('class', 'icon-resize-vertical');
        if (cl.indexOf('headerSortDown') != -1) {
            i.attr('class', 'icon-chevron-down');
        } else if (cl.indexOf('headerSortUp') != -1) {
            i.attr('class', 'icon-chevron-up');
        } else if (cl === 'header') {
            i.attr('class', 'icon-chevron-up');
        }
    });
});