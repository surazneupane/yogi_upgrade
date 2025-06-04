
<script type="text/javascript">
	jQuery(document).ready(function () {
		
		/*jQuery('#videolist-datTable').DataTable({
		  "ordering": false
		});*/
		
		// initialize the datatable 
		manageTable = jQuery('#videolist-datTable').DataTable({
			"bStateSave": true,
			"processing": true,
        	"serverSide": true,
			'ajax': baseURL + 'administrator/videos/fetchVideoData',
			'order': [],
			"columnDefs": [
			    { "name": "id", "orderable": true,  "targets": 0},
			    { "name": "featured", "orderable": true,  "targets": 1 },
			    { "name": "title", "orderable": true,  "targets": 2 },
			    { "name": "created_date", "orderable": true,  "targets": 4 },
			    { "name": "action", "orderable": false,  "targets": 4 }
			]
		});
		
		jQuery('#videoModal').on('show.bs.modal', function (event) {
			var button = jQuery(event.relatedTarget) // Button that triggered the modal
			var video_title = button.data('video_title') // Extract info from data-* attributes
			var video_embed = button.data('video_embed') // Extract info from data-* attributes
			// If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
			// Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
			var modal = jQuery(this)
			modal.find('.modal-title').text(video_title)
			modal.find('.modal-body iframe').attr('src', video_embed);
		});
		
		jQuery('#videoModal').on('hide.bs.modal', function (event) {
			// If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
			// Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
			var modal = jQuery(this)
			modal.find('.modal-body iframe').attr('src', '');
		});
			

		jQuery('#synch-videos-btn').click( function() {
		
			jQuery('#synch-videos-btn i').addClass('fa-pulse');
			jQuery('#synchvideoModal').modal({
				backdrop: 'static',
				keyboard: false
			});
			
			jQuery('#synchvideoModal').modal('show');
			
			importMamgoVideosAjax(1);
			
		});
		
		
		jQuery('#synchvideoModal').on('hide.bs.modal', function (event) {
			// If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
			// Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
			jQuery('#video-list-form').submit();
		});
		
		
		function importMamgoVideosAjax(page, limit) {
			
			// get the hash 
   			var csrf_test_name = jQuery("input[name=csrf_test_name]").val();
			
			jQuery.ajax({
				method: "POST",
				url: baseURL+"importMamgoVideosAjax",
				data: { csrf_test_name: csrf_test_name, page: page, limit: 50 },
				dataType: "json",
				beforeSend: function() {
					swal("Video synchronizing...", { closeOnClickOutside: false, buttons: false});
				}
			})
			.done(function( response ) {
				
				jQuery("input[name=csrf_test_name]").val(response.csrf_test_name);
				
				if(response.notifications != '') {
					var notificationsHtml = '';
					jQuery.each(response.notifications, function (index, value) {
						notificationsHtml += '<div class="callout callout-info">';
							notificationsHtml += '<p>'+response.notifications[index]+'</p>';
						notificationsHtml += '</div>';
					});
					
					swal.close();
					
					jQuery('#synchResponseBlock').html(notificationsHtml);
					
					importMamgoVideosAjax(response.page);
					
				} else {
					
					swal.close();
					swal('Video synchronization done successfully.', { icon: 'success'});
										
					jQuery('#synch-videos-btn i').removeClass('fa-pulse');
					
				}
								
			});
			
		}
		
			
	});
</script>


