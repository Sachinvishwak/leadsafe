var base_url  = $('body').data('base-url'); // Base url
var authToken = $('body').data('auth-url'); // Base url
var errorClass    = 'invalid';
var errorElement  = 'em';
//confirmAction
function confirmAction(e){
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
                   //  swal("Success", "Your process  has been successfully done.", "success");
                   if($(e).data('list')==1){
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
function readURL(input,i) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    
    reader.onload = function(e) {
      $('#blah_'+i).attr('src', e.target.result);
    }
    
    reader.readAsDataURL(input.files[0]); // convert to base64 string
  }
}
