<?php $backend_assets=base_url().'backend_assets/'; ?>
<link rel="stylesheet" href="<?php echo base_url('backend_assets/css/chat.css')?>"/>

<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>">Home</a></li>
    <li class="breadcrumb-item"><a href="#" onclick="history.back();">Task</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail</li>
  </ol>
</nav>

<?php
    
    if(isset($_GET['type']) && $_GET['type']=="view" )
    {
        $is_show = true;
    }else{
        $is_show = false;
    }

?>
<?php if($is_show){ ?>
<div class="row">

	<div class="col-sm-12">
			<div class="well well-sm">

				<div class="row">

					
					<div class="col-sm-12 col-md-12 col-lg-12">
						<div class="well well-light well-sm no-margin padding-4">

							<div class="row">

							

								<div class="col-sm-12">

									<div class="row">
										<div class="col-sm-12 padding-left-1">
											<input type="hidden" id="task_id_id" name="task_id_id" value="<?= encoding($task['taskId']); ?>">
											<h3 class="margin-top-0"><a href="javascript:void(0);"> <?= $task['name']; ?> </a></h3>
											
											<hr>
											<p maxlength="400"><?= $task['description']; ?>
												<ul class="list-inline padding-10 pull-right">
													<li style="display:none;">
													<i class="fa fa-edit"></i>
													<a href="<?= base_url().'admin/tasks/edit/'.encoding($task['taskId']);?>" > Edit</a>
													</li>
												</ul>
											</p>
										</div>
									
									
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-12 col-md-12 col-lg-12">
						<!-- data -->
							<div class="row">
								<div style="display:none;" class="col-sm-12 col-md-12 col-lg-12 padding-left-1">
									<legend>
									Steps To Complete Tasks <a href="javascript:void(0);" class="btn btn-labeled btn-info  pull-right" onclick="openActionOption(this);" id="layerOpt" data-id="show" > <span class="btn-label"><i class="glyphicon glyphicon-plus"></i></span> Add Steps </a>
									</legend>								
								</div>

								<div style="display:none;" class="col-sm-12 col-md-12 col-lg-12 padding-left-1">
									<p class="Show_option" style="display: none;">
										<span class="pull-right" >

										<a href="javascript:void(0);" class="btn btn-labeled btn-info" onclick="openAction('text');" > <span class="btn-label"><i class="fa fa-comment-o"></i></span> Text </a>&nbsp;&nbsp;/&nbsp;&nbsp;
										<a href="javascript:void(0);" class="btn btn-labeled btn-info" onclick="openAction('image');" > <span class="btn-label"><i class="fa fa-file-image-o"></i></span> Image </a>&nbsp;&nbsp;/&nbsp;&nbsp;
										<a href="javascript:void(0);" class="btn btn-labeled btn-info" onclick="openAction('video');" > <span class="btn-label"><i class="fa fa-file-video-o"></i></span> Video </a>
									</span>
										<hr>
									</p>

															
								</div>
								<div class="col-sm-12 col-md-12 col-lg-12" style="margin-top:10px;">
									<div class="row connectedSortable"  id="sortable2">
										<?php if(!empty($task_meta)): $colors = array('info', 'warning','success'); ?>
											<?php foreach ($task_meta as $sm => $step) { $rand_color = $colors[array_rand($colors)]; ?>
											<div class="col-sm-12 col-md-12 col-lg-12 ui-state-default sortlayer  alert alert-<?= $rand_color; ?>" data-metaid="<?= $step->taskmetaId; ?>"data-type="<?= $step->fileType; ?>">
												<?php if($step->fileType=='TEXT'):?>
													<p class="text-muted">
														<?= $step->description; ?> 
														<input type="hidden" id="filetext_<?= $step->taskmetaId; ?>" name="filetext" value="<?= $step->description; ?>" >
															<ul class="list-inline padding-10" style="display:none;">
															<li>
															<i class="fa fa-trash"></i>
															<a href="javascript:void(0);" onclick="confirmAction(this);" data-message="You want to Delete this record!" data-id="<?= encoding($step->taskmetaId); ?>" data-url="adminapi/tasks/recordDeleteMeta" data-list="">Delete</a>
															</li>
															<li>
															<i class="fa fa-edit"></i>
															<a href="javascript:void(0);" onclick="editActionText('text','<?= $step->taskmetaId; ?>');" > Edit</a>
															</li>
															</ul>
													</p>
												<?php endif; ?>
												<?php if($step->fileType=='IMAGE'):?>
													<img  width="300" height="250" src="<?= base_url('uploads/task_image/').$step->file; ?>" class="img-responsive"  alt="img">
															<ul class="list-inline padding-10" style="display:none;">
																<li>
															<i class="fa fa-trash"></i>
															<a href="javascript:void(0);" onclick="confirmAction(this);" data-message="You want to Delete this record!" data-id="<?= encoding($step->taskmetaId); ?>" data-url="adminapi/tasks/recordDeleteMeta" data-list="">Delete</a>
															</li>
															<!-- <li>
															<i class="fa fa-edit"></i>
															<a href="javascript:void(0);" onclick="openAction('image');" > Edit</a>
															</li>
															 -->
															</ul>
															
												<?php endif; ?>
												<?php if($step->fileType=='VIDEO'):?>
													<div class="embed-responsive embed-responsive-16by9">
    <video width="420" height="315" controls="true" class="embed-responsive-item">
      <source  src="<?= base_url('uploads/task_video/').$step->file; ?>" type="video/mp4" />
    </video>
</div>
													<!--  <iframe width="420" height="315"
src="<?= base_url('uploads/task_video/').$step->file; ?>" controls="controls" autoplay="false" frameborder="0" scrolling="no" frameborder="0" allow="accelerometer; encrypted-media; gyroscope; picture-in-picture">
</iframe>  -->
												<!-- 	<video  width="300" height="250" controls>
													<source src="<?= base_url('uploads/task_video/').$step->file; ?>" type="video/mp4">
													<source src="<?= base_url('uploads/task_video/').$step->file; ?>" type="video/ogg">
													Your browser does not support HTML video.
													</video> -->
															<ul class="list-inline padding-10" style="display:none;">
															<li>
															<i class="fa fa-trash"></i>
															<a href="javascript:void(0);" onclick="confirmAction(this);" data-message="You want to Delete this record!" data-id="<?= encoding($step->taskmetaId); ?>" data-url="adminapi/tasks/recordDeleteMeta" data-list="">Delete</a>
															</li>
															<!-- <li>
																<i class="fa fa-edit"></i>
																<a href="javascript:void(0);" onclick="openAction('video');" > Edit</a>
															</li> -->
															</ul>
															
												<?php endif; ?>
											</div>
											<?php } ?>
										<?php else: ?>
											<div class="col-sm-12 col-md-12 col-lg-12 text-center">No record found.</div>
										<?php endif; ?>
									</div>		
								</div>

							</div>
							<!-- end row -->
						<!-- data -->
					</div>
				</div>
			</div>
	</div>
</div>
<?php } ?>

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
					<form action="tasks/addTaskStep" id="create-task-step" class="smart-form" novalidate="novalidate" autocomplete="off">
				
						<fieldset>
						<input type="hidden" name="id" id="taskId_ss" value="<?= encoding($task['taskId']); ?>" >
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


