<?php



?>

<script type="text/javascript">
	
	jQuery(document).ready( function() {
				
		jQuery('.btn-toolbar .btn-submit').click( function() {
			
			var btn_task = jQuery(this).attr('btn-task');
			jQuery('input[name="task"]').val(btn_task);
			jQuery('#video-create-form').submit();
			
		});
		
	});

</script>


<section class="content-header">
	<h1>
		Add Video
		<small>Add content video</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url('administrator/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="<?php echo site_url('administrator/videos'); ?>">Videos</a></li>
		<li class="active">Add video</li>
	</ol>
</section>

<section class="content">

	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
				
				<!-- form start -->
				<?php echo form_open_multipart('',array('id'=>'video-create-form', 'class'=>'video-create-form')); ?>
				
					<div class="box-header with-border">
						<div class="btn-toolbar">
							<?php echo form_button('submit_save', '<i class="fa fa-floppy-o"></i> Save', 'id="submit_save" class="btn btn-submit btn-success" btn-task="save"');?>
							<?php echo form_button('submit_save_close', '<i class="fa fa-check"></i> Save & Close', 'id="submit_save_close" class="btn btn-submit btn-default" btn-task="save_close"');?>
							<?php echo form_button('submit_save_new', '<i class="fa fa-plus"></i> Save & New', 'id="submit_save_new" class="btn btn-submit btn-default" btn-task="save_new"');?>
							<a href="<?php echo site_url('administrator/videos'); ?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
						</div>
					</div>
				
					<div class="box-body">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<?php echo form_label('Featured','featured');?>
									<?php echo form_error('featured');?>
									<div class="radiogroup_codeignitor">
										<div class="btn-group" data-toggle="buttons">
											<label class="btn btn-primary active">
												<?php echo form_radio(array('name' => 'featured', 'value' => '0', 'checked' => TRUE, 'id' => 'featured_0')); ?> <?php echo form_label('No','featured_0', 'class="radio_label"');?>
											</label>
											<label class="btn btn-primary">
											<?php echo form_radio(array('name' => 'featured', 'value' => '1', 'checked' => FALSE, 'id' => 'featured_1')); ?> <?php echo form_label('Yes','featured_1', 'class="radio_label"');?>
											</label>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<?php echo form_label('Title','title');?>
									<?php echo form_error('title');?>
									<?php echo form_input('title', '', 'class="form-control"'); ?>
								</div>
								<div class="form-group">
									<?php echo form_label('Description','description');?>
									<?php echo form_error('description');?>
									<?php echo form_textarea('description', '', 'class="textarea"'); ?>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<?php echo form_label('Category','category_id');?>
									<?php echo form_error('category_id');?>
									<?php 
										$options = array();
										if(!empty($categorieslist)) {
											$default = $categorieslist[0]->id;
											foreach ($categorieslist AS $categorylist) {
												if($categorylist->title_en != '' && $categorylist->title_en != $categorylist->title_ar) {
													$options[$categorylist->id] = $categorylist->title_ar.' ('.$categorylist->title_en.')';
												} else {
													$options[$categorylist->id] = $categorylist->title_ar;
												}
											}
										} else {
											$default = '';
											$options[] = 'No Categories';
										}
										echo form_dropdown('category_id', $options, $default, 'class="form-control select2" data-placeholder="Select a Category"');
									?>
								</div>
								<div class="form-group">
									<?php echo form_label('Author','author_id');?>
									<?php echo form_error('author_id');?>
									<?php 
										$options = array();
										if(!empty($userlists)) {
											$default = $userlists[0]['id'];
											foreach ($userlists AS $userlist) {
												$options[$userlist['id']] = $userlist['first_name'].' '.$userlist['last_name'];
											}
										} else {
											$default = '';
											$options[] = 'No Author';
										}
										echo form_dropdown('author_id', $options, $default, 'class="form-control select2" data-placeholder="Select a Author"');
									?>
								</div>
								<div class="form-group">
									<?php echo form_label('Presenter','presenter_id');?>
									<?php echo form_error('presenter_id');?>
									<?php 
										$options = array();
										if(!empty($presenterlists)) {
											$default = $presenterlists[0]['id'];
											foreach ($presenterlists AS $presenterlist) {
												$options[$presenterlist['id']] = $presenterlist['name'];
											}
										} else {
											$default = '';
											$options[] = 'No Presenter';
										}
										echo form_dropdown('presenter_id', $options, $default, 'class="form-control select2" data-placeholder="Select a Presenter"');
									?>
								</div>
								<div class="form-group">
									<?php echo form_label('Video','video_file');?>
									<?php echo form_error('video_file');?>
									<?php echo form_upload('video_file', '', 'class="file-input"'); ?>
									<div class="input-group col-xs-12">
										<span class="input-group-addon"><i class="glyphicon glyphicon-facetime-video"></i></span>
										<input type="text" class="form-control" disabled placeholder="Upload MP4 Video">
										<span class="input-group-btn">
											<button class="btn-browse btn btn-primary" type="button"><i class="fa fa-cloud-upload" aria-hidden="true"></i> Browse</button>
										</span>
									</div>
								</div>
								<p class="text-right text-bold text-red">Note : Only MP4 Format *</p>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-6">
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
							<div class="col-md-6">
								<div class="form-group">
									<?php echo form_label('Created By','created_by');?>
									<?php echo form_error('created_by');?>
									<?php
										$options = array();
										foreach ($userlists AS $user) {
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
							<a href="<?php echo site_url('administrator/videos'); ?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
						</div>
					</div>
					
				<?php echo form_close();?>
				
			</div>
		</div>
	</div>
</section>
