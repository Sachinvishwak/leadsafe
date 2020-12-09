
  <!-- MAIN CONTENT -->
        <?php
            
            if($status)
            { ?>
                <div id="main" role="main">
                    <div id="content" class="container">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3">
                                <div class="well no-padding">
                                  <form id="contractorresetpassForm" action="<?php echo base_url('contractor/Contractorapi/resetpassword'); ?>" class="smart-form contractor-form" method="post">
                                    <header>Set Password</header>
                                    <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                                    <fieldset>
                                      <section>
                                        <label class="label">New Password<span class="error">*</span></label>
                                        <label class="input"> <i class="icon-append fa fa-user"></i>
                                          <input type="password" id="password" name="password" maxlength="100" size="100">
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
                                      <button type="submit" id="submit" class="btn btn-primary">
                                        submit
                                      </button>
                                    </footer>
                                  </form>
                                </div> 
                              </div>
                          </div>
                    </div>
                </div>
            <?php 
            }else{
                echo '<div style="height: 100vh;width: 100%;"><h1 style="position: absolute;top: 50%;left: 50%;transform: translate(-50%,-50%);"><center>'.$title.'</center></h1></div>';
            }
        ?>
      
    
