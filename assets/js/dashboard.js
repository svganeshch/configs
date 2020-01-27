$(document).ready(function(){
    $('body').on('click', '#btnArrowPie', function(){
        var version = $(this).val();
        $.post("setVersion.php", {"version": version});
        window.location.href = window.location.href;
    });

    $('body').on('click', '#btnArrowQ', function(){
        var version = $(this).val();
        $.post("setVersion.php", {"version": version});
        window.location.href = window.location.href;
    });
});