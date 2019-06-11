$(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip();
  var a=0,b=0,c=0,d=0;
	var cur_devname = $("#config_dev_name").text();
	var lunch_override_name = '';
	var initial_override_show_done = 'no';
	
	function cur_devdata (which_dev) {
		$.ajax({
			url:"get_data.php",
			method:"GET",
		}).done(function( data ) {
				var result = $.parseJSON(data);
				var j =0;

				if (result != null) {
					$.each( result, function( key, value ) {

						if (which_dev == 'cur_dev') {
							// set switch states
							lunch_override_name = value['lunch_override_name'];
							if (initial_override_show_done == 'no')
								$('#override_lunch').bootstrapToggle((value['lunch_override_state'] == 'yes') ? 'on' : 'off');
							$('#global_override').bootstrapToggle((value['global_override'] == 'yes') ? 'on' : 'off');
							$('#is_official').bootstrapToggle((value['is_official'] == 'yes') ? 'on' : 'off');
							$('#test_build').bootstrapToggle((value['test_build'] == 'yes') ? 'on' : 'off');
							$('#force_clean').bootstrapToggle((value['force_clean'] == 'yes') ? 'on' : 'off');
							$('#buildtype').bootstrapToggle((value['buildtype'] == 'user') ? 'on' : 'off');
							$('#bootimage').bootstrapToggle((value['bootimage'] == 'yes') ? 'on' : 'off');

							// text fields
							var repo_paths = $.parseJSON(value['repo_paths']);
							var repo_clones = $.parseJSON(value['repo_clones']);
							var repo_clones_paths = $.parseJSON(value['repo_clones_paths']);
							var repopick_topics = $.parseJSON(value['repopick_topics']);
							var repopick_changes = $.parseJSON(value['repopick_changes']);
							var changelog = value['changelog'];
						} 
						else if (which_dev == 'ovr_dev') {
							lunch_override_name = value['lunch_override_name'];
							$('#global_override').bootstrapToggle((value['global_override'] == 'yes') ? 'on' : 'off');
							$('#is_official').bootstrapToggle((value['ovr_is_official'] == 'yes') ? 'on' : 'off');
							$('#test_build').bootstrapToggle((value['ovr_test_build'] == 'yes') ? 'on' : 'off');
							$('#force_clean').bootstrapToggle((value['ovr_force_clean'] == 'yes') ? 'on' : 'off');
							$('#buildtype').bootstrapToggle((value['ovr_buildtype'] == 'user') ? 'on' : 'off');
							$('#bootimage').bootstrapToggle((value['ovr_bootimage'] == 'yes') ? 'on' : 'off');

							// text fields
							var repo_paths = $.parseJSON(value['ovr_repo_paths']);
							var repo_clones = $.parseJSON(value['ovr_repo_clones']);
							var repo_clones_paths = $.parseJSON(value['ovr_repo_clones_paths']);
							var repopick_topics = $.parseJSON(value['ovr_repopick_topics']);
							var repopick_changes = $.parseJSON(value['ovr_repopick_changes']);
							var changelog = value['ovr_changelog'];
						}

						// repo paths to delete json data
						if ( repo_paths != null && repo_paths != 'null' && repo_paths != 'NULL') {
							$.each( repo_paths, function( key, value) {
								if ( value != null && value != 'null' && value != 'NULL') {
									for (j=0; j <= value.length-1; j++) {
										if (j == 0) {
											$("#int_repo_paths_text_field").val(value[j]);
										}

										if (j != 0) {
											if ($('#' + 'repo_paths_text_field'+j+'').length == 0)
												$( "#add1" ).trigger( "click" );
											$('#repo_paths_text_field'+a+'').val(value[j]);
										}
									}
								} else {
									$("#int_repo_paths_text_field").val('');
									$("#int_repo_paths_text_field").closest('ul')
									.find("input[id^=repo_paths_text_field], textarea").closest('li')
									.remove();
								}
							});
						}

						// repos to clone json data
						if ( repo_clones != null && repo_clones != 'null' && repo_clones != 'NULL') {
							$.each( repo_clones, function( key, value) {
								if ( value != null && value != 'null' && value != 'NULL') {
									for (j=0; j <= value.length-1; j++) {
										if (j == 0) {
											$("#int_repo_clones_text_field").val(value[j]);
										}

										if (j != 0) {
											if ($('#' + 'repo_clones_text_field'+j+'').length == 0)
												$( "#add2" ).trigger( "click" );
											$('#repo_clones_text_field'+b+'').val(value[j]);
										}
									}
								} else {
									$("#int_repo_clones_text_field").val('');
									$("#int_repo_clones_text_field").closest('ul')
									.find("input[id^=repo_clones_text_field], textarea").closest('li')
									.remove();
								}
							});
						}

						// repo clone paths json data
						if ( repo_clones_paths != null && repo_clones_paths != 'null' && repo_clones_paths != 'NULL') {
							$.each( repo_clones_paths, function( key, value) {
								if ( value != null && value != 'null' && value != 'NULL') {
									for (j=0; j <= value.length-1; j++) {
										if (j == 0) {
											$("#int_repo_clones_paths_text_field").val(value[j]);
										}

										if (j != 0) {
											$('#repo_clones_paths_text_field'+b+'').val(value[j]);
										}
									}
								} else {
									$("#int_repo_clones_paths_text_field").val('');
									$("#int_repo_clones_paths_text_field").closest('ul')
									.find("input[id^=repo_clones_paths_text_field], textarea").closest('li')
									.remove();
								}
							});
						}

						// repopick topics json data
						if ( repopick_topics != null && repopick_topics != 'null' && repopick_topics != 'NULL') {
							$.each( repopick_topics, function( key, value) {
								if ( value != null && value != 'null' && value != 'NULL') {
									for (j=0; j <= value.length-1; j++) {
										if (j == 0) {
											$("#int_repopick_topics_text_field").val(value[j]);
										}

										if (j != 0) {
											if ($('#' + 'repopick_topics_text_field'+j+'').length == 0)
												$( "#add3" ).trigger( "click" );
											$('#repopick_topics_text_field'+c+'').val(value[j]);
										}
									}
								} else {
									$("#int_repopick_topics_text_field").val('');
									$("#int_repopick_topics_text_field").closest('ul')
									.find("input[id^=repopick_topics_text_field], textarea").closest('li')
									.remove();
								}
							});
						}

						// repopick changes json data
						if ( repopick_changes != null && repopick_changes != 'null' && repopick_changes != 'NULL') {
							$.each( repopick_changes, function( key, value) {
								if ( value != null && value != 'null' && value != 'NULL') {
									for (j=0; j <= value.length-1; j++) {
										if (j == 0) {
											$("#int_repopick_changes_text_field").val(value[j]);
										}

										if (j != 0) {
											if ($('#' + 'repopick_changes_text_field'+j+'').length == 0)
												$( "#add4" ).trigger( "click" );
											$('#repopick_changes_text_field'+d+'').val(value[j]);
										}
									}
								} else {
									$("#int_repopick_changes_text_field").val('');
									$("#int_repopick_changes_text_field").closest('ul')
									.find("input[id^=repopick_changes_text_field], textarea").closest('li')
									.remove();
								}
							});
						}

						if (changelog != null && value != 'null' && value != 'NULL') {
							$('#changelog').val(changelog);
						} else {
							$('#changelog').val('');
						}

					});
				}
		});
	}

	// Initially fetch the current device data
	cur_devdata('cur_dev');

  $('body').on('click', '#add1', function(){
		a++;
		$('#dynamic_field-1-').append('<li class="list-group-item" style="border:none" id="dynamic_field-repo_paths'+a+'"><div class="row"> <div class="col-md-11 col-xs-11" style="padding-left:0px"> <div class="form-group"> <input autocomplete="on" type="text" id="repo_paths_text_field'+a+'" name="repo_paths[]" class="form-control" /> </div> </div> <button type="button" name="remove" id="repo_paths'+a+'" class="btn btn-danger btn_remove" style="margin-top: 5px;">X</button></div></li>');
		$('#repo_paths_text_field'+a+'').focus();
  });
  $('body').on('click', '#add2', function(){
		b++;
		$('#dynamic_field-2-').append('<li class="list-group-item" style="border:none" id="dynamic_field-repo_clones'+b+'"><div class="row"> <div class="col-md-11 col-xs-11" style="padding-left:0px"> <div class="form-group"> <input placeholder="Enter repo url" autocomplete="on" type="text" id="repo_clones_text_field'+b+'" name="repo_clones[]" class="form-control" /> <br class="custom_br" id="custom_br"/> <input placeholder="Enter clone path for repo" autocomplete="on" type="text" id="repo_clones_paths_text_field'+b+'" name="repo_clones_paths[]" class="form-control" /> </div> </div> <button type="button" name="remove" id="repo_clones'+b+'" class="btn btn-danger btn_remove" style="margin-top: 5px;">X</button></div></li>');
		$('#repo_clones_text_field'+b+'').focus();
  });
  $('body').on('click', '#add3', function(){
		c++;
		$('#dynamic_field-3-').append('<li class="list-group-item" style="border:none" id="dynamic_field-repopick_topics'+c+'"><div class="row"> <div class="col-md-11 col-xs-11" style="padding-left:0px"> <div class="form-group"> <input autocomplete="on" type="text" id="repopick_topics_text_field'+c+'" name="repopick_topics[]" class="form-control" /> </div> </div> <button type="button" name="remove" id="repopick_topics'+c+'" class="btn btn-danger btn_remove" style="margin-top: 5px;">X</button></div></li>');
		$('#repopick_topics_text_field'+c+'').focus();
  });
  $('body').on('click', '#add4', function(){
		d++;
		$('#dynamic_field-4-').append('<li class="list-group-item" style="border:none" id="dynamic_field-repopick_changes'+d+'"><div class="row"> <div class="col-md-11 col-xs-11" style="padding-left:0px"> <div class="form-group"> <input autocomplete="on" type="text" id="repopick_changes_text_field'+d+'" name="repopick_changes[]" class="form-control" /> </div> </div> <button type="button" name="remove" id="repopick_changes'+d+'" class="btn btn-danger btn_remove" style="margin-top: 5px;">X</button></div></li>');
		$('#repopick_changes_text_field'+d+'').focus();
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
    off: 'Userdebug',
    onstyle: 'success',
    offstyle: 'danger'
  });
  $('body').on('change', '#buildtype', function(){
		if ($(this).prop('checked')) {
			$('#hidden_buildtype').val('user');
		} else {
			$('#hidden_buildtype').val('userdebug');
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
			$('#info-con-rem').remove();
			$('#config_dev_name').text("Enter lunch name of the device to override:");
			$('#config_dev_name').append('<div class="row"> <div class="col-md-12 col-xs-12"> <div class="form-group"> <input autocomplete="on" type="text" name="lunch_override_name" id="lunch_override_name" class="form-control" /> </div> </div> </div>');
			$('#lunch_override_name').focus();
			if (lunch_override_name != null && lunch_override_name != 'null' && lunch_override_name != 'NULL' && lunch_override_name != '') {
				$('#lunch_override_name').val(lunch_override_name);
				initial_override_show_done = 'yes';
				cur_devdata('ovr_dev');
			}
		} else {
			$('#hidden_override_lunch').val('no');
			$('#config_dev_name').text(cur_devname);
			initial_override_show_done = 'yes';
			if (lunch_override_name != null && lunch_override_name != 'null' && lunch_override_name != 'NULL' && lunch_override_name != '')
				$('#info-con').append('<i data-toggle="tooltip" class="fa fa-info-circle fa-lg" id="info-con-rem" title="Override data present for '+lunch_override_name+'"></i>');
			cur_devdata('cur_dev');
		}
  });

  $(document).on('click', '.btn_remove', function(){
	 var button_id = $(this).attr("id"); 
	 $('#dynamic_field-'+button_id+'').remove();
  });
  
  $('body').on('click', '#submit', function(){
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

  $('body').on('click', '#reset-hard', function(){
  	//set defaults
		$('#is_official').bootstrapToggle('on');
		$('#test_build').bootstrapToggle('off');
		$('#force_clean').bootstrapToggle('off');
		$('#buildtype').bootstrapToggle('on');
		$('#bootimage').bootstrapToggle('off');
  });
});