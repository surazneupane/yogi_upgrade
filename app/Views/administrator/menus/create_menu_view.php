<?php

helper('form')


?>

<script type="text/javascript">
	jQuery(document).ready(function() {

		jQuery('.btn-toolbar .btn-submit').click(function() {

			var btn_task = jQuery(this).attr('btn-task');
			jQuery('input[name="task"]').val(btn_task);
			jQuery('#menu-create-form').submit();

		});

		jQuery('#menu_type').change(function(e) {

			var menu_type = jQuery(this).val();

			if (menu_type == 'category') {

				jQuery('#article_menu_type_col').hide();
				jQuery('#customlink_menu_type_col').hide();
				jQuery('#video_category_menu_type_col').hide();
				jQuery('#tag_menu_type_col').hide();
				jQuery('#category_menu_type_col').show();

			} else if (menu_type == 'video_category') {

				jQuery('#customlink_menu_type_col').hide();
				jQuery('#category_menu_type_col').hide();
				jQuery('#article_menu_type_col').hide();
				jQuery('#tag_menu_type_col').hide();
				jQuery('#video_category_menu_type_col').show();

			} else if (menu_type == 'tag') {

				jQuery('#customlink_menu_type_col').hide();
				jQuery('#category_menu_type_col').hide();
				jQuery('#article_menu_type_col').hide();
				jQuery('#video_category_menu_type_col').hide();
				jQuery('#tag_menu_type_col').show();

			} else if (menu_type == 'article') {

				jQuery('#customlink_menu_type_col').hide();
				jQuery('#category_menu_type_col').hide();
				jQuery('#video_category_menu_type_col').hide();
				jQuery('#tag_menu_type_col').hide();
				jQuery('#article_menu_type_col').show();

			} else if (menu_type == 'custom_link') {

				jQuery('#category_menu_type_col').hide();
				jQuery('#article_menu_type_col').hide();
				jQuery('#video_category_menu_type_col').hide();
				jQuery('#tag_menu_type_col').show();
				jQuery('#customlink_menu_type_col').show();

			} else {

				jQuery('#category_menu_type_col').hide();
				jQuery('#article_menu_type_col').hide();
				jQuery('#video_category_menu_type_col').hide();
				jQuery('#tag_menu_type_col').hide();
				jQuery('#customlink_menu_type_col').hide();

			}

			//Initialize Select2 Elements
			jQuery(".select2").select2();

		});

	});
</script>


