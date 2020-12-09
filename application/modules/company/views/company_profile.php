<?php

    //echo "<pre>";
    //print_r($userData);
    //die;

?>

<?php $backend_assets=base_url().'backend_assets/'; ?>

<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link href="<?php echo $backend_assets.'js/jquery.growl.css';?>" rel="stylesheet" type="text/css">
<link href="<?php echo $backend_assets.'css/fileup.css'?>" rel="stylesheet" type="text/css">
<script src="<?php echo $backend_assets.'js/fileup.js'?>"></script>

<div class="row">
	<div class="col-sm-12">
		<div class="well well-sm">
			<div class="row">
				<div class="col-sm-12 col-md-12 col-lg-6">
					<div class="well well-light well-sm no-margin no-padding">
						<div class="row">
							<div class="col-sm-12">
								<div id="myCarousel" class="carousel fade profile-carousel">
									<div class="air air-bottom-right padding-10">
										<label class="center-block padding-5 label label-<?php echo $userData['status']?'success':'danger'; ?>"> <i class="fa fa-<?php echo $userData['status']?'check':'close'; ?>"></i><?php echo $userData['status']?' Active':' Inactive'; ?></label>
									</div>
									<div class="air air-top-left padding-10">
									<!-- 	<h4 class="txt-color-white font-md"><?php echo date('M d,Y',strtotime($userData['created_at'])); ?></h4> -->
									</div>
									<div class="carousel-inner">
										<div class="item active">
											<img src="<?php echo $backend_assets;?>img/demo/s1.jpg" alt="demo user">
										</div>	
									</div>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="row">
									<div class="col-sm-4 profile-pic">
										<?php 
										
										    if($userData['profile_photo'] == "" || $userData['profile_photo'] == NULL )
										    {
										        $img = $backend_assets.'img/avatars/sunny-big.png';
										    }else{
										        $img = base_url('uploads/company/').$userData['profile_photo'] ;
										    }
											?>
										<img src="<?php echo $img;?>" alt="<?php echo $userData['name'];?>">
									</div>
									<div class="col-sm-8">
										<h1>
											<?php echo $userData['name']; ?>
										<br>
										<small><?php echo 'Company'; ?></small></h1>
										<ul class="list-unstyled">
											<li>
												<p class="text-muted">
													<i class="fa fa-envelope"></i>&nbsp;&nbsp;<a href="mailto:<?php echo $userData['email']; ?>"><?php echo $userData['email']; ?></a>
												</p>
											</li>
											<li>
												<p class="text-muted">
													<i class="fa fa-phone"></i>&nbsp;&nbsp;<a href="javascript:void(0);"><?php echo $userData['phone_number']; ?></a>
												</p>
											</li>
										</ul>
										<br>
										<br>
										<br>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-12 col-md-12 col-lg-6">
					<!-- update -->
					<form action="updateCompany" id="smart-form-company-updateuser" class="smart-form client-form" enctype="multipart/form-data" novalidate="" autocomplete="off">
						<header>
							Update
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
    								<label class="input">
    								     <i class="icon-append fa fa-phone"></i>
            						<input class="form-control" type="text" name="fax_number" value="<?= @$userData['fax_number']; ?>" class="number-only" placeholder="Fax Number" data-mask="9999999999" maxlength="10"> 
            						<b class="tooltip tooltip-bottom-right"> Please Enter Company Fax number</b>
    								</label>
    							</section>				
    						</div>
    						
    						<div class="row">
                                <section class="col col-md-12">
            						<label class="label">Profile Image</label>
            						<input id="profileImage_media" style="width:90px;border:none;float:left;" class="form-control" type="file" name="profileImage" title="profile image">
            						<label id="profileImage_media_label"><?= @$userData['profile_photo']; ?></label>
            						<span id="profileImage_image"></span>
        						</section>
            				</div>
					

                        <div class="row" style="display: none;">
                            <section class="col col-md-12">
        						<label class="label">License</label>
        						<input id="licence_media" style="width:90px;border:none;float:left;" class="form-control" type="file" name="licence" title="license">
        						<label id="licence_media_label"><?= @$userData['licence']; ?></label>
        						<span id="uploaded_image"></span>
    						</section>
						</div>

						<div class="row">
							<div class="col col-md-12">
								<?php
									foreach($license_media as $licenses)
									{ ?>
										<div class="col col-md-6" id="icenses_<?php echo $licenses->id; ?>">

										<?php
											$mediapath = base_url('uploads/crew/').$licenses->file_name;
											if(@is_array(getimagesize($mediapath))){
												$image = 1;
											} else {
												$image = 0;
											}
											if($image == 0)
											{ ?>
												<div>
													<object style="width:100%; overflow:hidden;" src="<?php echo base_url('uploads/crew/').$licenses->file_name; ?>"><iframe style="width:450px;height:400px;" src="https://docs.google.com/viewer?url=<?php echo base_url('uploads/crew/').$licenses->file_name; ?>&embedded=true"></iframe></object>
												</div>
												<i onclick="deleteLicense('<?php echo $licenses->id; ?>')" style="position: absolute;pointer:curse;" class="text-danger fa fa-2x fa-close"></i>
											<?php 
											}else{ ?>
												<div>
													<img style="height:300px;width:100%;" src="<?php echo $mediapath; ?>"/>
													<i onclick="deleteLicense('<?php echo $licenses->id; ?>')" style="position: absolute;pointer:curse;" class="text-danger fa fa-2x fa-close"></i>
												</div>
												
											<?php
											}
										?>
										</div>
								<?php }
								?>
							</div>
						</div>

						<div class="row" >
							<section class="col col-md-12">
								<label class="label">Upload License<span style="font-size: 11px;margin-left: 6px;color: #574f45;">(Not required)</span></label>
								<button type="button" class="btn btn-success fileup-btn">
									Upload files
									<input type="file" name="fileUpload[]" id="upload-2" multiple>
								</button>
							</section>
						</div>

						<input type="hidden" name="all_docs1" id="all_docs" value="" />

						<a class="control-button btn btn-link" style="display: none"
						href="javascript:$.fileup('upload-2', 'upload', '*')">Upload all</a>
						<a class="control-button btn btn-link" style="display: none"
						href="javascript:$.fileup('upload-2', 'remove', '*')">Remove all</a>

						<div id="upload-2-queue" class="queue"></div>
						
						<div class="row" style="display: none;">
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
        						<input id="insurence_certificate_media" style="width:90px;border:none;float:left;" class="form-control" type="file" name="insurence_certificate">
        						<label id="insurence_certificate_media_label"><?= @$userData['insurence_certificate']; ?></label>
        						<span id="insurence_certificate_image"></span>
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
<!-- end row-->



