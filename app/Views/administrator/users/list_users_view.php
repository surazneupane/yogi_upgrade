<?php

use App\Libraries\IonAuth;
use App\Models\IonAuthModel;

$ionAuthModel = new IonAuthModel();
$ionAuthLibrary = new IonAuth();
$current_user_groups = $ionAuthModel->get_users_groups($current_user->id)->getRow();
$current_user_groups = (array) $current_user_groups;

?>


<script type="text/javascript">
	jQuery(document).ready(function() {

		jQuery('#userslist-datTable').DataTable();

		jQuery('#userslist-datTable').on("click", ".btn-delete", function(e) {

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
		Users
		<small>User List</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url('administrator/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li class="active">Users</li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-primary">
				<div class="box-header">
					<!--<h3 class="box-title">Group List</h3>-->
					<a class="btn btn-primary <?php if (!$ionAuthLibrary->in_access('user_access', 'add')) { ?>disabled<?php } ?>" href="<?php echo site_url('administrator/users/create'); ?>"><i class="fa fa-plus-circle"></i> Create user</a>
					<a class="btn btn-warning" href="<?php echo site_url('administrator/users/mailchimpsynch'); ?>"><i class="fa fa-refresh"></i>&nbsp; MailChimp Sychronization</a>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<?php if (!empty($users)) { ?>

						<table id="userslist-datTable" class="table table-bordered table-striped userslist-datTable">
							<thead>
								<tr>
									<th>ID</th>
									<th>Username</th>
									<th>Name</th>
									<th>Email</th>
									<th>Group</th>
									<th>Last login</th>
									<th>Total Articles</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($users as $user) { ?>
									<tr>
										<td><?php echo $user->id; ?></td>
										<td><?php echo $user->username; ?></td>
										<td><?php echo $user->first_name . ' ' . $user->last_name; ?></td>
										<td><?php echo $user->email; ?></td>
										<td>
											<?php
											$user_groups = $ionAuthModel->get_users_groups($user->id)->getResult();
											$groups = array();
											foreach ($user_groups as $user_group) {
												$groups[] = $user_group->name;
											}
											?>
											<span class="text-capitalize"><?php echo implode(', ', $groups); ?></span>
										</td>
										<td><?php if ($user->last_login != '') {
												echo date('Y-m-d H:i:s', $user->last_login);
											} else {
												echo 'Not yet.';
											} ?></td>
										<td>
											<?php

											$counter = GetArticleCounterByUserId($user->id);
											if ($counter <= 20) {
												$hit_class = 'bg-primary';
											} elseif ($counter <= 40) {
												$hit_class = 'bg-primary';
											} elseif ($counter <= 60) {
												$hit_class = 'bg-red';
											} elseif ($counter <= 80) {
												$hit_class = 'bg-red';
											} elseif ($counter <= 100) {
												$hit_class = 'bg-green';
											} else {
												$hit_class = 'bg-green';
											}
											?>
											<a href="javascript:void(0)" class="label <?php echo $hit_class; ?>" title="<?php echo $counter . ' Articles'; ?>"><?php echo $counter; ?></a>
										</td>
										<td>
											<?php if ($current_user->id == $user->id || in_array('admin', $current_user_groups)) { ?>
												<?php
												$class = 'btn btn-info';
												if (!$ionAuthLibrary->in_access('user_access', 'update')) {
													$class .= ' disabled';
												}
												?>
												<?php echo anchor('administrator/users/edit/' . $user->id, '<i class="fa fa-pencil"></i>', array('class' => $class, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit')); ?>
											<?php } ?>
											<?php if ($current_user->id != $user->id) { ?>
												<?php // echo anchor('administrator/users/delete/'.$user->id,'<i class="fa fa-trash"></i>', array('class' => 'btn btn-danger', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Delete')); 
												?>
												<a href="javascript:void(0)" data-url="<?php echo site_url('administrator/users/delete/' . $user->id); ?>" class="btn btn-danger btn-delete <?php if (!$ionAuthLibrary->in_access('user_access', 'delete')) { ?>disabled<?php } ?>" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
											<?php } ?>
										</td>
									</tr>
								<?php } ?>
							</tbody>
							<tfoot>
								<tr>
									<th>ID</th>
									<th>Username</th>
									<th>Name</th>
									<th>Email</th>
									<th>Group</th>
									<th>Last login</th>
									<th>Total Articles</th>
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