<section class="content-header">
	<h1>
		Videos
		<small>Content videos</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url('administrator/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li class="active">Videos</li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-primary">
			
				<?php echo form_open('',array('id'=>'video-list-form', 'class'=>'video-list-form')); ?>
			
		            <div class="box-header with-border">
		            	<div class="btn-toolbar">
							<a class="btn btn-success <?php if(!$this->ion_auth->in_access('video_access', 'add')) { ?>disabled<?php } ?>" href="<?php echo site_url('administrator/videos/create');?>"><i class="fa fa-plus-circle"></i> Add video</a>
							
							<button type="button" id="synch-videos-btn" class="btn bg-maroon synch-videos-btn"><i class="fa fa-spinner"></i> Synchronize videos</button>
							
		            	</div>
		            </div>
		            
		           
		            <!-- /.box-header -->
		            <div class="box-body">
		            	<?php if(!empty($videos)) { ?>
		            		
							<table id="videolist-datTable" class="table table-bordered table-striped videolist-datTable">
								<thead>
									<tr>
										<th class="text-center" width="1%">ID</th>
										<th class="text-center" width="10%">Featured</th>
										<th>Title</th>
										<th class="text-center" width="15%">Created Date</th>
										<th class="text-center" width="15%">Action</th>
									</tr>
								</thead>
								<tbody>
									<!--<?php $i = 0; foreach($videos as $video) { ?>
									<?php // $mangomolovideo_details = $this->mangomolo->GetMangomoloVideoDetailsByID($video['mangovideo_id']); ?>
									<tr>
										<td class="text-center"><?php echo $video['id']; ?></td>
										<td class="text-center">
											<div class="btn-group">
												<?php $staus = $video['featured']; ?>
												<?php if($staus == 1) { ?>
													<?php echo anchor('administrator/videos/unfeatured/'.$video['id'], '<i class="fa fa-star"></i>', array('class' => 'btn btn-sm btn-default active', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Unfeatured Item')); ?>
												<?php } else { ?>
													<?php echo anchor('administrator/videos/featured/'.$video['id'], '<i class="fa fa-star-o"></i>', array('class' => 'btn btn-sm btn-default active', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Featured Item')); ?>
												<?php } ?>
											</div>
										</td>
										<td>
											<?php echo anchor('administrator/videos/edit/'.$video['id'],$video['title'], array('class' => '', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit')); ?>
										</td>
										<td class="text-center">
											<?php echo date('Y-m-d', strtotime($video['created_date'])); ?>
										</td>
										<td class="text-center">
											<?php // $video_embed = $mangomolovideo_details->embed; ?>
											<?php // $video_title = $mangomolovideo_details->video->title_ar; ?>
											<?php // echo anchor('#','<i class="fa fa-film" aria-hidden="true"></i>', array('class' => 'btn btn-info', 'data-toggle' => 'modal', 'data-target' => '#videoModal', 'data-video_title' => $video_title, 'data-video_embed' => $video_embed, 'title' => 'Preview')); ?>
											<?php 
											$class = 'btn btn-success';
											if(!$this->ion_auth->in_access('video_access', 'update')) {  $class .= ' disabled'; } 
											?>
											<?php echo anchor('administrator/videos/edit/'.$video['id'],'<i class="fa fa-pencil"></i>', array('class' => $class, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit')); ?>
											<?php if(!in_array($video['title'], array('admin'))) { ?>
												<?php // echo anchor('administrator/videos/delete/'.$video['id'],'<i class="fa fa-trash"></i>', array('class' => 'btn btn-danger', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Delete')); ?>
											<?php } ?>
										</td>
									</tr>
									<?php $i++; } ?>-->
								</tbody>
								<tfoot>
									<tr>
										<th class="text-center" width="1%">ID</th>
										<th>Featured</th>
										<th>Title</th>
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
<!-- Modal -->
<div class="videoModal modal fade" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="videoModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="videoModalLabel"></h4>
			</div>
			<div class="modal-body">
				<div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" src="" allowfullscreen=""></iframe>
                 </div>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="synchvideoModal modal fade" id="synchvideoModal" tabindex="-1" role="dialog" aria-labelledby="synchvideoModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="synchvideoModalLabel">Synchronize videos from Mangomolo</h4>
			</div>
			<div class="modal-body">
				<div class="alert alert-warning alert-dismissible">
					<h4><i class="icon fa fa-info"></i> Alert!</h4>
					Do not close popup or refresh the webpage untill synchronization has finished.
				</div>
				<div id="synchResponseBlock" class="synchResponseBlock"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>