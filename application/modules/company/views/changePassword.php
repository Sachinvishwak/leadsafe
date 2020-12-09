<!-- widget grid -->
<section id="widget-grid" class="">       
	<!-- row -->
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3">
			<div class="well no-padding">
				<form method="post" action="changepassword" id="smart-form-changepass-company" class="smart-form client-form" enctype="multipart/form-data" novalidate autocomplete="off">
					<header>
						Change Password
					</header>
					<fieldset>
					     <input type="hidden" value="<?php echo $_SESSION['company_sess']['id']; ?>" name="company_id" />
						<section>
							<label class="input"> <i class="icon-append fa fa-lock"></i>
								<input type="password" name="password" placeholder="Current Password" id="password" autocomplete="new-password" >
								<b class="tooltip tooltip-bottom-right"> Please enter your current password</b> 
							</label>
						</section>
						<section>
							<label class="input"> <i class="icon-append fa fa-lock"></i>
								<input type="password" name="npassword" id="npassword" placeholder="Password" autocomplete="new-password">
								<b class="tooltip tooltip-bottom-right"> Please enter your new password</b> 
							</label>
						</section>
						<section>
							<label class="input"> <i class="icon-append fa fa-lock"></i>
								<input type="password" name="rnpassword" placeholder="Confirm password" autocomplete="new-password">
								<b class="tooltip tooltip-bottom-right"> Please re-enter your password</b> 
							</label>
						</section>		
					</fieldset>
					<footer>
						<button type="submit" id="submit" class="btn btn-primary"> Change</button>
					</footer>
				</form>
			</div>	
		</div>
	</div>    
  	<!-- end row -->
</section>
<!-- end widget grid -->