
<div id="main" role="main">
	<!-- MAIN CONTENT -->
	<div id="content" class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3">
				<div class="well no-padding">
					<form action="registration" method="post" id="smart-form-company-register" class="smart-form client-form" enctype="multipart/form-data" autocomplete="off">
						<header>
							Company Registration
						</header>
						<fieldset>
							<section>
							 	<label class="label">Full Name<span class="error">*</span></label>
								<label class="input"> <i class="icon-append fa fa-user"></i>
									<input type="text" name="fullName" placeholder="Full name" maxlength="50" size="50">
									<b class="tooltip tooltip-bottom-right"> Please enter your full name</b> </label>
							</section>
							<section>
							 	<label class="label">Email<span class="error">*</span></label>
								<label class="input"> <i class="icon-append fa fa-envelope"></i>
									<input type="email" name="email" placeholder="Email address"  maxlength="100" size="100">
									<b class="tooltip tooltip-bottom-right"> Please enter your email</b> </label>
							</section>
							<section>
							    <label class="label">Password<span class="error">*</span></label>
                				<label class="input"> <i class="icon-append fa fa-lock"></i>
                					<input type="password" name="npassword" id="npassword" placeholder="Password" autocomplete="new-password">
                					<b class="tooltip tooltip-bottom-right"> Please enter your new password</b> 
                				</label>
                			  </section>
                			  <section>
                			    <label class="label">Confirm Password<span class="error">*</span></label>
                				<label class="input"> <i class="icon-append fa fa-lock"></i>
                					<input type="password" name="rnpassword" placeholder="Confirm password" autocomplete="new-password">
                					<b class="tooltip tooltip-bottom-right"> Please re-enter your password</b> 
                				</label>
                			  </section>
							<section>
								<label class="label">Contact Number<span class="error">*</span></label>
								<label class="input"> <i class="icon-append fa fa-phone"></i>
								<input type="text" name="contact" maxlength="20" size="20" class="number-only" placeholder="Contact" data-mask="(999) 999-9999">
								<b class="tooltip tooltip-bottom-right"> Please enter your contact number</b> </label>
							</section>
							<section>
								<label class="label">Fax Number<span class="error">*</span></label>
								<label class="input"> <i class="icon-append fa fa-phone"></i>
								<input type="text" name="fax_number" maxlength="20" size="20" class="number-only" placeholder="Contact" data-mask="(999) 999-9999">
								<b class="tooltip tooltip-bottom-right"> Please enter your fax number</b> </label>
							</section>
							<section>
								<label class="label">license<span class="error">*</span></label>
								<label class="input"> <i class="icon-append fa fa-phone"></i>
								<input type="file" name="licence"  placeholder="Licence" >
								<b class="tooltip tooltip-bottom-right"> Upload license</b> </label>
							</section>
							<section>
								<label class="label">Insurance Certificate<span class="error">*</span></label>
								<label class="input"> <i class="icon-append fa fa-phone"></i>
								<input type="file" name="insurence_certificate" placeholder="Insurence Certificate">
								<b class="tooltip tooltip-bottom-right"> Upload Insurance Certificate</b> </label>
							</section>
						</fieldset>
						<footer>
						    <a class="text-danger" href="<?php echo base_url('admin/login'); ?>">Already have an account ?</a>
							<button type="submit" id="submit" class="btn btn-primary">
								Register
							</button>
						</footer>
						<div class="message">
							<i class="fa fa-check"></i>
							<p> Thank you for your registration!</p>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>


