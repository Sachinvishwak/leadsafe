<div id="main" role="main">
  <!-- MAIN CONTENT -->
  <div id="content" class="container">
    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3">
        <div class="well no-padding">
            
          <?php
            if($ispasswordset)
            {  ?>
            
            <form action="resetpassword" id="allmemberresetpassword" class="smart-form client-form">
                <header>Create Password</header>
                <fieldset>
                    <input type="hidden" name="role" value="<?php echo $role; ?>"/>
                    <section>
        				<label class="input"> <i class="icon-append fa fa-user"></i>
        					<input type="text" name="name" value="<?php echo $name; ?>" readonly>
        					<b class="tooltip tooltip-bottom-right"> name</b> 
        				</label>
        			  </section>
        			  <section>
        				<label class="input"> <i class="icon-append fa fa-envelope"></i>
        					<input type="text" name="email" value="<?php echo $email; ?>" readonly>
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
                
            <?php }else{ ?>
                
                <header>Successful Verification</header>
              <fieldset>
               <section>
                <label class="label" style="font-family:'Courier New';font-size:15px" >Hello <?php echo $full_name; ?>,</label>
                <label class="input"> 
                 <blockquote class="blockquote text-center" style="font-family:'Courier New';font-size:19px">
                 <p class="mb-0"><?php echo $message; ?></p>
                
                     <td>
                    <a style="border: 10px #dd4b39 solid;background:#dd4b39;color: white;text-decoration: none;" class="button button--green" href="<?php echo $reseturl; ?>">Create Your Passsword</a>
                    </td>
                 </blockquote>
                </label>
              </section>
                  
            </fieldset>
                
           <?php }
          ?>
          
        </div> 
      </div>
    </div>
  </div>
</div>