<?php $backend_assets=base_url().'backend_assets/'; ?>



<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link href="<?php echo $backend_assets.'js/jquery.growl.css';?>" rel="stylesheet" type="text/css">
<link href="<?php echo $backend_assets.'css/fileup.css'?>" rel="stylesheet" type="text/css">
<script src="<?php echo $backend_assets.'js/fileup.js'?>"></script>

<style>

.container { margin:50px auto;}
.dropzone {
    background-color: #ccc;
    border: 3px dashed #888;
    width: 350px;
    height: 150px;
    border-radius: 25px;
    font-size: 20px;
    color: #777;
    padding-top: 50px;
    text-align: center;
}
.dropzone.over {
    opacity: .7;
    border-style: solid;
}
#dropzone .dropzone {
    margin-top: 25px;
    padding-top: 60px;
}
</style>


<section id="widget-grid" class="">
    
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="#" onclick="history.back();">Crew</a></li>
        <li class="breadcrumb-item active" aria-current="page">Add</li>
      </ol>
    </nav>
    
	<!-- row -->
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
			<div class="well no-padding">
				<form action="Crewapi/add" id="AddCrewMember" class="smart-form" novalidate="novalidate" autocomplete="off" enctype="multipart/form-data" method="post">
					
					<fieldset>
					<!-- 	<header>
					Basic Information
					<input type="hidden" name="id" value="0">
					</header> -->
						<div class="row">
								<input type="hidden" name="company_id" value="<?= @$company_id; ?>">
							<section class="col col-md-12">
								<label class="label">Crew Name</label>
								<label class="input"> <i class="icon-append fa fa-tag"></i>
									<input type="text" name="name" placeholder="Name" maxlength="30" size="30"  value="<?= @$company['name']; ?>" >
									
								</label>
							</section>				
						</div>
						<section>
						    
						<div class="row">
							<section class="col col-md-12">
								<label class="label">Crew Email</label>
								<label class="input">
									<input class="form-control" type="email" name="email" placeholder="Crew Email" maxlength="100" size="100"  value="<?= @$company['email']; ?>" >
    						    </label>
								</label>
							</section>				
						</div>
						    
						<div class="row">
							<section class="col col-md-12">
								<label class="label">Phone Number<span style="font-size: 11px;margin-left: 6px;color: #574f45;">(Not required)</span></label>
								<label class="input">
									<input class="form-control" type="text" name="phone_number" maxlength="10" size="20" class="number-only" placeholder="Contact" data-mask="9999999999" aria-invalid="false">
								
								</label>
							</section>		
						</div>
						
						<div class="row">
							<section class="col col-md-12">
								<label class="label">Address<span style="font-size: 11px;margin-left: 6px;color: #574f45;">(Not required)</span></label>
								<label class="input">
						        <input class="form-control" type="text" name="address" placeholder="Address" maxlength="30" size="30"> 
								</label>
							</section>				
						</div>

						<!-- <div class="form-horizontal">
							<div class="form-group">
								<label class="control-label col-md-3">Upload Image</label>
								<div class="col-md-8">
									<div class="row">
										<div id="demo"></div>
									</div>
								</div>
							</div>
						</div> -->

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
								<label class="label">Upload License<span style="font-size: 11px;margin-left: 6px;color: #574f45;">(Not required)</span></label>
								<label class="input">
                                <input id="licence_media" class="form-control" type="file" name="licence" value="<?= @$company['licence']; ?>">
								</label>
								<span id="uploaded_image"></span>
							</section>				
						</div>
						
						<div class="row">
							<section class="col col-md-12">
								<label class="label">Upload Insurance Certificate<span style="font-size: 11px;margin-left: 6px;color: #574f45;">(Not required)</span></label>
								<label class="input">
						        <input id="insurence_certificate_media" class="form-control" type="file" name="insurence_certificate" value="<?= @$company['insurence_certificate']; ?>">
								</label>
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
<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>

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
 
    /*For Insurence Certificate*/
   $(document).on('change', '#insurence_certificate_media', function(){
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
		
</script>

