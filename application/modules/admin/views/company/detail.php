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
											<input type="hidden" id="task_id_id" name="task_id_id" value="<?= encoding($task['company_id']); ?>">
											<h3 class="margin-top-0">Company Name  : <a href="javascript:void(0);"> <?= $task['name']; ?> </a></h3>

											<p>Email : <?= $task['email']; ?></p>
											<p>Phone Number : <?= $task['phone_number']; ?></p>
											<p>Fax Number : <?= $task['fax_number']; ?></p>
											<p>License </p>
											
											
										<?php
											
 										 if($task['licence'] == "" || $task['licence'] == NULL)
										    {
										        echo '<h4>No License Uploaded Yet</h4>';
										    }else{										    $mediapath = base_url('uploads/company/').$task['licence'];
											
											    if(@is_array(getimagesize($mediapath))){
                                                    $image = 1;
                                                } else {
                                                    $image = 0;
                                                }
										
										if($image == 0)
										{ ?>
										
                                        <div>
                                        <object style="width:100%; overflow:hidden;" src="<?php echo base_url('uploads/company/').$task['licence']; ?>"><iframe style="width:450px;height:400px;" src="https://docs.google.com/viewer?url=<?php echo base_url('uploads/company/').$task['licence']; ?>&embedded=true"></iframe></object>
                                        </div>
										    
										<?php }else{ ?>
										
										                                        <div>
                                            <img style="height:300px;" src="<?php echo $mediapath; ?>"/>
                                        </div>
										    
										<?php }
										   
										}
										?>
											
											
											
											



											<p>Insurance Certificate </p>
											
											
										<?php
											
							if($task['insurence_certificate'] == "" || $task['insurence_certificate'] == NULL)
											{
											    echo '<h4>No Insurance Certificate Uploaded Yet</h4>';
											}else{										$mediapath = base_url('uploads/company/').$task['insurence_certificate'];
											
											    if(@is_array(getimagesize($mediapath))){
                                                    $image = 1;
                                                } else {
                                                    $image = 0;
                                                }
                                                
                                        
                                                
                                        if($image == 0)
                                        { ?>
                                        <div>
                                        <object style="width:100%; overflow:hidden;" src="<?php echo base_url('uploads/company/').$task['insurence_certificate']; ?>"><iframe style="width:450px;height:400px;" src="https://docs.google.com/viewer?url=<?php echo base_url('uploads/company/').$task['insurence_certificate']; ?>&embedded=true"></iframe></object>
                                        </div>
                                             
                                        <?php }else{ ?>
                                        
                                        <div>
                                            <img style="height:300px;" src="<?php echo $mediapath; ?>"/>
                                        </div>
                                            
                                        <?php }
                                            }
                                        ?>

											

                                        
                                        

                                        
                                        
                                        </div>									
											<hr>
											<p>
												<ul class="list-inline padding-10 pull-right">
													<li style="display:none;">
													<i class="fa fa-edit"></i>
													<a href="<?= base_url().'admin/company/edit/'.encoding($task['company_id']);?>" > Edit</a>
													</li>
												</ul>
											</p>
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
					Step Manage
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
											<textarea rows="4" class="textClassStep" name="textfile_1" id="textfile_1" placeholder="Enter Task instuctions step" maxlength="400"></textarea>
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