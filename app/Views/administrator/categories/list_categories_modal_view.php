<?php

helper('form');

$categories_modal_filter = session()->get('categories_modal_filter');
$search_val = $categories_modal_filter['search_val'];

?>

<script type="text/javascript">
	jQuery(document).ready(function () {
		
		var categorylistdatTable = jQuery('#categorylist-datTable').DataTable({
		  "ordering": false,
		  stateSave: true
		});
		
		jQuery('.category_option').click(function() {
			var category_data = jQuery('.category_option:checked').attr('data');			
			parent.tinymce.activeEditor.category_data = category_data;
		});
		
		var parentCheck = 0;
		jQuery('.btn-parent-category-toggle').click( function() {
			if(parentCheck == 0) {
				jQuery.fn.dataTable.ext.search.push(
					function(settings, data, dataIndex) {
						return jQuery(categorylistdatTable.row(dataIndex).node()).attr('parent-category') == 0;
					}
				);
				categorylistdatTable.draw();
				parentCheck = 1;
			} else {
				jQuery.fn.dataTable.ext.search.pop();
    			categorylistdatTable.draw();
    			parentCheck = 0;
			}
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
			
				<?php echo form_open('',array('id'=>'category-list-modal-form', 'class'=>'category-list-modal-form')); ?>
			
		            <div class="box-header with-border">
		            	<div class="row">
							<div class="col-md-4">
					            <?php echo form_button('btn_parent_category_toggle', '<i class="fa fa-bars"></i> Toggle Parent Category', 'id="btn_parent_category_toggle" class="btn btn-parent-category-toggle btn-info"');?>
							</div>
						</div>
					</div>
		            <!-- /.box-header -->
		            		            
		            <div class="box-body">
		            
		            	<?php if(!empty($categorieslist_modal)) { ?>
		            		
							<table id="categorylist-datTable" class="table table-bordered table-striped categorylist-datTable">
								<thead>
									<tr>
										<th class="text-center" width="1%">
											#
										</th>
										<th class="text-center" width="1%">ID</th>
										<th>Title</th>
									</tr>
								</thead>
								<tbody>
									<?php $i = 0; foreach($categorieslist_modal as $category) { ?>
									
									<?php 
									// GENERATE CATEGORY LINKS FROM CATEGORY HELPER
									$category_preview_link = generateCategoryLink($category['id']); 
									if($categories_modal_filter['search_val'] != '') {
										$data_category_title = $categories_modal_filter['search_val'];
									} else {
										$data_category_title = str_replace('"', '`', $category['title']);
									}
									?>
									
									<tr parent-category="<?php echo $category['parent_id']; ?>">
										<td class="text-center">
											<input type="radio" id="category_id<?php echo $i; ?>" name="category_id[]" data="<a href='<?php echo $category_preview_link; ?>' title='<?php echo trim(str_replace('-', '', $data_category_title)); ?>'><?php echo trim(str_replace('-', '', $data_category_title)); ?></a>" class="category_option category_option_id<?php echo $i; ?>" value="<?php echo $category['id']; ?>" />
										</td>
										<td class="text-center"><?php echo $category['id']; ?></td>
										<td>
											<?php echo $category['title']; ?>
											<span class="small">(Alias : <?php echo $category['alias']; ?>)</span>
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