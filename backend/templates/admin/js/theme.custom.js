$(function() {
    $('#preloader').fadeOut('slow', function () {
        $(this).remove();
    });

    $('select.chosen').chosen();

    if (alert_success !== null) {
        new Noty({
            layout: 'topRight',
            theme: 'relax',
            type: 'success',
            text: alert_success,
            timeout: 20000
        }).show();
    }

    if (alert_danger !== null) {
        new Noty({
            layout: 'topRight',
            theme: 'relax',
            type: 'danger',
            text: alert_success,
            timeout: 20000
        }).show();
    }
});