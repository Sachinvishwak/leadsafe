var base_url  = $('body').data('base-url'); // Base url
var authToken = $('body').data('auth-url'); // Base url
//rember me
$(function() {
  if (localStorage.chkbx && localStorage.chkbx != '') {
    $('#remember_me').attr('checked', 'checked');
    $('#username').val(localStorage.usrname);
    $('#password').val(localStorage.pass);
  } else {
    $('#remember_me').removeAttr('checked');
    $('#username').val('');
    $('#password').val('');
  }
  $('#remember_me').click(function() {
    if ($('#remember_me').is(':checked')) {

      localStorage.usrname  = $('#username').val();
      localStorage.pass     = $('#password').val();
      localStorage.chkbx    = $('#remember_me').val();

    } else {

      localStorage.usrname  = '';
      localStorage.pass     = '';
      localStorage.chkbx    = '';
    }
  });
});
//rember me
//login method
// Validation
$("#login-form").validate({
  // Rules for form validation
  rules : {
    email   : {
              required  : true,
              email     : true
            },
    password : {
              required  : true,
              minlength : 3,
              maxlength : 20
            }
  },
  // Messages for form validation
  messages : {
    email : {
              required  : 'Please enter your email address',
              email     : 'Please enter a valid email address'
            },
    password : {
              required  : 'Please enter your password'
            }
  },
  // Do not change code below
  errorPlacement : function(error, element) {
            error.insertAfter(element.parent());
          },
  // ajax 
  submitHandler: function (form) {
    toastr.clear();
    $('#submit').prop('disabled', true);
      $.ajax({
        type        : "POST",
        url         : base_url+'adminapi/'+$(form).attr('action'),
        data        : $(form).serialize(),
        cache       : false,
        beforeSend  : function() {
          preLoadshow(true);
          $('#submit').prop('disabled', true);  
        },     
        success: function (res) {
          preLoadshow(false);
          setTimeout(function(){  $('#submit').prop('disabled', false); },4000);
          if(res.status=='success'){
            toastr.success(res.message, 'Success', {timeOut: 3000});
            setTimeout(function(){ window.location = base_url+'dashboard'; },4000);
          }else{
            toastr.error(res.message, 'Alert!', {timeOut: 3000});
          }
        }
      });
      return false; // required to block normal submit since you used ajax
  }
});    // Validation
//Forgot
$("#forgot-form").validate({
  // Rules for form validation
  rules : {
            email : {
              required  : true,
              email     : true
            }
          },
  // Messages for form validation
  messages : {
            email : {
              required  : 'Please enter your email address',
              email     : 'Please enter a valid email address'
            },
  },// Do not change code below
  errorPlacement : function(error, element) {
    error.insertAfter(element.parent());
  },
  // ajax 
  submitHandler: function (form) {
    toastr.clear();
    $('#submit').prop('disabled', true);
      $.ajax({
        type        : "POST",
        url         : base_url+'adminapi/'+$(form).attr('action'),
        data        : $(form).serialize(),
        cache       : false,
        beforeSend  : function() {
          preLoadshow(true);
          $('#submit').prop('disabled', true);  
        },     
        success     : function (res) {
          preLoadshow(false);
          setTimeout(function(){  $('#submit').prop('disabled', false); },4000);
          if(res.status=='success'){
            toastr.success(res.message, 'Success', {timeOut: 3000});
            setTimeout(function(){ window.location = base_url; },4000);
          // window.location = base_url;
          }else{
            toastr.error(res.message, 'Alert!', {timeOut: 4000});
          }
        }
    });
    return false; // required to block normal submit since you used ajax
  }
});
//sign up
$("#smart-form-register").validate({// Rules for form validation
  rules : {
    fullName    : {
      required  : true
    },
    email : {
      required  : true,
      email     : true
    },
    contact : {
      required  : true,
    
    },
    password : {
      required  : true,
      minlength : 3,
      maxlength : 20
    },
    passwordConfirm : {
      required  : true,
      minlength : 3,
      maxlength : 20,
      equalTo   : '#password'
    },

  },
  // Messages for form validation
  messages : {
    fullName : {
      required  : 'Please enter your full name'
    },
    email : {
      required  : 'Please enter your email address',
      email     : 'Please enter a valid email address'
    },
    contact : {
      required  : 'Please enter your contact number'
    },
    password : {
      required  : 'Please enter your password'
    },
    passwordConfirm : {
      required  : 'Please re-enter your password',
      equalTo   : 'Please enter the same password as above'
    }
  },
  // Ajax form submition
  submitHandler : function(form) {
    toastr.clear();
    $('#submit').prop('disabled', true);
    $.ajax({
      type            : "POST",
      url             : base_url+'adminapi/'+$(form).attr('action'),
      data            : $(form).serialize(),
      cache           : false,
      beforeSend      : function() {
        preLoadshow(true);
        $('#submit').prop('disabled', true);  
      },     
      success: function (res) {
        preLoadshow(false);
        setTimeout(function(){  $('#submit').prop('disabled', false); },4000);
        if(res.status=='success'){
          toastr.success(res.message, 'Success', {timeOut: 3000});
          setTimeout(function(){ window.location = base_url+'dashboard'; },4000);
        }else{
          toastr.error(res.message, 'Alert!', {timeOut: 4000});
        }
      }
    });
    return false; // required to block normal submit since you used ajax
  },
  // Do not change code below
  errorPlacement : function(error, element) {
    error.insertAfter(element.parent());
  }
});
//date 
  $("#resetpassForm").validate({
    rules: {
      password: {
        required  : true,
        minlength : 6,
        maxlength : 15,
      },
      cpassword: {
        required  : true,  
        minlength : 6,
        maxlength : 15,
        equalTo   : "#password",
      }
    },
    messages: {
      password:{
        required  : "Please enter password.",
        minlength : "Password should have minimum 6 characters.",
        maxlength : "Password should have Maxlength 15 characters.",
      }, 
      cpassword:{ 
        required  : "Please enter confirm password.",
        minlength : "Confirm password should have minimum 6 characters.",
        maxlength : "Confirm password should have Maxlength 15 characters.",
        equalTo   : "Confirm password does not match.",
      }
    },
    // Do not change code below
    errorPlacement : function(error, element) {
      error.insertAfter(element.parent());
    },
    // ajax 
    submitHandler: function (form) {
      toastr.clear();
      $('#submit').prop('disabled', true);
      $.ajax({
        type       : "POST",
        url        : $(form).attr('action'),
        data       : $(form).serialize(),
        dataType   :'json',
        cache      : false,
        beforeSend : function() {
          preLoadshow(true);
          $('#submit').prop('disabled', true);  
        },     
        success     : function (res) {
          preLoadshow(false);
          setTimeout(function(){  $('#submit').prop('disabled', false); },4000);
          if(res.status=='success'){
            toastr.success(res.message, 'Success', {timeOut: 3000});
            setTimeout(function(){ window.location = base_url; },4000);
          }else{
            toastr.error(res.message, 'Alert!', {timeOut: 4000});
          } 
          //  $('#submit').prop('disabled', false);  
        }
      });
      return false; // required to block normal submit since you used ajax
    }
  });
  
  $("#contractorresetpassForm").validate({
    rules: {
      password: {
        required  : true,
        minlength : 6,
        maxlength : 15,
      },
      cpassword: {
        required  : true,  
        minlength : 6,
        maxlength : 15,
        equalTo   : "#password",
      }
    },
    messages: {
      password:{
        required  : "Please enter password.",
        minlength : "Password should have minimum 6 characters.",
        maxlength : "Password should have Maxlength 15 characters.",
      }, 
      cpassword:{ 
        required  : "Please enter confirm password.",
        minlength : "Confirm password should have minimum 6 characters.",
        maxlength : "Confirm password should have Maxlength 15 characters.",
        equalTo   : "Confirm password does not match.",
      }
    },
    // Do not change code below
    errorPlacement : function(error, element) {
      error.insertAfter(element.parent());
    },
    // ajax 
    submitHandler: function (form) {
      toastr.clear();
      $('#submit').prop('disabled', true);
      $.ajax({
        type       : "POST",
        url        : $(form).attr('action'),
        data       : $(form).serialize(),
        dataType   :'json',
        cache      : false,
        beforeSend : function() {
          preLoadshow(true);
          $('#submit').prop('disabled', true);  
        },     
        success     : function (res) {
          preLoadshow(false);
          setTimeout(function(){  $('#submit').prop('disabled', false); },4000);
          if(res.status=='success'){
            toastr.success(res.message, 'Success', {timeOut: 3000});
            setTimeout(function(){ window.location = location.reload(); },4000);
          }else{
            toastr.error(res.message, 'Alert!', {timeOut: 4000});
          } 
          //  $('#submit').prop('disabled', false);  
        }
      });
      return false; // required to block normal submit since you used ajax
    }
  });
  
  
  
  /* admin or company */
   //company registration
