<!DOCTYPE html>
<html lang="en-us" id="extr-page">
  <head>
    <meta charset="utf-8">
    <title>Bild-it</title>
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <?php $backend_assets =  base_url().'backend_assets/'; ?>
    <!-- #CSS Links -->
    <!-- Basic Styles -->
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $backend_assets; ?>css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $backend_assets; ?>css/font-awesome.min.css">
    <!-- SmartAdmin Styles : Caution! DO NOT change the order -->
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $backend_assets; ?>css/smartadmin-production-plugins.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $backend_assets; ?>css/smartadmin-production.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $backend_assets; ?>css/smartadmin-skins.min.css">
    <!-- SmartAdmin RTL Support -->
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $backend_assets; ?>css/smartadmin-rtl.min.css"> 
    <!-- We recommend you use "your_style.css" to override SmartAdmin
         specific styles this will also ensure you retrain your customization with each SmartAdmin update.
    <link rel="stylesheet" type="text/css" media="screen" href="css/your_style.css"> -->
    <!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $backend_assets; ?>css/demo.min.css">
    <!-- #FAVICONS -->
    <link rel="shortcut icon" href="<?php echo base_url(); ?>/backend_assets/img/logo.png" type="image/x-icon">
    <link rel="icon" href="<?php echo base_url(); ?>/backend_assets/img/logo.png" type="image/x-icon">
    <!-- #GOOGLE FONT -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">
    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
    <!-- custom -->
    <link rel="stylesheet" type="text/css" href="<?php echo $backend_assets; ?>custom/css/custom.css">
  </head>
  <body class="animated fadeInDown" data-base-url="<?php echo base_url(); ?>">
    <!-- #preloader -->
    <div class="preloader" id="preloader">
      <div class="spinner"></div>
    </div>
   
    <!-- #preloader -->
    <header id="header">
      <div id="logo-group">
        <span id="logo" style="color:black;"> <img src="<?php echo $backend_assets; ?>img/logo.png" alt="<?= SITE_NAME; ?>"></span>
      </div>
      <!--  <span id="extr-page-header-space"> <span class="hidden-mobile hiddex-xs">Need an account?</span> <a href="<?php echo base_url().'admin/signup'; ?>" class="btn btn-danger">Create account</a> </span>  -->
      <!--   <span id="extr-page-header-space"> <span class="hidden-mobile hiddex-xs">Already registered?</span> <a href="<?php echo base_url(); ?>" class="btn btn-danger">Sign In</a> </span> -->
    
    </header>
  
<div id="main" role="main">

  <!-- MAIN CONTENT -->
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
                
                
                <?php
                    if(isset($isshow) && $isshow == true)
                    { ?>
                      
                        <table class="body-action" align="center" width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td align="center">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td align="center">
                                                <table border="0" cellspacing="0" cellpadding="0">
                                                    <tr>
                                                        <td>
                                                            <a style="border: 10px #dd4b39 solid;background:#dd4b39;color: white;text-decoration: none;" class="button button--green" href="<?php echo $reseturl; ?>">Create Your Passsword</a>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                         </table>    
                        
                <?php }
                ?>
                
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