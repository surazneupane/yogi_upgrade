<?php

use App\Libraries\IonAuth;

$ionAuthLibrary = new IonAuth();
helper('form');

?>

<script type="text/javascript">
	jQuery(document).ready(function() {

		var categorylistdatTable = jQuery('#categorylist-datTable').DataTable({
			"ordering": false,
			stateSave: true
		});

		jQuery("#checkAll").click(function() {
			jQuery('.category-list-form input:checkbox').not(this).prop('checked', this.checked);
		});

		jQuery('#submit_orderingsave').click(function() {

			var btn_task = jQuery(this).attr('btn-task');
			jQuery('input[name="task"]').val(btn_task);
			jQuery('#category-list-form').submit();

		});

		jQuery('.btn-toolbar .btn-submit').click(function() {

			var dataCount = <?php echo count($categories); ?>;
			if (jQuery('#categorylist-datTable input[type="checkbox"]:checked').length != 0 && dataCount != 0) {

				var btn_task = jQuery(this).attr('btn-task');
				if (btn_task == 'publish') {
					var alertTitle = "Are you sure?";
					var alertText = "Want to publish selected data?";
				}
				if (btn_task == 'unpublish') {
					var alertTitle = "Are you sure?";
					var alertText = "Want to unpublish selected data?";
				}
				if (btn_task == 'delete') {
					var alertTitle = "Are you sure?";
					var alertText = "Once deleted, you will not be able to recover this data!";
				}
				swal({
						title: alertTitle,
						text: alertText,
						icon: "warning",
						buttons: true,
						dangerMode: true,
					})
					.then((willConfirm) => {
						if (willConfirm) {
							jQuery('input[name="task"]').val(btn_task);
							jQuery('#category-list-form').submit();
						} else {

						}
					});

			} else {

				swal("Please select checkbox.");
			}

		});

		jQuery('#categorylist-datTable').on("click", ".btn-delete", function(e) {

			var url = jQuery(this).attr("data-url");
			swal({
					title: "Are you sure?",
					text: "Once deleted, you will not be able to recover this data!",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				})
				.then((willConfirm) => {
					if (willConfirm) {
						window.location.href = url;
					} else {
						return false;
					}
				});

		});

		var parentCheck = 0;
		jQuery('.btn-parent-category-toggle').click(function() {
			if (parentCheck == 0) {
				jQuery.fn.dataTable.ext.search.push(
					function(settings, data, dataIndex) {
						return jQuery(categorylistdatTable.row(dataIndex).node()).attr('parent-category') == 0;
					}
				);
				categorylistdatTable.draw();
				parentCheck = 1;
			} else {
				jQuery.fn.dataTable.ext.search.pop();
				categorylistdatTable.draw();
				parentCheck = 0;
			}
		});

	});
</script>


