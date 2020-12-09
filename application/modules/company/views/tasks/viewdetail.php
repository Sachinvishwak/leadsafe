<?php $backend_assets=base_url().'backend_assets/'; ?>
<section id="widget-grid" class="">
    
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="#" onclick="history.back();">Task</a></li>
        <li class="breadcrumb-item active" aria-current="page">Detail</li>
      </ol>
    </nav>
    
    <input type="hidden" value="<?php echo $task['taskId']; ?>" id="this_task_id"/>
    
	<!-- row -->
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
			<div class="well no-padding">
				<form action="Tasksapi/add" id="companytaskAddUpdate" class="smart-form" novalidate="novalidate" autocomplete="off" enctype="multipart/form-data" method="post">
					
					<fieldset>
					<!-- 	<header>
					Basic Information
					<input type="hidden" name="id" value="0">
					</header> -->
						<div class="row">
							<input type="hidden" name="id" value="<?= encoding(@$task['taskId']); ?>" id="task_id_id">
							<section class="col col-md-12">
								<label class="label">Task name</label>
								<label class="input"> <i class="icon-append fa fa-tag"></i>
									<input type="text"  onfocus="blur()" name="name" placeholder="Name" maxlength="30" size="30"  value="<?= @$task['name']; ?>" >
									
								</label>
							</section>				
						</div>
						<section>
						<label class="label">Description</label>
						<label class="textarea" >
						<textarea rows="3" name="description" onfocus="blur()" placeholder="Description"><?= @$task['description']; ?></textarea>
						</label>
						
						</section>				
						
					</fieldset>	
	
					
				</form>
			</div>	
		</div>
	</div>
 	<!-- end row -->        
</section>
<!-- end widget grid -->

<!-- add steps --->
<div class="col-sm-12 col-md-12 col-lg-12" style="display:<?php if(isset($task['taskId'])){ echo 'block';} else {echo 'none';}  ?>">
	<!-- data -->
	<div class="row">
		
		<div class="col-sm-12 col-md-12 col-lg-12 padding-left-1">
		    <p class="Show_option" style="display: none;">
			<span class="pull-right" >

				<a href="javascript:void(0);" class="btn btn-labeled btn-info" onclick="openAction('text');" > <span class="btn-label"><i class="fa fa-comment-o"></i></span> Text </a>&nbsp;&nbsp;/&nbsp;&nbsp;
				<a href="javascript:void(0);" class="btn btn-labeled btn-info" onclick="openAction('image');" > <span class="btn-label"><i class="fa fa-file-image-o"></i></span> Image </a>&nbsp;&nbsp;/&nbsp;&nbsp;
				<a href="javascript:void(0);" class="btn btn-labeled btn-info" onclick="openAction('video');" > <span class="btn-label"><i class="fa fa-file-video-o"></i></span> Video </a>
			</span>
			<hr>
			</p>
		</div>
	</div>
    <!-- end row -->
	<!-- data -->
</div>

