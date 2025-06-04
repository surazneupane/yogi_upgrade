<?php



$schedule_timing = json_decode($schedule['schedule_timing']);
$schedule_timing_count = count($schedule_timing);

?>

<script type="text/javascript">
	
	jQuery(document).ready( function() {
		
		jQuery('.btn-toolbar .btn-submit').click( function() {
			
			var btn_task = jQuery(this).attr('btn-task');
			jQuery('input[name="task"]').val(btn_task);
			jQuery('#schedule-edit-form').submit();
			
		});
		
		
		// SCHEDULE ADD MORE [START]
		
		var schedule_timing_max_fields = 5; //maximum input boxes allowed
    	var schedule_timing_list_wrapper = jQuery("#schedule_timing-wrapper"); //Fields wrapper
    	var schedule_timing_button   = jQuery("#add_schedule_timing"); //Add button ID
    	var x = <?php echo $schedule_timing_count; ?>; //initlal text box count
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
		Edit Schedule
		<small>Edit yoga schedule</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url('administrator/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="<?php echo site_url('administrator/schedules'); ?>">Schedules</a></li>
		<li class="active">Edit schedule</li>
	</ol>
</section>

<section class="content">

	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
			
				<!-- form start -->
				<?php echo form_open('',array('id'=>'schedule-edit-form', 'class'=>'schedule-edit-form')); ?>
				
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
										<?php echo form_input('schedule_date', $schedule['schedule_date'],'class="form-control pull-right"'); ?>
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
								<?php $i = 0; foreach ($schedule_timing AS $timing) { ?>
								<div class="form-group">
									<div class="row">
										<div class="col-md-4">
											<p><?php echo form_input('schedule_timing['.$i.'][timing]', $timing->timing,'id="schedule_timingID'.$i.'" class="form-control" placeholder="Yoga Timing"'); ?></p>
										</div>
										<div class="col-md-4">
											<p><?php echo form_input('schedule_timing['.$i.'][yoga_type]', $timing->yoga_type,'id="schedule_yoga_typeID'.$i.'" class="form-control" placeholder="Yoga Type"'); ?></p>
										</div>
										<div class="col-md-4">
											<p><?php
												$options = array();
												foreach ($teacherlist AS $teacher) {
													$options[$teacher['id']] = $teacher['title'];
												}
												echo form_dropdown('schedule_timing['.$i.'][teacher_id]', $options, $timing->teacher_id, 'id="schedule_teacher_idID'.$i.'" class="form-control"');
											?>
											</p>
										</div>
									</div>
									<p><?php echo form_input('schedule_timing['.$i.'][seat_info]', $timing->seat_info,'id="schedule_seat_infoID'.$i.'" class="form-control"  placeholder="Extra Info"'); ?></p>
									<?php if($i == 0) { ?>
									<hr/>
									<?php } ?>
									<?php if($i != 0) { ?>
									<span class="">
										<a href="#" class="btn btn-danger remove_field">Remove</a>
									</span>
									<?php } ?>
								</div>
								<?php $i++; } ?>
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
										echo form_dropdown('status', $options, $schedule['status'], 'class="form-control"');
									?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<?php echo form_label('Created Date','created_date');?>
									<?php echo form_error('created_date');?>
									<?php echo form_input('created_date', $schedule['created_date'],'class="form-control readonly" readonly="readonly"'); ?>
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
										echo form_dropdown('created_by', $options, $schedule['created_by'], 'class="form-control"');
									?>
								</div>
							</div>
						</div>
						
					</div>
					<!-- /.box-body -->
					
					<?php echo form_hidden('schedule_id',set_value('schedule_id', $schedule['id']));?>
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