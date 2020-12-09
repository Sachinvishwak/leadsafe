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
					<span class="widget-icon"> <i class="fa fa-tags"></i> </span>
					<h2>Tasks List</h2>
				</header>
				<!-- widget div-->
				<div>
				    <!--<a type="button" href="<?php echo site_url('admin/Company/companytask'); ?>" class="btn bg-green btn-flat " style="font-weight: bold;background-color: #3c8dbc!important;">-->
				    <!--    EXPORT ADMIN TASKS-->
				    <!--</a>-->
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->
					</div>
					<!-- end widget edit box -->
					<!-- widget content -->
					<!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist" style="margin-bottom: 28px;border:none!important;">
                      <li class="active"><a href="#home" role="tab" data-toggle="tab">Prepopulated(Active) Tasks</a></li>
                      <li><a href="#admin_tasks" role="tab" data-toggle="tab">Other users(Inactive) Tasks</a></li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                      <div class="tab-pane active" id="home">
                          <div class="widget-body padding">
					    <select id='filter_value' class="form-control input-sm" style="width:300px!important;float:left;margin-right:10px;">
                            <option value='-1' <?php if(isset($_SESSION['filter_value']) && $_SESSION['filter_value'] == -1) echo 'selected'; ?> >All Tasks</option>
                            <option value='1' <?php if(isset($_SESSION['filter_value']) && $_SESSION['filter_value'] == 1) echo 'selected'; ?>>Imported From Admin</option>
                            <option value='0' <?php if(isset($_SESSION['filter_value']) && $_SESSION['filter_value'] == 0) echo 'selected'; ?>>Created By Super Admin</option>
                        </select>
                        
                        
                        <select id='status_filter' class="form-control input-sm" style="width:300px!important;">
                            <option value='-1' <?php if(isset($_SESSION['status_value']) && $_SESSION['status_value'] == -1) echo 'selected'; ?> >All Tasks</option>
                            <option value='1' <?php if(isset($_SESSION['status_value']) && $_SESSION['status_value'] == 1) echo 'selected'; ?>>Published</option>
                            <option value='0' <?php if(isset($_SESSION['status_value']) && $_SESSION['status_value'] == 0) echo 'selected'; ?>>Unpublished</option>
                        </select>

						<div class="table-responsive">
							<table  class="table table-striped table-bordered table-hover dataTables-example-list" width="100%" data-list-url = "adminapi/tasks/list" data-id ="" data-no-record-found = "">
								<thead>			                
									<tr>
										<th data-hide="phone">ID</th>
										<th data-hide="phone,tablet">Task Name</th>
										<th data-hide="phone,tablet">Description</th>
										<th data-hide="phone,tablet">Uploaded Media</th>
										<th data-hide="phone,tablet" style="width:14%!important;">Created By</th>
										<th data-hide="phone,tablet">Status</th>
										<th style="width:14%!important;" data-hide="phone,tablet">Action</th>
									</tr>
								</thead>
								<tbody>			
								</tbody>
							</table>
						</div>
					</div>
                      </div>
                      <div class="tab-pane" id="admin_tasks">
                          <div class="table-responsive">
							<table  class="table table-striped table-bordered table-hover" id="example9" width="100%">
								<thead>			                
									<tr>
										<th data-hide="phone">ID</th>
										<th data-hide="phone,tablet">Task Name</th>
										<th data-hide="phone,tablet">Description</th>
										<th data-hide="phone,tablet">Company Name</th>
										<th data-hide="phone,tablet">Uploaded Media</th>
										<th style="width:14%!important;" data-hide="phone,tablet">Action</th>
									</tr>
								</thead>
								<tbody>			
								</tbody>
							</table>
						</div>
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



<script>
    $('#filter_value').change(function(){
        filter_value = $("#filter_value").val();
        $.ajax({
            url:"<?php echo site_url('adminapi/tasks/setSession') ?>",
            type: "post",
            "headers" : { 'authToken':authToken},
            data: {filter_value: filter_value},
            beforeSend  : function() {
                preLoadshow(true);
            },
            success:function(result){
                preLoadshow(false);
                $('.dataTables-example-list').DataTable().draw();
            }
        });
    });
    $('#status_filter').change(function(){
        status_value = $("#status_filter").val();
        $.ajax({
            url:"<?php echo site_url('adminapi/tasks/setSessionStatus') ?>",
            type: "post",
            "headers" : { 'authToken':authToken},
            data: {status_value: status_value},
            beforeSend  : function() {
                preLoadshow(true);
            },
            success:function(result){
                preLoadshow(false);
                $('.dataTables-example-list').DataTable().draw();
            }
        });
    });
    $(document).ready(function() {
        $('#dataTables_wrapper div div').text("hello");
        $('#example9').DataTable( {
            "processing": true,
            "serverSide": true,
            "order":[],  
            "ajax":{  
                    url:"<?php echo site_url('adminapi/Company/tasklist'); ?>", 
                    type:"POST",
                    headers : { 'authToken':authToken},
               },  
            "columnDefs":[  
                {  
                         "targets":[0, 3, 4],  
                         "orderable":false,  
                },  
            ],  
        } );
    } );
    function superAdminimport(taskId)
    {
        $.ajax({
            url:"<?php echo site_url('admin/Company/superAdminimport') ?>",
            type: "post", //request type,
            dataType: 'json',
            data: {taskId: taskId},
            success:function(result){
                toastr.clear();
                toastr.success('Admin Task Imported Successfully.', 'Success', {timeOut: 3000});
                setTimeout(function(){ 
                    window.location.reload(); 
                }, 3000);
            }
         });
    }
    
    var base_url  = $('body').data('base-url'); // Base url
    var authToken = $('body').data('auth-url'); // Base url
    var errorClass    = 'invalid';
    var errorElement  = 'em';
    //confirmAction
    function confirmAction1(e){
        toastr.clear();
        swal({
        title               : "Are you sure?",
        text                : $(e).data('message'),
        type                : "warning",
        showCancelButton    : true,
        confirmButtonClass  : "btn-danger",
        confirmButtonText   : "Yes",
        cancelButtonText    : "No",
        closeOnConfirm      : true,
        closeOnCancel       : true,
        // showLoaderOnConfirm: true
      },
      function(isConfirm) {
        if (isConfirm) {
          /*ajax*/
          $.ajax({
                  type          : "POST",
                  url           : base_url+$(e).data('url'),
                  data          : {id:$(e).data('id')},
                  headers       : { 'authToken':authToken},
                  cache         : false,
                  beforeSend    : function() {
                    preLoadshow(true);
                  },     
                  success       : function (res) {
                    preLoadshow(false);
                    if(res.status=='success'){
                      toastr.success(res.message, 'Success', {timeOut: 3000});
                       if($(e).data('list')==1){
                           $('#example9').DataTable().ajax.reload();
                           $('.dataTables-example-list').DataTable().ajax.reload();
                          
                       }else{
                          setTimeout(function(){window.location.reload(); },2000);
                       }
                    }else{
                      toastr.error(res.message, 'Alert!', {timeOut: 5000});
                    }             
                  }
                });
          /*ajax*/
        } else {
        //swal("Cancelled", "Your Process has been Cancelled", "error");
        }
      });
    }
    
</script>
