<?php



$articles_reports_filter = $this->session->userdata('articles_reports_filter');

?>


<script type="text/javascript">
	jQuery(document).ready(function () {
		
		<?php if($articles_reports_filter['start_date'] != '' && $articles_reports_filter['end_date'] != '') { ?>
			jQuery('.daterange-filter-input-group .daterange-btn span').html('<?php echo date('F d, Y', strtotime($articles_reports_filter['start_date'])) .' - '. date('F d, Y', strtotime($articles_reports_filter['end_date'])); ?>');
		<?php } ?>
		
		<?php if($articles_reports_filter['usergroup_id'] != '') { ?>
			getUsersAjax(<?php echo $articles_reports_filter['usergroup_id']; ?>);
		<?php } ?>
		
		jQuery('#articles_reportslist-datTable').DataTable({
		  "ordering": false
		});
				
		jQuery('.btn-clear').click( function() {
						
			jQuery('select[name="filter[status]"]').val('');
			jQuery('input[name="filter[start_date]"]').val('');
			jQuery('input[name="filter[end_date]"]').val('');
			jQuery('select[name="filter[category_id]"]').val('');
			jQuery('select[name="filter[tag_id]"]').val('');
			jQuery('select[name="filter[usergroup_id]"]').val('');
			newOption = new Option('- Select User -', '', false, false);
			jQuery('#user_id-control').html(newOption).trigger('change');
			jQuery('#articles_reports-list-form').submit();
			
		});
		
		jQuery('#usergroup_id-control').change( function() {
						
			var usergroup_id = jQuery(this).val();
			getUsersAjax(usergroup_id);
			
		});
		
		
		function getUsersAjax(usergroup_id) {
			
			// get the hash 
   			var csrf_test_name = jQuery("input[name=csrf_test_name]").val();
			
			jQuery.ajax({
				method: "POST",
				url: baseURL+"getUsersAjax",
				data: { csrf_test_name: csrf_test_name, usergroup_id: usergroup_id },
				dataType: "json",
				beforeSend: function() {
					swal("Wait...", { closeOnClickOutside: false, buttons: false});
				}
			})
			.done(function( response ) {
				swal.close();
				jQuery("input[name=csrf_test_name]").val(response.csrf_test_name);

				var newOption;
				jQuery('#user_id-control').html('').trigger('change');
				if(response.data == '') {
					newOption = new Option('- No User -', '', false, false);
					jQuery('#user_id-control').append(newOption).trigger('change');
				}
				jQuery.each(response.data, function (index, value) {
					newOption = new Option(response.data[index].first_name+' '+response.data[index].last_name, response.data[index].id, false, false);
					jQuery('#user_id-control').append(newOption).trigger('change');
				});
				
			});
			
		}
		
	});
</script>


