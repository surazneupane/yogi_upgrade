<?php

helper('form');

$tags_modal_filter = session()->get('tags_modal_filter');

?>

<script type="text/javascript">
	jQuery(document).ready(function () {
		
		jQuery('#taglist-modal-datTable').DataTable({
		  "ordering": false,
		  "searching": false
		});
				
		jQuery('.tag_option').click(function() {
			var tag_data = jQuery('.tag_option:checked').attr('data');			
			parent.tinymce.activeEditor.tag_data = tag_data;
		});
		
		jQuery('.btn-clear').click( function() {
			jQuery('.search_val').val('');
			jQuery('#tag-list-modal-form').submit();
		});
					
	});
	
	jQuery(window).load( function() {
		
		jQuery('#taglist-modal-datTable').removeClass('hide');
		
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
			
				<?php echo form_open('',array('id'=>'tag-list-modal-form', 'class'=>'tag-list-modal-form')); ?>
			
		            <div class="box-header with-border">
		            	<div class="row">
		            		<div class="col-md-2">
								<?php echo form_input('filter[search_val]', $tags_modal_filter['search_val'], 'class="form-control search_val" placeholder="Search..."'); ?>
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
		           
		            <!-- /.box-header -->
		            <div class="box-body">
		            	<?php if(!empty($tagslist_modal)) { ?>
		            		
							<table id="taglist-modal-datTable" class="table table-bordered table-striped taglist-modal-datTable hide">
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
									<?php $i = 0; foreach($tagslist_modal as $tag) { ?>
									
									<?php 
									// GENERATE TAG
									$tag_preview_link = site_url('مقالات-الوسم/'.$tag['alias']); 
									if($tags_modal_filter['search_val'] != '') {
										//$data_tag_title = $tags_modal_filter['search_val'];
										$data_tag_title = str_replace('"', '`', $tag['title']);
									} else {
										$data_tag_title = str_replace('"', '`', $tag['title']);
									}
									?>
									
									<tr>
										<td class="text-center">
											<input type="radio" id="tag_id<?php echo $i; ?>" name="tag_id[]" data="<a href='<?php echo $tag_preview_link; ?>' title='<?php echo $data_tag_title; ?>'><?php echo $data_tag_title; ?></a>" class="tag_option tag_option_id<?php echo $i; ?>" value="<?php echo $tag['id']; ?>" />
										</td>
										<td class="text-center"><?php echo $tag['id']; ?></td>
										<td>
											<?php echo $tag['title']; ?>
											<span class="small">(Alias : <?php echo $tag['alias']; ?>)</span>
										</td>
									</tr>
									<?php $i++; } ?>
								</tbody>
								<tfoot>
									<tr>
										<th class="text-center" width="1%"></th>
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