<section class="content-header">
	<h1>
		Create Menu
		<small>Create site menu</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url('administrator/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="<?php echo site_url('administrator/menus'); ?>">Menus</a></li>
		<li class="active">Create menu</li>
	</ol>
</section>

<section class="content">

	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">

				<!-- form start -->
				<?php echo form_open('', array('id' => 'menu-create-form', 'class' => 'menu-create-form')); ?>

				<div class="box-header with-border">
					<div class="btn-toolbar">
						<?php echo form_button('submit_save', '<i class="fa fa-floppy-o"></i> Save', 'id="submit_save" class="btn btn-submit btn-success" btn-task="save"'); ?>
						<?php echo form_button('submit_save_close', '<i class="fa fa-check"></i> Save & Close', 'id="submit_save_close" class="btn btn-submit btn-default" btn-task="save_close"'); ?>
						<?php echo form_button('submit_save_new', '<i class="fa fa-plus"></i> Save & New', 'id="submit_save_new" class="btn btn-submit btn-default" btn-task="save_new"'); ?>
						<a href="<?php echo site_url('administrator/menus'); ?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
					</div>
				</div>

				<div class="box-body">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<?php echo form_label('Title', 'title'); ?>
								<?php echo '<span class="text-danger">' . session('errors.title') . '</span>'; ?>
								<?php echo form_input('title', '', 'class="form-control"'); ?>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<?php echo form_label('FR Title', 'title_fr'); ?>
								<?php echo '<span class="text-danger">' . session('errors.title_fr') . '</span>'; ?>
								<?php echo form_input('title_fr', '', 'class="form-control"'); ?>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<?php echo form_label('Alias', 'alias'); ?>
								<?php echo '<span class="text-danger">' . session('errors.alias') . '</span>'; ?>
								<?php echo form_input('alias', '', 'class="form-control"'); ?>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<?php echo form_label('Parent Menu', 'parent_id'); ?>
								<?php echo '<span class="text-danger">' . session('errors.parent_id') . '</span>'; ?>
								<?php
								$options = array();
								$options[''] = 'Menu Root';
								foreach ($menuslist as $menulist) {
									$options[$menulist['id']] = $menulist['title'];
								}
								echo form_dropdown('parent_id', $options, '', 'class="form-control select2"');
								?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<?php echo form_label('Menu Type', 'menu_type'); ?>
								<?php echo '<span class="text-danger">' . session('errors.menu_type') . '</span>'; ?>
								<?php
								$options   = array();
								$options[''] = 'Select Menu Type';
								$options['category'] = 'Article Category';
								$options['article'] = 'Article';
								//$options['tag'] = 'Article Tag';
								//$options['video_category'] = 'Videos Category';
								$options['custom_link'] = 'Custom Link';
								echo form_dropdown('menu_type', $options, '', 'id="menu_type" class="form-control" ');
								?>
							</div>
						</div>
						<div class="category_menu_type_col col-md-4" id="category_menu_type_col" style="display: none;">
							<div class="form-group">
								<?php echo form_label('Category', 'menu_category_alias'); ?>
								<?php echo '<span class="text-danger">' . session('errors.menu_category_alias') . '</span>'; ?>

								<?php
								$options = array();
								foreach ($categorieslist as $categorylist) {
									$options[$categorylist['alias']] = $categorylist['title'];
								}
								echo form_dropdown('menu_category_alias', $options, '', 'class="form-control select2"');
								?>
							</div>
						</div>
						<div class="video_category_menu_type_col col-md-4" id="video_category_menu_type_col" style="display: none;">
							<div class="form-group">
								<?php echo form_label('Category', 'menu_video_category_id'); ?>
								<?php echo '<span class="text-danger">' . session('errors.menu_video_category_id') . '</span>'; ?>
								<?php
								$options = array();
								$options[] = '- All Category -';
								foreach ($video_categorieslist as $video_category) {
									if ($video_category->title_en != '' && $video_category->title_en != $video_category->title_ar) {
										$options[$video_category->id] = $video_category->title_ar . ' (' . $video_category->title_en . ')';
									} else {
										$options[$video_category->id] = $video_category->title_ar;
									}
								}
								echo form_dropdown('menu_video_category_id', $options, '', 'class="form-control select2"');
								?>
							</div>
						</div>
						<div class="article_menu_type_col col-md-8" id="article_menu_type_col" style="display: none;">
							<div class="form-group">
								<?php echo form_label('Article', 'menu_article_alias'); ?>
								<?php echo '<span class="text-danger">' . session('errors.menu_article_alias') . '</span>'; ?>
								<?php
								$options = array();
								foreach ($articleslist as $articlelist) {
									$options[$articlelist['alias']] = $articlelist['title'] . ' - (Category - ' . $articlelist['categories'] . ')';
								}
								echo form_dropdown('menu_article_alias', $options, '', 'class="form-control select2"');
								?>
							</div>
						</div>
						<div class="tag_menu_type_col col-md-4" id="tag_menu_type_col" style="display: none;">
							<div class="form-group">
								<?php echo form_label('Tag', 'menu_tag_alias'); ?>
								<?php echo '<span class="text-danger">' . session('errors.menu_tag_alias') . '</span>'; ?>
								<?php
								$options = array();
								foreach ($tagslist as $taglist) {
									$options[$taglist['alias']] = $taglist['title'];
								}
								echo form_dropdown('menu_tag_alias', $options, '', 'class="form-control select2"');
								?>
							</div>
						</div>
						<div class="col-md-8" id="customlink_menu_type_col" style="display: none;">
							<div class="form-group">
								<?php echo form_label('Custom Link', 'menu_custom_link'); ?>
								<?php echo '<span class="text-danger">' . session('errors.menu_custom_link') . '</span>'; ?>
								<?php echo form_input('menu_custom_link', '#', 'class="form-control"'); ?>
							</div>
							<div class="form-group">
								<?php echo form_label('FR Custom Link', 'menu_custom_link_fr'); ?>
								<?php echo '<span class="text-danger">' . session('errors.menu_custom_link_fr') . '</span>'; ?>
								<?php echo form_input('menu_custom_link_fr', '#', 'class="form-control"'); ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<?php echo form_label('CSS Class', 'menu_class'); ?>
								<?php echo '<span class="text-danger">' . session('errors.menu_class') . '</span>'; ?>
								<?php echo form_input('menu_class', '', 'class="form-control"'); ?>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<?php echo form_label('Target Window', 'menu_target'); ?>
								<?php echo '<span class="text-danger">' . session('errors.menu_target') . '</span>'; ?>
								<?php
								$options   = array();
								$options['_parent'] = 'Parent';
								$options['_blank'] = 'New Window With Navigation';
								echo form_dropdown('menu_target', $options, '', 'id="menu_target" class="form-control" ');
								?>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<?php echo form_label('Status', 'status'); ?>
								<?php echo '<span class="text-danger">' . session('errors.status') . '</span>'; ?>
								<?php
								$options = array();
								$options[1] = 'Publish';
								$options[0] = 'Unpublish';
								echo form_dropdown('status', $options, '', 'class="form-control"');
								?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">

								<?php echo form_label('Created Date', 'created_date'); ?>
								<div class="input-group date datetimepicker-control">
									<?php echo form_input('created_date', date('Y-m-d H:i:s'), 'class="form-control pull-right"'); ?>
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
								</div>
								<?php echo '<span class="text-danger">' . session('errors.created_date') . '</span>'; ?>

							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<?php echo form_label('Created By', 'created_by'); ?>
								<?php echo '<span class="text-danger">' . session('errors.created_by') . '</span>'; ?>
								<?php
								$options = array();
								foreach ($userlist as $user) {
									$options[$user['id']] = $user['first_name'] . ' ' . $user['last_name'];
								}

								echo form_dropdown('created_by', $options, $current_user->id, 'class="form-control"');
								?>
							</div>
						</div>
					</div>
				</div>
				<!-- /.box-body -->

				<?php echo form_hidden('task', ''); ?>

				<div class="box-footer">
					<div class="btn-toolbar">
						<?php echo form_button('submit_save', '<i class="fa fa-floppy-o"></i> Save', 'id="submit_save" class="btn btn-submit btn-success" btn-task="save"'); ?>
						<?php echo form_button('submit_save_close', '<i class="fa fa-check"></i> Save & Close', 'id="submit_save_close" class="btn btn-submit btn-default" btn-task="save_close"'); ?>
						<?php echo form_button('submit_save_new', '<i class="fa fa-plus"></i> Save & New', 'id="submit_save_new" class="btn btn-submit btn-default" btn-task="save_new"'); ?>
						<a href="<?php echo site_url('administrator/menus'); ?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
					</div>
				</div>

				<?php echo form_close(); ?>

			</div>
		</div>
	</div>
</section>