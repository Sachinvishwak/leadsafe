<section id="widget-grid" class="">
	<!-- row -->
	<div class="row">
		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false" data-widget-editbutton="false" data-widget-deletebutton="false">
				<!-- widget options:
				usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">
				data-widget-colorbutton="false"
				data-widget-editbutton="false"
				data-widget-togglebutton="false"
				data-widget-deletebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-custombutton="false"
				data-widget-collapsed="true"
				data-widget-sortable="false"
				-->
				<header>
					<span class="widget-icon"> <i class="fa fa-users"></i> </span>
					<h2>Client</h2>
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
							<table  class="table table-striped table-bordered table-hover dataTables-example-list" width="100%" data-list-url = "company/Clientapi/list" data-id ="" data-no-record-found = "">
								<thead>			                
									<tr>
										<th data-hide="phone">ID</th>
										<th data-hide="phone,tablet">Name</th>
										<th data-hide="phone,tablet">Email</th>
										<th data-hide="phone,tablet">Address</th>
										<th data-hide="phone,tablet">Phone Number</th>
										<th data-hide="phone,tablet">Projects</th>
										<th data-hide="phone,tablet">Action</th>
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

<div class="modal" tabindex="-1" role="dialog" id="inviteclientmodal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Invite Client</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="height:480px!important;">
        <div class="col-xs-12" style="height:100%!important; overflow-y:scroll;">
            <div class="col-md-12" >
                <form id="inviteclientform"  method="post">
                <div id="field">
                    <div id="field0">
                    <!-- Text input-->
                    <div class="form-group">
                      <label class="col-md-4 control-label" for="action_id">Client Name</label>  
                      <div class="col-md-5">
                        <input id="client_name" name="client_name[]" type="text" placeholder="Client Name" class="form-control input-md" required="">
                      </div>
                    </div>
                    <br><br>
                    <!-- Text input-->
                    <div class="form-group">
                      <label class="col-md-4 control-label" for="action_name">Client Email</label>  
                      <div class="col-md-5">
                      <input id="client_email" name="client_email[]" type="email"  placeholder="Client Email" class="form-control input-md" required="">
                        
                      </div>
                    </div>
                    <div class="form-group" id="projectList">
                      <label class="col-md-4 control-label" for="action_name">Project</label>  
                      <div class="col-md-5">
                        <select name="projectId[]" class="form-control">
						    <?php
						        foreach($projectList as $project)
						        {
						            echo '<option value='.$project->id.'>'.$project->name.'</option>';
						        }
						    ?>
						</select>
                        
                      </div>
                    </div>
                    
                    
                    
                    
                    <br><br>
                </div>
                </div>
                
                <!-- Button -->
                <div class="form-group">
              <div class="col-md-4">
                <button id="add-more" name="add-more" class="btn btn-primary">Add More</button>
              </div>
            </div>
            <br><br>
            </div>
            <div class="modal-footer">
            <input type="submit"  class="btn btn-primary" value="Invite"/>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </form>
          </div>
        </div>
      </div>
</div>


<script>
    function InviteClients()
    {
        $('#inviteclientmodal').modal("show");
    }
    
    // function ValidateEmail(inputText)
    // {
    //     var mailformat = /^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/;
    //     if(inputText.value.match(mailformat))
    //     {
    //         //alert("Valid email address!");
    //         document.form1.client_email.focus();
    //         return true;
    //     }
    //     else
    //     {
    //         alert("You have entered an invalid email address!");
    //         //document.form1.client_email.focus();
    //         return false;
    //     }
    // }

    $(document).ready(function () {
    //@naresh action dynamic childs
    var next = 0;
    $("#add-more").click(function(e){
        var projectList = $('#projectList').html();
        e.preventDefault();
        var addto = "#field" + next;
        var addRemove = "#field" + (next);
        next = next + 1;
        var newIn = ' <div id="field'+ next +'" name="field'+ next +'"><!-- Text input--><div class="form-group"> <label class="col-md-4 control-label" for="action_id">Client Name</label> <div class="col-md-5"> <input id="client_name" name="client_name[]" type="text" placeholder="Client Name" class="form-control input-md" required=""> </div></div><br><br> <!-- Text input--><div class="form-group"> <label class="col-md-4 control-label" for="action_name">Client Email</label> <div class="col-md-5"> <input id="cleint_email" name="client_email[]" type="text" placeholder="Client Email" class="form-control input-md" required=""> </div></div>'+projectList+'<br><br></div></div>';
        var newInput = $(newIn);
        var removeBtn = '<button id="remove' + (next - 1) + '" class="btn btn-danger remove-me" >Remove</button></div></div><div id="field">';
        var removeButton = $(removeBtn);
        $(addto).after(newInput);
        $(addRemove).after(removeButton);
        $("#field" + next).attr('data-source',$(addto).attr('data-source'));
        $("#count").val(next);
        $('.remove-me').click(function(e){
            e.preventDefault();
            var fieldNum = this.id.charAt(this.id.length-1);
            var fieldID = "#field" + fieldNum;
            $(this).remove();
            $(fieldID).remove();
        });
    });

});
    
</script>
