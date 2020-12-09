/***********************/
//taskAddUpdate Add 
$("#taskAddUpdate").validate({// Rules for form validation
  errorClass    : errorClass,
  errorElement  : errorElement,
  highlight: function(element) {
    $(element).parent().removeClass('state-success').addClass("state-error");
    $(element).removeClass('valid');
  },
  unhighlight: function(element) {
    $(element).parent().removeClass("state-error").addClass('state-success');
    $(element).addClass('valid');
  },
  rules : {
    name : {
      required : true
    },
    email : {
      required : true,
      email : true
    },
    // phone_number : {
    //   required : true
    // },
    // fax_number : {
    //   required : true
    // },

  
  },
  // Messages for form validation
  messages : {
    name : {
      required : 'Please enter Company Name'
    },

    email : {
      required : 'Please enter Company Email',
      email : 'Please Enter Valid Company Email'
    },
    
    phone_number : {
      required : 'Please enter 10 digits Phone Number'
    },

    fax_number : {
      required : 'Please enter 10 digits Fax Number'
    },
  },
  onfocusout: injectTrim($.validator.defaults.onfocusout),
  // Do not change code below
  errorPlacement : function(error, element) {
    error.insertAfter(element.parent());
  }
});

// Validation
jQuery.validator.addClassRules('textClass', {
  required: true /*,
        other rules */
});

$(function() {   
  $(document).on('submit', "#taskAddUpdate", function (event) {
    toastr.clear();
   /*   var total_element_text = $(".elementPro").length;
      var total_element_image = $(".elementProImg").length;
      var total_element_video = $(".elementProVideo").length;
    $('#total_element_text').val(total_element_text);
    $('#total_element_image').val(total_element_image);
    $('#total_element_video').val(total_element_video);*/
    event.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        type            : "POST",
        url             : base_url+'adminapi/'+$(this).attr('action'),
        headers         : { 'authToken': authToken },
        data            : formData, //only input
        processData     : false,
        contentType     : false,
        cache           : false,
        beforeSend      : function () {
          preLoadshow(true);
          $('#submit').prop('disabled', true);
        },
        success         : function (res) {
          preLoadshow(false);
          setTimeout(function(){  $('#submit').prop('disabled', false); },4000);
          if(res.status=='success'){
            toastr.success(res.message, 'Success', {timeOut: 3000});
            setTimeout(function(){
               window.location = res.url; 
             // window.location.reload(); 
            },4000);
          }else{
            toastr.error(res.message, 'Alert!', {timeOut: 4000});
          }
        }
    });
  });
  //fromsubmit
});

/*  company */

/***********************/
//companyAddUpdate Add 
$("#companyAddUpdate").validate({// Rules for form validation
  errorClass    : errorClass,
  errorElement  : errorElement,
  highlight: function(element) {
    $(element).parent().removeClass('state-success').addClass("state-error");
    $(element).removeClass('valid');
  },
  unhighlight: function(element) {
    $(element).parent().removeClass("state-error").addClass('state-success');
    $(element).addClass('valid');
  },
  rules : {
    name : {
      required : true
    },
    email : {
      required : true,
      email : true
    },
    // phone_number : {
    //   required : true
    // },
    // fax_number : {
    //   required : true
    // },
    // licence : {
    //   required : true
    // },
    // insurence_certificate : {
    //   required : true
    // },
    
  },
  // Messages for form validation
  messages : {
    name : {
      required : 'Please enter Company Name'
    },

    email : {
      required : 'Please enter Company Email',
      email : 'Please Enter Valid Company Email'
    },
    
    phone_number : {
      required : 'Please enter 10 digits Phone Number'
    },

    fax_number : {
      required : 'Please enter 10 digits Fax Number'
    },
    
    licence : {
      required : 'Please Upload License'
    },
    insurence_certificate : {
      required : 'Please Upload Insurance Certificate'
    },
  },
  onfocusout: injectTrim($.validator.defaults.onfocusout),
  // Do not change code below
  errorPlacement : function(error, element) {
    error.insertAfter(element.parent());
  }
});

// Validation
jQuery.validator.addClassRules('textClass', {
  required: true /*,
        other rules */
});

