<?php



?>

<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

		<title><?php echo $page_title;?></title>
		
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
		
		<meta name="description" content="PSCMS - Site is Offline"/>
        <meta name="author" content="PSCMS">
        
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,900" rel="stylesheet">
        
        <link rel="stylesheet" href="<?php echo site_url('assets/site/css/bootstrap.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo site_url('assets/site/css/offline.css'); ?>">
        
        <script type="text/javascript">
	        var baseURL = "<?php echo base_url(); ?>";
	    </script>
        
	</head>
	<body>
	
		<div class="pscms-wrapper site-wrapper">

			<div id="notfound">
				<div class="notfound">
					<a href="#" style="background: none; box-shadow: none;">
						<?php if($site_offlineimage != '') { ?>
							<img src="<?php echo site_url('assets/media/').$site_offlineimage; ?>" class="img-responsive" />
						<?php } else { ?>
							<img src="<?php echo site_url('assets/site/images/logo.png'); ?>" class="img-responsive" />
						<?php } ?>
					</a>
					<div class="notfound-404">
						<h1>Offline</h1>
					</div>
				
					<?php if($site_offlinemessage != '') { ?>
						<h2><?php echo $site_offlinemessage; ?></h2>
					<?php } else { ?>
						<h2>Site is offline</h2>
					<?php } ?>
					<br/>
					<br/>	
					
					<a href="#">Check it back soon</a>
				</div>
			</div>
		
		</div>
	
	</body>
</html>