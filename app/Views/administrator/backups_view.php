<?php



if(!$this->ion_auth->in_access('media_access', 'access')) {
	
	$this->session->set_flashdata('message_type', 'warning');
	$this->session->set_flashdata('message','You are not allowed to visit the Backups page');
	redirect('administrator','refresh');
	
}

?>
<section class="content-header">
	<h1>
		Backups Manager
		<small>Manage backups</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url('administrator/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li class="active">Backups Manager</li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-primary">
	            <div class="box-body">
	            	
	            	<div class="embed-responsive embed-responsive-16by9">
	            		<iframe class="embed-responsive-item" src="<?php echo site_url('assets/administrator/'); ?>psbackup/"></iframe>
	            	</div>
	            	
	            </div>
	        </div>
		</div>
	</div>
</section>