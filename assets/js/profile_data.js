$(document).ready(function(){
    var username = $('#username').val();
	$('#profile_username').val(username);

    $('body').on('click', '#update', function(){
	var formdata = $('#profileData').serialize();
		$.ajax({
		    url:"UpdateProfile.php",
			method:"POST",
		    data:formdata,
		    success:function(data)
		    {
				//alert(data);
				$('#profile_update_msg').text(data);
		    }
		});
    });
});