$("#smart-form-company-register").validate({// Rules for form validation
  rules : {
    fullName : {
      required : true
    },
    email : {
      required : true
    },
    contact : {
      required : true
    },
    fax_number : {
      required : true
    },
    licence : {
      required : true
    },
    insurence_certificate : {
      required : true
    },
    npassword : {
        required  : true,
        minlength : 6,
        maxlength : 15,
    },
    rnpassword : {
        required  : true,
        minlength : 6,
        maxlength : 15,
        equalTo   : "#npassword",
    },

  },
  // Messages for form validation
  messages : {
    fullName : {
      required : 'Please enter your Company name'
    },

   description : {
      email : 'Please enter your Company Email'
    },
    
    contact : {
      required : 'Please enter your Phone Number'
    },

    fax_number : {
      required : 'Please enter your Fax Number'
    },

    licence : {
      required : "Please enter your license"
    },
    insurence_certificate : {
      required : "please Upload Yours Insurance certificate"
    },
    npassword : {
        required  : 'Please enter passsword',
        minlength : "Password should have minimum 6 characters.",
        maxlength : "Password should have Maxlength 15 characters.",
    },
    rnpassword : {
        required  : 'Please enter Confirm password',
        minlength : "Confirm password should have minimum 6 characters.",
        maxlength : "Confirm password should have Maxlength 15 characters.",
        equalTo   : "Confirm password does not match.",
    },

  },
  // Ajax form submition
  submitHandler : function(form) {
    var formData = new FormData(form);
    toastr.clear();
    $('#submit').prop('disabled', true);
    $.ajax({
      type            : "POST",
      url             : base_url+'company/Companyapi/'+$(form).attr('action'),
      data: formData, 
      dataType: 'json',
      processData: false,
      contentType: false,
      beforeSend      : function() {
        preLoadshow(true);
        $('#submit').prop('disabled', true);  
      },     
      success: function (res) {
        preLoadshow(false);
        setTimeout(function(){  $('#submit').prop('disabled', false); },4000);
        if(res.status=='success'){
          toastr.success(res.message, 'Success', {timeOut: 3000});
          setTimeout(function(){ 
            window.location = base_url+'admin/login'; 
          },4000);
        }else{
          toastr.error(res.message, 'Alert!', {timeOut: 4000});
        }
      }
    });
    return false; // required to block normal submit since you used ajax
  },
  // Do not change code below
  errorPlacement : function(error, element) {
    error.insertAfter(element.parent());
  }
});


