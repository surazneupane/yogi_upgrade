<?php

helper('form');

?>

<div class="profile-page-wrapper">
	<div class="container">

		<div class="profile-page-container">
			<div class="row">
				<div class="col-md-6 profile-page-col logout-form-col">
					<h3>الخروج</h3>
					<div class="seperator"></div>
					<div class="form-div logout-form-div">
					
						<p><?php echo anchor('user/logout', 'الخروج', array('title' => 'الخروج', 'class' => 'btn btn-lg btn-red-border')); ?></p>
						<hr/>
						<p><?php echo anchor('/قائمة-المقالات-المفضلة', 'قائمة المقالات المفضلة لديك', array('title' => 'قائمة المقالات المفضلة لديك', 'class' => 'btn btn-lg btn-black-border')); ?></p>	
					
					</div>
				</div>
				<div class="col-md-6 profile-page-col profile-form-col">
					<h3>معلومات الملف الشخصي</h3>
					<div class="seperator"></div>
					<div class="form-div profile-form-div">
						<?php echo form_open('الملف-الشخصي',array('id'=>'profile-form', 'class'=>'profile-form')); ?>
						
							<?php echo form_error('first_name'); ?>
							<div class="form-group has-feedback">
								<?php // echo form_label('First name','first_name'); ?>						        
						        <?php echo form_input('first_name',set_value('first_name',$user->first_name),'class="form-control" placeholder="الاسم الاول"'); ?>				
								<span class="glyphicon glyphicon-user form-control-feedback"></span>
							</div>
							
							<?php echo form_error('last_name'); ?>
							<div class="form-group has-feedback">
								<?php // echo form_label('Last name','last_name'); ?>
						        <?php echo form_input('last_name',set_value('last_name',$user->last_name),'class="form-control" placeholder="الكنية"'); ?>
								<span class="glyphicon glyphicon-user form-control-feedback"></span>
							</div>
							
							<?php echo form_error('username'); ?>
							<div class="form-group has-feedback">
								<?php // echo form_label('Username','username'); ?>
						        <?php echo form_input('username',set_value('username', $user->username),'class="form-control" placeholder="اسم المستخدم" readonly'); ?>
								<span class="glyphicon glyphicon-lock form-control-feedback"></span>
							</div>
							
							<?php echo form_error('email'); ?>
							<div class="form-group has-feedback">
								<?php // echo form_label('Email','email'); ?>
						        <?php echo form_input('email',set_value('email',$user->email),'class="form-control" placeholder="البريد الإلكتروني" readonly'); ?>				
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
						        <?php echo form_input('phone',set_value('email',$user->phone),'class="form-control" placeholder="رقم الهاتف"'); ?>				
								<span class="glyphicon glyphicon-earphone form-control-feedback"></span>
							</div>
							
							<div class="form-group">
								<?php echo form_submit('update', 'تحديث', 'class="btn pull-left btn-red-border"');?>
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
