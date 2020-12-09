<div id="main" role="main">
  <!-- MAIN CONTENT -->
  <div id="content" class="container">
    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3">
        <div class="well no-padding">
          <form  id="resetpassForm"  method="post" action="<?php echo base_url('password/ChangePassword/update_password') ?>" enctype="multipart/form-data" class="smart-form client-form">
            <header>
              Change Password
            </header>
            <fieldset>
              <section>
                <label class="label">Password</label>
                <label class="input"> <i class="icon-append fa fa-lock"></i>
                <input type="password" id="password" name="password" class="disablecopypaste">
                <input type="hidden" name="e" value="<?php echo $encode_email; ?>">
                <b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-teal"></i> Enter your password</b> </label>
              </section>
              <section>
                <label class="label">Confirm Password</label>
                <label class="input"> <i class="icon-append fa fa-lock"></i>
                <input type="password" id="cpassword" name="cpassword" class="disablecopypaste">
                <b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-teal"></i> Enter your confirm password</b> </label>
              </section>
            </fieldset>
            <footer>
              <button type="submit" id="submit" class="btn btn-primary">Change</button>
            </footer>
          </form>
        </div> 
      </div>
    </div>
  </div>
</div>