//company login form
$("#company-login-form").validate({
  // Rules for form validation
  rules : {
      email : {
        required  : true,
        email     : true
      },
      npassword : {
        required  : true,
        minlength : 6,
        maxlength : 15,
      },
    },
  // Messages for form validation
  messages : {
    email : {
        required  : 'Please enter your email address',
        email     : 'Please enter a valid email address'
    },
    npassword : {
        required  : 'Please enter passsword',
        minlength : "Password should have minimum 6 characters.",
        maxlength : "Password should have Maxlength 15 characters.",
      }
    },
  // Do not change code below
  errorPlacement : function(error, element) {
            error.insertAfter(element.parent());
          },
  // ajax 
  submitHandler: function (form) {
    toastr.clear();
    $('#submit').prop('disabled', true);
      $.ajax({
        type        : "POST",
        url         : base_url+'company/Companyapi/'+$(form).attr('action'),
        data        : $(form).serialize(),
        cache       : false,
        beforeSend  : function() {
          preLoadshow(true);
          $('#submit').prop('disabled', true);  
        },     
        success: function (res) {
          preLoadshow(false);
          setTimeout(function(){  $('#submit').prop('disabled', false); },4000);
          if(res.status=='success'){
            toastr.success(res.message, 'Success', {timeOut: 3000});
            setTimeout(function(){ window.location = base_url+res.url; },4000);
          }else{
            toastr.error(res.message, 'Alert!', {timeOut: 3000});
          }
        }
      });
      return false; // required to block normal submit since you used ajax
  }
});

