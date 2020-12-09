<div id="main" role="main">
  <!-- MAIN CONTENT -->
  <div id="content" class="container">
    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3">
        <div class="well no-padding">
          <form id="admin_forgetpassword"  class="smart-form client-form" method="POST" >
           
            <fieldset>
              <section>
                <label class="label">E-mail<span class="error">*</span></label>
                <label class="input"> <i class="icon-append fa fa-user"></i>
                  <input type="email" id="username" name="email" maxlength="100" size="100">
                   <input type="text"  name="role" value="admin" style="display:none" >
                  <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Please enter email address</b>
                </label>
              </section>
              
              
            </fieldset>
            <footer>
              <!--<a class="text-danger" href="<?php echo base_url('admin/forgot'); ?>">Don't have an account ?</a>-->
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