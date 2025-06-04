<?php

use App\Libraries\IonAuth;

helper('form');

$ionAuthLibrary = new IonAuth();

$articles_filter = session()->get('articles_filter');

?>

<script type="text/javascript">
	function deleteArticle(url) {
		
		// var url = jQuery(this).attr("data-url");
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

		/*jQuery('#articlelist-datTable').DataTable({
		  "ordering": false
		});*/

		// initialize the datatable 
		manageTable = jQuery('#articlelist-datTable').DataTable({
			"bStateSave": true,
			"processing": true,
			"serverSide": true,
			"ajax": {
				"url": baseURL + 'administrator/articles/fetchArticleData',
				"type": "GET",
				"data": function(data) {
					data.status = jQuery('select[name="filter[status]"]').val();
					data.category_id = jQuery('select[name="filter[category_id]"]').val();
					data.tag_id = jQuery('select[name="filter[tag_id]"]').val();
					data.csrf_test_name = jQuery('input[name="csrf_test_name"]').val();
				}
			},
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
					"name": "status",
					"orderable": true,
					"targets": 2
				},
				{
					"name": "title",
					"orderable": true,
					"targets": 3
				},
				{
					"name": "article_layout",
					"orderable": true,
					"targets": 4
				},
				{
					"name": "created_date",
					"orderable": true,
					"targets": 5
				},
				{
					"name": "hits",
					"orderable": true,
					"targets": 6
				},
				{
					"name": "action",
					"orderable": false,
					"targets": 7
				}
			],
			"initComplete": function(settings, response) {
				jQuery("input[name=csrf_test_name]").val(response.csrf_test_name);
			},
			"drawCallback": function(settings) {
				jQuery("input[name=csrf_test_name]").val('<?php echo csrf_hash() ?>');
				var api = this.api();
				// Output the data for the visible rows to the browser's console
				console.log(api.rows({
					page: 'current'
				}).data());
			}
		});

		jQuery('#btn-search').click(function() { //button filter event click
			manageTable.ajax.reload(function(response) {
				jQuery("input[name=csrf_test_name]").val(response.csrf_test_name);
			}); //just reload table
		});

		/*jQuery('#btn-clear').click(function(){ //button reset event click
	        jQuery('#article-list-form')[0].reset();
	        manageTable.ajax.reload( function ( json ) {
			    jQuery("input[name=csrf_test_name]").val(response.csrf_test_name);
			} );  //just reload table
	    });*/

		jQuery("#checkAll").click(function() {
			jQuery('.article-list-form input:checkbox').not(this).prop('checked', this.checked);
		});

		jQuery('.btn-toolbar .btn-submit').click(function() {

			var dataCount = <?php echo count($articles); ?>;
			if (jQuery('#articlelist-datTable input[type="checkbox"]:checked').length != 0 && dataCount != 0) {

				var btn_task = jQuery(this).attr('btn-task');
				if (btn_task == 'publish') {
					var alertTitle = "Are you sure?";
					var alertText = "Want to publish selected data?";
				}
				if (btn_task == 'unpublish') {
					var alertTitle = "Are you sure?";
					var alertText = "Want to unpublish selected data?";
				}
				if (btn_task == 'featured') {
					var alertTitle = "Are you sure?";
					var alertText = "Want to make featured selected data?";
				}
				if (btn_task == 'unfeatured') {
					var alertTitle = "Are you sure?";
					var alertText = "Want to make unfeatured selected data?";
				}
				if (btn_task == 'delete') {
					var alertTitle = "Are you sure?";
					var alertText = "Once deleted, you will not be able to recover this data!";
				}
				/*if(btn_task == 'imagearrange') {
					var alertTitle = "Are you sure?";
					var alertText = "Once Image path change, you will not be able to change it again!";
				}*/
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
							jQuery('#article-list-form').submit();
						} else {

						}
					});

			} else {

				swal("Please select checkbox.");
			}


		});

		jQuery('.btn-clear').click(function() {

			jQuery('select[name="filter[status]"]').val('');
			jQuery('select[name="filter[category_id]"]').val('');
			jQuery('select[name="filter[tag_id]"]').val('');
			jQuery('#article-list-form').submit();

		});

		/*jQuery('.btn-delete').click( function() {
			
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


		jQuery('#btn-importExternalArticle').click(function() {

			var oldarticleid = jQuery('#oldarticleid').val();
			if (oldarticleid != '') {
				jQuery.ajax({
						method: "POST",
						url: "<?php echo site_url('administrator/articles/importexternalarticle'); ?>",
						data: {
							oldarticleid: oldarticleid
						},
						dataType: "json",
						beforeSend: function() {
							swal("Wait...", {
								closeOnClickOutside: false,
								buttons: false,
								timer: 1000
							});
						}
					})
					.done(function(response) {
						swal.close();
						console.log(response);
						jQuery('#oldarticleid').val('');
						swal({
								title: response.title,
								text: response.message,
								icon: response.type,
								dangerMode: true,
								closeOnClickOutside: false
							})
							.then((willConfirm) => {
								if (willConfirm) {
									jQuery('#article-list-form').submit();
								} else {

								}
							});
					});
			} else {
				swal("Please Add OLD Article ID", {
					closeOnClickOutside: false,
					buttons: false,
					timer: 1000
				});
			}

		});



	});
</script>


<section class="content-header">
	<h1>
		Articles
		<small>Content articles</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url('administrator/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li class="active">Articles</li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-primary">

				<?php echo form_open('', array('id' => 'article-list-form', 'class' => 'article-list-form')); ?>

				<div class="box-header with-border">
					<div class="btn-toolbar">
						<a class="btn btn-success <?php if (!$ionAuthLibrary->in_access('article_access', 'add')) { ?>disabled<?php } ?>" href="<?php echo site_url('administrator/articles/create'); ?>"><i class="fa fa-plus-circle"></i> Create article</a>
						<?php
						$featured_class = 'btn btn-submit btn-default';
						$disabled = '';
						if (!$ionAuthLibrary->in_access('article_access', 'update')) {
							$featured_class .= ' disabled';
							$disabled = 'disabled="disabled"';
						}
						?>
						<?php echo form_button('submit_featured', '<i class="fa fa-star"></i> Featured', 'id="submit_featured" class="' . $featured_class . '" btn-task="featured" ' . $disabled); ?>
						<?php
						$unfeatured_class = 'btn btn-submit btn-default';
						$disabled = '';
						if (!$ionAuthLibrary->in_access('article_access', 'update')) {
							$unfeatured_class .= ' disabled';
							$disabled = 'disabled="disabled"';
						}
						?>
						<?php echo form_button('submit_unfeatured', '<i class="fa fa-star-o"></i> Unfeatured', 'id="submit_unfeatured" class="' . $unfeatured_class . '" btn-task="unfeatured" ' . $disabled); ?>
						<?php
						$publish_class = 'btn btn-submit btn-default';
						$disabled = '';
						if (!$ionAuthLibrary->in_access('article_access', 'update')) {
							$publish_class .= ' disabled';
							$disabled = 'disabled="disabled"';
						}
						?>
						<?php echo form_button('submit_publish', '<i class="fa fa-check"></i> Publish', 'id="submit_publish" class="' . $publish_class . '" btn-task="publish" ' . $disabled); ?>
						<?php
						$unpublish_class = 'btn btn-submit btn-default';
						$disabled = '';
						if (!$ionAuthLibrary->in_access('article_access', 'update')) {
							$unpublish_class .= ' disabled';
							$disabled = 'disabled="disabled"';
						}
						?>
						<?php echo form_button('submit_unpublish', '<i class="fa fa-times-circle"></i> Unpublish', 'id="submit_unpublish" class="' . $unpublish_class . '" btn-task="unpublish" ' . $disabled); ?>
						<?php
						$delete_class = 'btn btn-submit btn-danger';
						$disabled = '';
						if (!$ionAuthLibrary->in_access('article_access', 'delete')) {
							$delete_class .= ' disabled';
							$disabled = 'disabled="disabled"';
						}
						?>
						<?php echo form_button('submit_delete', '<i class="fa fa-trash"></i> Delete', 'id="submit_delete" class="' . $delete_class . '" btn-task="delete" ' . $disabled); ?>
						<?php // echo form_button('submit_imagearrange', '<i class="fa fa-refresh"></i> Arrange Images', 'id="submit_imagearrange" class="btn btn-submit btn-warning" btn-task="imagearrange" ');
						?>

						<?php  // echo form_button('modal_importarticle', '<i class="fa fa-cloud-upload"></i> Import Article', 'id="modal_importarticle" class="btn btn-warning" data-toggle="modal" data-target="#articleImportModal" ');
						?>

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
							echo form_dropdown('filter[status]', $options, $articles_filter['status'] ?? "", 'class="form-control select2" data-placeholder="- Select Status -"');
							?>
						</div>
						<div class="col-md-3">
							<?php
							$options = array();
							$options[''] = '- Select Category -';
							foreach ($categorieslist as $categorylist) {
								$options[$categorylist['id']] = $categorylist['title'];
							}
							echo form_dropdown('filter[category_id]', $options, $articles_filter['category_id'] ?? "", 'class="form-control select2" data-placeholder="- Select Category -"');
							?>
						</div>
						<div class="col-md-2">
							<?php
							$options = array();
							$options[''] = '- Select Tag -';
							foreach ($taglist as $tag) {
								$options[$tag['id']] = $tag['title'];
							}
							echo form_dropdown('filter[tag_id]', $options, $articles_filter['tag_id'] ?? "", 'class="form-control select2" data-placeholder="- Select Tag -"');
							?>
						</div>
						<div class="col-md-2">
							<button type="button" id="btn-search" class="btn btn-primary btn-search" data-toggle="tooltip" data-placement="top" title="Search">
								<i class="fa fa-search"></i>&nbsp; Search
							</button>
							<button type="button" id="btn-clear" class="btn btn-default btn-clear" data-toggle="tooltip" data-placement="top" title="Clear">
								Clear
							</button>
						</div>
					</div>
				</div>

				<!-- /.box-header -->
				<div class="box-body">
					<?php if (!empty($articles)) { ?>

						<table id="articlelist-datTable" class="table table-bordered table-striped articlelist-datTable">
							<thead>
								<tr>
									<th class="text-center" width="1%">
										<input type="checkbox" id="checkAll" name="checkall-toggle" value="" data-toggle="tooltip" data-placement="top" title="Check All Items" />
									</th>
									<th class="text-center" width="1%">ID</th>
									<th class="text-center" width="10%">Status</th>
									<th>Title</th>
									<th class="text-center" width="10%">Layout</th>
									<th class="text-center" width="10%">Created Date</th>
									<th class="text-center" width="1%"><span data-toggle="tooltip" data-placement="top" title="Views"><i class="fa fa-eye"></i></span></th>
									<th class="text-center" width="15%">Action</th>
								</tr>
							</thead>
							<tbody>
								<!--<?php // $i = 0; foreach($articles as $article) { 
									?>
									
									<?php
									// GENERATE ARTICLE LINKS FROM ARTICLE HELPER
									// $article_preview_link = generateArticleLink($article['category_ids'], $article['alias']); 
									?>
									
									<tr>
										<td class="text-center">
											<input type="checkbox" id="article_id<?php // echo $i; 
																					?>" name="article_id[]" value="<?php // echo $article['id']; 
																													?>" />
										</td>
										<td class="text-center"><?php // echo $article['id']; 
																?></td>
										<td class="text-center">
											<div class="btn-group">
												<?php // $staus = $article['status']; 
												?>
												<?php // if($staus == 1) { 
												?>
													<?php // echo anchor('administrator/articles/unpublish/'.$article['id'], '<i class="fa fa-check"></i>', array('class' => 'btn btn-sm btn-default active', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Unpublish Item')); 
													?>
												<?php // } else { 
												?>
													<?php // echo anchor('administrator/articles/publish/'.$article['id'], '<i class="fa fa-times-circle"></i>', array('class' => 'btn btn-sm btn-default active', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Publish Item')); 
													?>
												<?php // } 
												?>
												<?php // $staus = $article['featured']; 
												?>
												<?php // if($staus == 1) { 
												?>
													<?php // echo anchor('administrator/articles/unfeatured/'.$article['id'], '<i class="fa fa-star"></i>', array('class' => 'btn btn-sm btn-default active', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Unfeatured Item')); 
													?>
												<?php // } else { 
												?>
													<?php // echo anchor('administrator/articles/featured/'.$article['id'], '<i class="fa fa-star-o"></i>', array('class' => 'btn btn-sm btn-default active', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Featured Item')); 
													?>
												<?php // } 
												?>
											</div>
										</td>
										<td>
											<?php // echo anchor('administrator/articles/edit/'.$article['id'],$article['title'], array('class' => '', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit')); 
											?> <span class="small">(Alias : <?php // echo $article['alias']; 
																			?>)</span>
											<?php // $categorylist = explode(',', $article['category_ids']); 
											?>
											<div class="small">Category : <?php // echo getCategoryName($categorylist); 
																			?></div>
										</td>
										<td class="text-center">
											<?php // $article_layout = $article['article_layout']; 
											?>
											<?php // if($article_layout == 'text') { 
											?>
												<span class="label label-info label-block"><i class="ion ion-document-text"></i>&nbsp;&nbsp; Text</span>
											<?php // } elseif ($article_layout == 'media') { 
											?>
												<span class="label label-info label-block"><i class="ion ion-easel"></i>&nbsp;&nbsp; Media</span>
											<?php // } elseif ($article_layout == 'recipe') { 
											?>
												<span class="label label-info label-block"><i class="ion ion-android-restaurant"></i>&nbsp;&nbsp; Recipe</span>
											<?php // } elseif ($article_layout == 'horoscope') { 
											?>
												<span class="label label-info label-block"><i class="ion ion-wand"></i>&nbsp;&nbsp; Horoscope</span>
											<?php // } elseif ($article_layout == 'text_media') { 
											?>
												<span class="label label-info label-block"><i class="ion ion-image"></i>&nbsp;&nbsp; Text + Media</span>
											<?php // } elseif ($article_layout == 'text_mulitplemedia') { 
											?>
												<span class="label label-info label-block"><i class="ion ion-images"></i>&nbsp;&nbsp; Text + Multiplemedia</span>
											<?php // } elseif ($article_layout == 'text_mulitplemedia_6') { 
											?>
												<span class="label label-info label-block"><i class="ion ion-images"></i>&nbsp;&nbsp; Text + Multiplemedia 6++</span>
											<?php // } elseif ($article_layout == 'fashion') { 
											?>
												<span class="label label-info label-block"><i class="ion ion-bowtie"></i>&nbsp;&nbsp; Fashion</span>
											<?php // } elseif ($article_layout == 'travel') { 
											?>
												<span class="label label-info label-block"><i class="ion ion-plane"></i>&nbsp;&nbsp; Travel</span>
											<?php // } elseif ($article_layout == 'questions_test') { 
											?>
												<span class="label label-info label-block"><i class="ion ion-help"></i>&nbsp;&nbsp; Questions Test</span>
											<?php // } else { 
											?>
												<span class="label label-info label-block"><?php // echo $article_layout; 
																							?></span>
											<?php // } 
											?>
										</td>
										<td class="text-center">
											<?php // echo date('Y-m-d', strtotime($article['created_date'])); 
											?>
										</td>
										<td class="text-center">
											<?php
											/*if($article['hits'] <= 20) {
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
											}*/
											?>											
											<a href="javascript:void(0)" class="label <?php // echo $hit_class; 
																						?>" title="<?php // echo $article['hits'].' Views'; 
																									?>"><?php // echo $article['hits']; 
																																?></a>
										</td>
										<td class="text-center">
											<?php // echo anchor($article_preview_link,'<i class="fa fa-eye"></i>', array('target' => '_blank', 'class' => 'btn btn-info', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Preview')); 
											?>
											<?php
											// $class = 'btn btn-success';
											// if(!$ionAuthLibrary->in_access('article_access', 'update')) {  $class .= ' disabled'; } 
											?>
											<?php // echo anchor('administrator/articles/edit/'.$article['id'],'<i class="fa fa-pencil"></i>', array('class' => $class, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit')); 
											?>
											<?php // if(!in_array($article['title'], array('admin'))) { 
											?>
												<a href="javascript:void(0)" data-url="<?php // echo site_url('administrator/articles/delete/'.$article['id']); 
																						?>" class="btn btn-danger btn-delete <?php // if(!$ionAuthLibrary->in_access('article_access', 'delete')) { 
																																?>disabled<?php // } 
																																																					?>" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
											<?php // } 
											?>
										</td>
									</tr>
									<?php // $i++; } 
									?>-->
							</tbody>
							<tfoot>
								<tr>
									<th class="text-center" width="1%">
										<!--<input type="checkbox" name="checkall-toggle" value="" data-toggle="tooltip" title="" onclick="checkAll()" data-placement="top" title="Check All Items" />-->
									</th>
									<th class="text-center" width="1%">ID</th>
									<th class="text-center" width="10%">Status</th>
									<th>Title</th>
									<th class="text-center" width="10%">Layout</th>
									<th class="text-center" width="10%">Created Date</th>
									<th class="text-center" width="1%"><i class="fa fa-eye"></i></th>
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

					<?php echo form_hidden('task', ''); ?>

				</div>
				<!-- /.box-body -->
				<?php echo form_close(); ?>

			</div>
			<!-- /.box -->
		</div>
	</div>
</section>


<!-- Modal -->
<div class="modal fade articleImportModal" id="articleImportModal" tabindex="-1" role="dialog" aria-labelledby="articleImportModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="articleImportModalLabel">Import Article By ID</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<?php echo form_label('Old Article ID', 'oldarticleid'); ?>
					<?php echo '<span class="text-danger">' . session('errors.oldarticleid') . '</span>'; ?>
					<?php echo form_input('oldarticleid', '', 'class="form-control" id="oldarticleid"'); ?>
				</div>
				<!--<div class="text-right text-danger">
					Add article ID.
				</div>-->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-success" id="btn-importExternalArticle">Import</button>
			</div>
		</div>
	</div>
</div>