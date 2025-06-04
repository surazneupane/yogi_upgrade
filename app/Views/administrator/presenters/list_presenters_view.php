<?php

use App\Libraries\IonAuth;

$ionAuthLibrary = new IonAuth();

helper('form');

$presenters_filter = session()->get('presenters_filter');

?>

<script type="text/javascript">
	jQuery(document).ready(function () {
		
		jQuery('#presenterlist-datTable').DataTable({
		  "ordering": false
		});
		
		jQuery("#checkAll").click(function () {
			jQuery('.presenter-list-form input:checkbox').not(this).prop('checked', this.checked);
		});
		
		jQuery('.btn-toolbar .btn-submit').click( function() {
									
			var dataCount = <?php echo count($presenters); ?>;
			if (jQuery('#presenterlist-datTable input[type="checkbox"]:checked').length != 0 && dataCount != 0) {
				
				var btn_task = jQuery(this).attr('btn-task');
				if(btn_task == 'publish') {
					var alertTitle = "Are you sure?";
					var alertText = "Want to publish selected data?";
				}
				if(btn_task == 'unpublish') {
					var alertTitle = "Are you sure?";
					var alertText = "Want to unpublish selected data?";
				}
				if(btn_task == 'delete') {
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
						jQuery('#presenter-list-form').submit();
					} else {
						
					}
				});
				
			} else {
				
		        swal("Please select checkbox.");
		    }
			
			
		});
				
		jQuery('.btn-clear').click( function() {
						
			jQuery('select[name="filter[status]"]').val('');
			jQuery('#presenter-list-form').submit();
			
		});
		
		jQuery('#presenterlist-datTable').on("click", ".btn-delete", function(e) {
			
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
		
			
	});
</script>