<!-- edit items -->
<div class="col-sm-12 col-md-12 col-lg-12" style="margin-bottom:60px; style="display:<?php if(isset($task['taskId'])){ echo 'block';} else {echo 'none';}  ?>"">
	<div class="row connectedSortable"  id="sortable1">
		<?php if(!empty($task_meta)): $colors = array('info', 'warning','success'); ?>
			<?php foreach ($task_meta as $sm => $step) { $rand_color = $colors[array_rand($colors)]; ?>
				<div class="col-sm-12 col-md-12 col-lg-12 ui-state-default sortlayer  alert alert-<?= $rand_color; ?>" data-metaid="<?= $step->taskmetaId; ?>"data-type="<?= $step->fileType; ?>">
					<?php if($step->fileType=='TEXT'):?>
						<p class="text-muted">
						<?= $step->description; ?> 
						<input type="hidden" id="filetext_<?= $step->taskmetaId; ?>" name="filetext" value="<?= $step->description; ?>" >
						<!--<ul class="list-inline padding-10">-->
						<!--	<li>-->
						<!--	    <i class="fa fa-trash"></i>-->
						<!--			<a href="javascript:void(0);" onclick="confirmAction(this);" data-message="You want to Delete this record!" data-id="<?= encoding($step->taskmetaId); ?>" data-url="company/Tasksapi/recordDeleteMeta" data-list="">Delete</a>-->
						<!--	</li>-->
						<!--	<li>-->
						<!--	    <i class="fa fa-edit"></i>-->
						<!--			<a href="javascript:void(0);" onclick="editActionText('text','<?= $step->taskmetaId; ?>');" > Edit</a>-->
						<!--	</li>-->
						<!--</ul>-->
					</p>
					<?php endif; ?>
					<?php if($step->fileType=='IMAGE'):?>
						<img  width="300" height="250" src="<?= base_url('uploads/task_image/').$step->file; ?>" class="img-responsive"  alt="img">
						<!--<ul class="list-inline padding-10">-->
						<!--	<li>-->
						<!--		<i class="fa fa-trash"></i>-->
						<!--			<a href="javascript:void(0);" onclick="confirmAction(this);" data-message="You want to Delete this record!" data-id="<?= encoding($step->taskmetaId); ?>" data-url="company/Tasksapi/recordDeleteMeta" data-list="">Delete</a>-->
						<!--	</li>-->

						<!--</ul>-->
					<?php endif; ?>
                    <?php if($step->fileType=='VIDEO'):?>
                    <div class="embed-responsive embed-responsive-16by9">
                        <video width="420" height="315" controls="true" class="embed-responsive-item">
                          <source  src="<?= base_url('uploads/task_video/').$step->file; ?>" type="video/mp4" />
                        </video>
                    </div>
		   <!--         <ul class="list-inline padding-10">-->
					<!--	<li>-->
					<!--		<i class="fa fa-trash"></i>-->
					<!--			<a href="javascript:void(0);" onclick="confirmAction(this);" data-message="You want to Delete this record!" data-id="<?= encoding($step->taskmetaId); ?>" data-url="company/Tasksapi/recordDeleteMeta" data-list="">Delete</a>-->
					<!--	</li>-->
															
					<!--</ul>-->
					<?php endif; ?>
				</div>
				<?php } ?>
				<?php else: ?>
					<div class="col-sm-12 col-md-12 col-lg-12 text-center" style="display:<?php if(isset($task['taskId'])){ echo 'block';} else {echo 'none';}  ?>">No record found.</div>
						<?php endif; ?>
					</div>		
				</div>
								
								
								

								
								
<!-- END ROW -->
<!-- Modal -->
<div class="modal fade" id="add-data" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title">
					Manage Steps
				</h4>
			</div>
			<div class="modal-body">
	           <!-- Add CUstomer -->
				<!-- widget content -->
				<div class="widget-body no-padding">
					<form action="Tasksapi/addTaskStep" id="create-task-step-company" class="smart-form" novalidate="novalidate" autocomplete="off">
				
						<fieldset>
						<input type="hidden" name="id" id="taskId_ss" value="<?php echo encoding($task['taskId']); ?>" >
						<input type="hidden" name="taskstepId" id="taskstepId" value="" >
							

							<div class="col-md-12 col-sm-12 col-lg-12" id="divPro_1">
								
								<section class="col col-md-12">
									<label class="label">TEXT<span class="error">*</span></label>
									<label class="textarea"><i class="icon-append fa fa-comment"></i>
											<textarea rows="4" class="textClassStep" name="textfile_1" id="textfile_1" placeholder="Enter Task Instructions step" maxlength="400"></textarea>
											<input type="hidden" id="textfileId_1" name="textfileId_1" value="0">
										</label>
								</section>
								
							</div>
							<div class="col-md-12 col-sm-12 col-lg-12" id="divProImg_1">

								<section class="col col-md-12 text-center">
									<label class="label"><strong>Image Preview</strong></label>
									<img width="400" height="300" src="https://via.placeholder.com/640x360.png?text=Image+Preview"  id="blah_1" alt="img">

								</section>
								<section class="col col-md-12">
									<label class="label">Image<span class="error">*</span></label>
									<div class="input input-file">
									<input type="hidden" name="imagefileId_1" value="0">
									<span class="button"><input type="file" class="textClassStep" name="fileImage_1" id="file_1" onchange="readURL(this,1);this.parentNode.nextSibling.value = this.value" accept="image/*">Browse</span><input type="text" readonly="">
									</div>
								</section>
							</div>
							<div class="col-md-12 col-sm-12 col-lg-12" id="divProVideo_1">

								<section class="col col-md-12 text-center">
									<label class="label"><strong>Video Preview</strong></label>
									<div id="privew1"><img  width="400" height="300" src="https://via.placeholder.com/640x360.png?text=Video+Preview"  alt="img"></div>
								</section>
							<section class="col col-md-12">
								<label class="label">Video<span class="error">*</span></label>
								<div class="input input-file">
								<input type="hidden" name="videofileId_1" value="0"><span class="button"><input type="file" class="textClassStep" name="videofile_1" id="videofile_1" onchange="filePreviewMain(this,1);this.parentNode.nextSibling.value = this.value" accept="video/*">Browse</span><input type="text" readonly="">
								</div>
							</section>
							</div>
		</div>
								
								
						</fieldset>

						<footer>
							<button type="submit" id="submit" class="btn btn-primary">
								Save
							</button>
						</footer>
					</form>
				</div>
				<!-- end widget content -->
				<!-- Add CUstomer -->
	        </div>
		</div>
	</div>
