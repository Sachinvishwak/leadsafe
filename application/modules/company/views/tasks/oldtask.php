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
							<section class="col col-md-12">
								<label class="label">Task name</label>
								<label class="input"> <i class="icon-append fa fa-tag"></i>
									<input type="text" name="name" placeholder="Name" maxlength="30" size="30"  >
									
								</label>
							</section>				
						</div>
						<section>
						<label class="label">Description</label>
						<label class="textarea" >
						<textarea rows="3" name="description" placeholder="Description"></textarea>
						</label>
						
						</section>				
						
					</fieldset>	
					
					<fieldset>
						<header class="text-center">
					<b>Steps To Complete Tasks</b>
					<input type="hidden" name="total_element_text" id="total_element_text" value="0" >
					<input type="hidden" name="total_element_image" id="total_element_image" value="0" >
					<input type="hidden" name="total_element_video" id="total_element_video" value="0" >
					</header>

						<header>
					<b>TEXT</b>
					
					</header>
					<!-- add  -->
						<div class="row textContainer">

							<div class="col-md-12 col-sm-12 col-lg-12 elementPro" id="divPro_1">
								<section class="col col-md-12">
									<label class="label"><strong>Step Text-1</strong><!-- 	<a href="javascript:void(0);" class="btn btn-default btn-circle pull-right removePro"><i class="fa fa-times" aria-hidden="true"></i></a> --></label>
								</section>
								<!-- add -->
								<section class="col col-md-12">
									<label class="label">TEXT<span class="error">*</span></label>
									<label class="textarea">
											<textarea rows="4" class="textClass" name="textfile_1" placeholder="Enter Task instuctions step" maxlength="400"></textarea>
											<input type="hidden" name="textfileId_1" value="0">
										</label>
								</section>
								
							</div>
						</div>
						<section>
							<a href="javascript:void(0);" class="btn btn-default btn-circle pull-right addPro"><i class="fa fa-plus" aria-hidden="true"></i></a>
						</section>
						<!-- add  -->
						<!-- <section>
						<label class="label">TEXT</label>
							<label class="textarea">

								<textarea rows="4" name="textfile" placeholder="Enter Task instuctions step"></textarea>

							</label>							
						</section>			 -->

					</fieldset>		
<!-- image -->
<fieldset>
		<header>
					<b>IMAGE</b>
					
					</header>
	<div class="row imageContainer">
		<div class="col-md-12 col-sm-12 col-lg-12 elementProImg" id="divProImg_1">
				<section class="col col-md-12">
									<label class="label"><strong>Step Image-1</strong><!-- 	<a href="javascript:void(0);" class="btn btn-default btn-circle pull-right removeProImage"><i class="fa fa-times" aria-hidden="true"></i></a> --></label>
								</section>
								<!-- add -->
				<section class="col col-6 text-center">
					<img height="120" width="120" src="<?php echo $backend_assets.'img/avatars/sunny-big.png';?>"  id="blah_1" alt="img">

				</section>
				<section class="col col-6">
					<div class="input input-file">
						<input type="hidden" name="imagefileId_1" value="0">
									<span class="button"><input type="file" name="fileImage_1" id="file_1" onchange="readURL(this,1);this.parentNode.nextSibling.value = this.value" accept="image/*">Browse</span><input type="text" readonly="">
								</div>
				</section>
				
				
		

		</div>
	</div>
	<section>
							<a href="javascript:void(0);" class="btn btn-default btn-circle pull-right addProImage"><i class="fa fa-plus" aria-hidden="true"></i></a>
						</section>
</fieldset>
<!-- image -->
<!-- video -->
<fieldset>
		<header>
					<b>VIDEO</b>
					
					</header>
	<div class="row videoContainer">
		<div class="col-md-12 col-sm-12 col-lg-12 elementProVideo" id="divProVideo_1">
				<section class="col col-md-12">
									<label class="label"><strong>Step video-1</strong><!-- 	<a href="javascript:void(0);" class="btn btn-default btn-circle pull-right removeProImage"><i class="fa fa-times" aria-hidden="true"></i></a> --></label>
								</section>
								<!-- add -->
				<section class="col col-6 text-center">
					<div id="privew1"><img height="120" width="120" src="<?php echo $backend_assets.'img/avatars/sunny-big.png';?>"  alt="img"></div>
					

				</section>
				<section class="col col-6">
					<div class="input input-file">
						<input type="hidden" name="videofileId_1" value="0"><span class="button"><input type="file" name="videofile_1" id="videofile_1" onchange="filePreviewMain(this,1);this.parentNode.nextSibling.value = this.value" accept="video/*">Browse</span><input type="text" readonly="">
								</div>
				</section>
				
				
		

		</div>
	</div>
		<section>
			<a href="javascript:void(0);" class="btn btn-default btn-circle pull-right addProVideo"><i class="fa fa-plus" aria-hidden="true"></i></a>
		</section>
</fieldset>
<!-- video -->
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