<!--- info and wall section --->
<?php if(!$is_show){ ?>
<div class="container" style="width:100%!important;max-width:100%!important;">
<div class="row">
	<div class="col-sm-12">
		<div class="well well-sm">
			<div class="row">
				<div class="col-sm-12 col-md-12 col-lg-12">
						<div class="well well-light well-sm no-margin padding-4">
						    <!-- Nav tabs -->
                            <ul class="nav nav-tabs" role="tablist">
                              <li class="active"><a href="#task" role="tab" data-toggle="tab">Info</a></li>
                              <li><a href="#wall" role="tab" data-toggle="tab">Wall</a></li>
                            </ul>
                            <!-- Tab panes -->
                                <style type="text/css">
								h5{
                                    margin-top:2px;
                                    margin-bottom:2px;
                                    font-size:16px;
                                }
                                #clientfont
    							{
    								font-weight: 800;
                                    color: #214e75;
    							}
							</style>
                            <div class="tab-content">
                                <div class="tab-pane active" id="task">
                                    
                                    <div class="col-sm-2" style="margin-top: 20px;">
									    <p id="clientfont">Task Name</p>
									</div>
									<div class="col-sm-10" style="margin-top: 20px;">
									    <p>: <?= $task['name']; ?></p>
									</div>
									
									<div class="col-sm-2">
									    <p id="clientfont">Task Description</p>
									</div>
									<div class="col-sm-10">
									    <p>: <?= $task['description']; ?></p>
									</div>
									
									<div class="col-sm-12">
									    
									</div>

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
                                                          <h4 class="card-title" style="font-weight: 500;margin-bottom: 10px;font-size:16px;word-break: break-all;"><?= $value->user_name ?><br><?= $value->user_email ?><br><?= $value->role ?></h4>
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
                                                          <h4 class="card-title" style="font-weight: 500;margin-bottom: 10px;font-size:16px;word-break: break-all;"><?= $value->user_name ?><br><?= $value->user_email ?><br><?= $value->role ?></h4>
                                                        </div>
                                                      </div>
                                                    </div>            
                                                <?php
                                                }
                                            }
                                            echo '</div></div>';
                                        }                     
                                    ?>
                                        
                                </div>
                                <div class="tab-pane" id="wall">
                                     <div class="">
                                         <div class="text-right" style="margin-right:100px;padding-top: 10px;">
                                             <input type="text" id="search_for_task_chat" value="" placeholder="search" />
                                         </div>
                                        <div class="mesgs">
                                          <div class="msg_history" id="yourDivID">
                                            <div class="incoming_msg">
                                              <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> <label class="Name_font">Rahul</label> </div>
                                              <div class="received_msg">
                                                <div class="received_withd_msg">
                                                  <p>Test which is a new approach to have all
                                                    solutions</p>
                                                  <span class="time_date"> 11:01 AM    |    June 9</span></div>
                                                </div>
                                             </div>
                                            <div class="outgoing_msg">
                                              <div class="outgoing_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil">  <label class="Name_font">vikash</label></div>
                                              <div class="outgoing_msg">
                                                <div class="outgoing_withd_msg">
                                                <div class="sent_msg">
                                                  <p>Test, which is a new approach to have</p>
                                                </div>
                                                  <span class="time_date"> 11:01 AM    |    Yesterday</span></div>
                                              </div>
                                            </div>
                                            <div class="incoming_msg">
                                              <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> <label class="Name_font">Rahul</label> </div>
                                              <div class="received_msg">
                                                <div class="received_withd_msg">
                                                  <p>Test, which is a new approach to have</p>
                                                  <span class="time_date"> 11:01 AM    |    Yesterday</span></div>
                                              </div>
                                            </div>
                                            <div class="outgoing_msg">
                                              <div class="outgoing_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> <label class="Name_font" >vikash</label> </div>
                                              <div class="outgoing_msg">
                                                <div class="outgoing_withd_msg">
                                                <div class="sent_msg">
                                                  <p>Test, which is a new approach to have</p>
                                                </div>
                                                  <span class="time_date"> 11:01 AM    |    Yesterday</span></div>
                                              </div>
                                            </div>
                                            <div class="incoming_msg">
                                              <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil">  <label class="Name_font">Rahul</label></div>
                                              <div class="received_msg">
                                                <div class="received_withd_msg">
                                                  <p>We work directly with our designers and suppliers,
                                                    and sell direct to you, which means quality, exclusive
                                                    products, at a price anyone can afford.</p>
                                                  <span class="time_date"> 11:01 AM    |    Today</span></div>
                                              </div>
                                            </div>
                                            <div class="outgoing_msg">
                                              <div class="outgoing_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> <label class="Name_font" >vikash</label> </div>
                                              <div class="outgoing_msg">
                                                <div class="outgoing_withd_msg">
                                                <div class="sent_msg">
                                                  <p>Test, which is a new approach to have</p>
                                                </div>
                                                  <span class="time_date"> 11:01 AM    |    Yesterday</span>
                                                
                                                </div>
                                              </div>
                                            </div>
                                            <div class="outgoing_msg">
                                            <div style="width:3%"><img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"><label class="Name_font" >Rahul</label></div>
                                             
                                            <div class="custom-file">
                                                <span class="hiddenFileInput">
                                                    <input type="file" name="theFile"/>
                                                </span>
                                                <span class="time_date"> 11:01 AM    |    Today</span>
                                                <label >doc.pdf</label>
                                            </div>
                                            </div>
                                
                                            
                                            <div class="incoming_msg">
                                            <div style="width:3%"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil">  <label class="Name_font">vikash</label></div>
                                             
                                            <div class="custom-file">
                                                <span class="hiddenFileInput">
                                                    <input type="file" name="theFile"/>
                                                </span>
                                                
                                                <span class="time_date"> 11:01 AM    |    Today</span>
                                                <label >doc.pdf</label>
                                            </div>
                                            </div>
                                
                                            <div class="outgoing_msg">
                                              <div class="outgoing_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> <label class="Name_font" >Rahul</label> </div>
                                              <div class="outgoing_msg">
                                                <div class="outgoing_withd_msg">
                                                <div class="sent_msg">
                                                  <p>Test, which is a new approach to have</p>
                                                </div>
                                                  <span class="time_date"> 11:01 AM    |    Yesterday</span>
                                              
                                                </div>
                                              </div>
                                            </div>
                                            
                                            <div class="incoming_msg">
                                              <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> <label class="Name_font" >vikash</label> </div>
                                              <div class="received_msg">
                                                <div class="received_withd_msg">
                                                  <p>We work directly with our designers and suppliers,
                                                    and sell direct to you, which means quality, exclusive
                                                    products, at a price anyone can afford.</p>
                                                  <span class="time_date"> 11:01 AM    |    Today</span></div>
                                              </div>
                                            </div>
                                          </div>
                                                                       
                                          <div class="type_msg">
                                            <div class="input_msg_write">
																							<input type="text" class="write_msg" id="message_input_box" placeholder="Type a message"/> 
																							
																							<form title="multiple file uplaod" id="message-form-multi" class="message-form-multi" method="post" enctype='multipart/form-data'>
																							  <input id="file1" type="file" name="attachment[]" class="fa fa-paperclip" aria-hidden="true" multiple="multiple"/>
                                                <input type="hidden" name="task_hidden_id" id="task_hidden_id" value="<?php echo $task['taskId']; ?>"/>
                                                <input type="hidden" name="user_type" id="user_type" value="company"/>
																								<input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION['company_sess']['id']; ?>"/>
                                              </form>

                                              <form id="message-form" class="message-form" method="post" enctype='multipart/form-data'>
                                                  <input id="file" type="file" name="attachment" class="fa fa-paperclip" aria-hidden="true" />
                                                  <input type="hidden" name="task_hidden_id" id="task_hidden_id" value="<?php echo $task['taskId']; ?>"/>
                                                  <input type="hidden" name="user_type" id="user_type" value="company"/>
                                              </form>
                                             <button class="msg_send_btn" onclick="sendMessageToDatabase()" id="msg_send_btn" type="button"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
                                            </div>
                                          </div>
                                         </div>
                                        </div>                        
                                </div>
                            </div>
						</div>
				    </div>
			</div>
		</div>
	</div>
