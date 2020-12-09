// Change Password
// Validation
$("#smart-form-changepass").validate({
  // Rules for form validation
  rules : {
            password : {
              required  : true,
              minlength : 3,
              maxlength : 20
            }, 
            npassword : {
              required  : true,
              minlength : 3,
              maxlength : 20
            },
            rnpassword : {
              required  : true,
              minlength : 3,
              maxlength : 20,
              equalTo   : '#npassword'
            }, 
          },
          // Messages for form validation
          messages : {
            
            password : {
              required  : 'Please enter your current password'
            },
            npassword : {
              required  : 'Please enter your new password'
            },
            rnpassword  : {
                required  : 'Please re-enter your password',
                equalTo   : 'Please enter the same password as above'
            }
         
          },

          // Ajax form submition
          submitHandler : function(form) {
            toastr.clear();
            $('#submit').prop('disabled', true);
            $.ajax({
              type        : "POST",
              url         : base_url+'adminapi/users/'+$(form).attr('action'),
              headers     : { 'authToken':authToken},
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
                  setTimeout(function(){ window.location = base_url+'dashboard'; },4000);
                  //window.location = base_url+'admin/dashboard';
                }else{
                  toastr.error(res.message, 'Alert!', {timeOut: 4000});
                }
                //$('#submit').prop('disabled', false);  
              }
             });
             return false; // required to block normal submit since you used ajax
          },
          // Do not change code below
          errorPlacement : function(error, element) {
            error.insertAfter(element.parent());
          }
        });
        // Change Password
        
        
// Company Change password

$("#smart-form-changepass-company").validate({
  // Rules for form validation
  rules : {
            password : {
              required  : true,
              minlength : 3,
              maxlength : 20
            }, 
            npassword : {
              required  : true,
              minlength : 3,
              maxlength : 20
            },
            rnpassword : {
              required  : true,
              minlength : 3,
              maxlength : 20,
              equalTo   : '#npassword'
            }, 
          },
          // Messages for form validation
          messages : {
            
            password : {
              required  : 'Please enter your current password'
            },
            npassword : {
              required  : 'Please enter your new password'
            },
            rnpassword  : {
                required  : 'Please re-enter your password',
                equalTo   : 'Please enter the same password as above'
            }
         
          },

          // Ajax form submition
          submitHandler : function(form) {
            toastr.clear();
            $('#submit').prop('disabled', true);
            $.ajax({
              type        : "POST",
              url         : base_url+'company/Companyapi/'+$(form).attr('action'),
              headers     : { 'authToken':authToken},
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
                    setTimeout(function(){ window.location = base_url+'company/Users/changePassword/changepassword'; },4000);
                  //window.location = base_url+'admin/dashboard';
                }
                else{
                  toastr.error(res.message, 'Alert!', {timeOut: 4000});
                }
                //$('#submit').prop('disabled', false);  
              }
             });
             return false; // required to block normal submit since you used ajax
          },
          // Do not change code below
          errorPlacement : function(error, element) {
            error.insertAfter(element.parent());
          }
        });
        // Change Password

// end
        
        

         // update profile