// compnay reset password
$("#company-reset-password-form").validate({
  // Rules for form validation
  rules : {  
      npassword : {
        required  : true,
        minlength : 6,
        maxlength : 15,
      },
      rnpassword : {
        required  : true,
        minlength : 6,
        maxlength : 15,
        equalTo   : "#npassword",
      },
    },
  // Messages for form validation
  messages : {
      npassword : {
        required  : 'Please enter passsword',
        minlength : "Password should have minimum 6 characters.",
        maxlength : "Password should have Maxlength 15 characters.",
      },
      rnpassword : {
        required  : 'Please enter Confirm password',
        minlength : "Confirm password should have minimum 6 characters.",
        maxlength : "Confirm password should have Maxlength 15 characters.",
        equalTo   : "Confirm password does not match.",
      },
    },
  // Do not change code below
  errorPlacement : function(error, element) {
            error.insertAfter(element.parent());
          },
  // ajax 
  submitHandler: function (form) {
    toastr.clear();
    $('#submit').prop('disabled', true);
      $.ajax({
        type        : "POST",
        url         : base_url+'company/Companyapi/'+$(form).attr('action'),
        data        : $(form).serialize(),
        cache       : false,
        beforeSend  : function() {
          preLoadshow(true);
          $('#submit').prop('disabled', true);  
        },     
        success: function (res) {
          preLoadshow(false);
          setTimeout(function(){  $('#submit').prop('disabled', false); },4000);
          if(res.status=='success'){
            toastr.success(res.message, 'Success', {timeOut: 3000});
            setTimeout(function(){ 
                window.location = base_url+res.url; 
            },4000);
          }else{
            toastr.error(res.message, 'Alert!', {timeOut: 3000});
          }
        }
      });
      return false; // required to block normal submit since you used ajax
  }
});








// member reset password
$("#allmemberresetpassword").validate({
  // Rules for form validation
  rules : {  
      npassword : {
        required  : true,
        minlength : 6,
        maxlength : 15,
      },
      rnpassword : {
        required  : true,
        minlength : 6,
        maxlength : 15,
        equalTo   : "#npassword",
      },
    },
  // Messages for form validation
  messages : {
      npassword : {
        required  : 'Please enter passsword',
        minlength : "Password should have minimum 6 characters.",
        maxlength : "Password should have Maxlength 15 characters.",
      },
      rnpassword : {
        required  : 'Please enter Confirm password',
        minlength : "Confirm password should have minimum 6 characters.",
        maxlength : "Confirm password should have Maxlength 15 characters.",
        equalTo   : "Confirm password does not match.",
      },
    },
  // Do not change code below
  errorPlacement : function(error, element) {
            error.insertAfter(element.parent());
          },
  // ajax 
  submitHandler: function (form) {
    toastr.clear();
    $('#submit').prop('disabled', true);
      $.ajax({
        type        : "POST",
        url         : base_url+'password/ChangePassword/meber_password_reset',
        data        : $(form).serialize(),
        cache       : false,
        beforeSend  : function() {
          preLoadshow(true);
          $('#submit').prop('disabled', true);  
        },     
        success: function (res) {
            res = JSON.parse(res);
          preLoadshow(false);
          setTimeout(function(){  $('#submit').prop('disabled', false); },4000);
          if(res.status == 'success'){
            toastr.success(res.message, 'Success', {timeOut: 3000});
            setTimeout(function(){ 
                // window.location.reload();
                toastr.success(res.message, 'Success', {timeOut: 3000});
                setTimeout(function(){ window.location = base_url+res.url; },4000);
            },4000);
          }else{
            toastr.error(res.message, 'Alert!', {timeOut: 3000});
          }
        }
      });
      return false; // required to block normal submit since you used ajax
  }
});
  
  
  
  
  
  
  
  

