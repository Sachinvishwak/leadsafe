<?php $backend_assets=base_url().'backend_assets/'; ?>

<input type="hidden" value="<?php echo base_url(); ?>" id="baseUrl"/>

<section id="widget-grid" class="">
    
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="#" onclick="history.back();">Group</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?=  @$group['name']; ?></li>
      </ol>
    </nav>
    
    
	<!-- row -->
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
			<div class="well no-padding">
				<form action="Groupapi/edit" id="editGroup" class="smart-form" novalidate="novalidate" autocomplete="off" enctype="multipart/form-data" method="post">
					<fieldset>
						<div class="row">
							<input type="hidden" name="id" value="<?= $group['id']; ?>">

							<input type="hidden" value="<?php echo serialize($contractor_group_memeber); ?>" id="contractor_group_memeber" />
							<input type="hidden" name="<?php echo $group['project_id']; ?>" id="productId"/>

							<section class="col col-md-12">
								<label class="label">Group Name</label>
								<label class="input"> <i class="icon-append fa fa-tag"></i>
									<input type="text" name="name" placeholder="Name" maxlength="30" size="30"  value="<?= @$group['name']; ?>" >
								</label>
							</section>				
						</div>
						<section>

						<div class="row">
							<section class="col col-md-12">
                <lable>Select Project</lable>
									<select id="dates-field2" class="form-control" name="project_id"  onchange="getMembers1(this.value)" required="" >
										<option value="">Select Project</option>
											<?php
												$project_list = $this->db->get_where('project',array('company_id'=>$company_id))->result();
												foreach($project_list as $projects)
												{
													if($projects->id == $group['project_id'])
													{
														echo '<option value="'.$projects->id.'" selected>'.$projects->name.'</option>';
													}else{
														echo '<option value="'.$projects->id.'">'.$projects->name.'</option>';
													}
												}
											?>
									</select>
								</label>
							</section>			
						</div>
						<section>
						 

						</section>				
						
					</fieldset>	

					<style>
                        .btn-default{
                            height:40px!important;
                            padding:10px!important;
                        }
                        .smart-form .checkbox input,.smart-form .radio input{position:unset!important;}
                        span.multiselect-native-select {
                        	position: relative
                        }
                        span.multiselect-native-select select {
                        	border: 0!important;
                        	clip: rect(0 0 0 0)!important;
                        	height: 1px!important;
                        	margin: -1px -1px -1px -3px!important;
                        	overflow: hidden!important;
                        	padding: 0!important;
                        	position: absolute!important;
                        	width: 1px!important;
                        	left: 50%;
                        	top: 30px
                        }
                        .multiselect-container {
                        	position: absolute;
                        	list-style-type: none;
                        	margin: 0;
                        	padding: 0
                        }
                        .multiselect-container .input-group {
                        	margin: 5px
                        }
                        .multiselect-container>li {
                        	padding: 0
                        }
                        .multiselect-container>li>a.multiselect-all label {
                        	font-weight: 700
                        }
                        .multiselect-container>li.multiselect-group label {
                        	margin: 0;
                        	padding: 3px 20px 3px 20px;
                        	height: 100%;
                        	font-weight: 700
                        }
                        .multiselect-container>li.multiselect-group-clickable label {
                        	cursor: pointer
                        }
                        .multiselect-container>li>a {
                        	padding: 0
                        }
                        .multiselect-container>li>a>label {
                        	margin: 0;
                        	height: 100%;
                        	cursor: pointer;
                        	font-weight: 400;
                        	padding: 3px 0 3px 30px
                        }
                        .multiselect-container>li>a>label.radio, .multiselect-container>li>a>label.checkbox {
                        	margin: 0
                        }
                        .multiselect-container>li>a>label>input[type=checkbox] {
                        	margin-bottom: 5px
                        }
                        .btn-group>.btn-group:nth-child(2)>.multiselect.btn {
                        	border-top-left-radius: 4px;
                        	border-bottom-left-radius: 4px
                        }
                        .form-inline .multiselect-container label.checkbox, .form-inline .multiselect-container label.radio {
                        	padding: 3px 20px 3px 40px
                        }
                        .form-inline .multiselect-container li a label.checkbox input[type=checkbox], .form-inline .multiselect-container li a label.radio input[type=radio] {
                        	margin-left: -20px;
                        	margin-right: 0
                        }
                    </style>

					<fieldset>
                        <div class="row">
                            <section class="col col-md-12">
                                <lable>Select Contractor</lable>
								<select id="constrcutor_list" class="multiselect-ui form-control" multiple="multiple" name="contractorIds[]"  >
								    <?php
                      //                   $contractor_list = $this->db->select('company_member_relations.type, contractor.*')
                      //                        ->from('company_member_relations')
                      //                        ->join('contractor', 'company_member_relations.member_id = contractor.id')
                      //                        ->where('company_member_relations.company_id',$company_id)
                      //                        ->where('contractor.is_role',1)
                      //                        ->where('company_member_relations.type','leadcontractor')->distinct()->get()->result();
                      //                   foreach($contractor_list as $contractor)
                      //                   {
											// if(in_array($contractor->id, $contractor_group_memeber))
											// {
											// 	echo '<option selected value="'.$contractor->id.'">'.$contractor->owner_first_name.'</option>';
											// }else{
											// 	echo '<option value="'.$contractor->id.'">'.$contractor->owner_first_name.'</option>';
											// }
																			 // }
																			 
																			 foreach($contractorList as $contractors)
																			 { 
																					if(in_array($contractors->id, $contractor_group_memeber))
																					{
																						echo '<option selected value="'.$contractors->id.'">'.$contractors->name.'</option>';
																					}else{
																						echo '<option value="'.$contractors->id.'">'.$contractors->name.'</option>';
																					}
																			 }

                                    ?>
								</select>
								</label>
							</section>
                        </div>
                    </fieldset>
                    
                    <fieldset>
                        <div class="row">
                            <section class="col col-md-12">
                                <lable>Select Crew</lable>
								<select id="crews_list" class="multiselect-ui form-control" multiple="multiple" name="crewIds[]"  >
								    <?php
                      // $crew_member_list = $this->db->select('company_member_relations.type, crew_member.*')
                      //                        ->from('company_member_relations')
                      //                        ->join('crew_member', 'company_member_relations.member_id = crew_member.id')
                      //                        ->where('company_member_relations.company_id',$company_id)
                      //                        ->where('company_member_relations.type','crew')->distinct()->get()->result();
                      //                   foreach($crew_member_list as $crew_membe)
                      //                   {
											// if(in_array($crew_membe->id, $crew_group_memeber))
											// {
											// 	echo '<option selected value="'.$crew_membe->id.'">'.$crew_membe->name.'</option>';
											// }else{
											// 	echo '<option value="'.$crew_membe->id.'">'.$crew_membe->name.'</option>';
											// }
											// }
											
											foreach($crewList as $crews)
											{ 
												if(in_array($crews->id, $crew_group_memeber))
												{
													echo '<option selected value="'.$crews->id.'">'.$crews->name.'</option>';
												}else{
													echo '<option value="'.$crews->id.'">'.$crews->name.'</option>';
												}
											}
                    ?>
								</select>
								</label>
							</section>
                        </div>
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
        				<form action="Clientapi/add" id="AddProjectClient" class="smart-form" novalidate="novalidate" autocomplete="off" enctype="multipart/form-data" method="post">
        					<fieldset>
        						<div class="row">
        								<input type="hidden" name="company_id" value="<?= $company_id; ?>">
        							<section class="col col-md-12">
        								<label class="label">Client Name</label>
        								<label class="input"> <i class="icon-append fa fa-tag"></i>
        									<input type="text" name="name" placeholder="Client Name" maxlength="30" size="30"  value="<?= @$company['name']; ?>" >
        									
        								</label>
        							</section>				
        						</div>
        						<section>
        						    
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

