<?php $backend_assets=base_url().'backend_assets/'; ?>
<section id="widget-grid" class="">
	<!-- row -->
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
			<div class="well no-padding">
				<form action="tasks/add" id="taskAddUpdate" class="smart-form" novalidate="novalidate" autocomplete="off" enctype="multipart/form-data" method="post">
					
					<fieldset>
					<!-- 	<header>
					Basic Information
					<input type="hidden" name="id" value="0">
					</header> -->
						<div class="row">
								<input type="hidden" name="id" value="<?= encoding(@$task['taskId']); ?>" id="task_id_id">
							<section class="col col-md-12">
								<label class="label">Task Name</label>
								<label class="input"> <i class="icon-append fa fa-tag"></i>
									<input type="text" name="name" placeholder="Name" maxlength="30" size="30"  value="<?= @$task['name']; ?>" >
									
								</label>
							</section>				
						</div>
						<section>
						<label class="label">Description</label>
						<label class="textarea" >
						<textarea rows="3" name="description" maxlength="400" placeholder="Description"><?= @$task['description']; ?></textarea>
						</label>
						
						</section>				
						
					</fieldset>	
					
					<?php
    
    if(!isset($task['taskId'])){ ?>
    
    <input type="hidden" name="total_element_text" id="total_element_text" value="0"/>
	<input type="hidden" name="total_element_image" id="total_element_image" value="0"/>
	<input type="hidden" name="total_element_video" id="total_element_video" value="0"/>
	
	<div id="taskstepsdiv">
	    
	</div>
	
	
	<div class="row" style="margin:15px;">
            		<div class="col-sm-12 col-md-12 col-lg-12 padding-left-1">
            		    <legend>
            			Steps To Complete Tasks <a href="javascript:void(0);" class="btn btn-labeled btn-info  pull-right" onclick="openCreateNew();" id="layerOpt" data-id="show" > <span class="btn-label" style="left: 0;"><i class="glyphicon glyphicon-plus"></i></span> Add Steps </a>
            			</legend>								
            		</div>
            		<div class="col-sm-12 col-md-12 col-lg-12 padding-left-1">
            		    <div class="col-sm-12 col-md-12 col-lg-12 choice" style="display:none">
            		        <div class="col-sm-6 col-md-6 col-lg-6 boxed" style="width:47%;margin-right: 20px;" onclick="openPrepopulatedList()">
                              Prepopulated
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-6 boxed" style="width:48%;" onclick="openCreateNew()">
                              Create New
                            </div>
            		    </div>
            		    <p class="Show_option" style="display: none;">
            			    <span class="pull-right" >
                				<a href="javascript:void(0);" class="btn btn-labeled btn-info"  onclick="addAction('text');" > <span class="btn-label" style="left: 0;"><i class="fa fa-comment-o" ></i></span> Text </a>&nbsp;&nbsp;/&nbsp;&nbsp;
                				<a href="javascript:void(0);" class="btn btn-labeled btn-info" onclick="addAction('image');" > <span class="btn-label" style="left: 0;"><i class="fa fa-file-image-o"></i></span> Image </a>&nbsp;&nbsp;/&nbsp;&nbsp;
                				<a href="javascript:void(0);" class="btn btn-labeled btn-info" onclick="addAction('video');" > <span class="btn-label" style="left: 0;"><i class="fa fa-file-video-o"></i></span> Video </a>
                			</span>
            			<hr>
            			</p>
            		</div>
            	</div>
        
<?php }

?>
	
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

