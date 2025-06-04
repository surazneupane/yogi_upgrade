<?php

use App\Libraries\IonAuth;
use Config\Services;

$ionAuthLibrary = new IonAuth();
$currentController = Services::router()->controllerName();
$currentController = strtolower(class_basename($currentController));

?>

<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	<title><?php echo $page_title; ?></title>

	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

	<!-- Bootstrap 3.3.6 -->
	<link rel="stylesheet" href="<?php echo base_url('assets/administrator/bootstrap/css/bootstrap.min.css'); ?>">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?php echo base_url('assets/administrator/font-awesome/css/font-awesome.min.css'); ?>">
	<!-- Ionicons -->
	<link rel="stylesheet" href="<?php echo base_url('assets/administrator/ionicons/css/ionicons.min.css'); ?>">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?php echo base_url('assets/administrator/plugins/datatables/dataTables.bootstrap.css'); ?>">

	<!-- iCheck -->
	<link rel="stylesheet" href="<?php echo base_url('assets/administrator/plugins/iCheck/flat/blue.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/administrator/plugins/iCheck/square/blue.css'); ?>">
	<!-- Morris chart -->
	<link rel="stylesheet" href="<?php echo base_url('assets/administrator/plugins/morris/morris.css'); ?>">
	<!-- jvectormap -->
	<link rel="stylesheet" href="<?php echo base_url('assets/administrator/plugins/jvectormap/jquery-jvectormap-1.2.2.css'); ?>">
	<!-- Date Picker -->
	<link rel="stylesheet" href="<?php echo base_url('assets/administrator/plugins/datepicker/datepicker3.css'); ?>">
	<!-- Daterange picker -->
	<link rel="stylesheet" href="<?php echo base_url('assets/administrator/plugins/daterangepicker/daterangepicker.css'); ?>">

	<link rel="stylesheet" href="<?php echo base_url('assets/administrator/plugins/colorpicker/bootstrap-colorpicker.min.css'); ?>">

	<!-- bootstrap wysihtml5 - text editor -->
	<link rel="stylesheet" href="<?php echo base_url('assets/administrator/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css'); ?>">

	<link rel="stylesheet" href="<?php echo base_url('assets/administrator/css/jquery.fancybox.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/administrator/plugins/select2/select2.min.css'); ?>">

	<link rel="stylesheet" href="<?php echo base_url('assets/administrator/css/AdminLTE.min.css'); ?>">

	<!-- AdminLTE Skins. Choose a skin from the css/skins
		folder instead of downloading all of them to reduce the load. -->
	<link rel="stylesheet" href="<?php echo base_url('assets/administrator/css/skins/_all-skins.min.css'); ?>">

	<link rel="stylesheet" href="<?php echo base_url('assets/administrator/plugins/datetimepicker/bootstrap-datetimepicker.min.css'); ?>">

	<link rel="stylesheet" href="<?php echo base_url('assets/administrator/css/admin_template.css'); ?>">

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

	<!-- jQuery 2.2.3 -->
	<script src="<?php echo base_url('assets/administrator/plugins/jQuery/jquery-2.2.3.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/administrator/plugins/sweetalert/sweetalert.min.js'); ?>"></script>

	<!-- Bootstrap 3.3.6 -->
	<script src="<?php echo base_url('assets/administrator/bootstrap/js/bootstrap.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/administrator/plugins/datatables/jquery.dataTables.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/administrator/plugins/datatables/dataTables.bootstrap.min.js'); ?>"></script>

	<!-- jQuery UI 1.11.4 -->
	<script src="<?php echo base_url('assets/administrator/plugins/jQueryUI/jquery-ui.min.js'); ?>"></script>

	<!-- Morris.js charts -->
	<script src="<?php echo base_url('assets/administrator/plugins/morris/raphael-min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/administrator/plugins/morris/morris.min.js'); ?>"></script>

	<script src="<?php echo base_url('assets/administrator/plugins/chartjs/Chart.min.js'); ?>"></script>

	<script type="text/javascript">
		var baseURL = "<?php echo base_url(); ?>";
	</script>

	<?php echo $before_head; ?>

</head>

