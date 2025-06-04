<?php

use App\Libraries\IonAuth;
use CodeIgniter\CodeIgniter;

$ionAuthLibrary = new IonAuth();

?>

	<?php if($ionAuthLibrary->logged_in()) { ?>
			
			<footer class="main-footer">
				<div class="pull-right hidden-xs">					
					<strong>PSCMS</strong> | Page rendered in <strong>{elapsed_time}</strong> seconds.<?php echo (ENVIRONMENT === 'development') ? ' | <b>Version</b> ' . CodeIgniter::CI_VERSION : '' ?>
				</div>
				<strong>Copyright &copy; <?php echo date('Y'); ?> <a title="DOTCOM" href="http://www.panchsoft.com" target="_blank">PANCHSOFT</a>.</strong> All rights reserved. <br/>Made with &nbsp;&nbsp;<i class="fa fa-heart" style="color: red;"></i>&nbsp;&nbsp; in AHMEDABAD (BHARAT).
			</footer>
			
			<!-- Control Sidebar -->
			<aside class="control-sidebar control-sidebar-dark">
				<!-- Create the tabs -->
				<ul class="nav nav-tabs nav-justified control-sidebar-tabs">
					
				</ul>
				<!-- Tab panes -->
				<div class="tab-content">
				
					<!-- Home tab content -->
					<div class="tab-pane" id="control-sidebar-home-tab">
					
						
					
					</div>
					<!-- /.tab-pane -->
					
					
				</div>
			</aside>
			<!-- /.control-sidebar -->
			<!-- Add the sidebar's background. This div must be placed
			immediately after the control sidebar -->
			<div class="control-sidebar-bg"></div>
	 
		</div>
		
	<?php } ?>
		
		
		
		
		<script src="<?php echo site_url('assets/administrator/plugins/iCheck/icheck.min.js'); ?>"></script>
		
		<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
		<script>
		$.widget.bridge('uibutton', $.ui.button);
		</script>
		<!-- Sparkline -->
		<script src="<?php echo site_url('assets/administrator/plugins/sparkline/jquery.sparkline.min.js'); ?>"></script>
		<!-- jvectormap -->
		<script src="<?php echo site_url('assets/administrator/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js'); ?>"></script>
		<script src="<?php echo site_url('assets/administrator/plugins/jvectormap/jquery-jvectormap-world-mill-en.js'); ?>"></script>
		<!-- jQuery Knob Chart -->
		<script src="<?php echo site_url('assets/administrator/plugins/knob/jquery.knob.js'); ?>"></script>
		<!-- daterangepicker -->
		<script src="<?php echo site_url('assets/administrator/plugins/daterangepicker/moment.min.js'); ?>"></script>
		<script src="<?php echo site_url('assets/administrator/plugins/daterangepicker/daterangepicker.js'); ?>"></script>
		<!-- datepicker -->
		<script src="<?php echo site_url('assets/administrator/plugins/datepicker/bootstrap-datepicker.js'); ?>"></script>
		
		<script src="<?php echo site_url('assets/administrator/plugins/colorpicker/bootstrap-colorpicker.min.js'); ?>"></script>
		
		<!-- Bootstrap WYSIHTML5 -->
		<script src="<?php echo site_url('assets/administrator/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'); ?>"></script>
		<!-- Slimscroll -->
		<script src="<?php echo site_url('assets/administrator/plugins/slimScroll/jquery.slimscroll.min.js'); ?>"></script>
		<!-- FastClick -->
		<script src="<?php echo site_url('assets/administrator/plugins/fastclick/fastclick.js'); ?>"></script>
		<script src="<?php echo site_url('assets/administrator/plugins/select2/select2.full.min.js'); ?>"></script>
		<!-- AdminLTE App -->
		<script src="<?php echo site_url('assets/administrator/js/app.min.js'); ?>"></script>
		
		<?php if(service('router')->methodName() == 'dashboard') { ?>
			<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
			<script src="<?php echo site_url('assets/administrator/js/pages/dashboard.js'); ?>"></script>
		<?php } ?>
		<!-- AdminLTE for demo purposes -->
		<script src="<?php echo site_url('assets/administrator/js/demo.js'); ?>"></script>
		
		<script src="<?php echo site_url('assets/administrator/plugins/datetimepicker/bootstrap-datetimepicker.min.js'); ?>"></script>
		
		<script src="<?php echo site_url('assets/administrator/tinymce/tinymce.min.js'); ?>"></script>
		
		<script src="<?php echo site_url('assets/administrator/js/jquery.fancybox.js'); ?>"></script>
		<script src="<?php echo site_url('assets/administrator/js/jquery.tinylimiter.js'); ?>"></script>
		<script src="<?php echo site_url('assets/administrator/js/jquery.matchHeight.min.js'); ?>"></script>
		
		<script src="<?php echo site_url('assets/administrator/js/template.js'); ?>"></script>
		
		<?php echo $before_body;?>
	</body>
	 
</html>