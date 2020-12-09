<div id="main" role="main">
  <!-- MAIN CONTENT -->
  <div id="content" class="container">
    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3">
        <div class="well no-padding">
          <form action="resetpassword" id="company-reset-password-form" class="smart-form client-form">
            <header>Create Password</header>
            <fieldset>
                <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                
                <section>
    				<label class="input"> <i class="icon-append fa fa-user"></i>
    					<input type="text" name="name" value="<?php echo $company_data->name; ?>" readonly>
    					<b class="tooltip tooltip-bottom-right"> name</b> 
    				</label>
    			  </section>
    			  <section>
    				<label class="input"> <i class="icon-append fa fa-envelope"></i>
    					<input type="text" name="email" value="<?php echo $company_data->email; ?>" readonly>
    					<b class="tooltip tooltip-bottom-right"> email </b> 
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
              <button type="submit" id="submit" class="btn btn-primary">
                Submit
              </button>
            </footer>
          </form>
        </div> 
      </div>
    </div>
  </div>
</div>