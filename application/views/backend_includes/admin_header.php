<!DOCTYPE html>
<html lang="en-us">
  <head>
    <meta charset="utf-8">
    <title><?php echo SITE_NAME.' | Super Admin' ?></title>
    <meta name="description" content="">
    <meta name="author" content="">
    <?php $backend_assets =  base_url().'backend_assets/';?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
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
    <!-- sweetalert -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.css">
    <!-- DataTables -->
    <!--  <link rel="stylesheet" href="<?php echo $backend_assets ?>plugins/datatables/dataTables.bootstrap.css"> -->
    <!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $backend_assets; ?>css/demo.min.css">
    <!-- #FAVICONS -->
    <!--<link rel="shortcut icon" href="<?php echo $backend_assets; ?>img/favicon/favicon.ico" type="image/x-icon">-->
    <link rel="shortcut icon" href="<?php echo base_url(); ?>/backend_assets/img/logo.png" type="image/x-icon">
    <link rel="icon" href="<?php echo base_url(); ?>/backend_assets/img/logo.png" type="image/x-icon">
    <!--<link rel="icon" href="<?php echo $backend_assets; ?>img/favicon/favicon.ico" type="image/x-icon">-->
    <!-- #GOOGLE FONT -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">
    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
    <?php if(!empty($front_styles)) { load_css($front_styles); } //load required page styles 
     ?>
    <!-- custom -->
    <link rel="stylesheet" type="text/css" href="<?php echo $backend_assets; ?>custom/css/custom.css">
    <!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src='https://maps.googleapis.com/maps/api/js?key=AIzaSyDjhKBJtoevmCuR5iD1El6cuDHTMByw9Co&v=3.exp&libraries=drawing,places'></script>
    <style>
        .profile-sidebar {
          padding: 20px 0 10px 0;
          background: #fff;
        }
        
        .profile-userpic {
          float: none;
          margin: 0 auto;
          width: 50%;
          height: 50%;
          -webkit-border-radius: 50% !important;
          -moz-border-radius: 50% !important;
          border-radius: 50% !important;
          margin-bottom : 10px!important;
        }
        
        .profile-usertitle {
          text-align: center;
          margin-top: 20px;
        }
        
        .profile-usertitle-name {
          color: white;
          font-size: 16px;
          /*font-weight: 600;*/
          margin-bottom: 7px;
        }
        
        .profile-usertitle-job {
          text-transform: uppercase;
          color: white;
          font-size: 12px;
          font-weight: 600;
          margin-bottom: 15px;
        }
        
        
    </style>
  </head>
  <!--
  TABLE OF CONTENTS.
  Use search to find needed section.
  ===================================================================
  
  |  01. #CSS Links                |  all CSS links and file paths  |
  |  02. #FAVICONS                 |  Favicon links and file paths  |
  |  03. #GOOGLE FONT              |  Google font link              |
  |  04. #APP SCREEN / ICONS       |  app icons, screen backdrops   |
  |  05. #BODY                     |  body tag                      |
  |  06. #HEADER                   |  header tag                    |
  |  07. #PROJECTS                 |  project lists                 |
  |  08. #TOGGLE LAYOUT BUTTONS    |  layout buttons and actions    |
  |  09. #MOBILE                   |  mobile view dropdown          |
  |  10. #SEARCH                   |  search field                  |
  |  11. #NAVIGATION               |  left panel & navigation       |
  |  12. #MAIN PANEL               |  main panel                    |
  |  13. #MAIN CONTENT             |  content holder                |
  |  14. #PAGE FOOTER              |  page footer                   |
  |  15. #SHORTCUT AREA            |  dropdown shortcuts area       |
  |  16. #PLUGINS                  |  all scripts and plugins       |
  
  ===================================================================
  -->
  <!-- #BODY -->
  <!-- Possible Classes

    * 'smart-style-{SKIN#}'
    * 'smart-rtl'         - Switch theme mode to RTL
    * 'menu-on-top'       - Switch to top navigation (no DOM change required)
    * 'no-menu'       - Hides the menu completely
    * 'hidden-menu'       - Hides the main menu but still accessable by hovering over left edge
    * 'fixed-header'      - Fixes the header
    * 'fixed-navigation'  - Fixes the main menu
    * 'fixed-ribbon'      - Fixes breadcrumb
    * 'fixed-page-footer' - Fixes footer
    * 'container'         - boxed layout mode (non-responsive: will not work with fixed-navigation & fixed-ribbon)
  -->
  <style>
      
    .nav>li>a:focus, .nav>li>a:hover{
        /*background-color: #245f75!important;*/
        color: #245f75!important;    
        
    }
  </style>
  <body class="" data-base-url="<?php echo base_url(); ?>" data-auth-url="<?php echo $user['authToken']; ?>">
    <!-- #preloader -->
    <!--   <div class="preloader" id="preloader">
    <div class="spinner"></div>
    </div> -->
    <!-- #preloader -->
    <!-- #HEADER -->
    <header id="header" style="display:none;">
      <div id="logo-group">
        <!-- PLACE YOUR LOGO HERE -->
        <span id="logo"> <img src="<?php echo $backend_assets; ?>img/Logo_01-01.png" alt="LEAD-SAFE"> BILD IT(Superadmin) </span>
        <!-- END LOGO PLACEHOLDER -->
          <!-- Note: The activity badge color changes when clicked and resets the number to 0
        Suggestion: You may want to set a flag when this happens to tick off all checked messages / notifications -->
       
                <!-- AJAX-DROPDOWN : control this dropdown height, look and feel from the LESS variable file -->

        <!-- END AJAX-DROPDOWN -->
      </div>
      <!-- #TOGGLE LAYOUT BUTTONS -->
      <!-- pulled right: nav area -->
      <div class="pull-right">   
        <!-- collapse menu button -->
        <div id="hide-menu" class="btn-header pull-right">
          <span> <a href="javascript:void(0);" data-action="toggleMenu" title="Collapse Menu"><i class="fa fa-reorder"></i></a> </span>
        </div>
        <!-- end collapse menu -->
        <!-- #MOBILE -->
        <!-- Top menu profile link : this shows only when top menu is active -->
        <ul id="mobile-profile-img" class="header-dropdown-list hidden-xs padding-5">
          <li class="">
            <a href="#" class="dropdown-toggle no-margin userdropdown" data-toggle="dropdown"> 
              <img src="<?php echo $user['profileImage']? $user['profileImage'] :base_url().'backend_assets/img/avatars/sunny.png'; ?>" alt="<?php echo $user['fullName']? $user['fullName'] :''; ?>" class="online" />  
            </a>
            <ul class="dropdown-menu pull-right">
              <!--     <li>
              <a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0"><i class="fa fa-cog"></i> Setting</a>
              </li>
              <li class="divider"></li> -->
              <li>
                <a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0"> <i class="fa fa-user"></i> <u>P</u>rofile</a>
              </li>
              <li class="divider"></li>
              <li>
                <a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0" data-action="toggleShortcut"><i class="fa fa-arrow-down"></i> <u>S</u>hortcut</a>
              </li>
              <li class="divider"></li>
              <!-- <li>
                <a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0" data-action="launchFullscreen"><i class="fa fa-arrows-alt"></i> Full <u>S</u>creen</a>
              </li>
              <li class="divider"></li> -->
              <li>
                <a href="javascript:void(0);" class="padding-10 padding-top-5 padding-bottom-5" data-action="userLogout"><i class="fa fa-sign-out fa-lg"></i> <strong><u>L</u>ogout</strong></a>
              </li>
            </ul>
          </li>
        </ul>
        <!-- logout button -->
        <div id="logout" class="btn-header transparent pull-right">
          <span> <a href="<?php echo base_url().'admin/logout'; ?>" title="Sign Out" data-action="userLogout" data-logout-msg="You can improve your security further after logging out by closing this opened browser"><i class="fa fa-sign-out"></i></a> </span>
        </div>
        <!-- end logout button -->
        <!-- search mobile button (this is hidden till mobile view port) -->
        <!-- end input: search field -->
        <!-- fullscreen button -->
        <div id="fullscreen" class="btn-header transparent pull-right">
          <span> <a href="javascript:void(0);" data-action="launchFullscreen" title="Full Screen"><i class="fa fa-arrows-alt"></i></a> </span>
        </div>
        <!-- end fullscreen button -->
      </div>
      <!-- end pulled right: nav area -->
    </header>
    <!-- END HEADER -->
    <!-- #NAVIGATION -->
    <!-- Left panel : Navigation area -->
    <!-- Note: This width of the aside area can be adjusted through LESS variables -->
    <aside id="left-panel" style="padding-top:5px;">
        
        <span id="logo" style="color: white;min-height: 40px;text-align:center;"> <img style="padding-left: 12px;height: 57px;width: 154px;margin-left: 20px;" src="https://www.valere.io/leadsafe/backend_assets/img/Logo_01-01.png" alt="LEAD-SAFE"></span>
        
      <!-- User info -->
      <div class="login-info" style="display:none;">
        <span> <!-- User image size is adjusted inside CSS, it should stay as it -->  
          <a href="javascript:void(0);" id="show-shortcut" data-action="toggleShortcut">
            <img src="<?php echo $user['profileImage']? $user['profileImage'] :base_url().'backend_assets/img/avatars/sunny.png'; ?>" alt="me" class="online" /> 
            <span>
             <?php echo $user['fullName']? $user['fullName'] :''; ?>
            </span>
            <i class="fa fa-angle-down"></i>
          </a> 
        </span>
      </div>
      <!-- end user info -->
      <nav>
        <!-- 
        NOTE: Notice the gaps after each icon usage <i></i>..
        Please note that these links work a bit different than
        traditional href="" links. See documentation for details.
        -->
        <ul>
            
          <div class="profile-usertitle">
            <img src="<?php echo $user['profileImage']? $user['profileImage'] :base_url().'backend_assets/img/avatars/sunny.png'; ?>" alt="me" class="profile-userpic online" />
			<div class="profile-usertitle-name">
				<?php echo $user['fullName']? $user['fullName'] :''; ?><br><br><br><br>
				<?php echo 'Super Admin'; ?>
			</div>
			
			<div class="profile-usertitle-job">
				
			</div>
		  </div>
            
          <li class="<?php echo (strtolower($this->router->fetch_class()) == "admin") ? "active" : "" ?>">
            <a href="<?php echo base_url('dashboard'); ?>" title="Dashboard"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Dashboard</span></a>
          </li>
        <li class="<?php echo (strtolower($this->router->fetch_class()) == "tasks") ? "active" : "" ?>">
            <a href="<?php echo base_url('tasks'); ?>" title="Tasks"><i class="fa fa-lg fa-fw fa-tags"></i> <span class="menu-item-parent">Tasks</span></a>
          </li>

          <!-- company --->
          <li class="<?php echo (strtolower($this->router->fetch_class()) == "company") ? "active" : "" ?>">
            <a href="<?php echo base_url('company'); ?>" title="Company"><i class="fa fa-lg fa-fw fa-building"></i> <span class="menu-item-parent">Company</span></a>
          </li>

         <!--    <li class="<?php echo (strtolower($this->router->fetch_class()) == "jobtype") ? "active" : "" ?>">
            <a href="<?php echo base_url('jobtype'); ?>" title="Job Types"><i class="fa fa-lg fa-fw fa-bars"></i> <span class="menu-item-parent">Job Types</span></a>
          </li>
          <li class="<?php echo (strtolower($this->router->fetch_class()) == "jobs") ? "active" : "" ?>">
            <a href="<?php echo base_url('jobs'); ?>" title="jobs"><i class="fa fa-lg fa-fw fa-tasks"></i> <span class="menu-item-parent">Jobs</span></a>
          </li>
          <li class="<?php echo (strtolower($this->router->fetch_class()) == "drivers") ? "active" : "" ?>">
          <?php echo base_url('drivers'); ?> <?php echo base_url('jobs'); ?>
            <a href="<?php echo base_url('drivers'); ?>" title="Drivers"><i class="fa fa-lg fa-fw fa-users"></i> <span class="menu-item-parent">Drivers</span></a>
          </li>
          <li class="<?php echo (strtolower($this->router->fetch_class()) == "vehicles") ? "active" : "" ?>">
            <a href="<?php echo base_url('vehicles'); ?>" title="Vehicles"><i class="fa fa-lg fa-fw fa-taxi"></i> <span class="menu-item-parent">Vehicles</span></a>
          </li>
          <li class="<?php echo (strtolower($this->router->fetch_class()) == "reports") ? "active" : "" ?>">
            <a href="<?php echo base_url('reports'); ?>" title="Reports"><i class="fa fa-lg fa-fw fa-line-chart"></i> <span class="menu-item-parent">Reports</span></a>
          </li> -->
        </ul> 
      </nav>
      <!--    <span class="minifyme" data-action="minifyMenu"> 
        <i class="fa fa-arrow-circle-left hit"></i> 
      </span> -->
    </aside>
    <!-- END NAVIGATION -->
    <!-- MAIN PANEL -->
    <div id="main" role="main">
      <!-- RIBBON -->
      <div id="ribbon" style="min-height:55px;border-left: 1px solid white;">
        <!--<span class="ribbon-button-alignment"> -->
        <!--  <span id="refresh" class="btn btn-ribbon" data-action="resetWidgets" data-title="refresh"  rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings." data-html="true">-->
        <!--    <i class="fa fa-refresh"></i>-->
        <!--  </span> -->
        <!--</span>-->
        <!-- breadcrumb -->
          <?php
            $segmentSearch   = array('-', '/', '_');
            $segmentReplace  = array(' ', ' ', ' ');
            $segments        = $this->uri->segment_array();
            $segmentCount    = count($this->uri->segment_array());
          ?>           
        <ol class="breadcrumb">
          <!-- <li>Home</li><li>Miscellaneous</li><li>Blank Page</li> -->
          <!--      <?php  foreach ($segments as $segment) {
          if ($foreach_count >= $segmentCount) { ?> 
          <li class='breadcrumb-item active'><?php echo ucfirst(str_replace($segmentSearch, $segmentReplace, $segment)); ?></li>;
          <?php  } else {  $last_segment = $last_segment . '/' . $segment; ?>
          <li class='breadcrumb-item'><a href='<?php echo base_url($last_segment); ?>'><?php  echo ucfirst(str_replace($segmentSearch, $segmentReplace, $segment)); ?></a></li>
          <?php   }$foreach_count+=1;} ?> -->
        </ol>
        <!-- end breadcrumb -->
        <!-- You can also add more buttons to the
        ribbon for further usability
        Example below:
        <span class="ribbon-button-alignment pull-right">
        <span id="search" class="btn btn-ribbon hidden-xs" data-title="search"><i class="fa-grid"></i> Change Grid</span>
        <span id="add" class="btn btn-ribbon hidden-xs" data-title="add"><i class="fa-plus"></i> Add</span>
        <span id="search" class="btn btn-ribbon" data-title="search"><i class="fa-search"></i> <span class="hidden-mobile">Search</span></span>
        </span> -->
        
        <ul class="nav navbar-nav ml-auto" style="float:right;">
            <div id="hide-menu" class="btn-header pull-right" style="">
          <span> <a href="javascript:void(0);" style="background: #245f75;
    border: none;
    color: white;" data-action="toggleMenu" title="Collapse Menu"><i class="fa fa-reorder"></i></a> </span>
        </div>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" style="padding-top: 11px;color:white;">
                    <img src="<?php echo $user['profileImage']? $user['profileImage'] :base_url().'backend_assets/img/avatars/sunny.png'; ?>" style="height: 33px;border-radius: 50%;" alt="me" class="online" /> 
                    <span>
                     <?php echo $user['fullName']? $user['fullName'] :''; ?>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" style="background: #245f75;padding: 9px;">
                    <a href="<?php echo base_url().'profile/'.encoding($user['userId']);  ?>" style="display:block;color:white;    margin-bottom: 6px;" class="dropdown-item"><i class="fa fa-user"></i> &nbsp;&nbsp; My Profile</a>
                    <a href="<?php echo base_url().'change_password/'.encoding($user['userId']);  ?>" style="display:block;color:white;    margin-bottom: 6px;" class="dropdown-item"><i class="fa fa-key"></i> &nbsp;&nbsp; Change Password</a>
                    <div class="dropdown-divider"></div>
                    <a href="<?php echo base_url().'admin/logout'; ?>" title="Sign Out" data-action="userLogout" data-logout-msg="You can improve your security further after logging out by closing this opened browser" style="display:block;color:white;" class="dropdown-item" data-action="userLogout"><i class="fa fa-sign-out"></i> &nbsp;&nbsp; Logout</a>
                </div>
            </li>
        </ul>
        
      </div>
      <!-- END RIBBON -->
      <!-- MAIN CONTENT -->
      <div id="content">
        <!-- row -->
        <div class="row"> 
          <!-- col -->
          <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
            <h1 class="page-title txt-color-blueDark">        
              <!-- PAGE HEADER -->
              <!--         <i class="fa-fw fa fa-home"></i>  -->
                <?php echo $title; ?>
              <span>  
              </span>
            </h1>
          </div>
          <!-- end col -->
          <!-- right side of the page with the sparkline graphs -->
          <!-- col -->
          <div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
            <!-- sparks -->
            <ul id="sparks">
              <?php 
                if(isset($recordSet) && !empty($recordSet)):
                  foreach ($recordSet as $k => $record) {
                     echo $record;
                   } 
                endif;
              ?>
            </ul>
            <!-- end sparks -->
          </div>
          <!-- end col --> 
        </div>
        <!-- end row -->
        <!--
          The ID "widget-grid" will start to initialize all widgets below 
          You do not need to use widgets if you dont want to. Simply remove 
          the <section></section> and you can use wells or panels instead 
          -->
        <!-- widget grid -->