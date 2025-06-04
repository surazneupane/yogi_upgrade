<?php



$album_images = json_decode($album['album_images']);

?>

<!-- blueimp Gallery styles -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/administrator/fileupload/css/blueimp-gallery.min.css">
<!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/administrator/fileupload/css/jquery.fileupload.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/administrator/fileupload/css/jquery.fileupload-ui.css">
<!-- CSS adjustments for browsers with JavaScript disabled -->
<noscript><link rel="stylesheet" href="<?php echo base_url(); ?>assets/administrator/fileupload/css/jquery.fileupload-noscript.css"></noscript>
<noscript><link rel="stylesheet" href="<?php echo base_url(); ?>assets/administrator/fileupload/css/jquery.fileupload-ui-noscript.css"></noscript>

<script type="text/javascript">
	
	jQuery(document).ready( function() {
		
		jQuery('.btn-toolbar .btn-submit').click( function() {
			
			var btn_task = jQuery(this).attr('btn-task');
			jQuery('input[name="task"]').val(btn_task);
			jQuery('#album-edit-form').submit();
			
		});
				
	});

</script>

<section class="content-header">
	<h1>
		Edit Album
		<small>Edit photo album</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url('administrator/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="<?php echo site_url('administrator/albums'); ?>">Albums</a></li>
		<li class="active">Edit album</li>
	</ol>
</section>

<section class="content">

	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
			
				<!-- form start -->				
				<?php echo form_open_multipart('',array('id'=>'album-edit-form', 'class'=>'album-edit-form')); ?>
				
					<div class="box-header with-border">
						<div class="btn-toolbar">
							<?php echo form_button('submit_save', '<i class="fa fa-floppy-o"></i> Save', 'id="submit_save" class="btn btn-submit btn-success" btn-task="save"');?>
							<?php echo form_button('submit_save_close', '<i class="fa fa-check"></i> Save & Close', 'id="submit_save_close" class="btn btn-submit btn-default" btn-task="save_close"');?>
							<?php echo form_button('submit_save_new', '<i class="fa fa-plus"></i> Save & New', 'id="submit_save_new" class="btn btn-submit btn-default" btn-task="save_new"');?>
							<a href="<?php echo site_url('administrator/albums'); ?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
						</div>
					</div>
				
					<div class="box-body">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<?php echo form_label('Title','title');?>
									<?php echo form_error('title');?>
									<?php echo form_input('title', $album['title'], 'class="form-control"'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<?php echo form_label('Alias','alias');?>
									<?php echo form_error('alias');?>
									<?php echo form_input('alias', $album['alias'], 'class="form-control"'); ?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<div class="form-group">
										<?php echo form_label('Album images','album_images');?>
										<?php echo form_error('album_images');?>
										
										<!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
								        <div class="fileupload-buttonbar">
								            <div class="form-group fileupload-buttons">
								            	<div class="btn-toolbar">
									                <!-- The fileinput-button span is used to style the file input field as button -->
									                <span class="btn btn-success fileinput-button">
									                    <i class="glyphicon glyphicon-plus"></i>
									                    <span>Add files...</span>
									                    <input type="file" name="files[]" multiple>
									                </span>
									                <button type="submit" class="btn btn-primary start">
									                    <i class="glyphicon glyphicon-upload"></i>
									                    <span>Start upload</span>
									                </button>
									                <button type="reset" class="btn btn-warning cancel">
									                    <i class="glyphicon glyphicon-ban-circle"></i>
									                    <span>Cancel upload</span>
									                </button>
									                <button type="button" class="btn btn-danger delete">
									                    <i class="glyphicon glyphicon-trash"></i>
									                    <span>Delete</span>
									                </button>
									                &nbsp;&nbsp;
									                <div class="btn btn-default">
									                	<input type="checkbox" class="toggle">
									                </div>
									                <!-- The global file processing state -->
									                <span class="fileupload-process"></span>
								            	</div>
								            </div>								            
								            <!-- The global progress state -->
								            <div class="form-group fileupload-progress fade">
								                <!-- The global progress bar -->
								                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
								                    <div class="progress-bar progress-bar-success" style="width:0%;"></div>
								                </div>
								                <!-- The extended global progress state -->
								                <div class="progress-extended">&nbsp;</div>
								            </div>
								        </div>
								        <!-- The table listing the files available for upload/download -->
								        <div role="presentation" class="jquery-fileupload-presentation">
								        	<div class="row files todo-list">
								        		<?php if(!empty($album_images)) { ?>
								        		<?php for ($i = 0; $i < count($album_images->name); $i++) { ?>
								        			<div class="col-md-3 template-download fade in">
												    	<div class="panel panel-default">
												    		<div class="panel-body">    			
														        <div class="form-group">
														            <span class="preview text-center">
													                    <a href="<?php echo $album_images->url[$i]; ?>" title="<?php echo $album_images->name[$i]; ?>" download="<?php echo $album_images->name[$i]; ?>" data-gallery>
													                    	<img src="<?php echo $album_images->url[$i]; ?>" class="img-responsive" style="height: 80px;">
													                    </a>
														            </span>
														        </div>
														        <div>
														            <p class="name">														                
													                   	<input type="hidden" name="album_images[name][]" class="form-control" value="<?php echo $album_images->name[$i]; ?>" />
													                    <input type="hidden" name="album_images[url][]" class="form-control" value="<?php echo $album_images->url[$i]; ?>" />
													                    <input type="text" name="album_images[title][]" class="form-control" value="<?php echo $album_images->title[$i]; ?>" />
														            </p>
														        </div>
														        <div class="btn-actions btn-group">														            
														                <button class="btn btn-danger delete" data-type="DELETE" data-url="<?php echo base_url(); ?>administrator/fileupload?file=<?php echo $album_images->name[$i]; ?>">								                    <i class="glyphicon glyphicon-trash"></i>
														                    <!--<span>Delete</span>-->
														                </button>
														                <!-- drag handle -->
																		<span class="btn btn-default input-group-move">
																			<span class="handle">
																				<i class="fa fa-arrows"></i>
																			</span>
																		</span>
														                <div class="btn btn-deletecheck btn-default">
														                	<input type="checkbox" name="delete" value="1" class="toggle">
														                </div>
														        </div>
												    		</div>
												   	 	</div>
												    </div>
								        		<?php } ?>
								        		<?php } ?>
								        	</div>
								        </div>
										
								        <!-- The blueimp Gallery widget -->
										<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-filter=":even">
										    <div class="slides"></div>
										    <h3 class="title"></h3>
										    <a class="prev">‹</a>
										    <a class="next">›</a>
										    <a class="close">×</a>
										    <a class="play-pause"></a>
										    <ol class="indicator"></ol>
										</div>
								        
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<?php echo form_label('Status','status');?>
									<?php echo form_error('status');?>
									<?php 
										$options = array();
										$options[1] = 'Publish';
										$options[0] = 'Unpublish';
										echo form_dropdown('status', $options, $album['status'], 'class="form-control"');
									?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<?php echo form_label('Created Date','created_date');?>
									<?php echo form_error('created_date');?>
									<?php echo form_input('created_date', $album['created_date'],'class="form-control readonly" readonly="readonly"'); ?>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<?php echo form_label('Created By','created_by');?>
									<?php echo form_error('created_by');?>
									<?php
										$options = array();
										foreach ($userlist AS $user) {
											$options[$user['id']] = $user['first_name'].' '.$user['last_name'];
										}
										echo form_dropdown('created_by', $options, $album['created_by'], 'class="form-control"');
									?>
								</div>
							</div>
						</div>
					</div>
					<!-- /.box-body -->
					
					<?php echo form_hidden('album_id',set_value('album_id', $album['id']));?>
					<?php echo form_hidden('task', '');?>
					
					<div class="box-footer">
						<div class="btn-toolbar">
							<?php echo form_button('submit_save', '<i class="fa fa-floppy-o"></i> Save', 'id="submit_save" class="btn btn-submit btn-success" btn-task="save"');?>
							<?php echo form_button('submit_save_close', '<i class="fa fa-check"></i> Save & Close', 'id="submit_save_close" class="btn btn-submit btn-default" btn-task="save_close"');?>
							<?php echo form_button('submit_save_new', '<i class="fa fa-plus"></i> Save & New', 'id="submit_save_new" class="btn btn-submit btn-default" btn-task="save_new"');?>
							<a href="<?php echo site_url('administrator/albums'); ?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
						</div>
					</div>
					
				<?php echo form_close();?>
			
			</div>
		</div>
	</div>

</section>	

<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <div class="col-md-3 template-upload fade">
    	<div class="panel panel-default">
    		<div class="panel-body">
		        <div>
		            <span class="preview text-center"></span>
		        </div>
		        <div>
		            <p class="name">{%=file.name%}</p>
		            <strong class="error text-danger"></strong>
		        </div>
		        <div>
		            <p class="size">Processing...</p>
		            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
		        </div>
		        <div class="btn-actions btn-group">
		            {% if (!i && !o.options.autoUpload) { %}
		                <button class="btn btn-primary start" disabled>
		                    <i class="glyphicon glyphicon-upload"></i>
		                    <!--<span>Start</span>-->
		                </button>
		            {% } %}
		            {% if (!i) { %}
		                <button class="btn btn-warning cancel">
		                    <i class="glyphicon glyphicon-ban-circle"></i>
		                    <!--<span>Cancel</span>-->
		                </button>
		            {% } %}
		        </div>
			</div>
	    </div>
    </div>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <div class="col-md-3 template-download fade">
    	<div class="panel panel-default">
    		<div class="panel-body">    			
		        <div class="form-group">
		            <span class="preview text-center">
		                {% if (file.thumbnailUrl) { %}
		                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery>
		                    	<img src="{%=file.thumbnailUrl%}" class="img-responsive" style="height: 80px;">
		                    </a>
		                {% } %}
		            </span>
		        </div>
		        <div>
		            <p class="name">
		                {% if (file.url) { %}
		                    <!--<a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>-->
		                    <input type="hidden" name="album_images[name][]" class="form-control" value="{%=file.name%}" />
		                    <input type="hidden" name="album_images[url][]" class="form-control" value="{%=file.url%}" />
		                    <input type="text" name="album_images[title][]" class="form-control" value="{%=file.name%}" />
		                {% } else { %}
		                    <!--<span>{%=file.name%}</span>-->
		                    <input type="hidden" name="album_images[name][]" class="form-control" value="{%=file.name%}" />
		                    <input type="hidden" name="album_images[url][]" class="form-control" value="{%=file.url%}" />
		                    <input type="text" name="album_images[title][]" class="form-control" value="{%=file.name%}" />
		                {% } %}
		            </p>
		            {% if (file.error) { %}
		                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
		            {% } %}
		        </div>
		        <!--<div>
		            <span class="size">{%=o.formatFileSize(file.size)%}</span>
		        </div>-->
		        <div class="btn-actions btn-group">
		            {% if (file.deleteUrl) { %}
		                <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
		                    <i class="glyphicon glyphicon-trash"></i>
		                    <!--<span>Delete</span>-->
		                </button>
		                <!-- drag handle -->
						<span class="btn btn-default input-group-move">
							<span class="handle">
								<i class="fa fa-arrows"></i>
							</span>
						</span>
		                <div class="btn btn-deletecheck btn-default">
		                	<input type="checkbox" name="delete" value="1" class="toggle">
		                </div>
		            {% } else { %}
		                <button class="btn btn-warning cancel">
		                    <i class="glyphicon glyphicon-ban-circle"></i>
		                    <!--<span>Cancel</span>-->
		                </button>
		            {% } %}
		        </div>
    		</div>
   	 	</div>
    </div>
{% } %}
</script>

<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="<?php echo base_url(); ?>assets/administrator/fileupload/js/vendor/jquery.ui.widget.js"></script>
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="<?php echo base_url(); ?>assets/administrator/fileupload/js/tmpl.min.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="<?php echo base_url(); ?>assets/administrator/fileupload/js/load-image.all.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="<?php echo base_url(); ?>assets/administrator/fileupload/js/canvas-to-blob.min.js"></script>

<!-- blueimp Gallery script -->
<script src="<?php echo base_url(); ?>assets/administrator/fileupload/js/jquery.blueimp-gallery.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="<?php echo base_url(); ?>assets/administrator/fileupload/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="<?php echo base_url(); ?>assets/administrator/fileupload/js/jquery.fileupload.js"></script>
<!-- The File Upload processing plugin -->
<script src="<?php echo base_url(); ?>assets/administrator/fileupload/js/jquery.fileupload-process.js"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="<?php echo base_url(); ?>assets/administrator/fileupload/js/jquery.fileupload-image.js"></script>
<!-- The File Upload audio preview plugin -->
<script src="<?php echo base_url(); ?>assets/administrator/fileupload/js/jquery.fileupload-audio.js"></script>
<!-- The File Upload video preview plugin -->
<script src="<?php echo base_url(); ?>assets/administrator/fileupload/js/jquery.fileupload-video.js"></script>
<!-- The File Upload validation plugin -->
<script src="<?php echo base_url(); ?>assets/administrator/fileupload/js/jquery.fileupload-validate.js"></script>
<!-- The File Upload user interface plugin -->
<script src="<?php echo base_url(); ?>assets/administrator/fileupload/js/jquery.fileupload-ui.js"></script>

<!-- The main application script -->
<script type="text/javascript">
	
	jQuery(document).ready( function() {
		
		// Initialize the jQuery File Upload widget:
	    jQuery('#album-edit-form').fileupload({
	        // Uncomment the following to send cross-domain cookies:
	        //xhrFields: {withCredentials: true},
	        url: '<?php echo base_url(); ?>administrator/fileupload',
	        acceptFileTypes: /(\.|\/)(jpe?g|png)$/i
	    });
		
	});

</script>

<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE 8 and IE 9 -->
<!--[if (gte IE 8)&(lt IE 10)]>
<script src="<?php echo base_url(); ?>assets/administrator/fileupload/js/cors/jquery.xdr-transport.js"></script>
<![endif]-->