</div>
<!-- End modal -->



<!-- people list -->
    <?php
        if(isset($invite_people_data))
        {    
            echo '<div style="margin-left: 15px;margin-top: 20px;margin-right: 15px;margin-bottom: 13px;font-size: 20px;><span style=" font-weight:="" 500;"="">Involved members</div>';
            echo '<div class="row"><div class="col-sm-12">';
            foreach($invite_people_data as $value)
            {
                if($value->is_removed == 0){?>
                    <div class="col-sm-2">
                        <div class="card" style="border: none;">
                        <img class="card-img-top" src="https://img.icons8.com/officel/2x/user.png" alt="Card image" style="width:100%;border:none;background: #d85e5e;border-top-left-radius: 10px;border-top-right-radius: 10px;">
                        <div class="card-body" style="border-bottom-left-radius: 10px;border: 1px solid #eadfdf;border-bottom-right-radius: 10px;padding: 13px;">
                          <h4 class="card-title" style="font-weight: 500;margin-bottom: 10px;word-break: break-all;font-size:16px;"><?= $value->user_name ?>
                            <!--<i id="actionIds" onclick="addPeople('<?php echo $value->id; ?>');" class="fa fa-trash"></i>-->
                          </span><br><?= $value->user_email ?><br><?= $value->role ?></h4>
                        </div>
                      </div>
                    </div>
                <?php
                }
            }
            
            echo '</div></div>';
            echo '<div style="margin-left: 15px;margin-top: 20px;margin-right: 15px;margin-bottom: 13px;font-size: 20px;><span style=" font-weight:="" 500;"="">Removed members</div>';
            echo '<div class="row"><div class="col-sm-12">';
            foreach($invite_people_data as $value)
            {
                if($value->is_removed == 1){?>
                    <div class="col-sm-2" style="margin-bottom:40px;">
                        <div class="card" style="border: none;">
                        <img class="card-img-top" src="https://img.icons8.com/officel/2x/user.png" alt="Card image" style="width:100%;border:none;background: #d85e5e;border-top-left-radius: 10px;border-top-right-radius: 10px;">
                        <div class="card-body" style="border-bottom-left-radius: 10px;border: 1px solid #eadfdf;border-bottom-right-radius: 10px;padding: 13px;">
                          <h4 class="card-title" style="font-weight: 500;word-break: break-all;margin-bottom: 10px;font-size:16px;"><?= $value->user_name ?>
                            <!--<i id="actionIds" onclick="addPeople('<?php echo $value->id; ?>');" class="fa fa-plus"></i>-->
                          </span><br><?= $value->user_email ?><br><?= $value->role ?></h4>
                        </div>
                      </div>
                    </div>            
                <?php
                }
            }
            echo '</div></div>';
        }                     
    ?>
<div style="margin-bottom:40px;">
    
</div>

<!--- Exising --->
<div id="inviteExisingdModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Invite People</h4>
      </div>
      <div class="modal-body" style="height:250px;">
        <div class="panel-group">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" href="#collapse1">+ Lead Contractor</a>
                </h4>
              </div>
              <div id="collapse1" class="panel-collapse collapse">
                <?php
                
                    foreach($existing_contractor as $value)
                    {
                        ?>
                        <div class="panel-body"><?php echo $value->owner_first_name; ?><button class="btn btn-success" onclick="inviteExisingPeople('<?php echo $value->owner_first_name; ?>','<?php echo $value->email; ?>','1','leadcontractor','<?php echo $value->id; ?>')" style="float:right;">Invite</button></div>
                        <?php
                    }
                ?>
              </div>
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" href="#collapse2">+ Crew Member</a>
                  
                </h4>
              </div>
              <div id="collapse2" class="panel-collapse collapse">
                    <?php
                
                    foreach($existing_crew_member as $value)
                    {
                        ?>
                        <div class="panel-body"><?php echo $value->name; ?><button class="btn btn-success" onclick="inviteExisingPeople('<?php echo $value->name; ?>','<?php echo $value->email; ?>','3','crew','<?php echo $value->id; ?>')" style="float:right;">Invite</button></div>
                        <?php
                    }
                ?>
              </div>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


