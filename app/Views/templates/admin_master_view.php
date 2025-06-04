<?php

use App\Libraries\IonAuth;

$ionAuthLibrary = new IonAuth();


echo view('templates/_parts/admin_master_header_view'); 

?>

<div class="<?php if($ionAuthLibrary->logged_in()) { ?> content-wrapper <?php } else { ?> login-wrapper <?php } ?>">

	<?php if($ionAuthLibrary->logged_in()) { ?>
	<?php if(session()->getFlashdata('message')) { ?>
		<div class="message-area-wrapper">
			<div class="alert alert-<?php echo session()->getFlashdata('message_type');?> alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<?php if(session()->getFlashdata('message_type') == 'success') { ?>				
					<h4><i class="icon fa fa-check"></i> Message!</h4>
				<?php } elseif(session()->getFlashdata('message_type') == 'danger') { ?>
					<h4><i class="icon fa fa-ban"></i> Error!</h4>
				<?php } elseif(session()->getFlashdata('message_type') == 'warning') { ?>
					<h4><i class="icon fa fa-warning"></i> Warning!</h4>
				<?php } elseif(session()->getFlashdata('message_type') == 'info') { ?>
					<h4><i class="icon fa fa-info"></i> Notice!</h4>
				<?php } else { ?>
					<h4><i class="icon fa fa-info"></i> Alert!</h4>
				<?php } ?>
				<?php echo session()->getFlashdata('message');?>
			</div>
		</div>
	<?php } ?>
	<?php } ?>

	<?php echo $the_view_content; ?>
	
</div>

<?php echo view('templates/_parts/admin_master_footer_view'); ?>