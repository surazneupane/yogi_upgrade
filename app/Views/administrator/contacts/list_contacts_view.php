<?php

use App\Libraries\IonAuth;

helper('form');

$ionAuthLibrary = new IonAuth();

$contacts_filter = session()->get('contacts_filter');

?>

<script type="text/javascript">
	function deleteContact(dataUrl) {

		var url = dataUrl;
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

	}

	jQuery(document).ready(function() {

		/*jQuery('#contactlist-datTable').DataTable({
		  "ordering": false
		});*/

		// initialize the datatable 
		manageTable = jQuery('#contactlist-datTable').DataTable({
			"bStateSave": true,
			"processing": true,
			"serverSide": true,
			'ajax': baseURL + 'administrator/contacts/fetchContactData',
			'order': [],
			"columnDefs": [{
					"name": "check",
					"orderable": false,
					"targets": 0
				},
				{
					"name": "id",
					"orderable": true,
					"targets": 1
				},
				{
					"name": "full_name",
					"orderable": true,
					"targets": 2
				},
				{
					"name": "email_address",
					"orderable": true,
					"targets": 3
				},
				{
					"name": "message",
					"orderable": true,
					"targets": 4
				},
				{
					"name": "created_date",
					"orderable": true,
					"targets": 5
				},
				{
					"name": "action",
					"orderable": false,
					"targets": 6
				}
			]
		});

		jQuery("#checkAll").click(function() {
			jQuery('.contact-list-form input:checkbox').not(this).prop('checked', this.checked);
		});

		jQuery('.btn-toolbar .btn-submit').click(function() {

			var dataCount = <?php echo count($contacts); ?>;
			if (jQuery('#contactlist-datTable input[type="checkbox"]:checked').length != 0 && dataCount != 0) {

				var btn_task = jQuery(this).attr('btn-task');
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
							jQuery('#contact-list-form').submit();

						} else {

						}
					});

			} else {

				swal("Please select checkbox.");

			}

		});

		jQuery('.btn-clear').click(function() {

			jQuery('select[name="filter[status]"]').val('');
			jQuery('#contact-list-form').submit();

		});

	});
</script>


<section class="content-header">
	<h1>
		Contacts
		<small>Site contacts</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url('administrator/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li class="active">Contacts</li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-primary">

				<?php echo form_open('', array('id' => 'contact-list-form', 'class' => 'contact-list-form')); ?>

				<div class="box-header with-border">
					<div class="btn-toolbar">
						<?php
						$delete_class = 'btn btn-submit btn-danger';
						$disabled = '';
						if (!$ionAuthLibrary->in_access('contact_access', 'delete')) {
							$delete_class .= ' disabled';
							$disabled = 'disabled="disabled"';
						}
						?>
						<?php echo form_button('submit_delete', '<i class="fa fa-trash"></i> Delete', 'id="submit_delete" class="' . $delete_class . '" btn-task="delete" ' . $disabled); ?>
					</div>
				</div>

				<!--<div class="box-header with-border">
		            	<div class="row">
		            		<div class="col-md-2">
					            <?php
								//									$options = array();
								//									$options[''] = '- Select Status -';
								//									$options[1] = 'Publish';
								//									$options[0] = 'Unpublish';
								//									echo form_dropdown('filter[status]', $options, $contacts_filter['status'], 'class="form-control select2" data-placeholder="- Select Status -"');
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
					</div>-->

				<!-- /.box-header -->
				<div class="box-body">


					<table id="contactlist-datTable" class="table table-bordered table-striped contactlist-datTable">
						<thead>
							<tr>
								<th class="text-center" width="1%">
									<input type="checkbox" id="checkAll" name="checkall-toggle" value="" data-toggle="tooltip" data-placement="top" title="Check All Items" />
								</th>
								<th class="text-center" width="1%">ID</th>
								<th width="20%">Full Name</th>
								<th width="20%">Email</th>
								<th>Message</th>
								<th class="text-center" width="10%">Created Date</th>
								<th class="text-center" width="5%">Action</th>
							</tr>
						</thead>
						<tbody>

						</tbody>
						<tfoot>
							<tr>
								<th class="text-center" width="1%">
									<!--<input type="checkbox" name="checkall-toggle" value="" data-toggle="tooltip" title="" onclick="checkAll()" data-placement="top" title="Check All Items" />-->
								</th>
								<th class="text-center" width="1%">ID</th>
								<th width="20%">Full Name</th>
								<th width="20%">Email</th>
								<th>Message</th>
								<th class="text-center" width="10%">Created Date</th>
								<th class="text-center" width="5%">Action</th>
							</tr>
						</tfoot>
					</table>



					<?php echo form_hidden('task', ''); ?>

				</div>
				<!-- /.box-body -->
				<?php echo form_close(); ?>

			</div>
			<!-- /.box -->
		</div>
	</div>
</section>