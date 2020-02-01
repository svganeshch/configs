$(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip();
  var a=0,b=0,c=0,d=0;

	//fields data holders
	var repo_paths;
	var repo_clones;
	var repo_clones_paths;
	var repopick_topics;
	var repopick_changes;
	var changelog;
	var xda_link;
	var is_fields_ok = false;
	
	$.ajax({
		url:"get_data.php",
		method:"GET",
	}).done(function( data ) {
		var result = $.parseJSON(data);
		var j =0;

		if (result != null) {
			$.each( result, function( key, value ) {

				$('#global_override').bootstrapToggle((value['global_override'] == 'yes') ? 'on' : 'off');
				$('#is_official').bootstrapToggle((value['is_official'] == 'yes') ? 'on' : 'off');
				$('#test_build').bootstrapToggle((value['test_build'] == 'yes') ? 'on' : 'off');
				$('#force_clean').bootstrapToggle((value['force_clean'] == 'yes') ? 'on' : 'off');
				$('#default_buildtype_state').bootstrapToggle((value['default_buildtype_state'] == 'yes') ? 'on' : 'off');
				$('#buildtype').val(value['buildtype']);
				$('#bootimage').bootstrapToggle((value['bootimage'] == 'yes') ? 'on' : 'off');
				$('#weeklies_opt').bootstrapToggle((value['weeklies_opt'] == 'yes') ? 'on' : 'off');

				// text fields
				repo_paths = $.parseJSON(value['repo_paths']);
				repo_clones = $.parseJSON(value['repo_clones']);
				repo_clones_paths = $.parseJSON(value['repo_clones_paths']);
				repopick_topics = $.parseJSON(value['repopick_topics']);
				repopick_changes = $.parseJSON(value['repopick_changes']);
				changelog = value['changelog'];
				xda_link = value['xda_link'];
			});
		}

		// repo paths to delete json data
		if ( repo_paths != null && repo_paths != 'null' && repo_paths != 'NULL') {
			$.each( repo_paths, function( key, value) {
				if ( value != null && value != 'null' && value != 'NULL') {
					for (j=0; j <= value.length-1; j++) {
						if (j != 0) {
							if ($('#' + 'repo_paths_text_field'+j+'').length == 0)
								$( "#add1" ).trigger( "click" );
						}
						$('#repo_paths_text_field'+a+'').val(value[j]);
					}
				}
			});
		}

		// repos to clone json data
		if ( repo_clones != null && repo_clones != 'null' && repo_clones != 'NULL') {
			$.each( repo_clones, function( key, value) {
				if ( value != null && value != 'null' && value != 'NULL') {
					for (j=0; j <= value.length-1; j++) {
						if ($('#' + 'repo_clones_text_field'+j+'').length == 0)
							$( "#add2" ).trigger( "click" );

						var clone_url = value[j];
						if (clone_url.includes("-b")) {
							var splitString = clone_url.split('-b');
							$('#repo_clones_text_field'+b+'').val(splitString[0].trim());
							$('#repo_clone_branch_text_field'+b+'').val(splitString[1].trim());
						} else {
							$('#repo_clones_text_field'+b+'').val(clone_url);
						}
					}
				}
			});
		}

		// repo clone paths json data
		if ( repo_clones_paths != null && repo_clones_paths != 'null' && repo_clones_paths != 'NULL') {
			$.each( repo_clones_paths, function( key, value) {
				if ( value != null && value != 'null' && value != 'NULL') {
					for (j=0; j <= value.length-1; j++) {
						b=j;										
						$('#repo_clones_paths_text_field'+b+'').val(value[j]);
					}
				}
			});
		}

		// repopick topics json data
		if ( repopick_topics != null && repopick_topics != 'null' && repopick_topics != 'NULL') {
			$.each( repopick_topics, function( key, value) {
				if ( value != null && value != 'null' && value != 'NULL') {
					for (j=0; j <= value.length-1; j++) {
						if ($('#' + 'repopick_topics_text_field'+j+'').length == 0)
							$( "#add3" ).trigger( "click" );
						$('#repopick_topics_text_field'+c+'').val(value[j]);
					}
				}
			});
		}

		// repopick changes json data
		if ( repopick_changes != null && repopick_changes != 'null' && repopick_changes != 'NULL') {
			$.each( repopick_changes, function( key, value) {
				if ( value != null && value != 'null' && value != 'NULL') {
					for (j=0; j <= value.length-1; j++) {
						if ($('#' + 'repopick_changes_text_field'+j+'').length == 0)
							$( "#add4" ).trigger( "click" );
						$('#repopick_changes_text_field'+d+'').val(value[j]);
					}
				}
			});
		}

		if (xda_link != null && xda_link != 'null' && xda_link != 'NULL') {
			$('#xda_link').val(xda_link);
		} else {
			$('#xda_link').val('');
		}

		if (changelog != null && changelog != 'null' && changelog != 'NULL') {
			$('#changelog').val(changelog);
		} else {
			$('#changelog').val('');
		}
	});

  $('body').on('click', '#add1', function(){
		a++;
		$('#dynamic_field-1-').append(
			'<li class="list-group-item" style="border:none" id="dynamic_field-repo_paths'+a+'">'+
			  '<div class="row">'+
				'<div class="col-md-11 col-xs-11" style="padding-left:0px">'+
				  '<div class="form-group" id="repo_paths_group'+a+'">'+
					'<input autocomplete="on" type="text" id="repo_paths_text_field'+a+'" name="repo_paths[]" class="form-control" />'+
				  '</div>'+
				'</div>'+
				'<button type="button" name="remove" id="repo_paths'+a+'" class="btn btn-danger btn_remove" style="margin-top: 5px;">X</button>'+
			  '</div>'+
			'</li>');
		$('#repo_paths_text_field'+a+'').focus();
  });
  $('body').on('click', '#add2', function(){
		b++;
		$('#dynamic_field-2-').append(
			'<li class="list-group-item" style="border:none" id="dynamic_field-repo_clones'+b+'">'+
			  '<div class="row">'+
			    '<div class="col-md-11 col-xs-11" style="padding-left:0px">'+
				  '<div class="row">'+
					'<div class="col-md-8 col-xs-8">' +
					  '<div class="form-group" id="repo_clones_group'+b+'">' +
					    '<input placeholder="repo url" autocomplete="on" type="text" id="repo_clones_text_field'+b+'" name="repo_clones[]" class="form-control" />'+
					  '</div>' +
					'</div>' +
					'<div class="col-md-4 col-xs-4">'+
					  '<div class="form-group" id="repo_clone_branch_group'+b+'">' +
						'<input placeholder="branch" autocomplete="on" type="text" id="repo_clone_branch_text_field'+b+'" name="repo_clone_branch[]" class="form-control" />'+
					  '</div>'+
					'</div>'+
				  '</div>' +
				  '<br class="custom_br" id="custom_br"/>' +
				  '<div class="form-group" id="repo_clones_paths_group'+b+'">' +
					'<input placeholder="path for repo" autocomplete="on" type="text" id="repo_clones_paths_text_field'+b+'" name="repo_clones_paths[]" class="form-control" />'+
				  '</div>'+
			    '</div>'+
				'<button type="button" name="remove" id="repo_clones'+b+'" class="btn btn-danger btn_remove" style="margin-top: 5px;">X</button>'+
			  '</div>'+
			'</li>');
		$('#repo_clones_text_field'+b+'').focus();
  });
  $('body').on('click', '#add3', function(){
		c++;
		$('#dynamic_field-3-').append(
			'<li class="list-group-item" style="border:none" id="dynamic_field-repopick_topics'+c+'">'+
			  '<div class="row">'+
				'<div class="col-md-11 col-xs-11" style="padding-left:0px">'+
				  '<div class="form-group" id="repopick_topics_group'+c+'">'+
					'<input autocomplete="on" type="text" id="repopick_topics_text_field'+c+'" name="repopick_topics[]" class="form-control" />'+
				  '</div>'+
				'</div>'+
				'<button type="button" name="remove" id="repopick_topics'+c+'" class="btn btn-danger btn_remove" style="margin-top: 5px;">X</button>'+
			  '</div>'+
			'</li>');
		$('#repopick_topics_text_field'+c+'').focus();
  });
  $('body').on('click', '#add4', function(){
		d++;
		$('#dynamic_field-4-').append(
			'<li class="list-group-item" style="border:none" id="dynamic_field-repopick_changes'+d+'">'+
			  '<div class="row">'+
				'<div class="col-md-11 col-xs-11" style="padding-left:0px">'+
				  '<div class="form-group" id="repopick_changes_group'+d+'">'+
					'<input autocomplete="on" type="text" id="repopick_changes_text_field'+d+'" name="repopick_changes[]" class="form-control" />'+
				  '</div>'+
				'</div>'+
				'<button type="button" name="remove" id="repopick_changes'+d+'" class="btn btn-danger btn_remove" style="margin-top: 5px;">X</button>'+
			  '</div>'+
			'</li>');
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
			$('#default_buildtype_state').trigger("change");
			$('#hidden_global_override').val('yes');
			$('#switch-block').show();
		} else {
			$('#default_buildtype_state').trigger("change");
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

  $('#default_buildtype_state').bootstrapToggle({
    on: 'Yes',
    off: 'No',
    onstyle: 'success',
    offstyle: 'danger'
  });
  $('body').on('change', '#default_buildtype_state', function(){
		if ($(this).prop('checked')) {
			$('#hidden_default_buildtype_state').val('yes');
			$('#buildtype_div').hide();
		} else {
			$('#hidden_default_buildtype_state').val('no');
			$('#buildtype_div').show();
		}
  });

  $('body').on('click', '#PipelineBuildTrigger', function(){
	$('#notifyDialog').modal({
		backdrop: "static",
		keyboard: false,
		show: true});
	$('#notifyDialogData').text('Please wait...!')
		$.ajax({
			url:"jenkinsBlueFunc.php",
			method:"POST",
			data: {
				PipelineBuildTrigger: 'yes'
			},
			success:function(data)
			{
				data = data.trim();
				$('#notifyDialogData').text(data);
				setTimeout(function() {
					$("#notifyDialog").modal("hide");
				}, 2000);
			}
		});
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

  $('#weeklies_opt').bootstrapToggle({
    on: 'Opt In',
    off: 'Opt Out',
    onstyle: 'success',
    offstyle: 'danger'
  });
  $('body').on('change', '#weeklies_opt', function(){
		if ($(this).prop('checked')) {
			$('#hidden_weeklies_opt').val('yes');
		} else {
			$('#hidden_weeklies_opt').val('no');
		}
  });

  $(document).on('click', '.btn_remove', function(){
	 var button_id = $(this).attr("id"); 
	 $('#dynamic_field-'+button_id+'').remove();
  });

  function parseValidateData(invalidFieldsData, validateData, KeyValidateData) {
	var field_index = 0;
	var is_data_ok= true;
	$.each( invalidFieldsData, function( key, value ) {
		if (value != null) {
			if (key == KeyValidateData) {
				$.each(validateData, function(form_key, form_data) {
					if (value[field_index] == $('#'+form_data).val()) {
						$('#'+KeyValidateData+'_group'+form_data[form_data.length - 1]).addClass('has-error has-feedback')
							.append('<span class="glyphicon glyphicon-remove form-control-feedback" id="field-error-icon"></span>');
						$('#'+KeyValidateData+'_text_field'+form_data[form_data.length - 1]).focus();
						field_index++;
						is_data_ok = false;
					}
				});
			}
		}
	});
	return is_data_ok;
  }

  function validateFields() {
	  $.ajax({
		  async: false,
		  url:"validateFields.php",
		  method:"POST",
		  data:$('#device_changes').serialize(),
		  success:function(data)
		  {
			var invalid_fields = $.parseJSON(data);
			var repo_paths_data = $("input[name='repo_paths[]']")
				.map(function(){return $(this).attr('id');}).get();
			$('[id^=repo_paths_group]').removeClass('has-error has-feedback');

			var repo_clones_data = $("input[name='repo_clones[]']")
				.map(function(){return $(this).attr('id');}).get();
			$('[id^=repo_clones_group]').removeClass('has-error has-feedback');

			var repo_clone_branch_data = $("input[name='repo_clone_branch[]']")
				.map(function(){return $(this).attr('id');}).get();
			$('[id^=repo_clone_branch_group]').removeClass('has-error has-feedback');

			var repo_clones_paths_data = $("input[name='repo_clones_paths[]']")
				.map(function(){return $(this).attr('id');}).get();
			$('[id^=repo_clones_paths_group]').removeClass('has-error has-feedback');

			var repopick_topics_data = $("input[name='repopick_topics[]']")
				.map(function(){return $(this).attr('id');}).get();
			$('[id^=repopick_topics_group]').removeClass('has-error has-feedback');

			var repopick_changes_data = $("input[name='repopick_changes[]']")
				.map(function(){return $(this).attr('id');}).get();
			$('[id^=repopick_changes_group]').removeClass('has-error has-feedback');

			$('span[id^=field-error-icon]').remove();

			is_rp_ok = parseValidateData(invalid_fields, repo_paths_data, 'repo_paths');
			is_rcd_ok = parseValidateData(invalid_fields, repo_clones_data, 'repo_clones');
			is_rcb_ok = parseValidateData(invalid_fields, repo_clone_branch_data, 'repo_clone_branch');
			is_rcp_ok = parseValidateData(invalid_fields, repo_clones_paths_data, 'repo_clones_paths');
			is_rptp_ok = parseValidateData(invalid_fields, repopick_topics_data, 'repopick_topics');
			is_rpc_ok = parseValidateData(invalid_fields, repopick_changes_data, 'repopick_changes');

			is_fields_ok = is_rp_ok && is_rcd_ok && is_rcb_ok && is_rcp_ok && is_rptp_ok && is_rpc_ok;
		  }
	});
	return is_fields_ok;
  }
  
  $('body').on('click', '#submit', function(){
	$('#notifyDialog').modal({
		backdrop: "static",
		keyboard: false,
		show: true});

	if(!validateFields()) {
		$('#notifyDialogData').text('Invalid data...!!');
		setTimeout(function() {
			$("#notifyDialog").modal("hide");
		}, 800);
		return;
	}

	$('#notifyDialogData').text('Please wait...!');
	$.ajax({
		url:"send_data.php",
		method:"POST",
		data:$('#device_changes').serialize(),
		success:function(data)
		{
			data = data.trim();
			$('#notifyDialogData').text(data);
			setTimeout(function() {
				$("#notifyDialog").modal("hide");
			}, 2000);
		}
	});
  });

  $('body').on('click', '#buildTrigger', function(){
	$('#submit').trigger('click');
	if(!is_fields_ok) return;
	$('#notifyDialog').modal({
		backdrop: "static",
		keyboard: false,
		show: true});
	$('#notifyDialogData').text('Please wait...!')
		$.ajax({
			async: false,
			url:"jenkinsBlueFunc.php",
			method:"POST",
			data: {
				buildTrigger: 'yes'
			},
			success:function(data)
			{
				$('#buildOutput').empty();
				data = data.trim();
				$('#notifyDialogData').text(data);
				setTimeout(function() {
					$("#notifyDialog").modal("hide");
				}, 2000);
			}
		});
  });

  $('body').on('click', '#buildRemoveQueue', function(){
	$('#notifyDialog').modal({
		backdrop: "static",
		keyboard: false,
		show: true});
	$('#notifyDialogData').text('Please wait...!')
		$.ajax({
			url:"jenkinsBlueFunc.php",
			method:"POST",
			data: {
				buildRemoveQueue: 'yes'
			},
			success:function(data)
			{
				data = data.trim();
				$('#notifyDialogData').text(data);
				setTimeout(function() {
					$("#notifyDialog").modal("hide");
				}, 2000);
			}
		});
  });

  $('body').on('click', '#buildStop', function(){
	$('#notifyDialog').modal({
		backdrop: "static",
		keyboard: false,
		show: true});
	$('#notifyDialogData').text('Please wait...!')
		$.ajax({
			url:"jenkinsBlueFunc.php",
			method:"POST",
			data: {
				buildStop: 'yes'
			},
			success:function(data)
			{
				data = data.trim();
				$('#notifyDialogData').text(data);
				setTimeout(function() {
					$("#notifyDialog").modal("hide");
				}, 2000);
			}
		});
  });

  // set build progress
  $('#build-progress-bar').hide();
  function getProgress(){
    $.ajax({
    method:"POST",
		url: "jenkinsBlueFunc.php",
		data: {
			getProgressStatus: 'yes'
		},
        success:function(data)
        {
			data = data.trim();
        	$('.progress-bar').css('width', data+'%').attr('aria-valuenow', data).text(data+'%');
        }
    });
	}
	
	// split textsize
	function getHeaderTextSize() {
		$.ajax({
			method:"POST",
			url: "jenkinsBlueFunc.php",
			data: {
				getHeaderTextSize: 'yes',
			},
				success:function(data)
				{
					if(data != null) {
						var headerData = JSON.parse(data);
						window.curTextSize = Number(headerData['x-text-size']);
					}
				}
		});
	}
	if (dev_name != 'Common_config config:')
		getHeaderTextSize();

	// set more log option
	function setIdleMoreLog() {
		if(window.curTextSize != null) {
			if(window.curTextSize >= 1000) {
				window.curTextSize = window.curTextSize - 1000;
				$('#fullLog').show();
				getBuildOutput();
			}
			getBuildOutput();
		}
	}

  // set jenkins build status
  function getJenkinsBuildStatus() {
		$.ajax({
			method:"POST",
			url: "jenkinsBlueFunc.php",
			data: {
				getBuildStatus: 'yes'
			},
				success:function(data)
				{
					$('#buildStatus').text(data);
					data = data.trim(); // cuz whitespaces are gey
					if((data.localeCompare('building')) == 0 || window.Morelog == 'true') {
						$('#build-progress-bar').show();
						getProgress();
						getBuildOutput();
					} else if((data.localeCompare('idle')) == 0) {
						if(!window.idleLogSetShown) setIdleMoreLog();
					}
					window.jenkinsLooper = setTimeout(function() {
						getJenkinsBuildStatus();
					}, 5000);
				}
			});
		}
	var dev_name = $('#config_dev_name').text().trim();
	if (dev_name != 'Common_config config:')
		getJenkinsBuildStatus();

  // get build log output
  function getBuildOutput(){
		window.idleLogSetShown = true;
    $.ajax({
    method:"POST",
		url: "jenkinsBlueFunc.php",
		data: {
			getBodyOutput: 'yes',
			headerTextSize: window.curTextSize
		},
      success:function(data)
      {
				if(data != null) {
					var parsedData = JSON.parse(data);
					var headerData = JSON.parse(parsedData.headers);
					var bodyData = parsedData.body;

					if(['x-more-data'] in headerData)
						window.Morelog = headerData['x-more-data'];
					else
						window.Morelog = false;

					if(Number(window.curTextSize) == Number(headerData['x-text-size'])) return;
					else {
						window.curTextSize = Number(headerData['x-text-size']);
						$('#buildOutput').append('<p id="logs">'+bodyData+'</p>');

						if (!$('#buildOutput').is(':hover'))
						$('#buildOutput').scrollTop($('#buildOutput').prop("scrollHeight"));
					}
				}
      }
    });
	}
	
	$('body').on('click', '#fullLog', function(){
		$.ajax({
			url:"jenkinsBlueFunc.php",
			method:"POST",
			data: {
				getBodyOutput: 'yes',
				headerTextSize: 0
			},
			success:function(data)
			{
				if(data != null) {
					clearTimeout(window.jenkinsLooper);
					var parsedData = JSON.parse(data);
					var headerData = JSON.parse(parsedData.headers);
					var bodyData = parsedData.body;
					window.curTextSize = Number(headerData['x-text-size']);
					$('#buildOutput').empty();
					$('#buildOutput').append('<p id="logs">'+bodyData+'</p>');
					getJenkinsBuildStatus();
				}
			}
		});
  });

  $('body').on('click', '#reset-hard', function(){
  	//set defaults
	$('#is_official').bootstrapToggle('on');
	$('#test_build').bootstrapToggle('off');
	$('#force_clean').bootstrapToggle('off');
	$('#buildtype').val('userdebug');
	$('#bootimage').bootstrapToggle('off');
	$('#weeklies_opt').bootstrapToggle('on');
	$('default_buildtype_state').bootstrapToggle('on');
  });
});