</div>
</div>
<?php } ?>





<script>

    $("input[name='attachment']").change(function(){
        $('#message-form').submit();
		});

		$("input[name='attachment[]']").change(function(){
				$('#message-form-multi').submit();
		});

		
		$('#message-form-multi').submit(function(e){
        e.preventDefault();
        var form = $(this);
        $.ajax({
            url:"<?php echo site_url('chat/Api/sendMessageToDatabaseTaskMultiDocs'); ?>",
            type: "post",
            dataType: 'json',
            data: new FormData(this),
             processData: false,
             contentType: false,
            beforeSend  : function() {
                preLoadshow(true);
            }, 
            success:function(res){
                $('#file1').val('');
                preLoadshow(false);
                getsentmessagestask();
                scrolled = false;
            }
        });
    })

    
    $('#message-form').submit(function(e){
        e.preventDefault();
        var form = $(this);
        $.ajax({
            url:"<?php echo site_url('chat/Api/sendMessageToDatabaseTask'); ?>",
            type: "post",
            dataType: 'json',
            data: new FormData(this),
             processData: false,
             contentType: false,
            beforeSend  : function() {
                preLoadshow(true);
            }, 
            success:function(res){
                $('#file').val('');
                preLoadshow(false);
                getsentmessagestask();
                scrolled = false;
            }
        });
    })

    $(document).on("keyup", "#message_input_box", function(e){
        if (e.key === 'Enter' || e.keyCode === 13){
            sendMessageToDatabase();
        }
    });
    
    function sendMessageToDatabase()
    {
        task_hidden_id = '<?php echo $task['taskId']; ?>';
        message = $('#message_input_box').val();
        if(message == "")
        {
             swal('You can not send blank message');
            //alert('you Can not send blank message');
        }else{
            $.ajax({
                url:"<?php echo site_url('chat/Api/sendMessageToDatabaseTask'); ?>",
                type: "post",
                data: {message:message,task_hidden_id:task_hidden_id,user_type:"company"},
                beforeSend  : function() {
                    preLoadshow(true);
                }, 
                success:function(res){
                    $('#message_input_box').val("");
                    getsentmessagestask();
                    $('#confirmModal').modal('hide');
                    preLoadshow(false);
                    scrolled = false;
                }
            });   
        }
    }
    
    getsentmessagestask();
    setInterval(function()
    { 
        getsentmessagestask();
    }, 3000);
    
    setInterval(updateScroll,1000);
    
    var scrolled = false;
    function updateScroll(){
        if(!scrolled){
            var element = document.getElementById("yourDivID");
            element.scrollTop = element.scrollHeight;
        }
    }
    
    $("#yourDivID").on('scroll', function(){
        scrolled=true;
        console.log(scrolled);
    });
    
    function getsentmessagestask(){
        let search_for_task_chat = $('#search_for_task_chat').val();
        $.ajax({
            url:"<?php echo site_url('chat/Api/getsentmessagestask'); ?>",
            type: "post",
            dataType: 'json',
            data: {task_hidden_id:'<?php echo $task['taskId']; ?>',search_for_task_chat:search_for_task_chat},
            success:function(res){
                $('.msg_history').html(res.messages)
                //message-area-container
            }
        });
    }
</script>



