<?php $backend_assets=base_url().'backend_assets/'; ?>
<section id="widget-grid" class="">
    
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="#" onclick="history.back();">Project</a></li>
        <li class="breadcrumb-item active" aria-current="page">Add</li>
      </ol>
    </nav>
    
	<!-- row -->
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
			<div class="well no-padding">
				<form action="Projectapi/add" id="AddProject" class="smart-form" novalidate="novalidate" autocomplete="off" enctype="multipart/form-data" method="post">
					<fieldset>
					<!-- 	<header>
					Basic Information
					<input type="hidden" name="id" value="0">
					</header> -->
						<div class="row">
								<input type="hidden" name="id" value="<?= $company_id; ?>">
							<section class="col col-md-12">
								<label class="label">Project Name</label>
								<label class="input"> <i class="icon-append fa fa-tag"></i>
									<input type="text" name="name" placeholder="Name" maxlength="30" size="30"  value="<?= @$company['name']; ?>" >
									
								</label>
							</section>				
						</div>
						<section>
						    
						<div class="row">
							<section class="col col-md-12">
								<label class="label">Project Description</label>
								<label class="input">
									<textarea style="padding-top: 0;" class="form-control" type="text" name="project_description" placeholder="Project Description" ><?= @$company['project_description']; ?></textarea>
    						    </label>
								</label>
							</section>				
						</div>
						
						<div class="row">
							<section class="col col-md-12">
								<label class="label">Start Date</label>
								<label class="input">
									<input class="form-control" id="start_date" type="date" name="start_date" placeholder="Project Start Date" >
    						    </label>
								</label>
							</section>				
						</div>
						
						<div class="row">
							<section class="col col-md-12">
								<label class="label">Estimated End Date</label>
								<label class="input">
									<input class="form-control" type="date" name="end_date" placeholder="Project Estimated End Date" >
    						    </label>
								</label>
							</section>				
						</div>
						
						<div class="row">
							<section class="col col-md-12">
								<label class="label">Status</label>
								<label class="input">
									<select id="status" name="status" class="form-control">
									    <option value='0'>In-progress</option>
									    <option value='1'>Completed</option>
									</select>
    						    </label>
								</label>
							</section>				
						</div>
						
						<div class="row">
							<section class="col col-md-12">
								<label class="label">Upload Document</label>
								<label class="input">
                                <input id="licence_media" class="form-control" type="file" name="licence" value="<?= @$company['licence']; ?>">
								</label>
								<span id="uploaded_image"></span>
							</section>				
						</div>
						
						<div class="row">
							<section class="col col-md-10">
								<label class="label">Client</label>
								<label class="input">
									<select id="projectclientlist" name="client" class="form-control">
									    <?php
									        foreach($client_list as $value){
									            echo "<option value='".$value->id."'>".$value->name."</option>";
									        }
									    ?>
									</select>
    						    </label>
								</label>
							</section>				
							
							<section class="col col-md-2 text-center">
							    <label class="label text-center">Add New Client</label>
							    <label class="input">
    							    <button type="button" style="background:black; border-radius: 50px!important" class="btn btn-primary form-control" data-toggle="modal" data-target="#exampleModal">
                                          New Client
                                        </button>
                                </label>
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

<!-- modal-->
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Client</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
        			<div class="well no-padding">
        				<!--<form action="Clientapi/addprojectclient" id="AddProjectClient" class="smart-form" novalidate="novalidate" autocomplete="off" enctype="multipart/form-data" method="post">-->
        				<form action="Clientapi/add" id="AddProjectClient" class="smart-form" novalidate="novalidate" autocomplete="off" enctype="multipart/form-data" method="post">
        					<fieldset>
        						<div class="row">
                         <input type="hidden" name="sendmailone" id="sendmailone" value="1">
        								<input type="hidden" name="company_id" value="<?= $company_id; ?>">
        							<section class="col col-md-12">
        								<label class="label">Client Name</label>
        								<label class="input"> <i class="icon-append fa fa-tag"></i>
        									<input type="text" name="name" placeholder="Client Name" maxlength="30" size="30"  value="<?= @$company['name']; ?>" >
        									
        								</label>
        							</section>				
        						</div>
        						<section>
        						    
        						<input type="hidden" value="no" name="is_notification_send" >
        						    
        						<div class="row">
        							<section class="col col-md-12">
        								<label class="label">Client Email</label>
        								<label class="input">
        									<input class="form-control" type="text" name="email" placeholder="Client Email" maxlength="100" size="100"  value="<?= @$company['email']; ?>" >
            						    </label>
        								</label>
        							</section>				
        						</div>
        						    
        						<div class="row">
        							<section class="col col-md-12">
        								<label class="label">Phone Number</label>
        								<label class="input">
        									<input class="form-control" type="text" name="phone_number" maxlength="10" size="20" class="number-only" placeholder="Contact" data-mask="9999999999" aria-invalid="false">
        								
        								</label>
        							</section>		
        						</div>
        						
        						<div class="row">
        							<section class="col col-md-12">
        								<label class="label">Address</label>
        								<label class="input">
        						        <input class="form-control" type="text" name="address" placeholder="Address" maxlength="30" size="30"> 
        								</label>
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
      </div>
    </div>
  </div>
</div>
<!--- end -->


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