<body site-url="<?php echo base_url(); ?>" class="hold-transition <?php if ($ionAuthLibrary->logged_in()) { ?> skin-blue sidebar-mini <?php } else { ?> login-page <?php } ?>">

	<?php if ($ionAuthLibrary->logged_in()) { ?>
		<div class="wrapper">

			<header class="main-header">

				<!-- form start -->
				<form action="" method="post" id="clear-cache-form" class="clear-cache-form">
					<?= csrf_field() ?>
				</form>

				<!-- Logo -->
				<a href="<?php echo base_url('administrator/dashboard'); ?>" class="logo">
					<!-- mini logo for sidebar mini 50x50 pixels -->
					<span class="logo-mini"><b>P</b>S</span>
					<!-- logo for regular state and mobile devices -->
					<span class="logo-lg"><b>PS</b>CMS</span>
				</a>
				<!-- Header Navbar: style can be found in header.less -->
				<nav class="navbar navbar-static-top">
					<!-- Sidebar toggle button-->
					<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
						<span class="sr-only">Toggle navigation</span>
					</a>

					<div class="navbar-custom-menu">
						<ul class="nav navbar-nav">
							<!-- User Account: style can be found in dropdown.less -->
							<li class="dropdown user user-menu">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									<?php if ($current_user->photo != '') { ?>
										<img src="<?php echo base_url('assets/media/' . $current_user->photo); ?>" class="user-image" alt="User Image">
									<?php } else { ?>
										<img src="<?php echo base_url('assets/administrator/source/no_user.png'); ?>" class="user-image" alt="User Image">
									<?php } ?>
									<span class="hidden-xs"><?php echo $current_user->first_name . ' ' . $current_user->last_name; ?></span>
								</a>
								<ul class="dropdown-menu">
									<!-- User image -->
									<li class="user-header">
										<?php if ($current_user->photo != '') { ?>
											<img src="<?php echo base_url('assets/media/' . $current_user->photo); ?>" class="img-circle" alt="User Image">
										<?php } else { ?>
											<img src="<?php echo base_url('assets/administrator/source/no_user.png'); ?>" class="img-circle" alt="User Image">
										<?php } ?>
										<p>
											<?php echo $current_user->first_name . ' ' . $current_user->last_name; ?>
											<small>Member since <?php echo date('M. Y', $current_user->created_on); ?></small>
										</p>
									</li>
									<!-- Menu Body -->
									<!--<li class="user-body">
										<div class="row">
											<div class="col-xs-4 text-center">
												<a href="#">Followers</a>
											</div>
											<div class="col-xs-4 text-center">
												<a href="#">Sales</a>
											</div>
											<div class="col-xs-4 text-center">
												<a href="#">Friends</a>
											</div>
										</div>
									</li>-->
									<!-- Menu Footer-->
									<li class="user-footer">
										<div class="pull-left">
											<a href="<?php echo base_url('administrator/user/profile'); ?>" class="btn btn-default btn-flat">Profile</a>
										</div>
										<div class="pull-right">
											<a href="<?php echo base_url('administrator/user/logout'); ?>" class="btn btn-default btn-flat">Sign out</a>
										</div>
									</li>
								</ul>
							</li>
							<!-- Control Sidebar Toggle Button -->
							<li>
								<a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
							</li>
							<?php if ($siteConfiguration['site_caching'] == 1) { ?>
								<li>
									<a href="javascript:void(0)" id="clear-cache-ajax" class="clear-cache-ajax"><i class="fa fa-bolt" aria-hidden="true"></i>&nbsp; Clear Cache</a>
								</li>
							<?php } ?>
							<li>
								<a href="<?php echo base_url(); ?>" target="_blank" class="btn btn-sitepreview">
									<i class="fa fa-eye"></i>
								</a>
							</li>
						</ul>
					</div>
				</nav>
			</header>

			<!-- Left side column. contains the logo and sidebar -->
			<aside class="main-sidebar">
				<!-- sidebar: style can be found in sidebar.less -->
				<section class="sidebar">
					<!-- Sidebar user panel -->
					<div class="user-panel">
						<div class="pull-left image">
							<?php if ($current_user->photo != '') { ?>
								<img src="<?php echo base_url('assets/media/' . $current_user->photo); ?>" class="img-circle" alt="User Image">
							<?php } else { ?>
								<img src="<?php echo base_url('assets/administrator/source/no_user.png'); ?>" class="img-circle" alt="User Image">
							<?php } ?>
						</div>
						<div class="pull-left info">
							<p><?php echo $current_user->first_name . ' ' . $current_user->last_name; ?></p>
							<a href="#"><i class="fa fa-circle text-success"></i> Online</a>
						</div>
					</div>
					<!-- search form -->
					<!--<form action="#" method="get" class="sidebar-form">
						<div class="input-group">
							<input type="text" name="q" class="form-control" placeholder="Search...">
								<span class="input-group-btn">
									<button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
								</button>
							</span>
						</div>
					</form>-->
					<!-- /.search form -->
					<!-- sidebar menu: : style can be found in sidebar.less -->
					<ul class="sidebar-menu">
						<li class="header">MAIN NAVIGATION</li>
						<li class="<?php if ($currentController == 'dashboard') { ?>active<?php } ?>">
							<a href="<?php echo base_url('administrator/dashboard'); ?>">
								<i class="fa fa-dashboard"></i> <span>Dashboard</span>
							</a>
						</li>
						<?php // if($ionAuthLibrary->is_admin()) { 
						?>
						<?php if ($ionAuthLibrary->in_access('group_access', 'access') || $ionAuthLibrary->in_access('user_access', 'access') || $ionAuthLibrary->in_access('presenter_access', 'access') || $ionAuthLibrary->in_access('subscriber_access', 'access')) { ?>
							<li class="<?php if ($currentController == 'groups' || $currentController == 'users' || $currentController == 'presenters' || $currentController == 'subscribers') { ?>active<?php } ?> <?php if ($ionAuthLibrary->is_admin()) { ?>treeview<?php } ?>">
								<a href="#">
									<i class="fa fa-users"></i> <span>Users</span>
									<span class="pull-right-container">
										<i class="fa fa-angle-left pull-right"></i>
									</span>
								</a>
								<ul class="treeview-menu">
									<?php if ($ionAuthLibrary->in_access('group_access', 'access')) { ?>
										<li class="<?php if ($currentController == 'groups') { ?>active<?php } ?>"><a href="<?php echo base_url('administrator/groups'); ?>"><i class="fa fa-circle-o"></i> Groups</a></li>
									<?php } ?>
									<?php if ($ionAuthLibrary->in_access('user_access', 'access')) { ?>
										<li class="<?php if ($currentController == 'users') { ?>active<?php } ?>"><a href="<?php echo base_url('administrator/users'); ?>"><i class="fa fa-circle-o"></i> Users</a></li>
									<?php } ?>
									<?php if ($ionAuthLibrary->in_access('presenter_access', 'access')) { ?>
										<li class="<?php if ($currentController == 'presenters') { ?>active<?php } ?>"><a href="<?php echo base_url('administrator/presenters'); ?>"><i class="fa fa-circle-o"></i> Presenters</a></li>
									<?php } ?>
									<?php if ($ionAuthLibrary->in_access('subscriber_access', 'access')) { ?>
										<li class="<?php if ($currentController == 'subscribers') { ?>active<?php } ?>"><a href="<?php echo base_url('administrator/subscribers'); ?>"><i class="fa fa-circle-o"></i> Subscribers</a></li>
									<?php } ?>
								</ul>
							</li>
						<?php } ?>
						<?php if ($ionAuthLibrary->in_access('media_access', 'access')) { ?>
							<li class="<?php if ($currentController == 'media') { ?>active<?php } ?>">
								<a href="<?php echo base_url('administrator/media'); ?>">
									<i class="fa fa-image"></i> <span>Media</span>
								</a>
							</li>
						<?php } ?>
						<?php if ($ionAuthLibrary->in_access('menu_access', 'access')) { ?>
							<li class="<?php if ($currentController == 'menus') { ?>active<?php } ?>">
								<a href="<?php echo base_url('administrator/menus'); ?>">
									<i class="fa fa-bars"></i> <span>Menus</span>
								</a>
							</li>
						<?php } ?>
						<?php if ($ionAuthLibrary->in_access('schedule_access', 'access')) { ?>
							<li class="<?php if ($currentController == 'schedules') { ?>active<?php } ?>">
								<a href="<?php echo base_url('administrator/schedules/edit'); ?>">
									<i class="fa fa-clock-o"></i> <span>Schedules</span>
								</a>
							</li>
						<?php } ?>
						<?php if ($ionAuthLibrary->in_access('teacher_access', 'access')) { ?>
							<li class="<?php if ($currentController == 'teachers') { ?>active<?php } ?>">
								<a href="<?php echo base_url('administrator/teachers'); ?>">
									<i class="fa fa-clock-o"></i> <span>Teachers</span>
								</a>
							</li>
						<?php } ?>
						<?php if ($ionAuthLibrary->in_access('category_access', 'access') || $ionAuthLibrary->in_access('tag_access', 'access') || $ionAuthLibrary->in_access('article_access', 'access')) { ?>
							<li class="<?php if ($currentController == 'categories' || $currentController == 'tags' || $currentController == 'articles') { ?>active<?php } ?> <?php if ($ionAuthLibrary->is_admin()) { ?>treeview<?php } ?>">
								<a href="#">
									<i class="fa fa-newspaper-o"></i> <span>Content</span>
									<span class="pull-right-container">
										<i class="fa fa-angle-left pull-right"></i>
									</span>
								</a>
								<ul class="treeview-menu">
									<?php if ($ionAuthLibrary->in_access('category_access', 'access')) { ?>
										<li class="<?php if ($currentController == 'categories') { ?>active<?php } ?>"><a href="<?php echo base_url('administrator/categories'); ?>"><i class="fa fa-circle-o"></i> Categories</a></li>
									<?php } ?>
									<?php if ($ionAuthLibrary->in_access('tag_access', 'access')) { ?>
										<li class="<?php if ($currentController == 'tags') { ?>active<?php } ?>"><a href="<?php echo base_url('administrator/tags'); ?>"><i class="fa fa-circle-o"></i> Tags</a></li>
									<?php } ?>
									<?php if ($ionAuthLibrary->in_access('article_access', 'access')) { ?>
										<li class="<?php if ($currentController == 'articles') { ?>active<?php } ?>"><a href="<?php echo base_url('administrator/articles'); ?>"><i class="fa fa-circle-o"></i> Articles</a></li>
									<?php } ?>
								</ul>
							</li>
						<?php } ?>
						<!--<?php // if($ionAuthLibrary->in_access('user_access', 'access')) { 
							?>
						<li class="<?php // if($currentController == 'reports') { 
									?>active<?php // } 
											?> <?php // if($ionAuthLibrary->is_admin()) { 
												?>treeview<?php // } 
																											?>">
							<a href="#">
								<i class="fa fa-pie-chart"></i> <span>Reports</span>
								<span class="pull-right-container">
									<i class="fa fa-angle-left pull-right"></i>
								</span>
							</a>
							<ul class="treeview-menu">
								<li class="<?php // if($this->router->method == 'articles_reports') { 
											?>active<?php // } 
													?>"><a href="<?php // echo base_url('administrator/reports/articles_reports'); 
																	?>"><i class="fa fa-circle-o"></i> Articles Reports</a>
								<li class="<?php // if($this->router->method == 'categories_reports') { 
											?>active<?php // } 
													?>"><a href="<?php // echo base_url('administrator/reports/categories_reports'); 
																	?>"><i class="fa fa-circle-o"></i> Categories Reports</a>
							</ul>
						</li>
						<?php // } 
						?>
						<?php // if($ionAuthLibrary->in_access('album_access', 'access')) { 
						?>
						<li class="<?php // if($currentController == 'albums') { 
									?>active<?php // } 
											?>">
							<a href="<?php // echo base_url('administrator/albums'); 
										?>">
								<i class="fa fa-camera-retro"></i> <span>Albums</span>
							</a>
						</li>
						<?php // } 
						?>-->
						<?php if ($ionAuthLibrary->in_access('contact_access', 'access')) { ?>
							<li class="<?php if ($currentController == 'contacts') { ?>active<?php } ?>">
								<a href="<?php echo base_url('administrator/contacts'); ?>">
									<i class="fa fa-envelope"></i> <span>Contacts</span>
								</a>
							</li>
						<?php } ?>
						<?php if ($ionAuthLibrary->is_admin()) { ?>
							<li class="<?php if ($currentController == 'site_configurations') { ?>active<?php } ?>">
								<a href="<?php echo base_url('administrator/site_configurations/edit'); ?>">
									<i class="fa fa-gear"></i> <span>Site Configuration</span>
								</a>
							</li>
							<li class="<?php if ($currentController == 'home_configurations') { ?>active<?php } ?>">
								<a href="<?php echo base_url('administrator/home_configurations/edit'); ?>">
									<i class="fa fa-gear"></i> <span>Home Configuration</span>
								</a>
							</li>
							<!--<li class="<?php // if($currentController == 'backups') { 
											?>active<?php // } 
													?>">
							<a href="<?php // echo base_url('administrator/backups'); 
										?>">
								<i class="fa fa-hdd-o" aria-hidden="true"></i> <span>Backups</span>
							</a>
						</li>-->
							<?php if ($siteConfiguration['site_caching'] == 1) { ?>
								<li>
									<a href="javascript:void(0)" class="clear-cache-ajax">
										<i class="fa fa-bolt" aria-hidden="true"></i> <span>Clear Cache</span>
									</a>
								</li>
							<?php } ?>
						<?php } ?>
					</ul>
				</section>
				<!-- /.sidebar -->
			</aside>

		<?php } ?>