$(function() {   
  $(document).on('submit', "#companyAddUpdate", function (event) {
    toastr.clear();
   /*   var total_element_text = $(".elementPro").length;
      var total_element_image = $(".elementProImg").length;
      var total_element_video = $(".elementProVideo").length;
    $('#total_element_text').val(total_element_text);
    $('#total_element_image').val(total_element_image);
    $('#total_element_video').val(total_element_video);*/
    event.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        type            : "POST",
        url             : base_url+'adminapi/'+$(this).attr('action'),
        headers         : { 'authToken': authToken },
        data            : formData, //only input
        processData     : false,
        contentType     : false,
        cache           : false,
        beforeSend      : function () {
          preLoadshow(true);
          $('#submit').prop('disabled', true);
        },
        success         : function (res) {
          preLoadshow(false);
          setTimeout(function(){  $('#submit').prop('disabled', false); },4000);
          if(res.status=='success'){
            toastr.success(res.message, 'Success', {timeOut: 3000});
            setTimeout(function(){
               window.location = res.url; 
             // window.location.reload(); 
            },4000);
          }else{
            toastr.error(res.message, 'Alert!', {timeOut: 4000});
          }
        }
    });
  });
  //fromsubmit
});

/* end */

/***************************************************/
$(document).ready(function(){
 // Add new element
  $(".addPro").click(function(){
    // Finding total number of elements added
    var total_element = Number($(".elementPro").length);
    // last <div> with element class id
    var lastid        = $(".elementPro:last").attr("id");
    var split_id      = lastid.split("_");
    var nextindex     = Number(split_id[1]) + 1;
    var max           = 10;
    // Check total number elements
    if(total_element < max){
      // Adding new div container after last occurance of element class
      $(".elementPro:last").after('<div class="col-md-12 col-sm-12 col-lg-12 elementPro" id="divPro_'+ nextindex +'"></div>');
      $("#divPro_" + nextindex).append('<section class="col col-md-12"><label class="label"><strong>Step Text-'+ nextindex +'</strong><a href="javascript:void(0);" id="remove_' + nextindex + '" class="btn btn-default btn-circle btn-danger pull-right removePro"><i class="fa fa-times" aria-hidden="true"></i></a></label></section><section class="col col-md-12"><label class="label">TEXT<span class="error">*</span></label><label class="textarea"><textarea rows="4" class="textClass" name="textfile_'+nextindex+'" placeholder="Enter Task instuctions step" maxlength="400"></textarea><input type="hidden" name="textfileId_'+nextindex+'" value="0"></label></section>');
    }else{
      toastr.error('text step max limit is 10.', 'Alert!', {timeOut: 4000});
    }
 });
 // Remove element
 $('.textContainer').on('click','.removePro',function(){
    var id          = this.id;
    var split_id    = id.split("_");
    var deleteindex = split_id[1];
    // Remove <div> with id
    $("#divPro_" + deleteindex).remove();
 }); 
  //change  
});
$(document).ready(function(){
 // Add new element
  $(".addProImage").click(function(){
    // Finding total number of elements added
    var total_element_img = Number($(".elementProImg").length);
    // last <div> with element class id
    var lastid_img        = $(".elementProImg:last").attr("id");
    var split_id_img      = lastid_img.split("_");
    var nextindex_img     = Number(split_id_img[1]) + 1;
    var max_img          = 10;
    // Check total number elements
    if(total_element_img < max_img){
      // Adding new div container after last occurance of element class
      $(".elementProImg:last").after('<div class="col-md-12 col-sm-12 col-lg-12 elementProImg" id="divProImg_'+ nextindex_img +'"></div>');
      $("#divProImg_" + nextindex_img).append('<section class="col col-md-12"><label class="label"><strong>Step Image-'+ nextindex_img +'</strong><a href="javascript:void(0);" id="removeimage_' + nextindex_img + '" class="btn btn-default btn-circle btn-danger pull-right removeProImage"><i class="fa fa-times" aria-hidden="true"></i></a></label></section><section class="col col-6 text-center"><img height="120" width="120" src="' + base_url + 'backend_assets/img/avatars/sunny-big.png"  id="blah_' + nextindex_img + '" alt="img"></section><section class="col col-6"><div class="input input-file"><input type="hidden" name="imagefileId_' + nextindex_img + '" value="0"><span class="button"><input type="file" name="fileImage_' + nextindex_img + '" id="file_' + nextindex_img + '" onchange="readURL(this,' + nextindex_img + ');this.parentNode.nextSibling.value = this.value" accept="image/*">Browse</span><input type="text" readonly=""></div></section>');
    }else{
      toastr.error('image step max limit is 10.', 'Alert!', {timeOut: 4000});
    }
 });
 // Remove element
 $('.imageContainer').on('click','.removeProImage',function(){
 
    var id_IMG          = this.id;
   
    var split_id_Img    = id_IMG.split("_");
    var deleteindex_img = split_id_Img[1];
    // Remove <div> with id
    $("#divProImg_" + deleteindex_img).remove();
 }); 
  //change  
});

