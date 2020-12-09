<section id="widget-grid" class="">
	<!-- row -->
	<div class="row">
		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false" data-widget-editbutton="false" data-widget-deletebutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-tags"></i> </span>
					<h2>Group List</h2>
				</header>
				<!-- widget div-->
				<div>
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->
					</div>
					<!-- end widget edit box -->
					<!-- widget content -->
					<div class="widget-body padding">
						<div class="table-responsive">
							<table  class="table table-striped table-bordered table-hover dataTables-example-list" width="100%" data-list-url = "company/Groupapi/list" data-id ="<?php echo $_SESSION['company_sess']['id']; ?>" data-no-record-found = "">
								<thead>			                
									<tr>
										<th data-hide="phone">ID</th>
										<th data-hide="phone,tablet">Group Name</th>
										<th data-hide="phone,tablet">Project Name</th>
										<th data-hide="phone,tablet">Member Name</th>
										<th data-hide="phone,tablet">Created At</th>
										<th data-hide="phone,tablet">Updated At</th>
										<!-- <th data-hide="phone,tablet">Status</th> -->
										<th style="width:14%!important;" data-hide="phone,tablet">Action</th>
									</tr>
								</thead>
								<tbody>			
								</tbody>
							</table>
						</div>
					</div>
					<!-- end widget content -->
				</div>
				<!-- end widget div -->
			</div>
			<!-- end widget -->
		</article>
		<!-- WIDGET END -->
	</div>
	<!-- end row -->
</section>
<!-- end widget grid -->


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="    height: 600px;
    overflow: scroll;">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Group <?= $project['name']; ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
                <form action="Groupapi/add" id="createGroup" class="smart-form" novalidate="novalidate" autocomplete="off" enctype="multipart/form-data" method="post">
                    
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
                    
					<input type="hidden" name="company_id" value="<?php echo $_SESSION['company_sess']['id']; ?>" />
					
					<fieldset>
                        <div class="row">
                            <section class="col col-md-12">
                                <lable>Select Project</lable>
								<select id="project_list" class="form-control" name="project_id"  onchange="getMembers1(this.value)" required="">
									<option value="">Select Project</option>
								    <?php
                                        $project_list = $this->db->get_where('project',array('company_id'=>$company_id))->result();
                                        foreach($project_list as $projects)
                                        {
                                            echo '<option value="'.$projects->id.'">'.$projects->name.'</option>';
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
                                <lable>Select Contractor</lable>
								<select id="constrcutor_list" class="multiselect-ui form-control" multiple="multiple" name="contractorIds[]"  >
								    <?php
                                        // $contractor_list = $this->db->select('company_member_relations.type, contractor.*')
                                        //      ->from('company_member_relations')
                                        //      ->join('contractor', 'company_member_relations.member_id = contractor.id')
                                        //      ->where('company_member_relations.company_id',$company_id)
                                        //      ->where('contractor.is_role',1)
                                        //      ->where('company_member_relations.type','leadcontractor')->distinct()->get()->result();
                                        // foreach($contractor_list as $contractor)
                                        // {
                                        //     echo '<option value="'.$contractor->id.'">'.$contractor->owner_first_name.'</option>';
                                        // }
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
                                        //      ->from('company_member_relations')
                                        //      ->join('crew_member', 'company_member_relations.member_id = crew_member.id')
                                        //      ->where('company_member_relations.company_id',$company_id)
                                        //      ->where('company_member_relations.type','crew')->distinct()->get()->result();
                                        // foreach($crew_member_list as $crew_membe)
                                        // {
                                        //     echo '<option value="'.$crew_membe->id.'">'.$crew_membe->name.'</option>';
                                        // }
                                    ?>
								</select>
								</label>
							</section>
                        </div>
                    </fieldset>
                    
                    
                    
					<fieldset>
						<div class="row">
							<input type="hidden" name="company_id" value="<?= $company_id; ?>">
							
							<section class="col col-md-12">
								<label class="label">Group name</label>
								<label class="input"> <i class="icon-append fa fa-tag"></i>
									<input type="text" name="name" placeholder="Name" maxlength="30" size="30"  value="" >
								</label>
							</section>				
						</div>
						
						<div class="row" style="display:none;">
							<section class="col col-md-12">
							    <label class="label">Assign Crew Members</label>
							    <?php
							        foreach($crew_list as $value){
							            ?>
							            <input class="checkbox1" type="checkbox" name="myCheckboxes[]" value="<?php echo $value->id; ?>">
                                        <label for="vehicle1"><?php echo $value->name; ?></label>
							        <?php }
							    ?>
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
</div>

<script>

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
