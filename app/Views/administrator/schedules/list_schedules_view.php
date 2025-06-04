<?php



$schedules_filter = $this->session->userdata('schedules_filter');

?>

<script type="text/javascript">

	function deleteSchedule(dataUrl) {
	
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

	jQuery(document).ready(function () {
		
		/*jQuery('#schedulelist-datTable').DataTable({
		  "ordering": false
		});*/
		
		// initialize the datatable 
		manageTable = jQuery('#schedulelist-datTable').DataTable({
			"bStateSave": true,
			"processing": true,
        	"serverSide": true,
			'ajax': baseURL + 'administrator/schedules/fetchScheduleData',
			'order': [],
			"columnDefs": [
				{ "name": "check", "orderable": false,  "targets": 0 },
			    { "name": "id", "orderable": true,  "targets": 1 },
			    { "name": "status", "orderable": true,  "targets": 2 },
			    { "name": "title", "orderable": true,  "targets": 3 },
			    { "name": "created_date", "orderable": true,  "targets": 4 },
			    { "name": "action", "orderable": false,  "targets": 5 }
			]
		});
		
		jQuery("#checkAll").click(function () {
			jQuery('.schedule-list-form input:checkbox').not(this).prop('checked', this.checked);
		});
		
		jQuery('.btn-toolbar .btn-submit').click( function() {
			
			var dataCount = <?php echo count($schedules); ?>;
			if (jQuery('#schedulelist-datTable input[type="checkbox"]:checked').length != 0 && dataCount != 0) {
				
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
						jQuery('#schedule-list-form').submit();
						
					} else {
						
					}
				});
				
			} else {
				
		        swal("Please select checkbox.");
		     	
		    }
		
		});
		
		jQuery('.btn-clear').click( function() {
		
			jQuery('select[name="filter[status]"]').val('');
			jQuery('#schedule-list-form').submit();
		
		});
		
		/*jQuery('.btn-delete').on('click', function() {
		
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
		
		});*/
		
	});
</script>


<section class="content-header">
	<h1>
		Schedules
		<small>Yoga schedules</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url('administrator/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li class="active">Schedules</li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-primary">
			
				<?php echo form_open('',array('id'=>'schedule-list-form', 'class'=>'schedule-list-form')); ?>
			
		            <div class="box-header with-border">
		            	<div class="btn-toolbar">
							<a class="btn btn-success <?php if(!$this->ion_auth->in_access('schedule_access', 'add')) { ?>disabled<?php } ?>" href="<?php echo site_url('administrator/schedules/create');?>"><i class="fa fa-plus-circle"></i> Add Schedule</a>
							<?php 
							$publish_class = 'btn btn-submit btn-default';
							$disabled = '';
							if(!$this->ion_auth->in_access('schedule_access', 'update')) {  $publish_class .= ' disabled'; $disabled = 'disabled="disabled"'; } 
							?>
							<?php echo form_button('submit_publish', '<i class="fa fa-check"></i> Publish', 'id="submit_publish" class="'.$publish_class.'" btn-task="publish" '.$disabled);?>
							<?php 
							$unpublish_class = 'btn btn-submit btn-default';
							$disabled = '';
							if(!$this->ion_auth->in_access('schedule_access', 'update')) {  $unpublish_class .= ' disabled'; $disabled = 'disabled="disabled"'; } 
							?>
							<?php echo form_button('submit_unpublish', '<i class="fa fa-times-circle"></i> Unpublish', 'id="submit_unpublish" class="'.$unpublish_class.'" btn-task="unpublish" '.$disabled);?>
							<?php 
							$delete_class = 'btn btn-submit btn-danger';
							$disabled = '';
							if(!$this->ion_auth->in_access('schedule_access', 'delete')) {  $delete_class .= ' disabled'; $disabled = 'disabled="disabled"'; } 
							?>
							<?php echo form_button('submit_delete', '<i class="fa fa-trash"></i> Delete', 'id="submit_delete" class="'.$delete_class.'" btn-task="delete" '.$disabled);?>
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
//									echo form_dropdown('filter[status]', $options, $schedules_filter['status'], 'class="form-control select2" data-placeholder="- Select Status -"');
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
		            	
		            		
							<table id="schedulelist-datTable" class="table table-bordered table-striped schedulelist-datTable">
								<thead>
									<tr>
										<th class="text-center" width="1%">
											<input type="checkbox" id="checkAll" name="checkall-toggle" value="" data-toggle="tooltip" data-placement="top" title="Check All Items" />
										</th>
										<th class="text-center" width="1%">ID</th>
										<th class="text-center" width="10%">Status</th>
										<th>Title</th>
										<th class="text-center" width="15%">Created Date</th>
										<th class="text-center" width="15%">Action</th>
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
										<th class="text-center" width="10%">Status</th>
										<th>Title</th>
										<th class="text-center" width="15%">Created Date</th>
										<th class="text-center" width="15%">Action</th>
									</tr>
								</tfoot>
							</table>
							
						
						
						<?php echo form_hidden('task', '');?>
						
		            </div>
	           	 	<!-- /.box-body -->
	           	 <?php echo form_close();?>
	           	 
          </div>
          <!-- /.box -->
		</div>
	</div>
</section>