<!-- preview ajax -->
<script>

function deleteLicense(id)
{
	console.log("deleteLicense");
	$.ajax({
		url:"<?php echo site_url('company/Crewapi/deleteLicense') ?>",
		method:"POST",
		data: {id:id},
		beforeSend:function(){
			
		},   
		success:function(data)
		{
			$('#icenses_'+id).css('display','none');
		}
	});
}

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
 
    /* For Profile Photo */
    $(document).on('change', '#profileImage_media', function(){
       $('#profileImage_media_label').css("display","none");
        $('#profileImage_media').css("width","100%");
      var name = document.getElementById("profileImage_media").files[0].name;
      var form_data = new FormData();
      var ext = name.split('.').pop().toLowerCase();
      if(jQuery.inArray(ext, ['doc','docx','pdf','ppt','jpeg','jpg','png','bmp']) == -1) 
      {
    	toastr.error('It will accept only doc, docx, pdf, ppt, jpeg, jpg, png, bmp format files.', 'Alert!', {timeOut: 4000});
        $('#profileImage_image').html('');
        var file = document.getElementById("profileImage_media");
   		file.value = file.defaultValue;
      }else{
          var oFReader = new FileReader();
          oFReader.readAsDataURL(document.getElementById("profileImage_media").files[0]);
          var f = document.getElementById("profileImage_media").files[0];
          var fsize = f.size||f.fileSize;
        
          form_data.append("profileImage_media", document.getElementById('profileImage_media').files[0]);
          $.ajax({
            url:"<?php echo site_url('company/companyapi/imagepreview') ?>",
            method:"POST",
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend:function(){
             $('#profileImage_image').html("<label class='text-success'>File Uploading...</label>");
            },   
            success:function(data)
            {
             $('#profileImage_image').html(data);
            }
           });
      }
  
	 });
	 
	var allnames = [];
	$.fileup({
		// url: 'example.com/your/path?file_upload=1',
		url: '<?php echo site_url("api/Api/imagepreview"); ?>?file_upload=1',
		inputID: 'upload-2',
		dropzoneID: 'upload-2-dropzone',
		queueID: 'upload-2-queue',
		onSelect: function(file) {
			$('#multiple .control-button').show();
		},
		onRemove: function(file, total) {
			remove_array_element(allnames,file.file.name);
			$('#all_docs').val(allnames);
			// if (file === '*' || total === 1) {
			//     $('#multiple .control-button').hide();
			// }
		},
		onSuccess: function(response, file_number, file) {
			response = JSON.parse(response);
			allnames.push(response.name)
			$('#all_docs').val(allnames);
			//$.growl.notice({ title: "Upload success!", message: file.name });
			
		},
		onError: function(event, file, file_number) {
			$.growl.error({ message: "Upload error!" });
		}
	});

	function remove_array_element(array, n)
	{
		var index = array.indexOf(n);
		if (index > -1) {
			array.splice(index, 1);
		}
		return array;
	}
 
});
</script>
