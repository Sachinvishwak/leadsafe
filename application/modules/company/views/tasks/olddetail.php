olddetail.php<?php $backend_assets=base_url().'backend_assets/'; ?>

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
											<h3 class="margin-top-0"><a href="javascript:void(0);"> <?= $task['name']; ?> </a></h3>
											
											<hr>
											<p><?= $task['description']; ?></p>
										</div>
									
									
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-12 col-md-12 col-lg-12">
						<!-- data -->
							<div class="row">
								<div class="col-sm-12">
									<div class="padding-10">
										<ul class="nav nav-tabs tabs-pull-left">
											<li class="active">
												<a href="#a1" data-toggle="tab">Text</a>
											</li>

											<li>
												<a href="#a2" data-toggle="tab">Image</a>
											</li>
											<li>
												<a href="#a3" data-toggle="tab">Video</a>
											</li>
											
										</ul>

										<div class="tab-content padding-top-10">
											<div class="tab-pane fade in active" id="a1">
												<div class="row">
													<div class="col-xs-12 col-sm-12">	
														
											<?php if(!empty($texts)): ?><ol class="list-styled">
											<?php foreach ($texts as $t => $text) { ?>
												<li>
													<p class="text-muted">
														<?= $text->description; ?> 
														<ul class="list-inline padding-10">
															<li>
															<i class="fa fa-trash"></i>
															<a href="javascript:void(0);">Delete</a>
															</li>
															<!-- <li>
															<i class="fa fa-comments"></i>
															<a href="javascript:void(0);"> 38 Comments </a>
															</li> -->
															</ul>
													</p>
												</li>
											<?php } ?>
											
											</ol> 

											<?php else: ?>
												<center><strong>No record found.</strong></center>
											<?php endif; ?>
											
											</div>
												</div>
											</div>
											<div class="tab-pane fade" id="a2">
												<div class="row">
													<?php if(!empty($images)): ?>
														<?php foreach ($images as $m => $image) { ?>
											
															<div class="col-md-4">
															<img width="600" height="400" src="<?= base_url('uploads/task_image/').$image->file; ?>" class="img-responsive"  alt="img">
															<ul class="list-inline padding-10">
															<li>
															<i class="fa fa-trash"></i>
															<a href="javascript:void(0);">Delete</a>
															</li>
															<!-- <li>
															<i class="fa fa-comments"></i>
															<a href="javascript:void(0);"> 38 Comments </a>
															</li> -->
															</ul>
															</div>
											<?php } ?>
														<?php else: ?>
													<div class="col-xs-12 col-sm-12">	
														<center><strong>No record found.</strong></center>
													</div>
											<?php endif; ?>
												
												</div>
											</div>
											<div class="tab-pane fade" id="a3">
																								<div class="row">
													<?php if(!empty($videos)): ?>
														<?php foreach ($videos as $v => $video) { ?>
											
															<div class="col-md-4 padding-5">
															
															<video width="100%" controls>
  <source src="<?= base_url('uploads/task_video/').$video->file; ?>" type="video/mp4">
  <source src="<?= base_url('uploads/task_video/').$video->file; ?>" type="video/ogg">
  Your browser does not support HTML video.
</video>
															<ul class="list-inline padding-10">
															<li>
															<i class="fa fa-trash"></i>
															<a href="javascript:void(0);">Delete</a>
															</li>
															<!-- <li>
															<i class="fa fa-comments"></i>
															<a href="javascript:void(0);"> 38 Comments </a>
															</li> -->
															</ul>
															</div>
											<?php } ?>
														<?php else: ?>
													<div class="col-xs-12 col-sm-12">	
														<center><strong>No record found.</strong></center>
													</div>
											<?php endif; ?>
												
												</div>
											</div>
										

		
											
											<!-- end tab -->
										</div>
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
<!-- end row-->
<!-- END ROW -->