<!-- add steps --->
<div class="col-sm-12 col-md-12 col-lg-12" style="display:<?php if(isset($task['taskId'])){ echo 'block';} else {echo 'none';}  ?>">
	<!-- data -->
	<div class="row">
		<div class="col-sm-12 col-md-12 col-lg-12 padding-left-1">
		    <legend>
			Steps To Complete Tasks <a href="javascript:void(0);" class="btn btn-labeled btn-info  pull-right" onclick="openActionOption(this);" id="layerOpt" data-id="show" > <span class="btn-label"><i class="glyphicon glyphicon-plus"></i></span> Add Steps </a>
			</legend>								
		</div>
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
						<ul class="list-inline padding-10">
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
						<ul class="list-inline padding-10">
							<li>
								<i class="fa fa-trash"></i>
									<a href="javascript:void(0);" onclick="confirmAction(this);" data-message="You want to Delete this record!" data-id="<?= encoding($step->taskmetaId); ?>" data-url="adminapi/tasks/recordDeleteMeta" data-list="">Delete</a>
							</li>

						</ul>
					<?php endif; ?>
                    <?php if($step->fileType=='VIDEO'):?>
                    <div class="embed-responsive embed-responsive-16by9">
                        <video width="420" height="315" controls="true" class="embed-responsive-item">
                          <source  src="<?= base_url('uploads/task_video/').$step->file; ?>" type="video/mp4" />
                        </video>
                    </div>
		            <ul class="list-inline padding-10">
						<li>
							<i class="fa fa-trash"></i>
								<a href="javascript:void(0);" onclick="confirmAction(this);" data-message="You want to Delete this record!" data-id="<?= encoding($step->taskmetaId); ?>" data-url="adminapi/tasks/recordDeleteMeta" data-list="">Delete</a>
						</li>
															
					</ul>
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
					<form action="tasks/addTaskStep" id="create-task-step" class="smart-form" novalidate="novalidate" autocomplete="off">
				
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

<script>
    function openCreateNew(){
        $('.choice').css('display','none');
        $('.Show_option').css('display','block');
    }
    
    function addAction(expression)
    {
        switch(expression) {
            case 'text':
                var total_element_text = $('#total_element_text').val();
                total_element_text = parseInt(total_element_text) + 1;
                $('#total_element_text').val(total_element_text);
                $('#taskstepsdiv').append('<div class="col-md-12 col-sm-12 col-lg-12" id="divPro_1"><section class=""><label class="label">TEXT<span class="error">*</span></label><label class="textarea"><i class="icon-append fa fa-comment"></i><textarea rows="4" class="textClassStep" name="textfile_'+total_element_text+'" id="textfile_1" placeholder="Enter Task Instructions step" maxlength="400"></textarea><input type="hidden" id="textfileId_'+total_element_text+'" name="textfileId_1" value="0"></label></section></div>');
            break;
            case 'image':
                var total_element_image = $('#total_element_image').val();
                total_element_image = parseInt(total_element_image) + 1;
                $('#total_element_image').val(total_element_image);
                $('#taskstepsdiv').append('<div class="col-md-12 col-sm-12 col-lg-12" id="divProImg_'+total_element_image+'"><section class="col col-md-12 text-center"><label class="label"><strong>Image Preview</strong></label><img width="400" height="300" src="https://via.placeholder.com/640x360.png?text=Image+Preview"  id="blah_'+total_element_image+'" alt="img"></section><section class="col col-md-12"><label class="label">Image<span class="error">*</span></label><div class="input input-file"><input type="hidden" name="imagefileId_'+total_element_image+'" value="0"><span class="button"><input type="file" class="textClassStep valid" name="fileImage_'+total_element_image+'" id="file_'+total_element_image+'" onchange="readURL(this,'+total_element_image+');this.parentNode.nextSibling.value = this.value" accept="image/*">Browse</span><input type="text" readonly=""></div></section></div>');
            break;
            case 'video':
                var total_element_video = $('#total_element_video').val();
                total_element_video = parseInt(total_element_video) + 1;
                $('#total_element_video').val(total_element_video);
                $('#taskstepsdiv').append('<div class="col-md-12 col-sm-12 col-lg-12" id="divProVideo_'+total_element_video+'"><section class="col col-md-12 text-center"><label class="label"><strong>Video Preview</strong></label><div id="privew'+total_element_video+'"><img  width="400" height="300" src="https://via.placeholder.com/640x360.png?text=Video+Preview"  alt="img"></div></section><section class="col col-md-12"><label class="label">Video<span class="error">*</span></label><div class="input input-file"><input type="hidden" name="videofileId_'+total_element_video+'" value="0"><span class="button"><input type="file" class="textClassStep" name="videofile_'+total_element_video+'" id="videofile_'+total_element_video+'" onchange="filePreviewMain(this,'+total_element_video+');this.parentNode.nextSibling.value = this.value" accept="video/*">Browse</span><input type="text" readonly=""></div></section></div>');
            break;
        }
    }

</script>