$(document).ready(function(){
 // Add new element
  $(".addProVideo").click(function(){
    // Finding total number of elements added
    var total_element_video = Number($(".elementProVideo").length);
    // last <div> with element class id
    var lastid_video        = $(".elementProVideo:last").attr("id");
    var split_id_video      = lastid_video.split("_");
    var nextindex_video     = Number(split_id_video[1]) + 1;
    var max_video          = 10;
    // Check total number elements
    if(total_element_video < max_video){
      // Adding new div container after last occurance of element class
      $(".elementProVideo:last").after('<div class="col-md-12 col-sm-12 col-lg-12 elementProVideo" id="divProVideo_'+ nextindex_video +'"></div>');
      $("#divProVideo_" + nextindex_video).append('<section class="col col-md-12"><label class="label"><strong>Step Video-'+ nextindex_video +'</strong><a href="javascript:void(0);" id="removevideo_' + nextindex_video + '" class="btn btn-default btn-circle btn-danger pull-right removeProVideo"><i class="fa fa-times" aria-hidden="true"></i></a></label></section><section class="col col-6 text-center"><div id="privew'+ nextindex_video +'"><img height="120" width="120" src="'+base_url+'backend_assets/img/avatars/sunny-big.png"  alt="Video"></div></section><section class="col col-6"><div class="input input-file"><input type="hidden" name="videofileId_'+ nextindex_video +'" value="0"><span class="button"><input type="file" name="videofile_'+ nextindex_video +'" id="videofile_'+ nextindex_video +'" onchange="filePreviewMain(this,'+ nextindex_video +');this.parentNode.nextSibling.value = this.value" accept="video/*">Browse</span><input type="text" readonly=""></div></section>');
    }else{
      toastr.error('Video step max limit is 10.', 'Alert!', {timeOut: 4000});
    }
 });
 // Remove element
 $('.videoContainer').on('click','.removeProVideo',function(){
 
    var id_V          = this.id;
   
    var split_id_v   = id_V.split("_");
    var deleteindex_v = split_id_v[1];
    // Remove <div> with id
    $("#divProVideo_" + deleteindex_v).remove();
 }); 
  //change  
});

/*
function editAction(e){
    var id    = $(e).data('id');
    var name  = $(e).data('name');
  
     $('#add-data').modal('show');
}*/
function openAction(expression){
     $('#create-pro').trigger("reset");
    // $('#taskId').val($('#task_id').val());
       $('#taskId_ss').val($('#task_id_id').val());
        $("#divPro_1").css("display","none");
        $("#divProImg_1").css("display","none");
        $("#divProVideo_1").css("display","none");
        switch(expression) {
  
    case 'text':

    $('#textfileId_1').val("");
        $('#taskstepId').val('text');
        $('#textfileId_1').val("");
        $('#textfile_1').val("");
        $("#divPro_1").css("display","block");
        $("#divProImg_1").css("display","none");
        $("#divProVideo_1").css("display","none");
    break;
     case 'image':
         $('#taskstepId').val('image');
        $("#divPro_1").css("display","none");
        $("#divProImg_1").css("display","block");
        $("#divProVideo_1").css("display","none");
    break;
    case 'video':
         $('#taskstepId').val('video');
        $("#divPro_1").css("display","none");
        $("#divProImg_1").css("display","none");
        $("#divProVideo_1").css("display","block");
    break;
    
    default:
         $('#taskstepId').val('');
        $("#divPro_1").css("display","none");
        $("#divProImg_1").css("display","none");
        $("#divProVideo_1").css("display","none");
    } 
      $("#layerOpt").data("id","hide");
     $(".Show_option").css("display","none");
     $('#add-data').modal('show');
}
// button group form
function openActionOption(e){
   var id    = $(e).data('id');
   //alert(id);
   if(id=="show"){
         $(e).data("id","hide");
       $(".Show_option").css("display","block");
      
   }else{
      $(e).data("id","show");
     $(".Show_option").css("display","none");
     
   }

}
function editActionText(text,e){
    $('#create-pro').trigger("reset");
   // alert($('#task_id_id').val());
    $('#taskId_ss').val($('#task_id_id').val());
    $('#textfileId_1').val(e);
    $('#textfile_1').val($('#filetext_'+e).val());
    $("#divPro_1").css("display","none");
    $("#divProImg_1").css("display","none");
    $("#divProVideo_1").css("display","none");
    /**/
            $('#taskstepId').val('text');
        $("#divPro_1").css("display","block");
        $("#divProImg_1").css("display","none");
        $("#divProVideo_1").css("display","none");
    /**/
      $("#layerOpt").data("id","hide");
     $(".Show_option").css("display","none");
     $('#add-data').modal('show');
}
//taskAddUpdate Add 
$("#create-task-step").validate({// Rules for form validation
  errorClass    : errorClass,
  errorElement  : errorElement,
  highlight: function(element) {
    $(element).parent().removeClass('state-success').addClass("state-error");
    $(element).removeClass('valid');
  },
  unhighlight: function(element) {
    $(element).parent().removeClass("state-error").addClass('state-success');
    $(element).addClass('valid');
  },
  rules : {
    id : {
      required : true
    },
    taskstepId : {
      required : true
    },
  },
  // Messages for form validation
  onfocusout: injectTrim($.validator.defaults.onfocusout),
  // Do not change code below
  errorPlacement : function(error, element) {
    error.insertAfter(element.parent());
  }
});

