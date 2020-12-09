<div id="main" role="main">
  <!-- MAIN CONTENT -->
  <div id="content" class="container">
    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3">
        <div class="well no-padding">
          <form action="login" id="company-login-form" class="smart-form client-form">
            <header>Sign In</header>
            <fieldset>
              <section>
                <label class="label">E-mail<span class="error">*</span></label>
                <label class="input"> <i class="icon-append fa fa-user"></i>
                  <input type="email" id="username" name="email" maxlength="100" size="100">
                  <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Please enter email address</b>
                </label>
              </section>
              <section>
			    <label class="label">Password<span class="error">*</span></label>
				<label class="input"> <i class="icon-append fa fa-lock"></i>
					<input type="password" name="npassword" id="npassword" placeholder="Password" autocomplete="new-password">
					<b class="tooltip tooltip-bottom-right"> Please enter your new password</b> 
				</label>
			  </section>
              <section>
                <label class="checkbox">
                  <input type="checkbox" id="remember_me" name="remember" checked="">
                  <i></i>Stay signed in</label>
              </section>
            </fieldset>
            <footer>
              <a class="text-danger" href="<?php echo base_url('admin/forgot'); ?>">Forgot Password</a>
              <button type="submit" id="submit" class="btn btn-primary">
                Sign In
              </button>
            </footer>
          </form>
        </div> 
      </div>
    </div>
  </div>
</div>