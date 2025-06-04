<?php

helper('form');

?>


<section class="content-header">
	<h1>
		Edit Group
		<small>Edit user group</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url('administrator/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="<?php echo site_url('administrator/groups'); ?>">Groups</a></li>
		<li class="active">Edit Group</li>
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
									<?php echo form_input('group_name', set_value('group_name', old('group_name') ?? $group->name), 'class="form-control"'); ?>
								</div>
								<div class="form-group">
									<div class="form-group">
										<?php echo form_label('Group description', 'group_description'); ?>
										<span class="text-danger">
											<?php echo session('errors.group_description')
											?>
										</span>
										<?php echo form_textarea('group_description', set_value('group_description', old('group_description') ?? $group->description), 'class="form-control"'); ?>
									</div>
								</div>
							</div>

							<div role="tabpanel" class="tab-pane" id="access_level">

								<div class="row">
									<div class="col-md-4">
										<?php
										$group_access = json_decode($group->group_access);
										if (!empty($group_access)) {
											if (isset($group_access->access)) {
												$group_access->access = 1;
											} else {
												$group_access->access = 0;
											}
											if (isset($group_access->add)) {
												$group_access->add = 1;
											} else {
												$group_access->add = 0;
											}
											if (isset($group_access->update)) {
												$group_access->update = 1;
											} else {
												$group_access->update = 0;
											}
											if (isset($group_access->delete)) {
												$group_access->delete = 1;
											} else {
												$group_access->delete = 0;
											}
										} else {
											$group_access = (object) array('access' => 0, 'add' => 0, 'update' => 0, 'delete' => 0);
										}
										?>

										<?php
										echo form_fieldset('Group Access Level', [
											'class' => 'access_level_fieldset group_access_level_fieldset',
											'data-mh' => 'heightConsistancy'
										]);

										?>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('group_access[access]', '1', $group_access->access, 'class="Icheck"'); ?> Access Administration Interface
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('group_access[add]', '1', $group_access->add, 'class="Icheck"'); ?> Add
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('group_access[update]', '1', $group_access->update, 'class="Icheck"'); ?> Update
											</div>
										</div>
										<div>
											<div class="checkbox">
												<?php echo form_checkbox('group_access[delete]', '1', $group_access->delete, 'class="Icheck"'); ?> Delete
											</div>
										</div>
										<?php echo form_fieldset_close(); ?>
									</div>
									<div class="col-md-4">
										<?php
										$user_access = json_decode($group->user_access);
										if (!empty($user_access)) {
											if (isset($user_access->access)) {
												$user_access->access = 1;
											} else {
												$user_access->access = 0;
											}
											if (isset($user_access->add)) {
												$user_access->add = 1;
											} else {
												$user_access->add = 0;
											}
											if (isset($user_access->update)) {
												$user_access->update = 1;
											} else {
												$user_access->update = 0;
											}
											if (isset($user_access->delete)) {
												$user_access->delete = 1;
											} else {
												$user_access->delete = 0;
											}
										} else {
											$user_access = (object) array('access' => 0, 'add' => 0, 'update' => 0, 'delete' => 0);
										}
										?>
										<?php
										echo form_fieldset('User Access Level', [
											'class' => 'access_level_fieldset user_access_level_fieldset',
											'data-mh' => 'heightConsistancy'
										]);

										?>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('user_access[access]', '1', $user_access->access, 'class="Icheck"'); ?> Access Administration Interface
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('user_access[add]', '1', $user_access->add, 'class="Icheck"'); ?> Add
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('user_access[update]', '1', $user_access->update, 'class="Icheck"'); ?> Update
											</div>
										</div>
										<div>
											<div class="checkbox">
												<?php echo form_checkbox('user_access[delete]', '1', $user_access->delete, 'class="Icheck"'); ?> Delete
											</div>
										</div>
										<?php echo form_fieldset_close(); ?>
									</div>
									<div class="col-md-4">
										<?php
										$presenter_access = json_decode($group->presenter_access);
										if (!empty($presenter_access)) {
											if (isset($presenter_access->access)) {
												$presenter_access->access = 1;
											} else {
												$presenter_access->access = 0;
											}
											if (isset($presenter_access->add)) {
												$presenter_access->add = 1;
											} else {
												$presenter_access->add = 0;
											}
											if (isset($presenter_access->update)) {
												$presenter_access->update = 1;
											} else {
												$presenter_access->update = 0;
											}
											if (isset($presenter_access->delete)) {
												$presenter_access->delete = 1;
											} else {
												$presenter_access->delete = 0;
											}
										} else {
											$presenter_access = (object) array('access' => 0, 'add' => 0, 'update' => 0, 'delete' => 0);
										}
										?>
										<?php echo form_fieldset('Presenter Access Level', [
											'class' => 'access_level_fieldset presenter_access_level_fieldset',
											'data-mh' => 'heightConsistancy'
										]);
										?>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('presenter_access[access]', '1', $presenter_access->access, 'class="Icheck"'); ?> Access Administration Interface
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('presenter_access[add]', '1', $presenter_access->add, 'class="Icheck"'); ?> Add
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('presenter_access[update]', '1', $presenter_access->update, 'class="Icheck"'); ?> Update
											</div>
										</div>
										<div>
											<div class="checkbox">
												<?php echo form_checkbox('presenter_access[delete]', '1', $presenter_access->delete, 'class="Icheck"'); ?> Delete
											</div>
										</div>
										<?php echo form_fieldset_close(); ?>
									</div>
									<div class="col-md-4">
										<?php
										$subscriber_access = json_decode($group->subscriber_access);
										if (!empty($subscriber_access)) {
											if (isset($subscriber_access->access)) {
												$subscriber_access->access = 1;
											} else {
												$subscriber_access->access = 0;
											}
											if (isset($subscriber_access->delete)) {
												$subscriber_access->delete = 1;
											} else {
												$subscriber_access->delete = 0;
											}
										} else {
											$subscriber_access = (object) array('access' => 0, 'delete' => 0);
										}
										?>
										<?php echo form_fieldset('Subscriber Access Level', [
											'class' => 'access_level_fieldset subscriber_access_level_fieldset',
											'data-mh' => 'heightConsistancy'
										]); ?>

										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('subscriber_access[access]', '1', $subscriber_access->access, 'class="Icheck"'); ?> Access Administration Interface
											</div>
										</div>
										<div>
											<div class="checkbox">
												<?php echo form_checkbox('subscriber_access[delete]', '1', $subscriber_access->delete, 'class="Icheck"'); ?> Delete
											</div>
										</div>
										<?php echo form_fieldset_close(); ?>
									</div>
									<div class="col-md-4">
										<?php
										$contact_access = json_decode($group->contact_access);
										if (!empty($contact_access)) {
											if (isset($contact_access->access)) {
												$contact_access->access = 1;
											} else {
												$contact_access->access = 0;
											}
											if (isset($contact_access->delete)) {
												$contact_access->delete = 1;
											} else {
												$contact_access->delete = 0;
											}
										} else {
											$contact_access = (object) array('access' => 0, 'delete' => 0);
										}
										?>
										<?php echo form_fieldset('Contact Access Level', [
											'class' => 'access_level_fieldset contact_access_level_fieldset',
											'data-mh' => 'heightConsistancy'
										]); ?>

										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('contact_access[access]', '1', $contact_access->access, 'class="Icheck"'); ?> Access Administration Interface
											</div>
										</div>
										<div>
											<div class="checkbox">
												<?php echo form_checkbox('contact_access[delete]', '1', $contact_access->delete, 'class="Icheck"'); ?> Delete
											</div>
										</div>
										<?php echo form_fieldset_close(); ?>
									</div>
									<div class="col-md-4">
										<?php
										$menu_access = json_decode($group->menu_access);
										if (!empty($menu_access)) {
											if (isset($menu_access->access)) {
												$menu_access->access = 1;
											} else {
												$menu_access->access = 0;
											}
											if (isset($menu_access->add)) {
												$menu_access->add = 1;
											} else {
												$menu_access->add = 0;
											}
											if (isset($menu_access->update)) {
												$menu_access->update = 1;
											} else {
												$menu_access->update = 0;
											}
											if (isset($menu_access->delete)) {
												$menu_access->delete = 1;
											} else {
												$menu_access->delete = 0;
											}
										} else {
											$menu_access = (object) array('access' => 0, 'add' => 0, 'update' => 0, 'delete' => 0);
										}
										?>
										<?php echo form_fieldset('Menu Access Level', [
											'class' => 'access_level_fieldset menu_access_level_fieldset',
											'data-mh' => 'heightConsistancy'
										]); ?>

										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('menu_access[access]', '1', $menu_access->access, 'class="Icheck"'); ?> Access Administration Interface
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('menu_access[add]', '1', $menu_access->add, 'class="Icheck"'); ?> Add
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('menu_access[update]', '1', $menu_access->update, 'class="Icheck"'); ?> Update
											</div>
										</div>
										<div>
											<div class="checkbox">
												<?php echo form_checkbox('menu_access[delete]', '1', $menu_access->delete, 'class="Icheck"'); ?> Delete
											</div>
										</div>
										<?php echo form_fieldset_close(); ?>
									</div>
									<div class="col-md-4">
										<?php
										$category_access = json_decode($group->category_access);
										if (!empty($category_access)) {
											if (isset($category_access->access)) {
												$category_access->access = 1;
											} else {
												$category_access->access = 0;
											}
											if (isset($category_access->add)) {
												$category_access->add = 1;
											} else {
												$category_access->add = 0;
											}
											if (isset($category_access->update)) {
												$category_access->update = 1;
											} else {
												$category_access->update = 0;
											}
											if (isset($category_access->delete)) {
												$category_access->delete = 1;
											} else {
												$category_access->delete = 0;
											}
										} else {
											$category_access = (object) array('access' => 0, 'add' => 0, 'update' => 0, 'delete' => 0);
										}
										?>
										<?php echo form_fieldset('Category Access Level', [
											'class' => 'access_level_fieldset category_access_level_fieldset',
											'data-mh' => 'heightConsistancy'
										]); ?>

										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('category_access[access]', '1', $category_access->access, 'class="Icheck"'); ?> Access Administration Interface
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('category_access[add]', '1', $category_access->add, 'class="Icheck"'); ?> Add
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('category_access[update]', '1', $category_access->update, 'class="Icheck"'); ?> Update
											</div>
										</div>
										<div>
											<div class="checkbox">
												<?php echo form_checkbox('category_access[delete]', '1', $category_access->delete, 'class="Icheck"'); ?> Delete
											</div>
										</div>
										<?php echo form_fieldset_close(); ?>
									</div>
									<div class="col-md-4">
										<?php
										$article_access = json_decode($group->article_access);
										if (!empty($article_access)) {
											if (isset($article_access->access)) {
												$article_access->access = 1;
											} else {
												$article_access->access = 0;
											}
											if (isset($article_access->add)) {
												$article_access->add = 1;
											} else {
												$article_access->add = 0;
											}
											if (isset($article_access->update)) {
												$article_access->update = 1;
											} else {
												$article_access->update = 0;
											}
											if (isset($article_access->delete)) {
												$article_access->delete = 1;
											} else {
												$article_access->delete = 0;
											}
										} else {
											$article_access = (object) array('access' => 0, 'add' => 0, 'update' => 0, 'delete' => 0);
										}
										?>
										<?php echo form_fieldset('Article Access Level', [
											'class' => 'access_level_fieldset article_access_level_fieldset',
											'data-mh' => 'heightConsistancy'
										]); ?>

										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('article_access[access]', '1', $article_access->access, 'class="Icheck"'); ?> Access Administration Interface
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('article_access[add]', '1', $article_access->add, 'class="Icheck"'); ?> Add
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('article_access[update]', '1', $article_access->update, 'class="Icheck"'); ?> Update
											</div>
										</div>
										<div>
											<div class="checkbox">
												<?php echo form_checkbox('article_access[delete]', '1', $article_access->delete, 'class="Icheck"'); ?> Delete
											</div>
										</div>
										<?php echo form_fieldset_close(); ?>
									</div>
									<div class="col-md-4">
										<?php
										$tag_access = json_decode($group->tag_access);
										if (!empty($tag_access)) {
											if (isset($tag_access->access)) {
												$tag_access->access = 1;
											} else {
												$tag_access->access = 0;
											}
											if (isset($tag_access->add)) {
												$tag_access->add = 1;
											} else {
												$tag_access->add = 0;
											}
											if (isset($tag_access->update)) {
												$tag_access->update = 1;
											} else {
												$tag_access->update = 0;
											}
											if (isset($tag_access->delete)) {
												$tag_access->delete = 1;
											} else {
												$tag_access->delete = 0;
											}
										} else {
											$tag_access = (object) array('access' => 0, 'add' => 0, 'update' => 0, 'delete' => 0);
										}
										?>
										<?php echo form_fieldset('Tag Access Level', [
											'class' => 'access_level_fieldset tag_access_level_fieldset',
											'data-mh' => 'heightConsistancy'
										]); ?>

										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('tag_access[access]', '1', $tag_access->access, 'class="Icheck"'); ?> Access Administration Interface
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('tag_access[add]', '1', $tag_access->add, 'class="Icheck"'); ?> Add
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('tag_access[update]', '1', $tag_access->update, 'class="Icheck"'); ?> Update
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('tag_access[delete]', '1', $tag_access->delete, 'class="Icheck"'); ?> Delete
											</div>
										</div>
										<?php echo form_fieldset_close(); ?>
									</div>
									<div class="col-md-4">
										<?php
										$teacher_access = json_decode($group->teacher_access);
										if (!empty($teacher_access)) {
											if (isset($teacher_access->access)) {
												$teacher_access->access = 1;
											} else {
												$teacher_access->access = 0;
											}
											if (isset($teacher_access->add)) {
												$teacher_access->add = 1;
											} else {
												$teacher_access->add = 0;
											}
											if (isset($teacher_access->update)) {
												$teacher_access->update = 1;
											} else {
												$teacher_access->update = 0;
											}
											if (isset($teacher_access->delete)) {
												$teacher_access->delete = 1;
											} else {
												$teacher_access->delete = 0;
											}
										} else {
											$teacher_access = (object) array('access' => 0, 'add' => 0, 'update' => 0, 'delete' => 0);
										}
										?>
										<?php echo form_fieldset('Teacher Access Level', [
											'class' => 'access_level_fieldset teacher_access_level_fieldset',
											'data-mh' => 'heightConsistancy'
										]); ?>

										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('teacher_access[access]', '1', $tag_access->access, 'class="Icheck"'); ?> Access Administration Interface
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('teacher_access[add]', '1', $tag_access->add, 'class="Icheck"'); ?> Add
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('teacher_access[update]', '1', $tag_access->update, 'class="Icheck"'); ?> Update
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('teacher_access[delete]', '1', $tag_access->delete, 'class="Icheck"'); ?> Delete
											</div>
										</div>
										<?php echo form_fieldset_close(); ?>
									</div>
									<div class="col-md-4">
										<?php
										$schedule_access = json_decode($group->schedule_access);
										if (!empty($schedule_access)) {
											if (isset($schedule_access->access)) {
												$schedule_access->access = 1;
											} else {
												$schedule_access->access = 0;
											}
											if (isset($schedule_access->add)) {
												$schedule_access->add = 1;
											} else {
												$schedule_access->add = 0;
											}
											if (isset($schedule_access->update)) {
												$schedule_access->update = 1;
											} else {
												$schedule_access->update = 0;
											}
											if (isset($schedule_access->delete)) {
												$schedule_access->delete = 1;
											} else {
												$schedule_access->delete = 0;
											}
										} else {
											$schedule_access = (object) array('access' => 0, 'add' => 0, 'update' => 0, 'delete' => 0);
										}
										?>
										<?php echo form_fieldset('Schedule Access Level', [
											'class' => 'access_level_fieldset schedule_access_level_fieldset',
											'data-mh' => 'heightConsistancy'
										]); ?>

										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('schedule_access[access]', '1', $schedule_access->access, 'class="Icheck"'); ?> Access Administration Interface
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('schedule_access[add]', '1', $schedule_access->add, 'class="Icheck"'); ?> Add
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('schedule_access[update]', '1', $schedule_access->update, 'class="Icheck"'); ?> Update
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('schedule_access[delete]', '1', $schedule_access->delete, 'class="Icheck"'); ?> Delete
											</div>
										</div>
										<?php echo form_fieldset_close(); ?>
									</div>
									<div class="col-md-4">
										<?php
										$album_access = json_decode($group->album_access);
										if (!empty($album_access)) {
											if (isset($album_access->access)) {
												$album_access->access = 1;
											} else {
												$album_access->access = 0;
											}
											if (isset($album_access->add)) {
												$album_access->add = 1;
											} else {
												$album_access->add = 0;
											}
											if (isset($album_access->update)) {
												$album_access->update = 1;
											} else {
												$album_access->update = 0;
											}
											if (isset($album_access->delete)) {
												$album_access->delete = 1;
											} else {
												$album_access->delete = 0;
											}
										} else {
											$album_access = (object) array('access' => 0, 'add' => 0, 'update' => 0, 'delete' => 0);
										}
										?>
										<?php echo form_fieldset('Album Access Level', [
											'class' => 'access_level_fieldset album_access_level_fieldset',
											'data-mh' => 'heightConsistancy'
										]); ?>

										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('album_access[access]', '1', $album_access->access, 'class="Icheck"'); ?> Access Administration Interface
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('album_access[add]', '1', $album_access->add, 'class="Icheck"'); ?> Add
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('album_access[update]', '1', $album_access->update, 'class="Icheck"'); ?> Update
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('album_access[delete]', '1', $album_access->delete, 'class="Icheck"'); ?> Delete
											</div>
										</div>
										<?php echo form_fieldset_close(); ?>
									</div>
									<div class="col-md-4">
										<?php
										$video_access = json_decode($group->video_access);
										if (!empty($video_access)) {
											if (isset($video_access->access)) {
												$video_access->access = 1;
											} else {
												$video_access->access = 0;
											}
											if (isset($video_access->add)) {
												$video_access->add = 1;
											} else {
												$video_access->add = 0;
											}
											if (isset($video_access->update)) {
												$video_access->update = 1;
											} else {
												$video_access->update = 0;
											}
										} else {
											$video_access = (object) array('access' => 0, 'add' => 0, 'update' => 0);
										}
										?>
										<?php echo form_fieldset('Video Access Level', [
											'class' => 'access_level_fieldset video_access_level_fieldset',
											'data-mh' => 'heightConsistancy'
										]); ?>

										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('video_access[access]', '1', $video_access->access, 'class="Icheck"'); ?> Access Administration Interface
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('video_access[add]', '1', $video_access->add, 'class="Icheck"'); ?> Add
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('video_access[update]', '1', $video_access->update, 'class="Icheck"'); ?> Update
											</div>
										</div>
										<?php echo form_fieldset_close(); ?>
									</div>
									<div class="col-md-4">
										<?php
										$media_access = json_decode($group->media_access);
										if (!empty($media_access)) {
											if (isset($media_access->access)) {
												$media_access->access = 1;
											} else {
												$media_access->access = 0;
											}
										} else {
											$media_access = (object) array('access' => 0);
										}
										?>
										<?php echo form_fieldset('Media Access Level', [
											'class' => 'access_level_fieldset media_access_level_fieldset',
											'data-mh' => 'heightConsistancy'
										]); ?>

										<div class="form-group">
											<div class="checkbox">
												<?php echo form_checkbox('media_access[access]', '1', $media_access->access, 'class="Icheck"'); ?> Access Administration Interface
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

				<?php echo form_hidden('group_id', set_value('group_id', $group->id)); ?>

				<div class="box-footer">
					<?php echo form_submit('submit', 'Save', 'class="btn btn-success"'); ?>
					<a href="<?php echo site_url('administrator/groups'); ?>" class="btn btn-danger">Cancel</a>
				</div>
				<?php echo form_close(); ?>

			</div>
		</div>
	</div>

</section>