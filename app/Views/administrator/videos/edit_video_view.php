<?php



?>

<script type="text/javascript">
	
	jQuery(document).ready( function() {
		
		jQuery('.btn-toolbar .btn-submit').click( function() {
			
			var btn_task = jQuery(this).attr('btn-task');
			jQuery('input[name="task"]').val(btn_task);
			jQuery('#video-edit-form').submit();
			
		});
				
	});

</script>

<section class="content-header">
	<h1>
		Edit Video
		<small>Edit content video</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url('administrator/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="<?php echo site_url('administrator/videos'); ?>">Videos</a></li>
		<li class="active">Edit Video</li>
	</ol>
</section>

<section class="content">

	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
			
				<!-- form start -->
				<?php echo form_open('',array('id'=>'video-edit-form', 'class'=>'video-edit-form')); ?>
				
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
							<div class="col-md-2">
								<div class="form-group">
									<?php echo form_label('Featured','featured');?>
									<?php echo form_error('title');?>
									<div class="radiogroup_codeignitor">
										<div class="btn-group" data-toggle="buttons">
											<label class="btn btn-primary <?php if($video['featured'] == 0) { ?>active<?php } ?>">
												<?php echo form_radio(array('name' => 'featured', 'value' => '0', 'checked' => ('0' == $video['featured']) ? TRUE : FALSE, 'id' => 'featured_0')); ?> <?php echo form_label('No','featured_0', 'class="radio_label"');?>
											</label>
											<label class="btn btn-primary <?php if($video['featured'] == 1) { ?>active<?php } ?>">
											<?php echo form_radio(array('name' => 'featured', 'value' => '1', 'checked' => ('1' == $video['featured']) ? TRUE : FALSE, 'id' => 'featured_1')); ?> <?php echo form_label('Yes','featured_1', 'class="radio_label"');?>
											</label>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<?php echo form_label('Mangomolo Video ID','mangovideo_id');?>
									<?php echo form_error('mangovideo_id');?>
									<?php echo form_input('mangovideo_id', $video['mangovideo_id'], 'class="form-control" readonly="readonly"'); ?>
								</div>
							</div>
						</div>
						<hr/>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<?php echo form_label('Title','title');?>
									<?php echo form_error('title');?>
									<?php echo form_input('title', $video['title'], 'class="form-control"'); ?>
								</div>
								<div class="form-group">
									<?php echo form_label('Description','description');?>
									<?php echo form_error('description');?>
									<?php echo form_textarea('description', $video['title'], 'class="textarea"'); ?>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<?php echo form_label('Category','category_id');?>
									<?php echo form_error('category_id');?>
									<?php 
										$options = array();
										if(!empty($categorieslist)) {
											$default = $video['category_id'];
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
										echo form_dropdown('category_id', $options, $default, 'class="form-control select2" data-placeholder="Select a Category" readonly="readonly" disabled="disabled"');
									?>
								</div>
								<div class="form-group">
									<?php echo form_label('Author','author_id');?>
									<?php echo form_error('author_id');?>
									<?php 
										$options = array();
										if(!empty($userlists)) {
											$default = $video['author_id'];
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
											$default = $video['presenter_id'];
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
									<?php echo form_label('Video','video_file'); ?>
									<div class="row">
										<div class="col-md-4">
											<div class="thumbnail">
												<img src="http://admango.cdn.mangomolo.com/analytics/<?php echo $mangomolovideo_details->video->img; ?>" class="img-responsive" />
											</div>
										</div>
										<div class="col-md-8">
											<div class="embed-responsive embed-responsive-16by9">
												<iframe class="embed-responsive-item" src="<?php echo $mangomolovideo_details->embed; ?>"></iframe>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<?php echo form_label('Created Date','created_date');?>
									<?php echo form_error('created_date');?>
									<?php echo form_input('created_date', $video['created_date'],'class="form-control readonly" readonly="readonly"'); ?>
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
										echo form_dropdown('created_by', $options, $video['created_by'], 'class="form-control"');
									?>
								</div>
							</div>
						</div>
					</div>
					<!-- /.box-body -->
					
					<?php echo form_hidden('video_id',set_value('video_id', $video['id']));?>
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