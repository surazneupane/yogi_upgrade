<?php


?>

<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		
		<meta charset="UTF-8">
		
		<link rel="shortcut icon" href="<?php echo base_url('assets/site/images/favicon.png'); ?>"/> 
    	<link rel="apple-touch-icon-precomposed" href="<?php echo base_url('assets/site/images/favicon.png'); ?>"/>
		
		<title><?php echo $page_title;?></title>
		
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
		
		<meta name="description" content="<?php echo ($page_description) ?? ($site_description) ?? ""; ?>"/>
        <meta name="author" content="PSCMS">
		
        <!-- Facebook -->
		<meta content="268162973533788" property="fb:app_id" />
		
		<meta property="og:type" content="<?php echo $fb_og_type; ?>" />
		<meta property="og:url" content="<?php echo $fb_og_url; ?>" />
		<meta property="og:image" content="<?php echo $fb_og_image; ?>" />
		<meta property="og:title" content="<?php echo $fb_og_title; ?>" />
		<meta property="og:description" content="<?php echo $fb_og_description; ?>" />
		<meta property="og:site_name" content="PSCMS" />
		
		<?php if($fb_og_published_time != '') { ?>
		<meta property="article:published_time" content="<?php echo $fb_og_published_time; ?>" />
		<?php } ?>
		<meta property="article:publisher" content="PSCMS" />
		
		<!-- Twitter -->
		<meta name="twitter:card" content="summary_large_image" />
		<meta name="twitter:site"  content="@PSCMS" />
		<meta name="twitter:title" content="<?php echo $fb_og_title; ?>"/>
		<meta name="twitter:description" content="<?php echo $fb_og_description; ?>" />
		<meta name="twitter:image" content="<?php echo $fb_og_image; ?>" />
		
		<!-- Bootstrap CSS -->
	    <link rel="stylesheet" href="<?php echo base_url('assets/site/css/bootstrap.min.css'); ?>">
	    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800|Old+Standard+TT:400,400i,700" rel="stylesheet">
	    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
	    <link rel="stylesheet" href="<?php echo base_url('assets/site/css/animate.css'); ?>">
	    <link rel="stylesheet" href="<?php echo base_url('assets/site/css/main.css'); ?>">
	    <link rel="stylesheet" href="<?php echo base_url('assets/site/css/custom.css'); ?>">

			
		<link rel="canonical" href="<?= base_url(urldecode(service('uri')->getPath())); ?>" />

		
		
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		
		<!-- jQuery first, then Popper.js, then Bootstrap JS -->
	    <script src="<?php echo base_url('assets/site/js/jquery.min.js'); ?>" type="text/javascript"></script>
	    <script src="<?php echo base_url('assets/site/js/popper.min.js'); ?>" type="text/javascript"></script>
	    <script src="<?php echo base_url('assets/site/js/bootstrap.min.js'); ?>" type="text/javascript"></script>
	    <script src="<?php echo base_url('assets/site/js/lazyload.min.js'); ?>" type="text/javascript"></script>
	    
	    		
		<script type="text/javascript">
	        var baseURL = "<?php echo base_url(); ?>";
	    </script>
	    
		<script src="https://www.google.com/recaptcha/api.js"></script>
		
		
		<!-- Google tag (gtag.js) -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=G-ZHYNJ2F8H9"></script>
		<script> window.dataLayer = window.dataLayer || []; function gtag() { dataLayer.push(arguments); } gtag('js', new Date()); gtag('config', 'G-ZHYNJ2F8H9'); </script>
		
		<?php echo $before_head; ?>
					
		
	</head>
	<body class="hold-transition">
	
		