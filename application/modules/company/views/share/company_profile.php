<?php $backend_assets=base_url().'backend_assets/'; 


    if(!isset($_SESSION['company_sess']))
    {
        $newURL = base_url('admin/login');
        header('Location: '.$newURL);
    }



?>

<div id="main" role="main">
	<!-- MAIN CONTENT -->
	<style type="text/css">
        #profileborder{
            border: 1px solid;
        }
    </style>
	<div id="content" class="container">
        <div class="row">
        	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3">
        		<div class="row no-padding" id="profileborder">
        					<!-- update -->
        					<form action="updateCompany" id="smart-form-company-updateuser" class="smart-form client-form" enctype="multipart/form-data" novalidate="" autocomplete="off">
        						<header>
        							Complete Profile
        						</header>
        						<fieldset>
        							<input type="hidden" name="userauth" value="<?php echo $userData['company_id']; ?>">
        							<section>
        								<label class="input"> <i class="icon-append fa fa-user"></i>
        								<input type="text" name="name" placeholder="Full name" value="<?php echo $userData['name']; ?>" maxlength="30" size="30">
        								<b class="tooltip tooltip-bottom-right"> Please enter your full name</b> </label>
        							</section>
        							<section>
        								<label class="input"> <i class="icon-append fa fa-envelope"></i>
        									<input type="text" name="email" placeholder="Email address" value="<?php echo $userData['email']; ?>" maxlength="100" size="100">
        									<b class="tooltip tooltip-bottom-right"> Please enter your registered email address</b>
        								</label>
        							</section>
        							
        							<div class="row">
            							<section class="col col-md-12">
            								<label class="input">
            								     <i class="icon-append fa fa-phone"></i>
                    						<input class="form-control" type="text" name="phone_number" value="<?= @$userData['phone_number']; ?>" class="number-only" placeholder="Phone Number" data-mask="9999999999" maxlength="10"> 
                    						<b class="tooltip tooltip-bottom-right"> Please Enter Company Phone number</b>
            								</label>
            							</section>				
            						</div>
        							
        							<div class="row">
                                        <section class="col col-md-12">
                    						<label class="input">Profile Image
                    						<input id="profile_image_complete" class="form-control" type="file" name="profileImage" title="profile image">
                    						</label>
                						</section>
            						</div>
        							
        							<div class="row">
            							<section class="col col-md-12">
            								<label class="input">
            								     <i class="icon-append fa fa-phone"></i>
                    						<input class="form-control" type="text" name="fax_number" value="<?= @$userData['fax_number']; ?>" class="number-only" placeholder="Fax Number" data-mask="9999999999" maxlength="10"> 
                    						<b class="tooltip tooltip-bottom-right"> Please Enter Company Fax number</b>
            								</label>
            							</section>				
            						</div>
        					
        
                                <div class="row">
                                    <section class="col col-md-12">
                						<label class="label">License</label>
                						<input id="doc_doc" class="form-control" type="file" name="licence" title="license">
            						</section>
        						</div>
        						
                                <div class="row">
                                    <section class="col col-md-12">
                						<label class="label">Insurance Certificate</label>
                						<input id="doc_doc1"  class="form-control" type="file" name="insurence_certificate">
        						    </section>
        						</div>
         
        							
        							
        						</fieldset>
        						<footer>
        							<button type="submit" id="submit" class="btn btn-primary">Update</button>
        						</footer>
        					</form>
        					<!-- update -->
        				</div>
        		</div>
        	</div>
        </div>
    </div>
</div>
<!-- end row-->
 
 




