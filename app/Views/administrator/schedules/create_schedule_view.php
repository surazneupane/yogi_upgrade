<?php



?>

<script type="text/javascript">
	
	jQuery(document).ready( function() {
				
		jQuery('.btn-toolbar .btn-submit').click( function() {
			
			var btn_task = jQuery(this).attr('btn-task');
			jQuery('input[name="task"]').val(btn_task);
			jQuery('#schedule-create-form').submit();
			
		});
		
		// SCHEDULE ADD MORE [START]
		
		var schedule_timing_max_fields = 5; //maximum input boxes allowed
    	var schedule_timing_list_wrapper = jQuery("#schedule_timing-wrapper"); //Fields wrapper
    	var schedule_timing_button   = jQuery("#add_schedule_timing"); //Add button ID
    	var x = 1; //initlal text box count
		jQuery(schedule_timing_button).click(function(e){
			e.preventDefault();
	        if(x < schedule_timing_max_fields){ //max input box allowed
	            x++; //text box increment
	            
	            var schedule_timing_list_html  = '<div class="form-group">';
		                schedule_timing_list_html += '<div class="row">';
		                	schedule_timing_list_html += '<div class="col-md-4">';
		                		schedule_timing_list_html += '<p><input name="schedule_timing['+x+'][timing]" value="" id="schedule_timingID'+x+'" class="form-control" type="text" placeholder="Yoga Timing"></p>';
		                	schedule_timing_list_html += '</div>';
		                	schedule_timing_list_html += '<div class="col-md-4">';
		                		schedule_timing_list_html += '<p><input name="schedule_timing['+x+'][yoga_type]" value="" id="schedule_yoga_typeID'+x+'" class="form-control" type="text" placeholder="Yoga Type"></p>';
		                	schedule_timing_list_html += '</div>';
		                	schedule_timing_list_html += '<div class="col-md-4">';
				                schedule_timing_list_html += '<p><select name="schedule_timing['+x+'][teacher_id]" id="schedule_teacher_idID'+x+'" class="form-control">';
				                <?php foreach ($teacherlist AS $teacher) { ?>
									schedule_timing_list_html += '<option value="<?php echo $teacher['id']; ?>"><?php echo $teacher['title']; ?></option>';
								<?php } ?>
								schedule_timing_list_html += '</select></p>';
							schedule_timing_list_html += '</div>';
						schedule_timing_list_html += '</div>';
						schedule_timing_list_html += '<p><input name="schedule_timing['+x+'][seat_info]" value="" id="schedule_seat_infoID'+x+'" class="form-control" type="text" placeholder="Extra Info"></p>';
		                schedule_timing_list_html += '<span class="">';
		               		schedule_timing_list_html += '<a href="#" class="btn btn-danger remove_field">Remove</a>';
		                schedule_timing_list_html += '</span>';		                
	                schedule_timing_list_html += '</div>';
	            jQuery(schedule_timing_list_wrapper).append(schedule_timing_list_html); //add input box
	        }
		});
		
		jQuery(schedule_timing_list_wrapper).on("click",".remove_field", function(e){ //user click on remove text
	        e.preventDefault(); jQuery(this).parent().parent('div').remove(); x--;
	    });
	    
	    // SCHEDULE ADD MORE [END]
		
		
	});

</script>


