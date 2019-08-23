$(document).ready(function() {
    function fetchMaintainers() {
        var i=1;
        $.ajax({
            url:"MaintainersData.php",
            method:"GET",
        }).done(function( data ) {
            var maintainerData = $.parseJSON(data);

            if (maintainerData != null) {
                $.each( maintainerData, function( key, value ) {
                    $('#maintainersTable').append(
                        '<tr class="success" id="maintainer' + i + '">' +
                        '<td>' + i + '</td>' +
                        '<td id="username'+i+'">' + value['username'] + '</td>' +
                        '<td>' + value['maintainer_device'] + '</td>' +
                        '<td>' + value['status'] + '</td>' +
                        '<td>' +
                        '<button type="button" id="'+i+'" class="btn btn-primary btn-xs nuke_button">' + 'nuke' + '</button>' +
                        '<button type="button" id="'+i+'" class="btn btn-danger btn-xs revoke_button">' + 'revoke' + '</button>' +
                        '<button type="button" id="'+i+'" class="btn btn-success btn-xs reset_pass">' + 'def passwd' + '</button>' +
                        '</td>' +
                        '</tr>'
                    );
                    if (value['status'].trim() != 'active') {
                        $('#maintainer'+i).addClass('danger').removeClass('success');
                    }
                    i++;
                });
            }
        });
    }

    // nuke maintainer
    $('body').on('click', '.nuke_button', function(){
        var id = $(this).attr("id");
        var username = $('#username'+id).text();
        $.ajax({
            url:"MaintainersFunc.php",
            method:"POST",
            data: {
                nuke_maintainer: 'yes',
                user: username
            },
            success:function(data) {
                $('#maintainersTable').empty();
                fetchMaintainers();

                $('#maintainer_info_msg').text(data);
            }
        });
    });

    // revoke maintainer access
    $('body').on('click', '.revoke_button', function(){
        var id = $(this).attr("id");
        var username = $('#username'+id).text();
        $.ajax({
            url:"MaintainersFunc.php",
            method:"POST",
            data: {
                revoke_maintainer: 'yes',
                user: username
            },
            success:function(data) {
                $('#maintainersTable').empty();
                fetchMaintainers();
                
                $('#maintainer_info_msg').text(data);
            }
        });
    });

    // reset to default password
    $('body').on('click', '.reset_pass', function(){
        var id = $(this).attr("id");
        var username = $('#username'+id).text();
        $.ajax({
            url:"MaintainersFunc.php",
            method:"POST",
            data: {
                reset_pass: 'yes',
                user: username
            },
            success:function(data) {
                $('#maintainer_info_msg').text(data);
            }
        });
    });

    // add new maintainer
    $('body').on('click', '#add_maintainer', function(){
        $.ajax({
            url:"MaintainersFunc.php",
            method:"POST",
            data: {
                add_new_maintainer: 'yes',
                new_maintainer_username: $('#new_maintainer_username').val(),
                new_maintainer_devices: $('#new_maintainer_devices').val()
            },
            success:function(data) {
                $('#MaintainersData').find("input[type=text], textarea").val("");
                $('#maintainersTable').empty();
                fetchMaintainers();
                
                $('#maintainer_info_msg').text(data);
            }
        });
    });

fetchMaintainers();
});