<?php


?>

<!--	CONTENT WRAPPER	[START]	  -->
<div class="content-wrapper">

	<!--	ARTICLE LAYOUT WRAPPER	[START]	  -->
	<div class="articledetails-layout-wrapper contactus-content-wrapper">

		<section class="banner-another ">
			<!-- Banner section Start-->
		</section>

		<section class="contact contactus-page">

			<!-- container Start-->
			<div class="container">
				<div class="row" data-aos="fade-up" data-aos-duration="400">
					<div class="col-lg-6 col-md-12 col-12 columns-1">
						<h2><?php echo lang('InformationLang.OUR_ADDRESS'); ?></h2>
						<p><img src="<?php echo base_url('assets/site/images/logo-all-yogis.png'); ?>" alt="logo" class="d-block mx-auto"></p>
						<address>
							<?php echo lang('InformationLang.ADDRESS'); ?>
						</address>
					</div>
					<div class="col-lg-1 col-md-12 col-12"></div>
					<div class="col-lg-5 col-md-12 col-12 columns-2">
						<h2><?php echo lang('InformationLang.QUICK_CONTACT'); ?></h2>
						<?php if (session()->getFlashdata('validationErrors')): ?>
							<?php foreach (session()->getFlashdata('validationErrors') as $error): ?>
								<p class="text-danger">
									<?= $error ?>
								</p>
							<?php endforeach; ?>
						<?php endif; ?>
						<form action="<?= base_url('contact') ?>" method="post" id="contact-form" class="row form-inline contact-form">

							<div class="col-md-6 form-group">
								<input
									type="text"
									name="full_name"
									value="<?= old('full_name') ?>"
									class="form-control"
									placeholder="<?= lang('InformationLang.YOUR_NAME') ?>"
									required>
							</div>

							<div class="col-md-6 form-group">
								<input
									type="email"
									name="email_address"
									value="<?= old('email_address') ?>"
									class="form-control"
									placeholder="<?= lang('InformationLang.YOUR_EMAIL') ?>"
									required>
							</div>

							<div class="col-md-12 form-group">
								<input
									type="text"
									name="subject"
									value="<?= old('subject') ?>"
									class="form-control"
									placeholder="<?= lang('InformationLang.SUBJECT') ?>">
							</div>

							<div class="col-md-12 form-group">
								<textarea
									name="message"
									class="form-control"
									placeholder="<?= lang('InformationLang.MESSAGE') ?>"><?= old('message') ?></textarea>
							</div>

							<div class="col-md-12 form-group">
								<div class="g-recaptcha" data-sitekey="<?php echo config('Validation')->captchaKey ?>"></div>
							</div>

							<div class="col-md-12">
								<br />
								<button type="submit" class="btn btn-primary"><?= lang('InformationLang.SUBMIT') ?></button>
							</div>

						</form>

					</div>
				</div>
			</div>
			<!-- container Ended-->

		</section>
		<section class="blog-page-another"></section>

	</div>
	<!--	ARTICLE LAYOUT WRAPPER	[START]	  -->

</div>
<!--	CONTENT WRAPPER	[END]	  -->