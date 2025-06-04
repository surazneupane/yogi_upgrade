<?php

helper('form');

?>


<script type="text/javascript">
	jQuery(document).ready(function() {

		jQuery('.btn-toolbar .btn-submit').click(function() {

			var btn_task = jQuery(this).attr('btn-task');
			jQuery('input[name="task"]').val(btn_task);
			jQuery('#schedules-edit-form').submit();

		});

	});
</script>


<section class="content-header">
	<h1>
		<i class="fa fa-gear"></i> Schedules
		<small>Change schedules</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url('administrator/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="<?php echo site_url('administrator/schedules/edit'); ?>">Schedules</a></li>
	</ol>
</section>

<section class="content">

	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">

				<!-- form start -->
				<?php echo form_open_multipart('', array('id' => 'schedules-edit-form', 'class' => 'schedules-edit-form')); ?>

				<div class="box-header with-border">
					<div class="btn-toolbar">
						<?php echo form_button('submit_save', '<i class="fa fa-floppy-o"></i> Save', 'id="submit_save" class="btn btn-submit btn-success" btn-task="save"'); ?>
						<?php echo form_button('submit_save_close', '<i class="fa fa-check"></i> Save & Close', 'id="submit_save_close" class="btn btn-submit btn-default" btn-task="save_close"'); ?>
						<a href="<?php echo site_url('administrator/dashboard'); ?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
					</div>
				</div>

				<div class="box-body data-tabs">

					<ul class="nav nav-tabs" role="tablist">
						<li role="presentation" class="active"><a href="#details" aria-controls="home" role="tab" data-toggle="tab">EN Details</a></li>
						<li role="presentation"><a href="#details_fr" aria-controls="details_fr" role="tab" data-toggle="tab">FR Details</a></li>
					</ul>
					<!-- Tab panes -->
					<div class="tab-content">

						<!--  DETAILS	[START]		-->
						<div role="tabpanel" class="tab-pane active" id="details">

							<div class="form-group">
								<?php echo form_label('Schedule', 'schedule_data'); ?>
								<?php echo '<span class="text-danger">' . session('errors.schedule_data') . '</span>'; ?>
								<?php echo form_textarea('schedule_data', $schedules['schedule_data'], 'class="textarea"'); ?>
							</div>

							<div class="form-group">
								<?php echo form_label('Pricing', 'pricing_data'); ?>
								<?php echo '<span class="text-danger">' . session('errors.pricing_data') . '</span>'; ?>
								<?php echo form_textarea('pricing_data', $schedules['pricing_data'], 'class="textarea"'); ?>
							</div>


						</div>
						<!--  DETAILS	[START]		-->
						<div role="tabpanel" class="tab-pane" id="details_fr">

							<div class="form-group">
								<?php echo form_label('FR Schedule', 'schedule_data_fr'); ?>
								<?php echo '<span class="text-danger">' . session('errors.schedule_data_fr') . '</span>'; ?>
								<?php echo form_textarea('schedule_data_fr', $schedules['schedule_data_fr'], 'class="textarea"'); ?>
							</div>

							<div class="form-group">
								<?php echo form_label('FR Pricing', 'pricing_data_fr'); ?>
								<?php echo '<span class="text-danger">' . session('errors.pricing_data_fr') . '</span>'; ?>
								<?php echo form_textarea('pricing_data_fr', $schedules['pricing_data_fr'], 'class="textarea"'); ?>
							</div>

						</div>

					</div>

					<!--<hr/>-->

				</div>
				<!-- /.box-body -->

				<?php echo form_hidden('task', ''); ?>

				<div class="box-footer">
					<div class="btn-toolbar">
						<?php echo form_button('submit_save', '<i class="fa fa-floppy-o"></i> Save', 'id="submit_save" class="btn btn-submit btn-success" btn-task="save"'); ?>
						<?php echo form_button('submit_save_close', '<i class="fa fa-check"></i> Save & Close', 'id="submit_save_close" class="btn btn-submit btn-default" btn-task="save_close"'); ?>
						<a href="<?php echo site_url('administrator/dashboard'); ?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
					</div>
				</div>

				<?php echo form_close(); ?>

			</div>
		</div>
	</div>

</section>