$("#smart-form-updateuser").validate({

          // Rules for form validation
          rules : {
            fullName : {
              required  : true
            },
            email : {
              required  : true,
              email     : true
            },
            contact : {
              required  : true,       
            },
          },

          // Messages for form validation
          messages : {
            fullName : {
              required : 'Please enter your full name'
            },
            email : {
              required : 'Please enter your email address',
              email    : 'Please enter a valid email address'
            },
            contact : {
              required : 'Please enter your contact number',
            
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
      
  $(document).on('submit', "#smart-form-updateuser", function (event) {
    toastr.clear();
    event.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        type            : "POST",
        url             : base_url+'adminapi/users/'+$(this).attr('action'),
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
            setTimeout(function(){ window.location = base_url+'profile/'+res.url; },4000);
          }else{
            toastr.error(res.message, 'Alert!', {timeOut: 4000});
          }         
        }
    });
  });        //fromsubmit
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
            fax_number : {
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
            fax_number : {
              required  : 'Please enter 10 digits Fax Number',       
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

// Crew Member add
$("#AddCrewMember").validate({// Rules for form validation
  rules : {
    name : {
      required : true
    },
    email : {
      required : true,
      email : true
    },
    
  },
  // Messages for form validation
  messages : {
    name : {
      required : 'Please enter Crew Member Name'
    },

    email : {
      required : 'Please enter Crew Member Email',
      email : 'Please Enter Valid Crew Member Email'
    },
  },
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
  $(document).on('submit', "#AddCrewMember", function (event) {
    toastr.clear();
    event.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        type            : "POST",
        url             : base_url+'company/'+$(this).attr('action'),
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
               window.location.href = base_url+res.url; 
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

// Crew Member Edit
$("#EditCrewMember").validate({// Rules for form validation
  rules : {
    name : {
      required : true
    },
    email : {
      required : true,
      email : true
    },
    
  },
  // Messages for form validation
  messages : {
    name : {
      required : 'Please enter Crew Member Name'
    },

    email : {
      required : 'Please enter Crew Member Email',
      email : 'Please Enter Valid Crew Member Email'
    },
  },
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
  $(document).on('submit', "#EditCrewMember", function (event) {
    toastr.clear();
    event.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        type            : "POST",
        url             : base_url+'company/'+$(this).attr('action'),
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

//Add Client
$("#AddClient").validate({// Rules for form validation
  rules : {
    name : {
      required : true
    },
    email : {
      required : true,
      email : true
    }
    
  },
  // Messages for form validation
  messages : {
    name : {
      required : 'Please enter Client Name'
    },

    email : {
      required : 'Please enter Client Email',
      email : 'Please Enter Valid Client Email'
    },
    
  },
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
  $(document).on('submit', "#AddClient", function (event) {
    toastr.clear();
    event.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        type            : "POST",
        url             : base_url+'company/'+$(this).attr('action'),
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
                base_url = base_url+res.url;
               window.location.href = base_url; 
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




// Crew Member Edit
$("#EditClient").validate({// Rules for form validation
  rules : {
    name : {
      required : true
    },
    email : {
      required : true,
      email : true
    }
    
  },
  // Messages for form validation
  messages : {
    name : {
      required : 'Please enter Client Name'
    },

    email : {
      required : 'Please enter Client Email',
      email : 'Please Enter Valid Client Email'
    },


  },
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
  $(document).on('submit', "#EditClient", function (event) {
    toastr.clear();
    event.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        type            : "POST",
        url             : base_url+'company/'+$(this).attr('action'),
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


//End




// Add Contractor
// $("#AddContractor").validate({// Rules for form validation
//   rules : {
//     company_name : {
//       required : true
//     },
//     owner_first_name : {
//       required : true
//     },
//     owner_last_name : {
//       required : true
//     },
//     email : {
//       required : true,
//       email : true
//     },
//     phone_number : {
//       required : true
//     },
//     address : {
//       required : true
//     },
//     licence : {
//       required : true
//     },
//     insurence_certificate : {
//       required : true
//     },
//     state : {
//       required : true
//     },
//     city : {
//       required : true
//     },
    
//   },
//   // Messages for form validation
//   messages : {
//     company_name : {
//       required : 'Please enter Company Name'
//     },
//     owner_first_name : {
//       required : 'Please enter Owner First Name'
//     },
//     owner_last_name : {
//       required : 'Please enter Owner Last Name'
//     },

//     email : {
//       required : 'Please enter Crew Member Email',
//       email : 'Please Enter Valid Crew Member Email'
//     },
    
//     phone_number : {
//       required : 'Please enter 10 digits Phone Number'
//     },

//     address : {
//       required : 'Please enter Crew Member Address'
//     },
    
//     licence : {
//       required : 'Please Upload License'
//     },
//     insurence_certificate : {
//       required : 'Please Upload Insurance Certificate'
//     },
//     state : {
//       required : 'Please Select Any State'
//     },
//     city : {
//       required : 'Please Select Any City'
//     },
//   },
//   // Do not change code below
//   errorPlacement : function(error, element) {
//     error.insertAfter(element.parent());
//   }
// });

// // Validation
// jQuery.validator.addClassRules('textClass', {
//   required: true /*,
//         other rules */
// });

// $(function() {   
//   $(document).on('submit', "#AddContractor", function (event) {
//     toastr.clear();
//     event.preventDefault();
//     var formData = new FormData(this);
//     $.ajax({
//         type            : "POST",
//         url             : base_url+'company/'+$(this).attr('action'),
//         headers         : { 'authToken': authToken },
//         data            : formData, //only input
//         processData     : false,
//         contentType     : false,
//         cache           : false,
//         beforeSend      : function () {
//           preLoadshow(true);
//           $('#submit').prop('disabled', true);
//         },
//         success         : function (res) {
//           preLoadshow(false);
//           setTimeout(function(){  $('#submit').prop('disabled', false); },4000);
//           if(res.status=='success'){
//             toastr.success(res.message, 'Success', {timeOut: 3000});
//             setTimeout(function(){
//               base_url1 = base_url+res.url;
//               window.location.href = base_url1; 
//              // window.location.reload(); 
//             },4000);
//           }else{
//             toastr.error(res.message, 'Alert!', {timeOut: 4000});
//           }
//         }
//     });
//   });
//   //fromsubmit
// });


$("#AddContractor").validate({// Rules for form validation
   rules : {
    company_name : {
      required : true
    },
    owner_first_name : {
      required : true
    },
    owner_last_name : {
      required : true
    },
 
  },
  // Messages for form validation
  messages : {
    company_name : {
      required : 'Please enter Company Name'
    },
    owner_first_name : {
      required : 'Please enter Contractor Name'
    },
    owner_last_name : {
      required : 'Please enter Owner Last Name'
    },

  
  },
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
  $(document).on('submit', "#AddContractor", function (event) {
    toastr.clear();
    event.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        type            : "POST",
        url             : base_url+'company/'+$(this).attr('action'),
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
               base_url1 = base_url+res.url;
               window.location.href = base_url1; 
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


// Update Contractor
$("#EditContractor").validate({// Rules for form validation
  rules : {
    company_name : {
      required : true
    },
    owner_first_name : {
      required : true
    },
    owner_last_name : {
      required : true
    },
    email : {
      required : true,
      email : true
    },
    phone_number : {
      required : true
    },
    address : {
      required : true
    },
    state : {
      required : true
    },
    city : {
      required : true
    },
    
  },
  // Messages for form validation
  messages : {
    company_name : {
      required : 'Please enter Company Name'
    },
    owner_first_name : {
      required : 'Please enter Contractor Name'
    },
    owner_last_name : {
      required : 'Please enter Owner Last Name'
    },

    email : {
      required : 'Please enter Contractor Member Email',
      email : 'Please Enter Valid Contractor Member Email'
    },
    
    phone_number : {
      required : 'Please enter 10 digits Phone Number'
    },

    address : {
      required : 'Please enter Crew Member Address'
    },
    state : {
      required : 'Please Select Any State'
    },
    city : {
      required : 'Please Select Any City'
    },
  },
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
  $(document).on('submit', "#EditContractor", function (event) {
    toastr.clear();
    event.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        type            : "POST",
        url             : base_url+'company/'+$(this).attr('action'),
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
               base_url1 = base_url+res.url;
               window.location.href = res.url; 
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


// $.validator.addMethod(
//     "australianDate",
//     function(value, element) {
//         return value.match(/^\d\d?\-\d\d?\-\d\d\d\d$/);
//     },
//     "Please enter a date in the format dd-mm-yyyy."
// );

jQuery.validator.addMethod("greaterThan", function(value, element, params) {
    console.log($(params).val());
    if (!/Invalid|NaN/.test(new Date(value))) {
        return new Date(value) > new Date($(params).val());
    }
    return isNaN(value) && isNaN($(params).val()) 
        || (Number(value) > Number($(params).val())); 
},'End Date Must be greater than Start Data.');

// Add Project
$("#AddProject").validate({// Rules for form validation
  rules : {
    name : {
      required : true
    },
    project_description : {
      required : true
    },
    crew_member : {
      required : true
    },
    contractor : {
      required : true
    },
    start_date : {
      required : true,
      date: true ,
    },
    // end_date : { 
    //   required : false,    
    //   date: false ,
    //   greaterThan: "#start_date" 
    // },
  },
  // Messages for form validation
  messages : {
    name : {
      required : 'Please enter Project Name'
    },
    project_description : {
      required : 'Please enter Project Description'
    },
    crew_member : {
      required : 'Please Assign At Least One Crew Member'
    },
    contractor : {
      required : 'Please Assign Contractor'
    },
    start_date : {
      required : 'Please Select Start Date',
      date : 'Please Enter Valid Date.'
    },
    // end_date : {
    //   required : 'Please Select Estimated End Date',
    //   date : 'Please Enter Valid Date.',
    //   EndDate : 'End date should be greater than start date.'
    // },
  },
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
  $(document).on('submit', "#AddProject", function (event) {
    toastr.clear();
    event.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        type            : "POST",
        url             : base_url+'company/'+$(this).attr('action'),
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
                base_url = base_url+res.url;
               window.location.href = base_url; 
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

// Edit project

jQuery.validator.addMethod("greaterThan", function(value, element, params) {
    console.log($(params).val());
    if (!/Invalid|NaN/.test(new Date(value))) {
        return new Date(value) > new Date($(params).val());
    }
    return isNaN(value) && isNaN($(params).val()) 
        || (Number(value) > Number($(params).val())); 
},'End Date Must be greater than Start Data.');

$("#EditProject").validate({// Rules for form validation
  rules : {
    name : {
      required : true
    },
    project_description : {
      required : true
    },
    crew_member : {
      required : true
    },
    contractor : {
      required : true
    },
    start_date : {
      required : true,
      date: true ,
    },
    end_date : { 
      required : true,    
      date: true ,
      greaterThan: "#start_date" 
    },
  },
  // Messages for form validation
  messages : {
    name : {
      required : 'Please enter Project Name'
    },
    project_description : {
      required : 'Please enter Project Description'
    },
    crew_member : {
      required : 'Please Assign At Least One Crew Member'
    },
    contractor : {
      required : 'Please Assign Contractor'
    },
    start_date : {
      required : 'Please Select Start Date',
      date : 'Please Enter Valid Date.'
    },
    end_date : {
      required : 'Please Select Estimated End Date',
      date : 'Please Enter Valid Date.',
      EndDate : 'End date should be greater than start date.'
    },
  },
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
  $(document).on('submit', "#EditProject", function (event) {
    toastr.clear();
    event.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        type            : "POST",
        url             : base_url+'company/'+$(this).attr('action'),
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
               window.location.href = res.url;
            },4000);
          }else{
            toastr.error(res.message, 'Alert!', {timeOut: 4000});
          }
        }
    });
  });
  //fromsubmit
});

// End





// add project client
//Add Client
$("#AddProjectClient").validate({// Rules for form validation
  rules : {
    name : {
      required : true
    },
    email : {
      required : true,
      email : true
    },
    phone_number : {
      required : true
    },
    address : {
      required : true
    },
  },
  // Messages for form validation
  messages : {
    name : {
      required : 'Please enter Client Name'
    },
    email : {
      required : 'Please enter Client Email',
      email : 'Please Enter Valid Client Email'
    },
    phone_number : {
      required : 'Please enter 10 digits Phone Number'
    },
    address : {
      required : 'Please enter Client Address'
    },
  },
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
  $(document).on('submit', "#AddProjectClient", function (event) {
    toastr.clear();
    event.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        type            : "POST",
        url             : base_url+'company/'+$(this).attr('action'),
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
            
            
            let name = res.users[0].name;
            let id = res.users[0].id;
            console.log(name);
            $('#projectclientlist').append('<option selected value="'+id+'">'+name+'</option>');

            // $('#projectclientlist').html(res.list);
            // $('#projectclientlist2').html(res.list);
            $('#exampleModal2').modal('hide');
          }else{
            toastr.error(res.message, 'Alert!', {timeOut: 4000});
          }
        }
    });
  });
  //fromsubmit
});




// client invite multiple
$(function() {   
  $(document).on('submit', "#inviteclientform", function (event) {
    toastr.clear();
    event.preventDefault();
    var formData = new FormData(this);
    let valid =true;
    let type = "";
    var values = $("input[name='client_email[]']").map(function(){
        let email = $(this).val();
        var emailReg = /^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/;
        if (email.match(emailReg)) {
            
        }else{
            valid = false;
            type = "email";
        }
    }).get();
    
    var values = $("input[name='client_name[]']").map(function(){
        let name = $(this).val();
        var emailReg = /^([a-z ])+$/i;
        if (name.match(emailReg)) {
            
        }else{
            valid = false;
            if(type != "")
            {
                type = type + " And name";
            }else{
                type = "name";
            }
        }
    }).get();
    
    

    if(valid)
    {
        $.ajax({
        type            : "POST",
        url             : base_url+'company/Clientapi/inviteClientMultiple',
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
           if(res.status =='success'){
              toastr.success(res.message, 'Success', {timeOut: 3000});
              $('#inviteclientmodal').modal('hide');
              }
              else{
                toastr.error(res.message, 'Alert!',{timeOut:4000});
              }
        }
    });
    }else{
        toastr.error(type+ ' format extensions Not match', 'Alert!',{timeOut:4000});
    }
  });
  //fromsubmit
});