<section class="content-header">
	<h1>
		Presenters
		<small>Video presenters</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url('administrator/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li class="active">Presenters</li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-primary">
			
				<?php echo form_open('',array('id'=>'presenter-list-form', 'class'=>'presenter-list-form')); ?>
			
		            <div class="box-header with-border">
		            	<div class="btn-toolbar">
							<a class="btn btn-success <?php if(!$ionAuthLibrary->in_access('presenter_access', 'add')) { ?>disabled<?php } ?>" href="<?php echo site_url('administrator/presenters/create');?>"><i class="fa fa-plus-circle"></i> Add Presenter</a>
							<?php 
							$publish_class = 'btn btn-submit btn-default';
							$disabled = '';
							if(!$ionAuthLibrary->in_access('presenter_access', 'update')) {  $publish_class .= ' disabled'; $disabled = 'disabled="disabled"'; } 
							?>
							<?php echo form_button('submit_publish', '<i class="fa fa-check"></i> Publish', 'id="submit_publish" class="'.$publish_class.'" btn-task="publish" '.$disabled);?>
							<?php 
							$unpublish_class = 'btn btn-submit btn-default';
							$disabled = '';
							if(!$ionAuthLibrary->in_access('presenter_access', 'update')) {  $unpublish_class .= ' disabled'; $disabled = 'disabled="disabled"'; } 
							?>
							<?php echo form_button('submit_unpublish', '<i class="fa fa-times-circle"></i> Unpublish', 'id="submit_unpublish" class="'.$unpublish_class.'" btn-task="unpublish" '.$disabled);?>
							<?php 
							$delete_class = 'btn btn-submit btn-danger';
							$disabled = '';
							if(!$ionAuthLibrary->in_access('presenter_access', 'delete')) {  $delete_class .= ' disabled'; $disabled = 'disabled="disabled"'; } 
							?>
							<?php echo form_button('submit_delete', '<i class="fa fa-trash"></i> Delete', 'id="submit_delete" class="'.$delete_class.'" btn-task="delete" '.$disabled);?>
		            	</div>
		            </div>
		            
		            <div class="box-header with-border">
		            	<div class="row">
		            		<div class="col-md-2">
					            <?php 
									$options = array();
									$options[''] = '- Select Status -';
									$options[1] = 'Publish';
									$options[0] = 'Unpublish';
									echo form_dropdown('filter[status]', $options, $presenters_filter['status'] ?? null, 'class="form-control select2" data-placeholder="- Select Status -"');
								?>
							</div>
							<div class="col-md-2">
					            <button type="submit" class="btn btn-primary btn-search" data-toggle="tooltip" data-placement="top" title="Search">
					            	<i class="fa fa-search"></i>&nbsp; Search
					            </button>
					            <button type="button" class="btn btn-default btn-clear" data-toggle="tooltip" data-placement="top" title="Clear">
					            	Clear
					            </button>
							</div>
						</div>
					</div>
		            
		            <!-- /.box-header -->
		            <div class="box-body">
		            	<?php if(!empty($presenters)) { ?>
		            		
							<table id="presenterlist-datTable" class="table table-bordered table-striped presenterlist-datTable">
								<thead>
									<tr>
										<th class="text-center" width="1%">
											<input type="checkbox" id="checkAll" name="checkall-toggle" value="" data-toggle="tooltip" data-placement="top" title="Check All Items" />
										</th>
										<th class="text-center" width="1%">ID</th>
										<th class="text-center" width="10%">Status</th>
										<th>Name</th>
										<th class="text-center" width="15%">Created Date</th>
										<th class="text-center" width="15%">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php $i = 0; foreach($presenters as $presenter) { ?>
									<tr>
										<td class="text-center">
											<input type="checkbox" id="presenter_id<?php echo $i; ?>" name="presenter_id[]" value="<?php echo $presenter['id']; ?>" />
										</td>
										<td class="text-center"><?php echo $presenter['id']; ?></td>
										<td class="text-center">
											<div class="btn-group">
												<?php $staus = $presenter['status']; ?>
												<?php if($staus == 1) { ?>
													<?php echo anchor('administrator/presenters/unpublish/'.$presenter['id'], '<i class="fa fa-check"></i>', array('class' => 'btn btn-sm btn-default active', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Unpublish Item')); ?>
												<?php } else { ?>
													<?php echo anchor('administrator/presenters/publish/'.$presenter['id'], '<i class="fa fa-times-circle"></i>', array('class' => 'btn btn-sm btn-default active', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Publish Item')); ?>
												<?php } ?>
											</div>
										</td>
										<td>
											<?php echo anchor('administrator/presenters/edit/'.$presenter['id'],$presenter['name'], array('class' => '', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit')); ?> <span class="small">(Alias : <?php echo $presenter['alias']; ?>)</span>
											</td>
										<td class="text-center">
											<?php echo date('Y-m-d', strtotime($presenter['created_date'])); ?>
										</td>
										<td class="text-center">
											<?php 
											$class = 'btn btn-success';
											if(!$ionAuthLibrary->in_access('presenter_access', 'update')) {  $class .= ' disabled'; } 
											?>
											<?php echo anchor('administrator/presenters/edit/'.$presenter['id'],'<i class="fa fa-pencil"></i>', array('class' => $class, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit')); ?>
											<?php if(!in_array($presenter['name'], array('admin'))) { ?>
												<a href="javascript:void(0)" data-url="<?php echo site_url('administrator/presenters/delete/'.$presenter['id']); ?>" class="btn btn-danger btn-delete <?php if(!$ionAuthLibrary->in_access('presenter_access', 'delete')) { ?>disabled<?php } ?>" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
											<?php } ?>
										</td>
									</tr>
									<?php $i++; } ?>
								</tbody>
								<tfoot>
									<tr>
										<th class="text-center" width="1%">
											<!--<input type="checkbox" name="checkall-toggle" value="" data-toggle="tooltip" title="" onclick="checkAll()" data-placement="top" title="Check All Items" />-->
										</th>
										<th class="text-center" width="1%">ID</th>
										<th class="text-center" width="10%">Status</th>
										<th>Name</th>
										<th class="text-center" width="15%">Created Date</th>
										<th class="text-center" width="15%">Action</th>
									</tr>
								</tfoot>
							</table>
							
						<?php } else { ?>
							<div class="alert alert-info">
								<h4><i class="icon fa fa-info"></i> Alert!</h4>
								Sorry. No Records at the moment.
							</div>
						<?php } ?>
						
						<?php echo form_hidden('task', '');?>
						
		            </div>
	           	 	<!-- /.box-body -->
	           	 <?php echo form_close();?>
	           	 
          </div>
          <!-- /.box -->
		</div>
	</div>
</section>