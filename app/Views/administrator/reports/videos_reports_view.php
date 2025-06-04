<?php



$videos_reports_filter = $this->session->userdata('videos_reports_filter');

?>


<script type="text/javascript">
	jQuery(document).ready(function () {
		
		<?php if($videos_reports_filter['start_date'] != '' && $videos_reports_filter['end_date'] != '') { ?>
			jQuery('.daterange-filter-input-group .daterange-btn span').html('<?php echo date('F d, Y', strtotime($videos_reports_filter['start_date'])) .' - '. date('F d, Y', strtotime($videos_reports_filter['end_date'])); ?>');
		<?php } ?>
		
		jQuery('#videos_reportslist-datTable').DataTable({
		  "ordering": false
		});
				
		jQuery('.btn-clear').click( function() {
			
			jQuery('input[name="filter[start_date]"]').val('');
			jQuery('input[name="filter[end_date]"]').val('');
			
			jQuery('#videos_reports-list-form').submit();
			
		});
				
	});
</script>


<section class="content-header">
	<h1>
		Reports
		<small>Videos Reports</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url('administrator/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li class="active">Reports</li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-primary">
			
				<?php echo form_open('',array('id'=>'videos_reports-list-form', 'class'=>'videos_reports-list-form')); ?>
			
		            <div class="box-header with-border">
		            	<div class="btn-toolbar">
							<div class="row">
								<div class="col-md-3">
						            <!-- Date and time range -->
									<div class="form-group">
										<div class="daterange-filter-input-group">
											<button type="button" class="btn btn-default btn-block daterange-btn" id="daterange-btn">
												<span>
													<i class="fa fa-calendar"></i> Select Date Range
												</span>&nbsp;
												<i class="fa fa-caret-down"></i>
											</button>
											<?php echo form_hidden('filter[start_date]', $videos_reports_filter['start_date']); ?>
											<?php echo form_hidden('filter[end_date]', $videos_reports_filter['end_date']); ?>
										</div>
										
									</div>
									<!-- /.form group -->
								</div>
								<div class="col-md-9">
									<div class="btn-group">
							            <button type="submit" class="btn btn-primary btn-search" data-toggle="tooltip" data-placement="top" title="Search">
							            	<i class="fa fa-search"></i>&nbsp; Search
							            </button>
							            <button type="button" class="btn btn-danger btn-clear" data-toggle="tooltip" data-placement="top" title="Clear">
							            	<i class="fa fa-times"></i>&nbsp; Clear
		  					            </button>
		  					        </div>
		  					        <a href="<?php echo site_url('administrator/reports/download_videos_reports'); ?>" target="_blank" class="btn bg-maroon btn-download pull-right" data-toggle="tooltip" data-placement="top" title="Download">
						            	<i class="fa fa-download"></i>&nbsp; Download
	  					            </a>
								</div>
							</div>
		            	</div>
		            </div>
		            
		            <!-- /.box-header -->
		            <div class="box-body">
		            
		            	<?php if(!empty($videos)) { ?>
		            			            		
							<table id="videos_reportslist-datTable" class="table table-bordered table-striped videos_reportslist-datTable">
								<thead>
									<tr>
										<th class="text-center" width="1%">ID</th>
										<th>Title</th>
										<th class="text-center" width="5%"><span data-toggle="tooltip" data-placement="top" title="Views"><i class="fa fa-eye"></i></span></th>
										<th class="text-center" width="10%">Created Date</th>
									</tr>
								</thead>
								<tbody>
									<?php $i = 0; foreach($videos as $video) { ?>
									
									<?php if($video['video_details'] != '') { $video_details = json_decode($video['video_details']); } else { $video_details = ''; } ?>
		            		
									
									<tr>
										<td class="text-center"><?php echo $video['id']; ?></td>
										<td>
											<a href="javascript:void(0)" class="" data-toggle="tooltip" data-placement="top" title="<?php echo $video['title']; ?>"><?php echo $video['title']; ?></a>
										</td>
										<td class="text-center">
											<?php if($video_details != '') { ?>
												<?php
												if($video_details->today_views <= 20) {
													$hit_class = 'bg-primary';
												} elseif($video_details->today_views <= 40) {
													$hit_class = 'bg-primary';
												} elseif($video_details->today_views <= 60) {
													$hit_class = 'bg-red';
												} elseif($video_details->today_views <= 80) {
													$hit_class = 'bg-red';
												} elseif($video_details->today_views <= 100) {
													$hit_class = 'bg-green';
												} else {
													$hit_class = 'bg-green';
												}
												?>
												<a href="javascript:void(0)" class="label <?php echo $hit_class; ?>" title="<?php echo $video_details->today_views.' Views'; ?>"><?php echo $video_details->today_views; ?></a>
											<?php } else { ?>
												<a href="javascript:void(0)" class="label bg-primary" title="<?php echo '0 Views'; ?>">0</a>
											<?php } ?>
										</td>
										<td class="text-center">
											<?php echo date('Y-m-d', strtotime($video['created_date'])); ?>
										</td>
									</tr>
									<?php $i++; } ?>
								</tbody>
								<tfoot>
									<tr>
										<th class="text-center" width="1%">ID</th>
										<th>Title</th>
										<th class="text-center" width="5%"><span data-toggle="tooltip" data-placement="top" title="Views"><i class="fa fa-eye"></i></span></th>
										<th class="text-center" width="10%">Created Date</th>
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