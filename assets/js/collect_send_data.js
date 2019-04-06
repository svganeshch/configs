$(document).ready(function(){
  var i=1;
  var cur_devname = $("#config_dev_name").text();

  $('body').on('click', '#add1', function(){
		i++;
		$('#dynamic_field-1-').append('<li class="list-group-item" style="border:none" id="dynamic_field'+i+'"><div class="row"> <div class="col-md-11 col-xs-11" style="padding-left:0px"> <div class="form-group"> <input type="text" id="text_field'+i+'" name="repo_paths[]" class="form-control" /> </div> </div> <button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove" style="margin-top: 5px;">X</button></div></li>');
		$('#text_field'+i+'').focus();
  });
  $('body').on('click', '#add2', function(){
		i++;
		$('#dynamic_field-2-').append('<li class="list-group-item" style="border:none" id="dynamic_field'+i+'"><div class="row"> <div class="col-md-11 col-xs-11" style="padding-left:0px"> <div class="form-group"> <input type="text" id="text_field'+i+'" name="repo_clones[]" class="form-control" /> </div> </div> <button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove" style="margin-top: 5px;">X</button></div></li>');
		$('#text_field'+i+'').focus();
  });
  $('body').on('click', '#add3', function(){
		i++;
		$('#dynamic_field-3-').append('<li class="list-group-item" style="border:none" id="dynamic_field'+i+'"><div class="row"> <div class="col-md-11 col-xs-11" style="padding-left:0px"> <div class="form-group"> <input type="text" id="text_field'+i+'" name="repopick_topics[]" class="form-control" /> </div> </div> <button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove" style="margin-top: 5px;">X</button></div></li>');
		$('#text_field'+i+'').focus();
  });
  $('body').on('click', '#add4', function(){
		i++;
		$('#dynamic_field-4-').append('<li class="list-group-item" style="border:none" id="dynamic_field'+i+'"><div class="row"> <div class="col-md-11 col-xs-11" style="padding-left:0px"> <div class="form-group"> <input type="text" id="text_field'+i+'" name="repopick_changes[]" class="form-control" /> </div> </div> <button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove" style="margin-top: 5px;">X</button></div></li>');
		$('#text_field'+i+'').focus();
  });

  // Switches
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
			$('#config_dev_name').append('<div class="row"> <div class="col-md-12 col-xs-12"> <div class="form-group"> <input type="text" name="lunch_override_name" class="form-control" /> </div> </div> </div>');
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
		    data:$('#add_name').serialize(),
		    success:function(data)
		    {
			    alert(data);
			    //$('#add_name')[0].reset();
		    }
		});
  });
});