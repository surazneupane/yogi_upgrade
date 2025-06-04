<?php



?>
<div class="forgotpassword-page-wrapper">
	<div class="container">

		<div class="forgotpassword-page-container">
			<div class="row">
				<div class="col-md-6 forgotpassword-page-col center-col forgotpassword-form-col">
					<h3>اعد ضبط كلمه السر</h3>
					<div class="seperator"></div>
					<div class="form-div forgotpassword-form-div">
					
						<!-- form start -->
						<?php echo form_open('هل-نسيت-كلمة-المرور',array('id'=>'forgotpassword-form', 'class'=>'forgotpassword-form')); ?>
					
						<?php echo form_error('email'); ?>
						<div class="form-group has-feedback">
							<?php // echo form_label('Email','email'); ?>					        
					        <?php echo form_input('email',set_value('email'),'class="form-control" placeholder="البريد الإلكتروني"'); ?>				
							<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
						</div>
						<div class="form-group text-center">
							
							<?php echo form_submit('submit', 'خضع', 'class="btn btn-red-border"'); ?>
														
						</div>
						
						<div class="form-group xlinks-div text-center">
							<p><?php echo anchor('تسجيل-الدخول', 'تسجيل الدخول', array('title' => 'تسجيل الدخول')); ?></p>
						</div>
						
						<?php echo form_close(); ?>
						
					</div>
				</div>
			</div>
		</div>
		
	</div>
</div>
		