function getMembers1(project_id)
	{
		$.ajax({
			type            : "POST",
			url             : base_url+'company/Projectapi/getMembersOfThePeoples',
			headers         : { 'authToken': authToken },
			data            : {project_id:project_id,value:''}, //only input
			beforeSend      : function () {
			preLoadshow(true);
				$('#submit').prop('disabled', true);
			},
			success         : function (res) {
				preLoadshow(false);
				setTimeout(function(){  $('#submit').prop('disabled', false); },4000);
				res = JSON.parse(res)
				console.log(res.success)
				if(res.success==true){ 
					var constrcutor_html = "";
					var crew_html = "";
					let contractorLength = res.data.contractorList.length;
					let crewLenght = res.data.crewList.length;
					for(let i = 0; i < contractorLength; i++)
					{
						constrcutor_html += '<option value="'+res.data.contractorList[i].id+'">'+res.data.contractorList[i].name+'</option>';
					}
					$('#constrcutor_list').html(constrcutor_html);
					$("#constrcutor_list").multiselect('refresh');
					$('#constrcutor_list').multiselect('destroy');
					$('#constrcutor_list').multiselect();
					for(let i = 0; i < crewLenght; i++)
					{
						crew_html += '<option value="'+res.data.crewList[i].id+'">'+res.data.crewList[i].name+'</option>';
					}
					$('#crews_list').html(crew_html);
					$('#crews_list').multiselect('refresh');
					$('#crews_list').multiselect('destroy');
					$('#crews_list').multiselect();
				}else{
					toastr.error(res.message, 'Alert!', {timeOut: 4000});
				}
			}
		});
	}

</script>



