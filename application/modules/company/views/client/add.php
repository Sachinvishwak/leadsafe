<?php $backend_assets=base_url().'backend_assets/'; ?>
<section id="widget-grid" class="">
    
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="#" onclick="history.back();">Client</a></li>
        <li class="breadcrumb-item active" aria-current="page">Add</li>
      </ol>
    </nav>
    
	<!-- row -->
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
			<div class="well no-padding">
				<form action="Clientapi/add" id="AddClient" class="smart-form" novalidate="novalidate" autocomplete="off" enctype="multipart/form-data" method="post">
					
					<fieldset>
					<!-- 	<header>
					Basic Information
					<input type="hidden" name="id" value="0">
					</header> -->
						<div class="row">
								<input type="hidden" name="company_id" value="<?= $company_id; ?>">
							<section class="col col-md-12">
								<label class="label">Client Name</label>
								<label class="input"> <i class="icon-append fa fa-tag"></i>
									<!--<input type="text" name="name" placeholder="Client Name" maxlength="30" size="30"  value="<?php echo $lastClientName; ?><?= @$company['name']; ?>" >-->
									<input type="text" name="name" placeholder="Client Name" maxlength="30" size="30"  value="" >
									
								</label>
							</section>				
						</div>
						<section>
						    
						<div class="row">
							<section class="col col-md-12">
								<label class="label">Client Email</label>
								<label class="input">
									<!--<input class="form-control" type="text" name="email" placeholder="Client Email" maxlength="30" size="30"  value="<?php echo $lastClientEmail; ?><?= @$company['email']; ?>" >-->
										<input class="form-control" type="text" name="email" placeholder="Client Email" maxlength="100" size="100"  value="" >
    						    </label>
								</label>
							</section>				
						</div>
						
						
						<div class="row">
							<section class="col col-md-12">
								<label class="label">Select Project</label>
								<label class="input">
									<select name="projectId" class="form-control">
									    <?php
									        foreach($projectList as $project)
									        {
									            echo '<option value='.$project->id.'>'.$project->name.'</option>';
									        }
									    ?>
									    
									</select>
    						    </label>
								</label>
							</section>				
						</div>
						    
						<div class="row">
							<section class="col col-md-12">
								<label class="label">Phone Number<span style="font-size: 11px;margin-left: 6px;color: #574f45;">(Not required)</span></label>

    
    								<label class="input">
									<input class="form-control" type="text" name="phone_number"  value="<?php echo $lastClientPhone; ?>" maxlength="10" size="20" class="number-only" placeholder="Contact" data-mask="9999999999" aria-invalid="false">
								
								</label>
							</section>		
						</div>
						
						<div class="row">
							<section class="col col-md-12">
								<label class="label">Address<span style="font-size: 11px;margin-left: 6px;color: #574f45;">(Not required)</span></label>
								<label class="input">
						        <!--<input class="form-control" type="text" name="address"  value="<?php echo $lastClientAddress; ?>" placeholder="Address" maxlength="30" size="30"> -->
						        <input class="form-control" type="text" name="address"  value="" placeholder="Address" maxlength="30" size="30"> 
								</label>
							</section>				
						</div>
						
						<div class="row">
							<section class="col col-md-12">
								<label class="label">Upload Documents<span style="font-size: 11px;margin-left: 6px;color: #574f45;">(Not required)</span></label>
								<label class="input">
                                <input id="licence_media" class="form-control" type="file" name="document" value="<?= @$company['document']; ?>">
								</label>
								<span id="uploaded_image"></span>
							</section>				
						</div>
						
						</section>				
						
					</fieldset>	
	
					<footer>
						<button type="submit" id="submit" class="btn btn-primary">Invite</button>
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
  var name = document.getElementById("licence_media").files[0].name;
  var form_data = new FormData();
  var ext = name.split('.').pop().toLowerCase();
  if(jQuery.inArray(ext, ['doc','docx','pdf','ppt','jpeg','jpg','png','bmp']) == -1) 
  {
   //alert("Invalid Image File");
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
 
});
</script>

