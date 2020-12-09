<?php $backend_assets=base_url().'backend_assets/'; ?>
<section id="widget-grid" class="">
	<!-- row -->
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
			<div class="well no-padding">
				<form action="Crewapi/edit" id="EditCrewMember" class="smart-form" novalidate="novalidate" autocomplete="off" enctype="multipart/form-data" method="post">
					
					<fieldset>
						<div class="row">
								<input type="hidden" name="id" value="<?= encoding(@$company['id']); ?>">
								<input type="hidden" name="company_id" value="<?= @$company['company_id']; ?>">
							<section class="col col-md-12">
								<label class="label">Crew Member Name</label>
								<label class="input"> <i class="icon-append fa fa-tag"></i>
									<input type="text" name="name" placeholder="Crew Member Name" maxlength="30" size="30"  value="<?= @$company['name']; ?>" >
									
								</label>
							</section>				
						</div>
						<section>
						    

						<div class="row">
							<section class="col col-md-12">
								<label class="label">Crew Member Email</label>
								<label class="input">
									<input class="form-control" type="text" name="email" placeholder="Crew Member Email" maxlength="100" size="100"  value="<?= @$company['email']; ?>" >
    						    </label>
								</label>
							</section>				
						</div>
						    
						    

						
						
						<div class="row">
							<section class="col col-md-12">
								<label class="label">Phone Number</label>
								<label class="input">
        						<input class="form-control" type="text" name="phone_number" value="<?= @$company['phone_number']; ?>" class="number-only" placeholder="Phone Number" data-mask="9999999999" maxlength="10"> 
								</label>
							</section>				
						</div>
						
						
						<div class="row">
							<section class="col col-md-12">
								<label class="label">Address</label>
								<label class="input">
        						<input class="form-control" type="text" name="address" value="<?= @$company['address']; ?>" placeholder="Address" maxlength="30"> 
								</label>
							</section>				
						</div>

                        <div class="row">
                            <section class="col col-md-12">
        						<label class="label">License</label>
        						<input id="licence_media" style="width:90px;border:none;float:left;" class="form-control" type="file" name="licence" value="<?= @$company['licence']; ?>" title="license">
        						<label id="licence_media_label"><?= @$company['licence']; ?></label>
        						<span id="uploaded_image"></span>
    						</section>
						</div>
						
                        <div class="row">
                            <section class="col col-md-12">
        						<label class="label">Insurance Certificate</label>
        						<input id="insurence_certificate_media" style="width:90px;border:none;float:left;" class="form-control" type="file" name="insurence_certificate" value="<?= @$company['insurence_certificate']; ?>">
        						<label id="insurence_certificate_media_label"><?= @$company['insurence_certificate']; ?></label>
        						<span id="insurence_certificate_image"></span>
						    </section>
						</div>

						</section>				
						
					</fieldset>	
	
					<footer>
						<button type="submit" id="submit" class="btn btn-primary">Save</button>
					</footer>
				</form>
			</div>	
		</div>
	</div>
 	<!-- end row -->        
</section>
<!-- end widget grid -->

<script>
$(document).ready(function(){
   /*For License*/
   $(document).on('change', '#licence_media', function(){
       $('#licence_media_label').css("display","none");
        $('#licence_media').css("width","100%");
  var name = document.getElementById("licence_media").files[0].name;
  var form_data = new FormData();
  var ext = name.split('.').pop().toLowerCase();
  if(jQuery.inArray(ext, ['doc','docx','pdf','ppt','jpeg','jpg','png','bmp']) == -1) 
  {
       toastr.error('It will accept only doc, docx, pdf, ppt, jpeg, jpg, png, bmp format files.', 'Alert!', {timeOut: 4000});
         $('#uploaded_image').html('');
         var file = document.getElementById("licence_media");
   file.value = file.defaultValue;
  }else{
      var oFReader = new FileReader();
      oFReader.readAsDataURL(document.getElementById("licence_media").files[0]);
      var f = document.getElementById("licence_media").files[0];
      var fsize = f.size||f.fileSize;
    
      form_data.append("licence_media", document.getElementById('licence_media').files[0]);
      $.ajax({
        url:"<?php echo site_url('company/companyapi/imagepreview') ?>",
        method:"POST",
        data: form_data,
        contentType: false,
        cache: false,
        processData: false,
        beforeSend:function(){
         $('#uploaded_image').html("<label class='text-success'>File Uploading...</label>");
        },   
        success:function(data)
        {
         $('#uploaded_image').html(data);
        }
       });
  }
  
 });
 
    /*For Insurence Certificate*/
   $(document).on('change', '#insurence_certificate_media', function(){
       $('#insurence_certificate_media_label').css("display","none");
        $('#insurence_certificate_media').css("width","100%");
      var name = document.getElementById("insurence_certificate_media").files[0].name;
      var form_data = new FormData();
      var ext = name.split('.').pop().toLowerCase();
      if(jQuery.inArray(ext, ['doc','docx','pdf','ppt','jpeg','jpg','png','bmp']) == -1) 
      {
          toastr.error('It will accept only doc, docx, pdf, ppt, jpeg, jpg, png, bmp format files.', 'Alert!', {timeOut: 4000});
         $('#insurence_certificate_image').html('');
         var file = document.getElementById("insurence_certificate_media");
   file.value = file.defaultValue;
      }else{
          var oFReader = new FileReader();
          oFReader.readAsDataURL(document.getElementById("insurence_certificate_media").files[0]);
          var f = document.getElementById("insurence_certificate_media").files[0];
          var fsize = f.size||f.fileSize;
        
          form_data.append("insurence_certificate_media", document.getElementById('insurence_certificate_media').files[0]);
          $.ajax({
            url:"<?php echo site_url('company/companyapi/imagepreview') ?>",
            method:"POST",
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend:function(){
             $('#insurence_certificate_image').html("<label class='text-success'>File Uploading...</label>");
            },   
            success:function(data)
            {
             $('#insurence_certificate_image').html(data);
            }
           });
      }
  
 });
});
</script>


