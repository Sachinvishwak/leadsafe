<div id="main" role="main">
	<!-- MAIN CONTENT -->
	<div id="content" class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3">
				<div class="well no-padding">
					<form action="forgotPassword" id="forgot-form" class="smart-form client-form">
						<header>
							Forgot Password
						</header>
						<fieldset>
							<section>
								<label class="label">Enter your email address <span class="error">*</span></label>
								<label class="input"> <i class="icon-append fa fa-envelope"></i>
									<input type="email" name="email" maxlength="100" size="100">
									<b class="tooltip tooltip-top-right"><i class="fa fa-envelope txt-color-teal"></i> Please enter email address to reset password</b></label>
							</section>
							<section>	
								<div class="note">
									<a href="<?php  echo base_url(); ?>">I remembered my password!</a>
								</div>
							</section>
						</fieldset>
						<footer>
							<button type="submit" class="btn btn-primary">
								<i class="fa fa-refresh"></i> Reset Password
							</button>
						</footer>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>