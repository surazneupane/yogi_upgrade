<?php



?>

<div class="login-box">
	<div class="login-logo">
		<a href="#"><b>PS</b>CMS</a>
	</div>
	<!-- /.login-logo -->
	<div class="login-box-body">

		<?php if (session()->getFlashdata('message')) { ?>
			<div class="message-area-wrapper">
				<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h4><i class="icon fa fa-ban"></i> Alert!</h4>
					<?php echo session()->getFlashdata('message'); ?>
				</div>
			</div>
		<?php } ?>

		<p class="login-box-msg">Sign in to start your session</p>

		<form action="" method="post" class="admin-login-form">
			<div class="form-group has-feedback">
				<label for="identity">Email</label>
				<!-- Display validation error manually here if needed -->
				<!-- <div class="text-danger">Email is required</div> -->
				<input type="text" name="identity" id="identity" class="form-control" placeholder="Email">
				<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
			</div>

			<div class="form-group has-feedback">
				<label for="password">Password</label>
				<!-- <div class="text-danger">Password is required</div> -->
				<input type="password" name="password" id="password" class="form-control" placeholder="Password">
				<span class="glyphicon glyphicon-lock form-control-feedback"></span>
			</div>

			<div class="row">
				<div class="col-xs-8">
					<div class="checkbox icheck">
						<label>
							<input type="checkbox" name="remember" value="1" class="Icheck"> Remember me
						</label>
					</div>
				</div>
				<div class="col-xs-4">
					<input type="submit" name="submit" value="Sign In" class="btn btn-primary btn-block btn-flat">
				</div>
			</div>
		</form>


		<!--<div class="social-auth-links text-center">
			<p>- OR -</p>
			<a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using	Facebook</a>
			<a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using Google+</a>
		</div>-->
		<!-- /.social-auth-links -->

		<!--<a href="<?php // echo site_url('forgotpassword'); 
						?>">I forgot my password</a><br>-->
		<!--<a href="<?php // echo site_url('register'); 
						?>" class="text-center">Register a new membership</a>-->

	</div>
	<!-- /.login-box-body -->
</div>
<!-- /.login-box -->