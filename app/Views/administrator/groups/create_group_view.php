<?php

helper('form');

?>

<section class="content-header">
	<h1>
		Create Group
		<small>Create user group</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url('administrator/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="<?php echo site_url('administrator/groups'); ?>">Groups</a></li>
		<li class="active">Create Group</li>
	</ol>
</section>

<section class="content">

	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">

				<!-- form start -->
				<?php echo form_open('', array('class' => 'group-form')); ?>
				<div class="box-body">
					<!-- Nav tabs -->
					<div class="data-tabs">
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation" class="active"><a href="#details" aria-controls="home" role="tab" data-toggle="tab">Details</a></li>
							<li role="presentation"><a href="#access_level" aria-controls="access" role="tab" data-toggle="tab">Access Level</a></li>
						</ul>
						<!-- Tab panes -->
						<div class="tab-content">

							<div role="tabpanel" class="tab-pane active" id="details">
								<div class="form-group">
									<?php echo form_label('Group name', 'group_name'); ?>
									<span class="text-danger">
										<?php echo session('errors.group_name')
										?>
									</span>
									<?php echo form_input('group_name', old('group_name'), 'class="form-control"'); ?>
								</div>
								<div class="form-group">
									<div class="form-group">
										<?php echo form_label('Group description', 'group_description'); ?>
										<span class="text-danger">
											<?php echo session('errors.group_description')
											?>
										</span>
										<?php echo form_textarea('group_description',  old('group_description'), 'class="form-control"'); ?>
									</div>
								</div>
							</div>

							<div role="tabpanel" class="tab-pane" id="access_level">

								<div class="row">
									<div class="col-md-4">
										<?php echo form_fieldset('Group Access Level', [
											'class' => 'access_level_fieldset group_access_level_fieldset',
											'data-mh' => 'heightConsistancy'
										]);
										?>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('group_access[access]', '1', FALSE, 'class="Icheck"'); ?> Access Administration Interface
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('group_access[add]', '1', FALSE, 'class="Icheck"'); ?> Add
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('group_access[update]', '1', FALSE, 'class="Icheck"'); ?> Update
											</div>
										</div>
										<div>
											<div class="checkbox">
												<?php echo form_checkbox('group_access[delete]', '1', FALSE, 'class="Icheck"'); ?> Delete
											</div>
										</div>
										<?php echo form_fieldset_close(); ?>
									</div>
									<div class="col-md-4">
										<?php echo
										form_fieldset('User Access Level', [
											'class' => 'access_level_fieldset user_access_level_fieldset',
											'data-mh' => 'heightConsistancy'
										]);
										?>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('user_access[access]', '1', FALSE, 'class="Icheck"'); ?> Access Administration Interface
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('user_access[add]', '1', FALSE, 'class="Icheck"'); ?> Add
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('user_access[update]', '1', FALSE, 'class="Icheck"'); ?> Update
											</div>
										</div>
										<div>
											<div class="checkbox">
												<?php echo form_checkbox('user_access[delete]', '1', FALSE, 'class="Icheck"'); ?> Delete
											</div>
										</div>
										<?php echo form_fieldset_close(); ?>
									</div>
									<div class="col-md-4">
										<?php echo
										form_fieldset('Presenter Access Level', [
											'class' => 'access_level_fieldset presenter_access_level_fieldset',
											'data-mh' => 'heightConsistancy'
										]);

										?>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('presenter_access[access]', '1', FALSE, 'class="Icheck"'); ?> Access Administration Interface
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('presenter_access[add]', '1', FALSE, 'class="Icheck"'); ?> Add
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('presenter_access[update]', '1', FALSE, 'class="Icheck"'); ?> Update
											</div>
										</div>
										<div>
											<div class="checkbox">
												<?php echo form_checkbox('presenter_access[delete]', '1', FALSE, 'class="Icheck"'); ?> Delete
											</div>
										</div>
										<?php echo form_fieldset_close(); ?>
									</div>
									<div class="col-md-4">
										<?php echo form_fieldset('Subscriber Access Level', [
											'class' => 'access_level_fieldset subscriber_access_level_fieldset',
											'data-mh' => 'heightConsistancy'
										]); ?>

										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('subscriber_access[access]', '1', FALSE, 'class="Icheck"'); ?> Access Administration Interface
											</div>
										</div>
										<div>
											<div class="checkbox">
												<?php echo form_checkbox('subscriber_access[delete]', '1', FALSE, 'class="Icheck"'); ?> Delete
											</div>
										</div>
										<?php echo form_fieldset_close(); ?>
									</div>
									<div class="col-md-4">
										<?php echo form_fieldset('Contact Access Level', [
											'class' => 'access_level_fieldset contact_access_level_fieldset',
											'data-mh' => 'heightConsistancy'
										]); ?>

										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('contact_access[access]', '1', FALSE, 'class="Icheck"'); ?> Access Administration Interface
											</div>
										</div>
										<div>
											<div class="checkbox">
												<?php echo form_checkbox('contact_access[delete]', '1', FALSE, 'class="Icheck"'); ?> Delete
											</div>
										</div>
										<?php echo form_fieldset_close(); ?>
									</div>
									<div class="col-md-4">
										<?php echo form_fieldset('Menu Access Level', [
											'class' => 'access_level_fieldset menu_access_level_fieldset',
											'data-mh' => 'heightConsistancy'
										]); ?>

										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('menu_access[access]', '1', FALSE, 'class="Icheck"'); ?> Access Administration Interface
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('menu_access[add]', '1', FALSE, 'class="Icheck"'); ?> Add
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('menu_access[update]', '1', FALSE, 'class="Icheck"'); ?> Update
											</div>
										</div>
										<div>
											<div class="checkbox">
												<?php echo form_checkbox('menu_access[delete]', '1', FALSE, 'class="Icheck"'); ?> Delete
											</div>
										</div>
										<?php echo form_fieldset_close(); ?>
									</div>
									<div class="col-md-4">
										<?php echo form_fieldset('Category Access Level', [
											'class' => 'access_level_fieldset category_access_level_fieldset',
											'data-mh' => 'heightConsistancy'
										]); ?>

										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('category_access[access]', '1', FALSE, 'class="Icheck"'); ?> Access Administration Interface
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('category_access[add]', '1', FALSE, 'class="Icheck"'); ?> Add
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('category_access[update]', '1', FALSE, 'class="Icheck"'); ?> Update
											</div>
										</div>
										<div>
											<div class="checkbox">
												<?php echo form_checkbox('category_access[delete]', '1', FALSE, 'class="Icheck"'); ?> Delete
											</div>
										</div>
										<?php echo form_fieldset_close(); ?>
									</div>
									<div class="col-md-4">
										<?php echo form_fieldset('Article Access Level', [
											'class' => 'access_level_fieldset article_access_level_fieldset',
											'data-mh' => 'heightConsistancy'
										]); ?>

										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('article_access[access]', '1', FALSE, 'class="Icheck"'); ?> Access Administration Interface
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('article_access[add]', '1', FALSE, 'class="Icheck"'); ?> Add
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('article_access[update]', '1', FALSE, 'class="Icheck"'); ?> Update
											</div>
										</div>
										<div>
											<div class="checkbox">
												<?php echo form_checkbox('article_access[delete]', '1', FALSE, 'class="Icheck"'); ?> Delete
											</div>
										</div>
										<?php echo form_fieldset_close(); ?>
									</div>
									<div class="col-md-4">
										<?php echo form_fieldset('Tag Access Level', [
											'class' => 'access_level_fieldset tag_access_level_fieldset',
											'data-mh' => 'heightConsistancy'
										]); ?>

										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('tag_access[access]', '1', FALSE, 'class="Icheck"'); ?> Access Administration Interface
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('tag_access[add]', '1', FALSE, 'class="Icheck"'); ?> Add
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('tag_access[update]', '1', FALSE, 'class="Icheck"'); ?> Update
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('tag_access[delete]', '1', FALSE, 'class="Icheck"'); ?> Delete
											</div>
										</div>
										<?php echo form_fieldset_close(); ?>
									</div>
									<div class="col-md-4">
										<?php echo form_fieldset('Teacher Access Level', [
											'class' => 'access_level_fieldset teacher_access_level_fieldset',
											'data-mh' => 'heightConsistancy'
										]); ?>

										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('teacher_access[access]', '1', FALSE, 'class="Icheck"'); ?> Access Administration Interface
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('teacher_access[add]', '1', FALSE, 'class="Icheck"'); ?> Add
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('teacher_access[update]', '1', FALSE, 'class="Icheck"'); ?> Update
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('teacher_access[delete]', '1', FALSE, 'class="Icheck"'); ?> Delete
											</div>
										</div>
										<?php echo form_fieldset_close(); ?>
									</div>
									<div class="col-md-4">
										<?php echo form_fieldset('Schedule Access Level', [
											'class' => 'access_level_fieldset schedule_access_level_fieldset',
											'data-mh' => 'heightConsistancy'
										]); ?>

										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('schedule_access[access]', '1', FALSE, 'class="Icheck"'); ?> Access Administration Interface
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('schedule_access[add]', '1', FALSE, 'class="Icheck"'); ?> Add
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('schedule_access[update]', '1', FALSE, 'class="Icheck"'); ?> Update
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('schedule_access[delete]', '1', FALSE, 'class="Icheck"'); ?> Delete
											</div>
										</div>
										<?php echo form_fieldset_close(); ?>
									</div>
									<div class="col-md-4">
										<?php echo form_fieldset('Album Access Level', [
											'class' => 'access_level_fieldset album_access_level_fieldset',
											'data-mh' => 'heightConsistancy'
										]); ?>

										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('album_access[access]', '1', FALSE, 'class="Icheck"'); ?> Access Administration Interface
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('album_access[add]', '1', FALSE, 'class="Icheck"'); ?> Add
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('album_access[update]', '1', FALSE, 'class="Icheck"'); ?> Update
											</div>
										</div>
										<div class="form-group">

											<div class="checkbox">
												<?php echo form_checkbox('album_access[delete]', '1', FALSE, 'class="Icheck"'); ?> Delete
											</div>
										</div>
										<?php echo form_fieldset_close(); ?>
									</div>
									<div class="col-md-4">
										<?php echo form_fieldset('Video Access Level', [
											'class' => 'access_level_fieldset video_access_level_fieldset',
											'data-mh' => 'heightConsistancy'
										]); ?>

										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('video_access[access]', '1', FALSE, 'class="Icheck"'); ?> Access Administration Interface
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('video_access[add]', '1', FALSE, 'class="Icheck"'); ?> Add
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('video_access[update]', '1', FALSE, 'class="Icheck"'); ?> Update
											</div>
										</div>
										<?php echo form_fieldset_close(); ?>
									</div>
									<div class="col-md-4">
										<?php echo form_fieldset('Media Access Level', [
											'class' => 'access_level_fieldset media_access_level_fieldset',
											'data-mh' => 'heightConsistancy'
										]); ?>

										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('media_access[access]', '1', FALSE, 'class="Icheck"'); ?> Access Administration Interface
											</div>
										</div>
										<?php echo form_fieldset_close(); ?>
									</div>
								</div>

							</div>

						</div>
					</div>
				</div>
				<!-- /.box-body -->

				<div class="box-footer">
					<?php echo form_submit('submit', 'Save', 'class="btn btn-success"'); ?>
					<a href="<?php echo site_url('administrator/groups'); ?>" class="btn btn-danger">Cancel</a>
				</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</section>