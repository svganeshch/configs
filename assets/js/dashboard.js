$(document).ready(function(){
    $('body').on('click', '#btnArrowPie', function(){
        var version = $(this).val();
        $.post("setVersion.php", {"version": version});
        location.reload();
    });

    $('body').on('click', '#btnArrowQ', function(){
        var version = $(this).val();
        $.post("setVersion.php", {"version": version});
        location.reload();
    });
});