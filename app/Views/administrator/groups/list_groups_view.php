<?php

use App\Libraries\IonAuth;

$ionAuthLibrary = new IonAuth();


?>

<script type="text/javascript">
	jQuery(document).ready(function () {	
		
		jQuery('#grouplist-datTable').DataTable();
		
		jQuery('.btn-delete').click( function() {
			
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
		Groups
		<small>User groups</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url('administrator/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li class="active">Groups</li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-primary">
	            <div class="box-header">
					<!--<h3 class="box-title">Group List</h3>-->
					<a class="btn btn-primary <?php if(!$ionAuthLibrary->in_access('group_access', 'add')) { ?>disabled<?php } ?>" href="<?php echo site_url('administrator/groups/create'); ?>"><i class="fa fa-plus-circle"></i> Create group</a>
	            </div>
	            <!-- /.box-header -->
	            <div class="box-body">
	            	<?php if(!empty($groups)) { ?>
	            		
						<table id="grouplist-datTable" class="table table-bordered table-striped grouplist-datTable">
							<thead>
								<tr>
									<th>ID</th>
									<th>Name</th>
									<th>Description</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($groups as $group) { ?>
								<tr>
									<td><?php echo $group->id; ?></td>
									<td><?php echo anchor('administrator/users/index/'.$group->id,$group->name); ?></td>
									<td><?php echo $group->description; ?></td>
									<td>
										<?php 
										$class = 'btn btn-info';
										if(!$ionAuthLibrary->in_access('group_access', 'update')) {  $class .= ' disabled'; } 
										?>
										<?php echo anchor('administrator/groups/edit/'.$group->id,'<i class="fa fa-pencil"></i>', array('class' => $class, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit')); ?>
										<?php if(!in_array($group->name, array('admin'))) { ?>
											<a href="javascript:void(0)" data-url="<?php echo site_url('administrator/groups/delete/'.$group->id); ?>" class="btn btn-danger btn-delete <?php if(!$ionAuthLibrary->in_access('group_access', 'delete')) { ?>disabled<?php } ?>" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
										<?php } ?>
									</td>
								</tr>
								<?php } ?>
							</tbody>
							<tfoot>
								<tr>
									<th>ID</th>
									<th>Name</th>
									<th>Description</th>
									<th>Action</th>
								</tr>
							</tfoot>
						</table>
						
					<?php } else { ?>
						<div class="alert alert-info">
							<h4><i class="icon fa fa-info"></i> Alert!</h4>
							Sorry. No Records at the moment.
						</div>
					<?php } ?>
	            </div>
           	 	<!-- /.box-body -->
          </div>
          <!-- /.box -->
		</div>
	</div>
</section>