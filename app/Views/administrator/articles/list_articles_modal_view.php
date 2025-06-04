<?php



$articles_modal_filter =session()->get('articles_modal_filter');

?>

<script type="text/javascript">
	jQuery(document).ready(function () {
		
		jQuery('#articlelist_modal-datTable').DataTable({
			"ordering": false,
			"searching": false
		});
		
		jQuery('.article_option').click(function() {
			var article_data = jQuery('.article_option:checked').attr('data');			
			parent.tinymce.activeEditor.article_data = article_data;
		});
		
		jQuery('.btn-clear').click( function() {
			jQuery('.search_val').val('');
			jQuery('#article-list-modal-form').submit();
		});
		
	});
</script>

<style type="text/css">
	.main-header,
	.main-sidebar,
	.main-footer { display: none;}
	body,
	.skin-blue .wrapper { background: #ecf0f5;}
	.content-wrapper { margin-left: 0;}
	.content-wrapper .box { margin-bottom: 0;}
</style>

<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-primary">
			
				<?php echo form_open('',array('id'=>'article-list-modal-form', 'class'=>'article-list-modal-form')); ?>
				
				<div class="box-header with-border">
	            	<div class="row">
	            		<div class="col-md-2">
							<?php echo form_input('filter[search_val]', $articles_modal_filter['search_val'], 'class="form-control search_val" placeholder="Search..."'); ?>
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
				
	            <!-- /.box-body -->
	            <div class="box-body">
	            	<?php if(!empty($articleslist_modal)) { ?>
	            		
						<table id="articlelist_modal-datTable" class="table table-bordered table-striped articlelist_modal-datTable">
							<thead>
								<tr>
									<th class="text-center" width="1%">#</th>
									<th class="text-center" width="1%">ID</th>
									<th>Title</th>
								</tr>
							</thead>
							<tbody>
								<?php $i = 0; foreach($articleslist_modal as $article) { ?>
								
								<?php 
								// GENERATE ARTICLE LINKS FROM ARTICLE HELPER
								$article_preview_link = generateArticleLink($article['category_ids'], $article['alias']); 
								if($articles_modal_filter['search_val'] != '') {
									$data_article_title = $articles_modal_filter['search_val'];
								} else {
									$data_article_title = str_replace('"', '`', $article['title']);
								}
								?>
								
								<tr>
									<td class="text-center">
										<input type="radio" id="article_id<?php echo $i; ?>" name="article_id[]" data="<a href='<?php echo $article_preview_link; ?>' title='<?php echo $data_article_title; ?>'><?php echo $data_article_title; ?></a>" class="article_option article_option_id<?php echo $i; ?>" value="<?php echo $article['id']; ?>" />
									</td>
									<td class="text-center"><?php echo $article['id']; ?></td>
									<td>
										<?php echo $article['title']; ?>
										<span class="small">(Alias : <?php echo $article['alias']; ?>)</span>
										<?php $categorylist = explode(',', $article['category_ids']); ?>
										<div class="small">Category : <?php echo getCategoryName($categorylist); ?></div>
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
									<th>Title</th>
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
	           	<?php echo form_close();?>
	           	 
          </div>
          <!-- /.box -->
		</div>
	</div>
</section>