<div id="main" role="main">
  <!-- MAIN CONTENT -->
  <div id="content" class="container">
    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3">
        <div class="well no-padding">
          <form action="crewreset" id="crew-resetpassword-form" class="smart-form client-form">
            <header>Sign In</header>
            <fieldset>
              <section>
                <label class="label">New Password<span class="error">*</span></label>
                <label class="input"> <i class="icon-append fa fa-user"></i>
                  <input type="password" id="newpassword" name="newpassword" maxlength="100" size="100">
                  <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Please enter New password</b>
                </label>
              </section>
              <section>
			    <label class="label"> confirm Password<span class="error">*</span></label>
				<label class="input"> <i class="icon-append fa fa-lock"></i>
					<input type="password" name="cpassword" id="cpassword" placeholder="confirm Password" autocomplete="confirm Password">
					<b class="tooltip tooltip-bottom-right"> Please enter confirm new password</b> 
				</label>
			  </section>
             
            </fieldset>
            <footer>
             <!--  <a class="text-danger" href="<?php echo base_url('contractor/signup'); ?>">Don't have an account ?</a>-->  <button type="submit" id="submit" class="btn btn-primary">
                Sign In
              </button>
            </footer>
          </form>
        </div> 
      </div>
    </div>
  </div>
</div>