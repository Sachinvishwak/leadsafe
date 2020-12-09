
  <body class="animated fadeInDown" data-base-url="<?php echo base_url(); ?>">
    <!-- #preloader -->
    <div class="preloader" id="preloader">
      <div class="spinner"></div>
    </div>
    <!-- #preloader -->
    <header id="header">
      <div id="logo-group">
        <span id="logo" style="color:black;"> <img src="<?php echo base_url('backend_assets/').'img/Logo_01-01.png'; ?>" alt="<?= SITE_NAME; ?>"></span>
      </div>
    </header>
  
    <div id="main" role="main">
      <div id="content" class="container">
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3">
            <div class="well no-padding">
              <form action="crewreset" id="crew-resetpassword-form" class="smart-form client-form">
                <header>Successful Verification</header>
                  <fieldset>
                   <section>
                    <label class="label" style="font-family:'Courier New';font-size:15px" >Hello <?php echo $full_name; ?>,</label>
                    <label class="input"> 
                     <blockquote class="blockquote text-center" style="font-family:'Courier New';font-size:19px">
                     <p class="mb-0"><?php echo $message; ?></p>
                          
                            <table class="body-action" align="center" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td align="center">
                                                    <table border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td>
                                                                <a style="border: 10px #dd4b39 solid;background:#dd4b39;color: white;text-decoration: none;" class="button button--green" href="<?php echo $reseturl; ?>">Reset Your Password</a>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                             </table>    
                    
                     </blockquote>
                    </label>
                  </section>
                      
                </fieldset>
                
              </form>
            </div> 
          </div>
        </div>
      </div>
    </div>
    
</body>
