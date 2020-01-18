$(document).ready(function() {
    function fetchDevOpts(devices) {
        var dev_opts_data;
        $.ajax({
            async: false,
            url:"MaintainersFunc.php",
            method:"POST",
            data: {
                fetch_devopts: 'yes',
                got_devices: devices
            },
            success:function(data) {
                dev_opts_data = $.parseJSON(data);
            }
        });
        return dev_opts_data;
    }

    function fetchMaintainers() {
        var i=1;
        var j=1;
        var opts;
        var opts_td;
        $.ajax({
            url:"MaintainersData.php",
            method:"GET",
        }).done(function( data ) {
            var maintainerData = $.parseJSON(data);

            if (maintainerData != null) {
                $.each( maintainerData, function( key, value ) {
                    $('#maintainersTable').append(
                        '<tr class="success clickable" id="maintainer' + i + '" data-toggle="collapse" data-target=".maintainer' + i + '">' +
                            '<td><i class="glyphicon glyphicon-chevron-down"></i></td>' +
                            '<td>' + i + '</td>' +
                            '<td id="username'+i+'">' + value['username'] + '</td>' +
                            '<td id="main_devs'+i+'">' + value['maintainer_device'] + '</td>' +
                            '<td>' + value['status'] + '</td>' +
                        '</tr>' +
                        '<tr class="collapse maintainer' + i + '" id="collapse_maintainer' + i + '">' +
                            '<td><button type="button" id="'+i+'" class="btn btn-primary btn-xs nuke_button">' + 'nuke' + '</button></td>' +
                            '<td><button type="button" id="'+i+'" class="btn btn-danger btn-xs revoke_button">' + 'revoke' + '</button></td>' +
                            '<td><button type="button" id="'+i+'" class="btn btn-success btn-xs reset_pass">' + 'def passwd' + '</button></td>' +
                        '</tr>'
                    );
                    if (value['status'].trim() != 'active') {
                        $('#maintainer'+i).addClass('danger').removeClass('success');
                        $('button#'+i+'.revoke_button').text('un-revoke');
                    }
                    if (value['is_admin'].trim() == '1') {
                        $('#maintainer'+i).addClass('info').removeClass('success');
                        $('button#'+i+'.nuke_button').remove();
                    }

                    var main_devs = value['maintainer_device'].split(" ");
                    var main_devs_td = ' ';
                    $.each(main_devs, function(key, value) {
                        main_devs_td = main_devs_td + '<li><button type="button" id="'+j+'" class="btn btn-warning btn-xs reset_opts">' + value + '</button></li>';
                        j++;
                    });

                    $('tr#collapse_maintainer' + i + '').append('<td><div class="dropdown">'+
                        '<button class="btn btn-block btn-warning btn-xs dropdown-toggle" type="button" data-toggle="dropdown">'+
                        'reset opts <span class="caret"></span>'+
                        '</button>'+
                        '<ul class="dropdown-menu dropdown-menu-center">'+
                        main_devs_td +
                        '</ul>' +
                        '</div>' +
                        '</td>');
        
                    opts = fetchDevOpts(value['maintainer_device']);
                    opts_td = ' ';
                    if (opts != null) {
                        $.each(opts, function(key, value) {
                            opts_td = opts_td + '<span class="badge badge-light">' +
                                                value +
                                                '</span><span class="sr-only">unread messages</span>' + ' ';
                        });
                    }
                    $('tr#maintainer' + i + '').append('<td>' + opts_td + '</td>');
                    i++;
                });
            }
        });
    }

    // reset opts
    $('body').on('click', '.reset_opts', function(){
        var id = $(this).attr("id");
        var main_device = $('button#'+id+'.reset_opts').text();
        $.ajax({
            url:"MaintainersFunc.php",
            method:"POST",
            data: {
                reset_opts: 'yes',
                main_device: main_device
            },
            success:function(data) {
                $('#maintainersTable').empty();
                fetchMaintainers();

                $('#maintainer_info_msg').text(data);
            }
        });
    });

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