<section class="content-header">
	<h1>
		Reports
		<small>Articles Reports</small>
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
			
				<?php echo form_open('',array('id'=>'articles_reports-list-form', 'class'=>'articles_reports-list-form')); ?>
			
		            <div class="box-header with-border">
		            	<div class="btn-toolbar">
							<div class="row">
			            		<div class="col-md-3">
			            			<div class="form-group">
							            <?php 
											$options = array();
											$options[''] = '- Select Status -';
											$options[1] = 'Publish';
											$options[0] = 'Unpublish';
											echo form_dropdown('filter[status]', $options, $articles_reports_filter['status'], 'class="form-control select2" data-placeholder="- Select Status -"');
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
											<?php echo form_hidden('filter[start_date]', $articles_reports_filter['start_date']); ?>
											<?php echo form_hidden('filter[end_date]', $articles_reports_filter['end_date']); ?>
										</div>
										
									</div>
									<!-- /.form group -->
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<?php 
											$options = array();
											$options[''] = '- Select Category -';
											foreach ($categorieslist AS $categorylist) {
												$options[$categorylist['id']] = $categorylist['title'];
											}
											echo form_dropdown('filter[category_id]', $options, $articles_reports_filter['category_id'], 'class="form-control select2" data-placeholder="- Select Category -"');
										?>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<?php 
											$options = array();
											$options[''] = '- Select Tag -';
											foreach ($taglist AS $tag) {
												$options[$tag['id']] = $tag['title'];
											}
											echo form_dropdown('filter[tag_id]', $options, $articles_reports_filter['tag_id'], 'class="form-control select2" data-placeholder="- Select Tag -"');
										?>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<?php 
											$options = array();
											$options[''] = '- Select Group -';
											foreach ($usergrouplist AS $usergroup) {
												$options[$usergroup['id']] = $usergroup['name'];
											}
											echo form_dropdown('filter[usergroup_id]', $options, $articles_reports_filter['usergroup_id'], 'id="usergroup_id-control" class="form-control usergroup_id-control select2" data-placeholder="- Select Group -"');
										?>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<?php 
											$options = array();
											$options[''] = '- Select User -';
											echo form_dropdown('filter[user_id]', $options, $articles_reports_filter['user_id'], 'id="user_id-control" class="form-control user_id-control select2" data-placeholder="- Select User -"');
										?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="btn-group">
							            <button type="submit" class="btn btn-primary btn-search" data-toggle="tooltip" data-placement="top" title="Search">
							            	<i class="fa fa-search"></i>&nbsp; Search
							            </button>
							            <button type="button" class="btn btn-danger btn-clear" data-toggle="tooltip" data-placement="top" title="Clear">
							            	<i class="fa fa-times"></i>&nbsp; Clear
		  					            </button>
		  					        </div>
		  					        <a href="<?php echo site_url('administrator/reports/download_articles_reports'); ?>" target="_blank" class="btn bg-maroon btn-download pull-right" data-toggle="tooltip" data-placement="top" title="Download">
						            	<i class="fa fa-download"></i>&nbsp; Download
	  					            </a>
								</div>
							</div>
		            	</div>
		            </div>
		            
		            <!-- /.box-header -->
		            <div class="box-body">
		            
		            	<?php if(!empty($articles)) { ?>
		            		
							<table id="articles_reportslist-datTable" class="table table-bordered table-striped articles_reportslist-datTable">
								<thead>
									<tr>
										<th class="text-center" width="1%">ID</th>
										<th class="text-center" width="1%">Status</th>
										<th>Title</th>
										<th class="text-center" width="1%"><span data-toggle="tooltip" data-placement="top" title="Views"><i class="fa fa-eye"></i></span></th>
										<th class="text-center" width="10%">Created Date</th>
										<th class="text-center" width="10%">Publish Date</th>
									</tr>
								</thead>
								<tbody>
									<?php $i = 0; foreach($articles as $article) { ?>
									<tr>
										<td class="text-center"><?php echo $article['id']; ?></td>
										<td class="text-center">
											<?php $staus = $article['status']; ?>
											<?php if($staus == 1) { ?>
												<a href="javascript:void(0)" class="btn btn-sm btn-default active" data-toggle="tooltip" data-placement="top" title="Published"><i class="fa fa-check"></i></a>
											<?php } else { ?>
												<a href="javascript:void(0)" class="btn btn-sm btn-default active" data-toggle="tooltip" data-placement="top" title="Unpublished"><i class="fa fa-times-circle"></i></a>
											<?php } ?>
										</td>
										<td>
											<a href="javascript:void(0)" class="" data-toggle="tooltip" data-placement="top" title="<?php echo $article['title']; ?>"><?php echo $article['title']; ?></a> <span class="small">(Alias : <?php echo $article['alias']; ?>)</span>
											<?php $categorylist = explode(',', $article['category_ids']); ?>
											<div class="small">Category : <?php echo getCategoryName($categorylist); ?></div>
											<?php if($article['tag_ids'] != '') { ?>
												<?php $taglist = explode(',', $article['tag_ids']); ?>
												<div class="small">Tag : <?php echo getTagName($taglist); ?></div>
											<?php } ?>
										</td>
										<td class="text-center">
											<?php
											if($article['hits'] <= 20) {
												$hit_class = 'bg-primary';
											} elseif($article['hits'] <= 40) {
												$hit_class = 'bg-primary';
											} elseif($article['hits'] <= 60) {
												$hit_class = 'bg-red';
											} elseif($article['hits'] <= 80) {
												$hit_class = 'bg-red';
											} elseif($article['hits'] <= 100) {
												$hit_class = 'bg-green';
											} else {
												$hit_class = 'bg-green';
											}
											?>
											<a href="javascript:void(0)" class="label <?php echo $hit_class; ?>" title="<?php echo $article['hits'].' Views'; ?>"><?php echo $article['hits']; ?></a>
										</td>
										<td class="text-center">
											<?php echo date('Y-m-d', strtotime($article['created_date'])); ?>
										</td>
										<td class="text-center">
											<?php echo date('Y-m-d', strtotime($article['published_date'])); ?>
										</td>
									</tr>
									<?php $i++; } ?>
								</tbody>
								<tfoot>
									<tr>
										<th class="text-center" width="1%">ID</th>
										<th class="text-center" width="1%">Status</th>
										<th>Title</th>
										<th class="text-center" width="1%"><span data-toggle="tooltip" data-placement="top" title="Views"><i class="fa fa-eye"></i></span></th>
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