<!--- invite new modal ---->
<div id="inviteNewdModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Invite People</h4>
      </div>
      <div class="modal-body">
            <form id="invitenewpeople" class="invitenewpeople" method="post">
              <div class="form-group">
                <label for="exampleInputEmail1">Email Name</label>
                <input type="text" name="name" class="form-control" id="invitenewpeoplename" aria-describedby="emailHelp" placeholder="Enter name" required="">
              </div>
              <div class="form-group">
                <label for="exampleInputPassword1">Enter Email</label>
                <input type="email" name="email" class="form-control" id="invitenewpeopleemail" placeholder="Enter email" required="">
              </div>
              
              <input type="hidden" name="is_for" value="task">
              <input type="hidden" name="task_id" value="<?= $task['taskId']; ?>">
              <input type="hidden" name="sender_type" value="company">
              <input type="hidden" name="company_id" value="<?= $_SESSION['company_sess']['id']; ?>">
              
              
              <div class="form-group">
                <label for="exampleInputPassword1">Select position</label>
                <select name="reciever_type" class="form-control" required="">
                    <option value="leadcontractor">Lead Contractor</option>
                    <option value="crew">Crew Member</option>
                </select>
              </div>
              <input type="submit" value="Invite" class="btn btn-primary"/>
            </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


<script>

    $('.invitenewpeople').submit(function(e){
        e.preventDefault();
        
        $.ajax({
            url:"<?php echo site_url('company/Tasksapi/inviteNewPeopleCreateandSend'); ?>",
            type: "post",
            dataType: 'json',
            processData: false,
            contentType: false,
            data: new FormData($('.invitenewpeople')[0]),
            beforeSend  : function() {
                preLoadshow(true);
            }, 
            success:function(res){
                if(res.status=='success'){
                  toastr.success(res.message, 'Success', {timeOut: 3000});
                }else{
                  toastr.error(res.message, 'Alert!', {timeOut: 4000});
                }
                preLoadshow(false);
            }
        });
    });

    function addPeople(inviteId)
    {
        
        $.ajax({
            url:"<?php echo site_url('company/Tasks/addPeople') ?>",
            type: "post",
            dataType: 'json',
            data: {inviteId: inviteId},
            success:function(result){
                toastr.clear();
                toastr.success(result.message, 'Success', {timeOut: 3000});
                setTimeout(function(){ window.location.reload(); }, 3000);
            }
        });    
    }
    
    function inviteExisingPeople(invitenewpeoplename,invitenewpeopleemail,invitenewpeopleposition,role,userid="")
    {
        let company_id = '<?php echo $_SESSION['company_sess']['id']; ?>';
        let task_id = $('#this_task_id').val();
        $.ajax({
            url:"<?php echo site_url('company/Tasksapi/ajaxForTaskPeopleinvite'); ?>",
            type: "post",
            dataType: 'json',
            data: {invitenewpeoplename: invitenewpeoplename,invitenewpeopleemail: invitenewpeopleemail,invitenewpeopleposition: invitenewpeopleposition,is_for:'task',task_id:task_id,role:role,company_id:company_id,userid:userid,sender_type:'company'},
            beforeSend  : function() {
                preLoadshow(true);
            }, 
            success:function(res){
                if(res.status=='success'){
                  toastr.success(res.message, 'Success', {timeOut: 3000});
                  //window.location.reload();
                }else{
                  toastr.error(res.message, 'Alert!', {timeOut: 4000});
                }
                preLoadshow(false);
            }
        });
    }
    
    function openOptionsbtn(){
        console.log('Hello');
        $('#openclosebtn').css('display','none');
        $('.optionbtn').css('display','inline-block');
    }
    
    function openExistingModel(){
        $('#inviteExisingdModal').modal('show')
    }
    
    function openModel(){
        $('#inviteNewdModal').modal('show')
    }
    
    
</script>