// Validation
jQuery.validator.addClassRules('textClassStep', {
  required: true /*,
        other rules */
});

$(function() {   
  $(document).on('submit', "#create-task-step", function (event) {
    toastr.clear();
    event.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        type            : "POST",
        url             : base_url+'adminapi/'+$(this).attr('action'),
        headers         : { 'authToken': authToken },
        data            : formData, //only input
        processData     : false,
        contentType     : false,
        cache           : false,
        beforeSend      : function () {
          preLoadshow(true);
          $('#submit').prop('disabled', true);
        },
        success         : function (res) {
          preLoadshow(false);
          setTimeout(function(){  $('#submit').prop('disabled', false); },4000);
          if(res.status=='success'){
            toastr.success(res.message, 'Success', {timeOut: 3000});
            setTimeout(function(){
              window.location.reload(); 
            },4000);
          }else{
            toastr.error(res.message, 'Alert!', {timeOut: 4000});
          }
        }
    });
  });
  //fromsubmit
});

/***************************************************/

  $( function() {
    $( "#sortable1" ).sortable({
      connectWith: ".connectedSortable",
      update: function( event, ui ) {
       var array = $('.sortlayer').map(function(index) {
        var obj ={
        'metaid':$(this).data('metaid'),
        'type':$(this).data('type'),
        'order':index
      };
  return obj;
}).get();
if (array.length > 0) {
    //alert('YES');
    swal({
  title: "Are you sure?",
  text: "You want to re-arrange task step record!",
  type: "warning",
  showCancelButton: true,
  confirmButtonColor: "#DD6B55",
  cancelButtonColor: "#DD6B55",
  confirmButtonText: "Yes",
  cancelButtonText: "No",
  closeOnConfirm: true,
  closeOnCancel: true
},
function(isConfirm){
  if (isConfirm) {

    //swal("Deleted!", "Your imaginary file has been deleted.", "success");
        toastr.clear();
  
    $.ajax({
        type            : "POST",
        url             : base_url+'adminapi/tasks/recordorderMeta',
        headers         : { 'authToken': authToken },
        data            : JSON.stringify(array), //only input
        processData     : false,
        contentType     : false,
        cache           : false,
        beforeSend      : function () {
          preLoadshow(true);
       
        },
        success         : function (res) {
          preLoadshow(false);
        
          if(res.status=='success'){
            toastr.success(res.message, 'Success', {timeOut: 3000});
          
          }else{
            toastr.error(res.message, 'Alert!', {timeOut: 4000});
          }
        }
    });
    //swal("Deleted!", "Your imaginary file has been deleted.", "success");

  } else {
   //Cancel
         
  }
});
    //alert('YES');
}
 /* $('.setPlayers').each(function(index) {
      var obj ={
        'metaid':$(this).data('metaid'),
        'type':$(this).data('type'),
        'order':index
      };
     
      array.push(obj);

      
  });*/
         console.log(array);
         /* $(".ui-state-default").get().forEach(function(entry, index, array) {
            alert(index+""+$(this).data('metaid'));
              console.log(array);
          // Here, array.length is the total number of items
          });*/
      }
    }).disableSelection();
  } );