//company profile update
// update profile
$("#smart-form-company-updateuser").validate({

          // Rules for form validation
          rules : {
            name : {
              required  : true
            },
            email : {
              required  : true,
              email     : true
            },
            phone_number : {
              required  : true,       
            },
            // fax_number : {
            //   required  : true,       
            // },
            // licence : {
            //   required  : true,       
            // },
            // insurence_certificate : {
            //   required  : true,       
            // },
            profileImage : {
              required  : true,       
            },
          },

          // Messages for form validation
          messages : {
            name : {
              required : 'Please enter Company Name'
            },
            email : {
              required : 'Please Enter Company Email',
              email    : 'Please Enter Valid Company Email'
            },
            phone_number : {
              required : 'Please enter 10 digits Phone Number',
            }, 
            // fax_number : {
            //   required  : 'Please enter 10 digits Fax Number',       
            // },
            // licence : {
            //   required  : 'Please enter License',       
            // },
            // insurence_certificate : {
            //   required  : 'Please enter Insurense Certificate',       
            // },
            profileImage : {
              required  : 'Please Upload Profile Image',       
            },
          },
          // Ajax form submition
         /* submitHandler : function(form) {
           
             return false; // required to block normal submit since you used ajax
          },
*/
          // Do not change code below
          errorPlacement : function(error, element) {
            error.insertAfter(element.parent());
          }
        });
        // update profile                         
// Validation
$(function() {
      
  $(document).on('submit', "#smart-form-company-updateuser", function (event) {
    toastr.clear();
    event.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        type            : "POST",
        url             : base_url+'company/Companyapi/'+$(this).attr('action'),
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
            setTimeout(function(){ window.location = base_url+res.url; },4000);
          }else{
            toastr.error(res.message, 'Alert!', {timeOut: 4000});
          }         
        }
    });
  });        //fromsubmit
});
  
  
  
  
 //forgot pass
 $("#admin_forgetpassword").validate({// Rules for form validation
  rules : {
    company : {
      required : true
    },
    email : {
      required : true
    }
  },
  // Messages for form validation
  messages : {
    company : {
      required : 'Please enter your Company name'
    },

   email : {
      required : 'Please enter your Company Email'
    },
    

  },
  // Ajax form submition
  submitHandler : function(form) {
    var formData = new FormData(form);
    toastr.clear();
    $('#submit').prop('disabled', true);
    $.ajax({
      type            : "POST",
      url             : base_url+'members/Api/forgotpassword/',
      data: formData, 
      dataType: 'json',
      processData: false,
      contentType: false,
      beforeSend      : function() {
        preLoadshow(true);
        $('#submit').prop('disabled', true);  
      },     
      success: function (res) {
        preLoadshow(false);
        setTimeout(function(){  $('#submit').prop('disabled', false); },4000);
        if(res.status=='success'){
          toastr.success(res.message, 'Success', {timeOut: 3000});
          setTimeout(function(){ 
            window.location = base_url+'admin/login'; 
          },4000);
        }else{
          toastr.error(res.message, 'Alert!', {timeOut: 4000});
        }
      }
    });
    return false; // required to block normal submit since you used ajax
  },
  // Do not change code below
  errorPlacement : function(error, element) {
    error.insertAfter(element.parent());
  }
});


  
  
  
  
  
  