<section class="content-header">
	<h1>
		Add Schedule
		<small>Add yoga schedule</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url('administrator/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="<?php echo site_url('administrator/schedules'); ?>">Schedules</a></li>
		<li class="active">Create schedule</li>
	</ol>
</section>

<section class="content">

	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
				
				<!-- form start -->
				<?php echo form_open('',array('id'=>'schedule-create-form', 'class'=>'schedule-create-form')); ?>
				
					<div class="box-header with-border">
						<div class="btn-toolbar">
							<?php echo form_button('submit_save', '<i class="fa fa-floppy-o"></i> Save', 'id="submit_save" class="btn btn-submit btn-success" btn-task="save"');?>
							<?php echo form_button('submit_save_close', '<i class="fa fa-check"></i> Save & Close', 'id="submit_save_close" class="btn btn-submit btn-default" btn-task="save_close"');?>
							<?php echo form_button('submit_save_new', '<i class="fa fa-plus"></i> Save & New', 'id="submit_save_new" class="btn btn-submit btn-default" btn-task="save_new"');?>
							<a href="<?php echo site_url('administrator/schedules'); ?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
						</div>
					</div>
				
					<div class="box-body">
					
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<?php echo form_label('Schedule Date','schedule_date');?>
									<div class="input-group date datepicker-control">
										<?php echo form_input('schedule_date', date('Y-m-d'),'class="form-control pull-right"'); ?>
										<div class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</div>
									</div>
									<?php echo form_error('schedule_date');?>
								</div>
							</div>
						</div>
						
						<div class="form-group yoga-schedule_timing-field">
							<?php echo form_label('Schedule','schedule_timing');?>
							<?php echo form_error('schedule_timing');?>
							<div class="schedule_timing_action">
								<button type="button" id="add_schedule_timing" class="btn bg-orange btn-add_schedule_timing"><i class="fa fa-plus"></i>&nbsp; Add</button>
							</div>
							<hr/>
							<div id="schedule_timing-wrapper" class="schedule_timing-wrapper">
								<div class="form-group">
									<div class="row">
										<div class="col-md-4">
											<p><?php echo form_input('schedule_timing[0][timing]','','id="schedule_timingID1" class="form-control" placeholder="Yoga Timing"'); ?></p>
										</div>
										<div class="col-md-4">
											<p><?php echo form_input('schedule_timing[0][yoga_type]','','id="schedule_yoga_typeID1" class="form-control" placeholder="Yoga Type"'); ?></p>
										</div>
										<div class="col-md-4">
											<p><?php
												$options = array();
												foreach ($teacherlist AS $teacher) {
													$options[$teacher['id']] = $teacher['title'];
												}
												echo form_dropdown('schedule_timing[0][teacher_id]', $options, '', 'id="schedule_teacher_idID1" class="form-control"');
											?>
											</p>
										</div>
									</div>
									<p><?php echo form_input('schedule_timing[0][seat_info]','','id="schedule_seat_infoID1" class="form-control"  placeholder="Extra Info"'); ?></p><hr/>
									<!--<span class="">
										<a href="#" class="btn btn-danger remove_field">Remove</a>
									</span>-->
								</div>
							</div>
						</div>
						
						<hr/>
						
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<?php echo form_label('Status','status');?>
									<?php echo form_error('status');?>
									<?php 
										$options = array();
										$options[1] = 'Publish';
										$options[0] = 'Unpublish';
										echo form_dropdown('status', $options, '', 'class="form-control"');
									?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
								
									<?php echo form_label('Created Date','created_date');?>
									<div class="input-group date datetimepicker-control">
										<?php echo form_input('created_date', date('Y-m-d H:i:s'),'class="form-control pull-right"'); ?>
										<div class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</div>
									</div>
									<?php echo form_error('created_date');?>
								
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<?php echo form_label('Created By','created_by');?>
									<?php echo form_error('created_by');?>
									<?php
										$options = array();
										foreach ($userlist AS $user) {
											$options[$user['id']] = $user['first_name'].' '.$user['last_name'];
										}
										echo form_dropdown('created_by', $options, $current_user->id, 'class="form-control"');
									?>
								</div>
							</div>
						</div>
												
					</div>
					<!-- /.box-body -->
					
					<?php echo form_hidden('task', '');?>
					
					<div class="box-footer">
						<div class="btn-toolbar">
							<?php echo form_button('submit_save', '<i class="fa fa-floppy-o"></i> Save', 'id="submit_save" class="btn btn-submit btn-success" btn-task="save"');?>
							<?php echo form_button('submit_save_close', '<i class="fa fa-check"></i> Save & Close', 'id="submit_save_close" class="btn btn-submit btn-default" btn-task="save_close"');?>
							<?php echo form_button('submit_save_new', '<i class="fa fa-plus"></i> Save & New', 'id="submit_save_new" class="btn btn-submit btn-default" btn-task="save_new"');?>
							<a href="<?php echo site_url('administrator/schedules'); ?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
						</div>
					</div>
					
				<?php echo form_close();?>
				
			</div>
		</div>
	</div>
</section>
