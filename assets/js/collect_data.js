$(document).ready(function(){
      var i=1;
      $('#add1').click(function(){
           i++;
           $('#dynamic_field-1-').append('<li class="list-group-item" style="border:none" id="dynamic_field'+i+'"><div class="row"> <div class="col-lg-4 col-md-4" style="padding-left:0px"> <div class="form-group"> <input type="text" name="repo_paths[]" class="form-control" /> </div> </div> <button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></div></li>');
      });
      $('#add2').click(function(){
           i++;
           $('#dynamic_field-2-').append('<li class="list-group-item" style="border:none" id="dynamic_field'+i+'"><div class="row"> <div class="col-lg-4 col-md-4" style="padding-left:0px"> <div class="form-group"> <input type="text" name="repo_clones[]" class="form-control" /> </div> </div> <button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></div></li>');
      });
      $('#add3').click(function(){
           i++;
           $('#dynamic_field-3-').append('<li class="list-group-item" style="border:none" id="dynamic_field'+i+'"><div class="row"> <div class="col-lg-4 col-md-4" style="padding-left:0px"> <div class="form-group"> <input type="text" name="repopick_topics[]" class="form-control" /> </div> </div> <button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></div></li>');
      });
      $('#add4').click(function(){
           i++;
           $('#dynamic_field-4-').append('<li class="list-group-item" style="border:none" id="dynamic_field'+i+'"><div class="row"> <div class="col-lg-4 col-md-4" style="padding-left:0px"> <div class="form-group"> <input type="text" name="repopick_changes[]" class="form-control" /> </div> </div> <button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></div></li>');
      });

      // Switches
      $('#is_official').bootstrapToggle({
        on: 'Yes',
        off: 'No',
        onstyle: 'success',
        offstyle: 'danger'
      });
      $('#is_official').change(function(){
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
      $('#test_build').change(function(){
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
      $('#force_clean').change(function(){
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
      $('#override_lunch').change(function(){
        if ($(this).prop('checked')) {
          $('#hidden_override_lunch').val('yes');
        } else {
          $('#hidden_override_lunch').val('no');
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