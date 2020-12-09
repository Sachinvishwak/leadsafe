var base_url  = $('body').data('base-url'); // Base url
var authToken = $('body').data('auth-url'); // Base url
/*listing service */
var service_list = $('#service_list').DataTable({ 
                    "processing"  : true, //Feature control the processing indicator.
                    "serverSide"  : true, //Feature control DataTables' servermside processing mode.
                    "order"       : [], //Initial no order.
                    "lengthChange": false,
                    "oLanguage"   : {
                      "sEmptyTable" : '<center>No serivce found</center>',
                      "sSearch"     : '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>' 
                    },
                    "oLanguage"   : {
                      "sZeroRecords"  : '<center>No serivce found</center>',
                      "sSearch"       : '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>' 
                    },
                    initComplete  : function () {
                      $('.dataTables_filter input[type="search"]').css({ 'height': '32px'});
                    },
                    // Load data for the table's content from an Ajax source
                    "ajax": {
                        "url"       : base_url+"api/service/addServiceList",
                        "type"      : "POST",
                        "dataType"  : "json",
                        "headers"   : { 'authToken':authToken},
                        "dataSrc"   : function (jsonData) {
                          return jsonData.data;
                        }
                    },
                    //Set column definition initialisation properties.
                    "columnDefs": [
                      { orderable: false, targets: -1 }, 
                    ]
                  });
/*listing service*/
/*listing user*/
var user_list = $('#user_list').DataTable({ 
                  "processing"    : true, //Feature control the processing indicator.
                  "serverSide"    : true, //Feature control DataTables' servermside processing mode.
                  "order"         : [], //Initial no order.
                  "lengthChange"  : false,
                  "oLanguage"     : {
                    "sEmptyTable" : '<center>No customer found</center>',
                    "sSearch"     : '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>' 
                  },
                  "oLanguage"     : {
                    "sZeroRecords"  : '<center>No customer found</center>',
                    "sSearch"       : '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>'
                  },
                  initComplete    : function () {
                    $('.dataTables_filter input[type="search"]').css({ 'height': '32px'});
                  },
                  // Load data for the table's content from an Ajax source
                  "ajax": {
                    "url"       : base_url+"api/users/userList",
                    "type"      : "POST",
                    "dataType"  : "json",
                    "headers"   : { 'authToken':authToken},
                    "dataSrc"   : function (jsonData) {
                      return jsonData.data;
                    }
                  },
                  //Set column definition initialisation properties.
                  "columnDefs"  : [
                    { orderable: false, targets: -1 },
                  ]
                });
/*listing user*/    
function statusChange(e){
  swal({
    title               : "Are you sure?",
    text                :  $(e).data('message'),
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
              type        : "POST",
              url         : base_url+'api/service/changeStatus',
              data        : {
                              srv:$(e).data('serid'),
                              srs:$(e).data('sid')
                            },
              cache       : false,
              beforeSend  : function() {
                preLoadshow(true);
              },     
              success     : function (res) {
                  preLoadshow(false);
                  if(res.status=='success'){
                    toastr.success(res.message, 'Success', {timeOut: 3000});
                    $('#service_list').DataTable().ajax.reload();
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
function statusChangeDetail(e){
  swal({
    title               : "Are you sure?",
    text                :  $(e).data('message'),
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
              url           : base_url+'api/service/changeStatus',
              data          : {srv:$(e).data('serid'),srs:$(e).data('sid')},
              cache         : false,
              beforeSend    : function() {
                preLoadshow(true);
              },     
              success       : function (res) {
                preLoadshow(true);
                if(res.status=='success'){
                  toastr.success(res.message, 'Success', {timeOut: 3000});
                  setTimeout(function(){ location.reload(); },4000);
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
function statusChangeuser(e){
  swal({
    title               : "Are you sure?",
    text                :  $(e).data('message'),
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
              type      : "POST",
              url       : base_url+'api/users/changeStatus',
              data      : {use:$(e).data('useid') },
              cache     : false,
              beforeSend: function() {
                preLoadshow(true);
              },     
              success   : function (res) {
                preLoadshow(false);
                if(res.status=='success'){
                  toastr.success(res.message, 'Success', {timeOut: 3000});
                  //  swal("Success", "Your process  has been successfully done.", "success");
                  $('#user_list').DataTable().ajax.reload();
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
function statusChangeuserDtails(e){
  swal({
    title               : "Are you sure?",
    text                :  $(e).data('message'),
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
              url           : base_url+'api/users/changeStatus',
              data          : {use:$(e).data('useid') },
              cache         : false,
              beforeSend    : function() {
                preLoadshow(true);
              },     
              success       : function (res) {
                 preLoadshow(false);
                if(res.status=='success'){
                  toastr.success(res.message, 'Success', {timeOut: 3000});
                  // swal("Success", "Your process  has been successfully done.", "success");   
                  setTimeout(function(){ location.reload(); },4000);
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