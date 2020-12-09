<!-- end widget grid-->
  <link rel="stylesheet" href="<?php echo base_url('backend_assets/css/massagechat.css')?>"/>

<!--<input type="hidden" id="user_id" value="<?= $_SESSION['company_sess']['id']; ?>" />-->
<!--<input type="hidden" id="reciever_id" value="" />-->
<!--<input type="hidden" id="reciever_type" value="" />-->
<!--<input type="hidden" id="user_type" value="company" />-->

	<style>
		.inbox_msg{
			border: 1px solid #205569;
		}
		.mesgs{
			border:none!important;
		}
	</style>

	<div class="container" style="width:100%;max-width:80%;">
		<div class="messaging">
			<div class="inbox_msg">
				<div class="inbox_people">
				<div class="headind_srch">
					<div class="recent_heading">
					<h4><strong>Members</strong></h4>
					</div>
					<div class="srch_bar">
						<div class="stylish-input-group">
							<input  onkeyup="searchchatpeople()" value="" id="people_search_box" type="text" class="search-bar"  placeholder=" Search .. " >
							<span class="input-group-addon">
								<!-- <button  type="button"> <i class="fa fa-search" aria-hidden="true"></i> </button> -->
								<select class="form-control" onchange="searchchatpeople()" id="project_selection">
									<option value="0">Select Project</option>
									<?php
									foreach($project_list as $project)
									{ ?>
										<option value="<?php echo $project->id; ?>"><?php echo $project->name; ?></option>
									<?php }
									?>
								</select>
							</span> 
						</div>
					</div>
				</div>
				<style>
					.chubbybaby { font-weight: bold; }
				</style>
				<div id="chat_people" class="inbox_chat">

					<div  class="container">
						<mark class="container">Members</mark>
					</div>
					<?php
						foreach($grouplist as $key1=>$groups)
						{  
						?>
							<!-- <div  class="chat_list" id="<?php echo "group".$key1; ?>" onclick="getGroupMessage('<?php echo $groups->id ?>','company')">
								<div class="chat_people">
									<div  class="chat_img"> <img style="height: 37px;width: 50px;border-radius: 50%;" src="https://www.tirpude.edu.in/wp-content/plugins/profilegrid-user-profiles-groups-and-communities/public/partials/images/default-group.png" alt="<?php echo $groups->name; ?>"></div>
									<div class="chat_ib">
									<h5 ><?php echo $groups->name; ?> <span class="chat_date"></span></h5>
									</div>
								</div>
							</div> -->
						<?php }
					?>

					<?php
						foreach($peopleList as $key=>$people)
						{  
							if($people->group_id==0){
								$chat_dp = $people->profile_photo;
								if($people->reciever_type =="crew")
								{
									$img = 'https://ptetutorials.com/images/user-profile.png';
									if($chat_dp!= NULL)
									{
										$img = base_url('uploads/crew/').$chat_dp;
									} 
								}
								if($people->reciever_type =="leadcontractor")
								{
									$img = 'https://ptetutorials.com/images/user-profile.png';
									if($chat_dp != NULL)
									{
										$img = base_url('uploads/contractor/').$chat_dp;
									}  
								}?>

								<div  class="chat_list" id="<?php echo $key; ?>" onclick="doActive('<?php echo $people->id; ?>','<?php echo $people->reciever_type; ?>')">
									<div class="chat_people">
										<div  class="chat_img"> <img style="height: 37px;width: 50px;border-radius: 50%;" src="<?php echo $img;?>" alt="<?php echo $people->name; ?>"></div>
										<div class="chat_ib">
										<h5 ><?php echo $people->name; ?> <span class="chat_date">position : <?php echo $people->reciever_type; ?></span></h5>
										</div>
									</div>
								</div>

								<?php
							}
						?>
						<?php }
					
					?>

					<div  class="container">
						<mark>Groups</mark>
					</div>

					<?php
						foreach($peopleList as $key=>$people)
						{  
							if($people->group_id!==0){
								$img = "https://www.tirpude.edu.in/wp-content/plugins/profilegrid-user-profiles-groups-and-communities/public/partials/images/default-group.png";
								$chat_dp = $people->profile_photo;
								if($people->reciever_type =="crew")
								{
									$img = 'https://ptetutorials.com/images/user-profile.png';
									if($chat_dp!= NULL)
									{
										$img = base_url('uploads/crew/').$chat_dp;
									}
								}
								if($people->reciever_type =="leadcontractor")
								{
									$img = 'https://ptetutorials.com/images/user-profile.png';
									if($chat_dp != NULL)
									{
										$img = base_url('uploads/contractor/').$chat_dp;
									}  
								}?>

								<div  class="chat_list" id="<?php echo $key; ?>" onclick="getGroupMessage('<?php echo $people->group_id ?>','company')">
									<div class="chat_people">
										<div  class="chat_img"> <img style="height: 37px;width: 50px;border-radius: 50%;" src="<?php echo $img;?>" alt="<?php echo $people->name; ?>"></div>
										<div class="chat_ib">
										<h5 ><?php echo $people->name; ?> <span class="chat_date">position : <?php echo $people->reciever_type; ?></span></h5>
										</div>
									</div>
								</div>

								<?php
							}
						?>
						<?php }
					
					?>
				</div>
				</div>
				<div class="mesgs">
				<div class="msg_history" id="yourDivID">
						<center>
							<img  style="max-width: 46%;" src="https://cdn.freebiesupply.com/logos/large/2x/hello-design-logo-png-transparent.png"/>
							<div class="text-center">
								<h4>Please Select Member To Start Conversation</h4>
							</div>
						</center>
				</div>
				<div class="type_msg">
					<div class="input_msg_write">
					<input type="text" class="write_msg" id="message_input_box" placeholder="Type a message"/>
					<form style="display:none;" title="multiple file uplaod" id="message-form-multi" class="message-form-multi" method="post" enctype='multipart/form-data'>
						<input id="file1" type="file" name="attachment[]" class="fa fa-paperclip" aria-hidden="true" multiple="multiple"/>
						<input type="hidden" name="file_status" value=""/>
						<input type="hidden" id="user_id1" name="user_id" value="<?= $_SESSION['company_sess']['id']; ?>" />
						<input type="hidden" id="reciever_id1" name="reciever_id" value="" />
						<input type="text" name="message" value="" />
						<input type="hidden" id="reciever_type1" name="reciever_type" value="" />
						<input type="hidden" id="user_type1" value="company" />
						<input type="hidden" name="group_id" id="group_id1" value="" />
					</form>

					<form id="message-form" class="message-form" method="post" enctype='multipart/form-data'>
						<input id="file" type="file" name="attachment" class="fa fa-paperclip" aria-hidden="true" />
						<input type="hidden" name="file_status" value=""/>
						<input type="hidden" id="user_id" name="user_id" value="<?= $_SESSION['company_sess']['id']; ?>" />
						<input type="hidden" id="reciever_id" name="reciever_id" value="" />
						<input type="text" name="message" value="" />
						<input type="hidden" id="reciever_type" name="reciever_type" value="" />
						<input type="hidden" id="user_type" value="company" />
						
						<input type="hidden" name="group_id" id="group_id" value="" />
					</form>
					<button class="msg_send_btn" onclick="sendMessageToDatabase()" type="button"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
					</div>
				</div>
				</div>
			</div>
		</div>
	</div>
    <script>
		$("input[name='attachment']").change(function(){
				$('#message-form').submit();
		});
		
		$('#message-form').submit(function(e){
			e.preventDefault();
			var form = $(this);
			$.ajax({
				url:"<?php echo site_url('chat/Api/sendMessageToDatabaseSingle'); ?>",
				type: "post",
				dataType: 'json',
				data: new FormData(this),
				processData: false,
				contentType: false,
				beforeSend  : function() {
					preLoadshow(true);
				}, 
				success:function(res){
					if(res.message){
						alert(res.message);
					}
					preLoadshow(false);
					//getTaskDocumentByName($('#document_name').val());
					$('#file').val('');
					getsentmessagestask();
					scrolled=false;
				}
			});
		})

		$("input[name='attachment[]']").change(function(){
				$('#message-form-multi').submit();
		});

		$('#message-form-multi').submit(function(e){
			e.preventDefault();
			var form = $(this);
			$.ajax({
				url:"<?php echo site_url('chat/Api/sendMessageToDatabaseMultiWeb'); ?>",
				type: "post",
				dataType: 'json',
				data: new FormData(this),
				processData: false,
				contentType: false,
				beforeSend  : function() {
					preLoadshow(true);
				}, 
				success:function(res){
					if(res.message){
						alert(res.message);
					}
					preLoadshow(false);
					$('#file1').val('');
					getsentmessagestask();
					scrolled=false;
				}
			});
		})
		
		$(document).on("keyup", "#message_input_box", function(e){
			if (e.key === 'Enter' || e.keyCode === 13){
				sendMessageToDatabase();
			}
		});
		
		function searchchatpeople()
		{
			let value = $('#people_search_box').val()
			let projectId = $('#project_selection').val();
			$.ajax({
				url:"<?php echo site_url('company/Admin/searchpeoplechat'); ?>",
				type: "post",
				dataType: 'json',
				data: {value:value,project_id:projectId},
				beforeSend  : function() {
					preLoadshow(true);
				}, 
				success:function(res){
					console.log(res);
					$("#chat_people").html(res)
					preLoadshow(false);
				}
			});
		}
		
		function startmessageget()
		{
			setInterval(function()
			{ 
				getsentmessagestask();
			}, 3000);
		}
			
		function getsentmessagestask(){
			reciever_id = $('#reciever_id').val();
			reciever_type = $('#reciever_type').val();
			user_type = $('#user_type').val();
			user_id = $('#user_id').val();
			
			group_id = $('#group_id').val();
			
			if(reciever_id != "")
			{
				$.ajax({
					url:"<?php echo site_url('chat/Api/getMessageToDatabaseSingle'); ?>",
					type: "post",
					dataType: 'json',
					data: {user_id:user_id,reciever_id:reciever_id,reciever_type:reciever_type,user_type:user_type,group_id:""},
					success:function(res){
						if(res.count <= 0)
						{
							$('.msg_history').html('<center><img style="max-width: 46%;" src="https://cdn.freebiesupply.com/logos/large/2x/hello-design-logo-png-transparent.png"/><div class="text-center"><h4>Say Hii To Start Conversation</h4></div></center>')
						}else{
							$('.msg_history').html(res.messages)
						}
						
					}
				});
			}
			
			if(group_id != "")
			{
				$.ajax({
					url:"<?php echo site_url('chat/Api/getMessageToDatabaseSingle'); ?>",
					type: "post",
					dataType: 'json',
					data: {user_id:user_id,reciever_id:reciever_id,reciever_type:reciever_type,user_type:user_type,group_id:group_id},
					success:function(res){
						if(res.count <= 0)
						{
							$('.msg_history').html('<center><img style="max-width: 46%;" src="https://cdn.freebiesupply.com/logos/large/2x/hello-design-logo-png-transparent.png"/><div class="text-center"><h4>Say Hii To Start Conversation</h4></div></center>')
						}else{
							$('.msg_history').html(res.messages)
						}
						
					}
				});
			}
			
		}
		
		function doActive(reciever_id,reciever_type)
		{
			$('#message-form-multi').css('display','none');
			$('.chat_list').removeClass('active_chat');
			$('#'+reciever_id).addClass('active_chat');
			$('#reciever_id').val(reciever_id);
			$('#reciever_type').val(reciever_type);
			$('#group_id').val("");
			$('#group_id1').val("");
			$('.msg_history').html('<center><img style="width:20%" src="https://frilysboutique.co.uk/wp-content/plugins/woocommerce-bookings-filters//assets/image/login-load.gif"/><div class="text-center"><h4>Loading ..</h4></div></center>')
			startmessageget();
		}
		
		
		function getGroupMessage(group_id,role)
		{
			$('#message-form-multi').css('display','block');
			$('.chat_list').removeClass('active_chat');
			$('#group'+group_id).addClass('active_chat');
			$('#reciever_id').val("");
			$('#reciever_type').val("");
			$('#reciever_id1').val("");
			$('#reciever_type1').val("");
			$('#group_id').val(group_id);
			$('#group_id1').val(group_id);
			
			$('.msg_history').html('<center><img style="width:20%" src="https://frilysboutique.co.uk/wp-content/plugins/woocommerce-bookings-filters//assets/image/login-load.gif"/><div class="text-center"><h4>Loading ..</h4></div></center>')
			startmessageget();
		}

		function sendMessageToDatabase()
		{
			user_id = $('#user_id').val();
			reciever_id = $('#reciever_id').val();
			group_id = $('#group_id').val();
			$('#reciever_type').val(reciever_type);
			message = $('#message_input_box').val();
			if(message == "")
			{
				swal('You can not send blank message');
			}else{
				$.ajax({
					url:"<?php echo site_url('chat/Api/sendMessageToDatabaseSingle'); ?>",
					type: "post",
					data: {message:message,user_id:user_id,reciever_id:reciever_id,reciever_type:reciever_type,user_type:"company",group_id:group_id},
					beforeSend  : function() {
						preLoadshow(true);
					}, 
					success:function(res){
						$('#message_input_box').val("");
						startmessageget();
						preLoadshow(false);
						scrolled=false;
					}
				});   
			}
		}
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
			
	</script>
