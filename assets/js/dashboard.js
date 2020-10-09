$(document).ready(function() {
    setTimeout(function() {
        $("#dashboard-nav[name=main]").trigger('click');
    }, 10);
    var version = localStorage.version;
    $.post("/helpers/setVersion.php", { "version": version });
    $('body').on('click', '#versionbutton', function() {
        var version = $(this).val();
        localStorage.setItem('version', version);
        $.post("/helpers/setVersion.php", { "version": version });
        window.location.href = window.location.href;
    });

    $('body').on('click', '#dashboard-nav', function() {
        var selectedPage = $(this).attr('name');
        $('#dashboard-content').load('/dashboard-content/dashboard-' + selectedPage + '.php');
    })
});