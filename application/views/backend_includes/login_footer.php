    <!--================================================== -->  
    <?php $backend_assets =  base_url().'backend_assets/'; ?><!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
    <script src="<?php echo $backend_assets; ?>js/plugin/pace/pace.min.js"></script>
    <!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script> if (!window.jQuery) { document.write('<script src="<?php echo $backend_assets; ?>js/libs/jquery-3.2.1.min.js"><\/script>');} </script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script> if (!window.jQuery.ui) { document.write('<script src="<?php echo $backend_assets; ?>js/libs/jquery-ui.min.js"><\/script>');} </script>
    <!-- IMPORTANT: APP CONFIG -->
    <script src="<?php echo $backend_assets; ?>js/app.config.js"></script>
    <!-- JS TOUCH : include this plugin for mobile drag / drop touch events     
    <script src="js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> -->
    <!-- BOOTSTRAP JS -->   
    <script src="<?php echo $backend_assets; ?>js/bootstrap/bootstrap.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <!-- JQUERY VALIDATE -->
    <script src="<?php echo $backend_assets; ?>js/plugin/jquery-validate/jquery.validate.min.js"></script>
    <!-- JQUERY MASKED INPUT -->
    <script src="<?php echo $backend_assets; ?>js/plugin/masked-input/jquery.maskedinput.min.js"></script>
    <!--[if IE 8]>
    <h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>
    <![endif]-->
    <!-- MAIN APP JS FILE -->
    <script src="<?php echo $backend_assets; ?>js/app.min.js"></script>
    <script src="<?php echo $backend_assets; ?>admin/js/login.js"></script>
    <script src="<?php echo $backend_assets; ?>custom/js/custom.js"></script>
    <script>
    $(document).ready(function(){
       /*For License   */
       $(document).on('change', '#profile_image_complete', function(){
          var name = document.getElementById("profile_image_complete").files[0].name;
          var form_data = new FormData();
          var ext = name.split('.').pop().toLowerCase();
          if(jQuery.inArray(ext, ['jpeg','jpg','png','bmp']) == -1) 
          {
               toastr.error('It will accept only jpeg, jpg, png, bmp format files.', 'Alert!', {timeOut: 4000});
               $('#profile_image_complete').html('');
               var file = document.getElementById("profile_image_complete");
               file.value = file.defaultValue;
          }
          
        });
        
       $(document).on('change', '#doc_doc', function(){
          var name = document.getElementById("doc_doc").files[0].name;
          var form_data = new FormData();
          var ext = name.split('.').pop().toLowerCase();
          if(jQuery.inArray(ext, ['doc','docx','pdf','ppt','jpeg','jpg','png','bmp']) == -1) 
          {
               toastr.error('It will accept only doc, docx, pdf, ppt, jpeg, jpg, png, bmp format files.', 'Alert!', {timeOut: 4000});
               $('#doc_doc').html('');
               var file = document.getElementById("doc_doc");
               file.value = file.defaultValue;
          }
          
        });
        
       $(document).on('change', '#doc_doc1', function(){
          var name = document.getElementById("doc_doc1").files[0].name;
          var form_data = new FormData();
          var ext = name.split('.').pop().toLowerCase();
          if(jQuery.inArray(ext, ['doc','docx','pdf','ppt','jpeg','jpg','png','bmp']) == -1) 
          {
               toastr.error('It will accept only doc, docx, pdf, ppt, jpeg, jpg, png, bmp format files.', 'Alert!', {timeOut: 4000});
               $('#doc_doc').html('');
               var file = document.getElementById("doc_doc1");
               file.value = file.defaultValue;
          }
          
        });
    
    });
</script>
  </body>
</html>