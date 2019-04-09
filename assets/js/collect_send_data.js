$(document).ready(function(){
  var i=1;
  var cur_devname = $("#config_dev_name").text();

  $('body').on('click', '#add1', function(){
		i++;
		$('#dynamic_field-1-').append('<li class="list-group-item" style="border:none" id="dynamic_field'+i+'"><div class="row"> <div class="col-md-11 col-xs-11" style="padding-left:0px"> <div class="form-group"> <input autocomplete="on" type="text" id="text_field'+i+'" name="repo_paths[]" class="form-control" /> </div> </div> <button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove" style="margin-top: 5px;">X</button></div></li>');
		$('#text_field'+i+'').focus();
  });
  $('body').on('click', '#add2', function(){
		i++;
		$('#dynamic_field-2-').append('<li class="list-group-item" style="border:none" id="dynamic_field'+i+'"><div class="row"> <div class="col-md-11 col-xs-11" style="padding-left:0px"> <div class="form-group"> <input placeholder="Enter repo url" autocomplete="on" type="text" id="text_field'+i+'" name="repo_clones[]" class="form-control" /> <br class="custom_br" id="custom_br"/> <input placeholder="Enter clone path for repo" autocomplete="on" type="text" name="repo_clones_paths[]" class="form-control" /> </div> </div> <button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove" style="margin-top: 5px;">X</button></div></li>');
		$('#text_field'+i+'').focus();
  });
  $('body').on('click', '#add3', function(){
		i++;
		$('#dynamic_field-3-').append('<li class="list-group-item" style="border:none" id="dynamic_field'+i+'"><div class="row"> <div class="col-md-11 col-xs-11" style="padding-left:0px"> <div class="form-group"> <input autocomplete="on" type="text" id="text_field'+i+'" name="repopick_topics[]" class="form-control" /> </div> </div> <button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove" style="margin-top: 5px;">X</button></div></li>');
		$('#text_field'+i+'').focus();
  });
  $('body').on('click', '#add4', function(){
		i++;
		$('#dynamic_field-4-').append('<li class="list-group-item" style="border:none" id="dynamic_field'+i+'"><div class="row"> <div class="col-md-11 col-xs-11" style="padding-left:0px"> <div class="form-group"> <input autocomplete="on" type="text" id="text_field'+i+'" name="repopick_changes[]" class="form-control" /> </div> </div> <button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove" style="margin-top: 5px;">X</button></div></li>');
		$('#text_field'+i+'').focus();
  });

  // Switches
  $('#global_override').bootstrapToggle({
		on: 'Yes',
		off: 'No',
		onstyle: 'success',
		offstyle: 'danger'
  });
  $('body').on('change', '#global_override', function(){
		if ($(this).prop('checked')) {
			$('#hidden_global_override').val('yes');
			$('#switch-block').show();
		} else {
			$('#hidden_global_override').val('no');
			$('#switch-block').hide();
		}
  });

  $('#is_official').bootstrapToggle({
		on: 'Yes',
		off: 'No',
		onstyle: 'success',
		offstyle: 'danger'
  });
  $('body').on('change', '#is_official', function(){
		if ($(this).prop('checked')) {
			$('#hidden_is_official').val('yes');
		} else {
			$('#hidden_is_official').val('no');
		}
  });

  $('#test_build').bootstrapToggle({
		on: 'Yes',
		off: 'No',
		onstyle: 'success',
		offstyle: 'danger'
  });
  $('body').on('change', '#test_build', function(){
		if ($(this).prop('checked')) {
			$('#hidden_test_build').val('yes');
		} else {
			$('#hidden_test_build').val('no');
		}
  });

  $('#force_clean').bootstrapToggle({
    on: 'Yes',
    off: 'No',
    onstyle: 'success',
    offstyle: 'danger'
  });
  $('body').on('change', '#force_clean', function(){
		if ($(this).prop('checked')) {
			$('#hidden_force_clean').val('yes');
		} else {
			$('#hidden_force_clean').val('no');
		}
  });

  $('#buildtype').bootstrapToggle({
    on: 'User',
    off: 'Eng',
    onstyle: 'success',
    offstyle: 'danger'
  });
  $('body').on('change', '#buildtype', function(){
		if ($(this).prop('checked')) {
			$('#hidden_buildtype').val('user');
		} else {
			$('#hidden_buildtype').val('eng');
		}
  });

  $('#bootimage').bootstrapToggle({
    on: 'Yes',
    off: 'No',
    onstyle: 'success',
    offstyle: 'danger'
  });
  $('body').on('change', '#bootimage', function(){
		if ($(this).prop('checked')) {
			$('#hidden_bootimage').val('yes');
		} else {
			$('#hidden_bootimage').val('no');
		}
  });

  $('#override_lunch').bootstrapToggle({
    on: 'Yes',
    off: 'No',
    onstyle: 'success',
    offstyle: 'danger'
  });
  $('body').on('change', '#override_lunch', function(){
		if ($(this).prop('checked')) {
			$('#hidden_override_lunch').val('yes');
			$('#config_dev_name').text("Enter lunch name of the device to override:");
			$('#config_dev_name').append('<div class="row"> <div class="col-md-12 col-xs-12"> <div class="form-group"> <input autocomplete="on" type="text" name="lunch_override_name" id="lunch_override_name" class="form-control" /> </div> </div> </div>');
			$('#lunch_override_name').focus();
		} else {
			$('#hidden_override_lunch').val('no');
			$('#config_dev_name').text(cur_devname);
		}
  });

  $(document).on('click', '.btn_remove', function(){
	 var button_id = $(this).attr("id"); 
	 $('#dynamic_field'+button_id+'').remove();
  });
  
  $('#submit').click(function(){ 
		$.ajax({
		    url:"send_data.php",
		    method:"POST",
		    data:$('#device_changes').serialize(),
		    success:function(data)
		    {
			    alert(data);
			    //$('#add_name')[0].reset();
		    }
		});
  });
  $( "#global_override" ).trigger( "change" );

  $('body').on('click', '#reset-hard', function(){
  	//set defaults
	$('#is_official').prop('checked', true);
	$('#test_build').prop('checked', false);
	$('#force_clean').prop('checked', false);
	$('#buildtype').prop('checked', true);
	$('#bootimage').prop('checked', false);

	// trigger defaults
	$("#is_official").trigger("change");
	$("#test_build").trigger("change");
	$("#force_clean").trigger("change");
	$("#buildtype").trigger("change");
	$("#bootimage").trigger("change");
  });
});