<section class="content-header">
	<h1>
		Categories
		<small>Article categories</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url('administrator/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li class="active">Categories</li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-primary">

				<?php echo form_open('', array('id' => 'category-list-form', 'class' => 'category-list-form')); ?>

				<div class="box-header with-border">
					<div class="btn-toolbar">
						<a class="btn btn-success <?php if (!$ionAuthLibrary->in_access('category_access', 'add')) { ?>disabled<?php } ?>" href="<?php echo site_url('administrator/categories/create'); ?>"><i class="fa fa-plus-circle"></i> Create category</a>
						<?php
						$publish_class = 'btn btn-submit btn-default';
						$disabled = '';
						if (!$ionAuthLibrary->in_access('category_access', 'update')) {
							$publish_class .= ' disabled';
							$disabled = 'disabled="disabled"';
						}
						?>
						<?php echo form_button('submit_publish', '<i class="fa fa-check"></i> Publish', 'id="submit_publish" class="' . $publish_class . '" btn-task="publish" ' . $disabled); ?>
						<?php
						$unpublish_class = 'btn btn-submit btn-default';
						$disabled = '';
						if (!$ionAuthLibrary->in_access('category_access', 'update')) {
							$unpublish_class .= ' disabled';
							$disabled = 'disabled="disabled"';
						}
						?>
						<?php echo form_button('submit_unpublish', '<i class="fa fa-times-circle"></i> Unpublish', 'id="submit_unpublish" class="' . $unpublish_class . '" btn-task="unpublish" ' . $disabled); ?>
						<?php
						$delete_class = 'btn btn-submit btn-danger';
						$disabled = '';
						if (!$ionAuthLibrary->in_access('category_access', 'delete')) {
							$delete_class .= ' disabled';
							$disabled = 'disabled="disabled"';
						}
						?>
						<?php echo form_button('submit_delete', '<i class="fa fa-trash"></i> Delete', 'id="submit_delete" class="' . $delete_class . '" btn-task="delete" ' . $disabled); ?>
						<?php echo form_button('btn_parent_category_toggle', '<i class="fa fa-bars"></i> Toggle Parent Category', 'id="btn_parent_category_toggle" class="btn btn-parent-category-toggle btn-info"'); ?>
					</div>
				</div>

				<!-- /.box-header -->
				<div class="box-body">

					<?php if (!empty($categories)) { ?>

						<table id="categorylist-datTable" class="table table-bordered table-striped categorylist-datTable">
							<thead>
								<tr>
									<th class="text-center" width="1%">
										<input type="checkbox" id="checkAll" name="checkall-toggle" value="" data-toggle="tooltip" data-placement="top" title="Check All Items" />
									</th>
									<th class="text-center" width="1%">ID</th>
									<th class="text-center" width="1%">Status</th>
									<th class="text-center" width="5%">
										<?php
										$order_class = 'btn btn-sm btn-default';
										$disabled = '';
										if (!$ionAuthLibrary->in_access('category_access', 'update')) {
											$order_class .= ' disabled';
											$disabled = 'disabled="disabled"';
										}
										?>
										<?php echo form_button('submit_orderingsave', '<i class="fa fa-floppy-o"></i>', 'id="submit_orderingsave" class="' . $order_class . '" btn-task="orderingsave" data-toggle="tooltip" data-placement="top" title="Save Order" ' . $disabled); ?>
									</th>
									<th>Title</th>
									<th class="text-center" width="1%"><i class="fa fa-check text-success"></i></th>
									<th class="text-center" width="1%"><i class="fa fa-times-circle text-danger"></i></th>
									<th class="text-center" width="10%">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php $i = 0;
								foreach ($categories as $category) { ?>
									<?php
									$published_articles = getArticlesByCatID($category['id'], '1');
									$published_articles_count = count($published_articles);

									$unpublished_articles = getArticlesByCatID($category['id'], '0');
									$unpublished_articles_count = count($unpublished_articles);
									?>
									<tr parent-category="<?php echo $category['parent_id']; ?>">
										<td class="text-center">
											<input type="checkbox" id="category_id<?php echo $i; ?>" name="category_id[]" value="<?php echo $category['id']; ?>" />
										</td>
										<td class="text-center"><?php echo $category['id']; ?></td>
										<td class="text-center">
											<?php $staus = $category['status']; ?>
											<?php if ($staus == 1) { ?>
												<?php echo anchor('administrator/categories/unpublish/' . $category['id'], '<i class="fa fa-check"></i>', array('class' => 'btn btn-sm btn-default active', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Unpublish Item')); ?>
											<?php } else { ?>
												<?php echo anchor('administrator/categories/publish/' . $category['id'], '<i class="fa fa-times-circle"></i>', array('class' => 'btn btn-sm btn-default active', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Publish Item')); ?>
											<?php } ?>
										</td>
										<td>
											<?php echo form_input('ordering[]', $category['ordering'], 'class="form-control ordering-input"'); ?>
											<?php echo form_hidden('ordering_ids[]', $category['id']); ?>
										</td>
										<td><?php echo anchor('administrator/categories/edit/' . $category['id'], $category['title'], array('class' => '', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit')); ?> <span class="small">(Alias : <?php echo $category['alias']; ?>)</span></td>
										<td class="text-center">
											<?php echo anchor('#', $published_articles_count, array('class' => 'label label-success', 'title' => 'Published items')); ?>
										</td>
										<td class="text-center">
											<?php echo anchor('#', $unpublished_articles_count, array('class' => 'label label-danger', 'title' => 'Unpublished items')); ?>
										</td>
										<td class="text-center">
											<?php
											$class = 'btn btn-success';
											if (!$ionAuthLibrary->in_access('category_access', 'update')) {
												$class .= ' disabled';
											}
											?>
											<?php echo anchor('administrator/categories/edit/' . $category['id'], '<i class="fa fa-pencil"></i>', array('class' => $class, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit')); ?>
											<?php if (!in_array($category['title'], array('admin'))) { ?>
												<a href="javascript:void(0)" data-url="<?php echo site_url('administrator/categories/delete/' . $category['id']); ?>" class="btn btn-danger btn-delete <?php if (!$ionAuthLibrary->in_access('category_access', 'delete')) { ?>disabled<?php } ?>" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
											<?php } ?>
										</td>
									</tr>
								<?php $i++;
								} ?>
							</tbody>
							<tfoot>
								<tr>
									<th class="text-center" width="1%">
										<!--<input type="checkbox" name="checkall-toggle" value="" data-toggle="tooltip" title="" onclick="checkAll()" data-placement="top" title="Check All Items" />-->
									</th>
									<th class="text-center" width="1%">ID</th>
									<th class="text-center" width="1%">Status</th>
									<th class="text-center" width="5%">Order</th>
									<th>Title</th>
									<th class="text-center" width="1%"><i class="fa fa-check text-success"></i></th>
									<th class="text-center" width="1%"><i class="fa fa-times-circle text-danger"></i></th>
									<th class="text-center" width="10%">Action</th>
								</tr>
							</tfoot>
						</table>

					<?php } else { ?>
						<div class="alert alert-info">
							<h4><i class="icon fa fa-info"></i> Alert!</h4>
							Sorry. No Records at the moment.
						</div>
					<?php } ?>

					<?php echo form_hidden('task', ''); ?>

				</div>
				<!-- /.box-body -->
				<?php echo form_close(); ?>

			</div>
			<!-- /.box -->
		</div>
	</div>
</section>