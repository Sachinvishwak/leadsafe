runAllForms();
//loader manage
function preLoadshow(e){
  if(e){
    $('.pace').addClass('pace-active').removeClass('pace-inactive');
  }else{
    $('.pace').addClass('pace-inactive').removeClass('pace-active');
  }
}//end function
function injectTrim(handler) {
  return function (element, event) {
    if (element.tagName === "TEXTAREA" || (element.tagName === "INPUT" 
                                       && element.type !== "password")) {
      element.value = $.trim(element.value);
    }
    return handler.call(this, element, event);
  };
}
//loader manage
//number check 
$('.number-only').keypress(function(e) {
  if(isNaN(this.value+""+String.fromCharCode(e.charCode))) return false;
}).on("cut copy paste",function(e){
  e.preventDefault();
});
$(".floatNumeric").on("keypress keyup blur",function (event) {
  //this.value = this.value.replace(/[^0-9\.]/g,'');
  $(this).val($(this).val().replace(/[^0-9\.]/g,''));
  if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
    event.preventDefault();
  }
});
$(".alfaNumeric").on("keypress keyup blur",function (event) {
  if (this.value.match(/[^a-zA-Z0-9 ]/g)) {
    this.value = this.value.replace(/[^a-zA-Z0-9 ]/g, '');
  }
});
//date  picker manange
$( "#purchaseDate" ).datepicker({  
  dateFormat  : 'mm/dd/yyyy'
});
/*
  * TIMEPICKER
*/ 
$('#timepicker').timepicker();
$('.select2').select2({
  minimumResultsForSearch : -1,
  placeholder             : function(){
    $(this).data('placeholder');
  }
});
$("#dob").datepicker({
  dateFormat  : 'dd-mm-yy',
  maxDate     : new Date(),
  changeMonth : true,
  changeYear  : true,
  yearRange   : "-100:+0",
  prevText    : '<i class="fa fa-chevron-left"></i>',
  nextText    : '<i class="fa fa-chevron-right"></i>',
});
$("#CreationDate").datepicker({
  dateFormat  : 'dd-mm-yy',
  minDate     : new Date(),
  //changeMonth : true,
  //changeYear  : true,
  // yearRange   : "-100:+0",
  prevText    : '<i class="fa fa-chevron-left"></i>',
  nextText    : '<i class="fa fa-chevron-right"></i>',
});

function filePreview(input) {
  if (input.files && input.files[0]) {
    var reader    = new FileReader();
    reader.onload = function (e) {
      /*   $('#uploadForm + img').remove();
      $('#uploadForm').after('<img src="'+e.target.result+'" width="450" height="300"/>');*/
      $('#privew + embed').remove();
      $('#privew + img').remove();
      $('#privew').after('');
      $('#privew').after('<embed src="'+e.target.result+'" width="400" height="300">');
    }
    reader.readAsDataURL(input.files[0]);
  }
}
function filePreviewMain(input,i) {
  if (input.files && input.files[0]) {
    var reader    = new FileReader();
    reader.onload = function (e) {
      /*   $('#uploadForm + img').remove();
      $('#uploadForm').after('<img src="'+e.target.result+'" width="450" height="300"/>');*/
      $('#privew'+i+' + embed').remove();
      $('#privew'+i+' + img').remove();
      $('#privew'+i).html('');
      $('#privew'+i).after('');
      $('#privew'+i).before('');
     // $('#privew'+i).after('<embed src="'+e.target.result+'" width="400" height="300">');
     // $('#privew'+i).after('<iframe width="420" height="315" src="'+e.target.result+'" controls="controls" autoplay="false" frameborder="0" scrolling="no"></iframe> ');
      $('#privew'+i).html('<div class="embed-responsive embed-responsive-16by9"><video width="420" height="315" controls="true" class="embed-responsive-item"><source  src="'+e.target.result+'" type="video/mp4" /></video></div>');
    }
    reader.readAsDataURL(input.files[0]);
  }
}