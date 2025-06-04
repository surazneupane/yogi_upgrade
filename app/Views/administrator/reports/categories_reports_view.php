<?php



$categories_reports_filter = $this->session->userdata('categories_reports_filter');

?>


<script type="text/javascript">
	jQuery(document).ready(function () {
		
		<?php if($categories_reports_filter['start_date'] != '' && $categories_reports_filter['end_date'] != '') { ?>
			jQuery('.daterange-filter-input-group .daterange-btn span').html('<?php echo date('F d, Y', strtotime($categories_reports_filter['start_date'])) .' - '. date('F d, Y', strtotime($categories_reports_filter['end_date'])); ?>');
		<?php } ?>
				
		jQuery('#categories_reportslist-datTable').DataTable({
		  "ordering": false
		});
				
		jQuery('.btn-clear').click( function() {
						
			jQuery('select[name="filter[status]"]').val('');
			jQuery('input[name="filter[start_date]"]').val('');
			jQuery('input[name="filter[end_date]"]').val('');
			jQuery('#categories_reports-list-form').submit();
			
		});
				
	});
</script>


<section class="content-header">
	<h1>
		Reports
		<small>Categories Reports</small>
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
			
				<?php echo form_open('',array('id'=>'categories_reports-list-form', 'class'=>'categories_reports-list-form')); ?>
			
		            <div class="box-header with-border">
		            	<div class="btn-toolbar">
							<div class="row">
			            		<div class="col-md-2">
			            			<div class="form-group">
							            <?php 
											$options = array();
											$options[''] = '- Select Status -';
											$options[1] = 'Publish';
											$options[0] = 'Unpublish';
											echo form_dropdown('filter[status]', $options, $categories_reports_filter['status'], 'class="form-control select2" data-placeholder="- Select Status -"');
										?>
									</div>
								</div>
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
											<?php echo form_hidden('filter[start_date]', $categories_reports_filter['start_date']); ?>
											<?php echo form_hidden('filter[end_date]', $categories_reports_filter['end_date']); ?>
										</div>
										
									</div>
									<!-- /.form group -->
								</div>
								<div class="col-md-7">
									<div class="btn-group">
							            <button type="submit" class="btn btn-primary btn-search" data-toggle="tooltip" data-placement="top" title="Search">
							            	<i class="fa fa-search"></i>&nbsp; Search
							            </button>
							            <button type="button" class="btn btn-danger btn-clear" data-toggle="tooltip" data-placement="top" title="Clear">
							            	<i class="fa fa-times"></i>&nbsp; Clear
		  					            </button>
		  					        </div>
		  					        <a href="<?php echo site_url('administrator/reports/download_categories_reports'); ?>" target="_blank" class="btn bg-maroon btn-download pull-right" data-toggle="tooltip" data-placement="top" title="Download">
						            	<i class="fa fa-download"></i>&nbsp; Download
	  					            </a>
								</div>
							</div>
		            	</div>
		            </div>
		            
		            <!-- /.box-header -->
		            <div class="box-body">
		            
		            	<?php if(!empty($categories)) { ?>
		            		
							<table id="categories_reportslist-datTable" class="table table-bordered table-striped categories_reportslist-datTable">
								<thead>
									<tr>
										<th class="text-center" width="1%">ID</th>
										<th class="text-center" width="1%">Status</th>
										<th>Title</th>
										<th class="text-center" width="2%"><i class="fa fa-check text-success"></i></th>
										<th class="text-center" width="2%"><i class="fa fa-times-circle text-danger"></i></th>
										<th class="text-center" width="5%">Total</th>
									</tr>
								</thead>
								<tbody>
									<?php $i = 0; foreach($categories as $category) { ?>
									<?php 
										$published_articles = getArticlesByCatID($category['id'], '1'); 
										$published_articles_count = count($published_articles);
									
										$unpublished_articles = getArticlesByCatID($category['id'], '0'); 
										$unpublished_articles_count = count($unpublished_articles);
									
										$total_articles = getArticlesByCatID($category['id'], ''); 
										$total_articles_count = count($total_articles);
									?>
									<tr>
										<td class="text-center"><?php echo $category['id']; ?></td>
										<td class="text-center">
											<?php $staus = $category['status']; ?>
											<?php if($staus == 1) { ?>
												<a href="javascript:void(0)" class="btn btn-sm btn-default active" data-toggle="tooltip" data-placement="top" title="Published"><i class="fa fa-check"></i></a>
											<?php } else { ?>
												<a href="javascript:void(0)" class="btn btn-sm btn-default active" data-toggle="tooltip" data-placement="top" title="Unpublished"><i class="fa fa-times-circle"></i></a>
											<?php } ?>
										</td>
										<td>
											<a href="javascript:void(0)" class="" data-toggle="tooltip" data-placement="top" title="<?php echo $category['title']; ?>"><?php echo $category['title']; ?></a> <span class="small">(Alias : <?php echo $category['alias']; ?>)</span>
										</td>
										<td class="text-center">
											<?php echo anchor('#', $published_articles_count, array('class' => 'label label-success', 'title' => 'Published items')); ?>
										</td>
										<td class="text-center">
											<?php echo anchor('#', $unpublished_articles_count, array('class' => 'label label-danger', 'title' => 'Unpublished items')); ?>
										</td>
										<td class="text-center">
											<?php echo anchor('#', $total_articles_count, array('class' => 'label label-primary', 'title' => 'Total items')); ?>
										</td>
									</tr>
									<?php $i++; } ?>
								</tbody>
								<tfoot>
									<tr>
										<th class="text-center" width="1%">ID</th>
										<th class="text-center" width="1%">Status</th>
										<th>Title</th>
										<th class="text-center" width="2%"><i class="fa fa-check text-success"></i></th>
										<th class="text-center" width="2%"><i class="fa fa-times-circle text-danger"></i></th>
										<th class="text-center" width="5%">Total</th>
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