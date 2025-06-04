<?php



?>

<div class="register-page-wrapper">
	<div class="container">

		<div class="register-page-container">
			<div class="row">
				<div class="col-md-6 register-page-col login-form-col">
					<h3>تسجيل الدخول</h3>
					<div class="seperator"></div>
					<div class="form-div login-form-div">
						<!-- form start -->
						<?php echo form_open('تسجيل-الدخول',array('id'=>'login-form', 'class'=>'login-form')); ?>
						
						<?php echo form_error('login_identity'); ?>
						<div class="form-group has-feedback">
							<?php // echo form_label('Email','login_identity'); ?>
					        <?php echo form_input('login_identity', '', 'class="form-control" placeholder="البريد الإلكتروني"'); ?>				
							<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
						</div>
						
						<?php echo form_error('login_password');?>
						<div class="form-group has-feedback">
							<?php // echo form_label('Password','login_identity');?>
					        <?php echo form_password('login_password', '', 'class="form-control" placeholder="كلمة السر"'); ?>
							<span class="glyphicon glyphicon-lock form-control-feedback"></span>
						</div>
						
						<div class="checkbox icheck">
							<label>
								<?php echo form_checkbox('remember', '1', FALSE, 'class="Icheck"'); ?> تذكر كلمة المرور
							</label>
						</div>
						
						<div class="form-group xlinks-div">
							<p><?php echo anchor('هل-نسيت-كلمة-المرور', 'هل نسيت كلمة المرور؟', array('title' => 'هل نسيت كلمة المرور؟')); ?></p>
						</div>
						
						<?php echo form_submit('submit', 'تسجيل الدخول', 'class="btn btn-red-border"'); ?>
						
						<?php // echo anchor(getFacebookLoginUrl('user/fb_register_callback'), '<i class="fab fa-facebook-f"></i> الدخول عبر فيسبوك', array('title' => 'الدخول عبر فيسبوك', 'class' => 'btn btn-facebook')); ?>
						
						
						<?php // echo form_hidden('task', '');?>
						
						<?php echo form_close();?>						
					</div>
				</div>
				<div class="col-md-6 register-page-col register-form-col">
					<h3>أليس لديك حساب؟</h3>
					<div class="seperator"></div>
					<div class="form-div register-form-div">
						
						<?php echo form_open('تسجيل',array('id'=>'register-form', 'class'=>'register-form')); ?>
						
							<?php echo form_error('first_name'); ?>
							<div class="form-group has-feedback">
								<?php // echo form_label('First name','first_name'); ?>						        
						        <?php echo form_input('first_name',set_value('first_name'),'class="form-control" placeholder="الاسم الاول"'); ?>				
								<span class="glyphicon glyphicon-user form-control-feedback"></span>
							</div>
							
							<?php echo form_error('last_name'); ?>
							<div class="form-group has-feedback">
								<?php // echo form_label('Last name','last_name'); ?>
						        <?php echo form_input('last_name',set_value('last_name'),'class="form-control" placeholder="الكنية"'); ?>
								<span class="glyphicon glyphicon-user form-control-feedback"></span>
							</div>
							
							<?php echo form_error('username'); ?>
							<div class="form-group has-feedback">
								<?php // echo form_label('Username','username'); ?>
						        <?php echo form_input('username',set_value('username'),'class="form-control" placeholder="اسم المستخدم"'); ?>
								<span class="glyphicon glyphicon-lock form-control-feedback"></span>
							</div>
							
							<?php echo form_error('email'); ?>
							<div class="form-group has-feedback">
								<?php // echo form_label('Email','email'); ?>
						        <?php echo form_input('email',set_value('email'),'class="form-control" placeholder="البريد الإلكتروني"'); ?>				
								<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
							</div>
							
							<?php echo form_error('password'); ?>
							<div class="form-group has-feedback">
								<?php // echo form_label('Password','password'); ?>
						        <?php echo form_password('password','','class="form-control" placeholder="كلمة السر"'); ?>				
								<span class="glyphicon glyphicon-lock form-control-feedback"></span>
							</div>
							
							<?php echo form_error('confirm_password'); ?>
							<div class="form-group has-feedback">
								<?php // echo form_label('Confirm password','confirm_password'); ?>
						        <?php echo form_password('confirm_password','','class="form-control" placeholder="تأكيد كلمة المرور"'); ?>				
								<span class="glyphicon glyphicon-log-in form-control-feedback"></span>
							</div>
							
							<?php echo form_error('phone'); ?>
							<div class="form-group has-feedback">
								<?php // echo form_label('Phone','phone'); ?>
						        <?php echo form_input('phone',set_value('phone'),'class="form-control" placeholder="رقم الهاتف"'); ?>				
								<span class="glyphicon glyphicon-earphone form-control-feedback"></span>
							</div>
							
							<div class="row">
								<div class="register-page-col col-xs-8">
									<div class="checkbox icheck">
										<label>
											<?php echo form_checkbox('agree_terms_privacy', '1', FALSE, 'class="Icheck", required'); ?> أنا أوافق على شروط الاستخدام و سياسة الخصوصية
										</label>
									</div>
								</div>
								<!-- /.col -->
								<div class="register-page-col col-xs-4">
									<?php echo form_submit('register', 'سجل الآن', 'class="btn pull-left btn-red-border"');?>
								</div>
								<!-- /.col -->
							</div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
		</div>
	
	</div>
</div>

<!--	LEADERBOARD WRAPPER	[START]	  -->
<?php $this->data['data'] = ''; ?>
<?php $this->load->view('ads/leaderboard', $this->data); ?>
<!--	LEADERBOARD WRAPPER	[END]	  -->
