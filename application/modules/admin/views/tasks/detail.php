<?php $backend_assets=base_url().'backend_assets/'; ?>

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
											<p><pre maxlength="400" style="background-color: white;font:icon;background-color: white;"><?= $task['description']; ?></pre>
											
											<?php
											
											    if($task['is_exported'] == 1)
											    {
											        echo '<p>Imported From Admin</p>';
											        $company_data = $this->db->get_where('company',array('company_id'=>$task['company_id']))->result();
											        $company_data = $company_data[0];
											        echo '<p>Company Name: '.$company_data->name.'</p>';
											        $project_data = $this->db->get_where('project',array('id'=>$task['project_id']))->result();
											        $project_data = $project_data[0];
											        echo '<p>Project Name: '.$project_data->name.'</p>';
											    }else{
											        echo '<p>Added By Super Admin</p>';
											    }
											
											?>
											
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