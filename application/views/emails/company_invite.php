<body style="font-family: 'Source Sans Pro', sans-serif; padding:0; margin:0;">
    <table style="max-width: 750px; margin: 0px auto; width: 100% ! important; background: #F3F3F3; padding:30px 30px 30px 30px;" width="100% !important"border="0" cellpadding="0" cellspacing="0">
        <tr>
             <?php $backend_assets =  base_url().'backend_assets/'; ?>
        <td style="text-align: center; background: #fff;">
                <table width="100%" border="0" cellpadding="30" cellspacing="0">
                    <tr>
                        <td style="padding:0px;">
                             <span id="logo" style="color:black;"> <img src="<?php echo $backend_assets; ?>img/logo.png" alt="<?= SITE_NAME; ?>"></span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;">
                <table width="100%" border="0" cellpadding="30" cellspacing="0" bgcolor="#fff">
                    <tr>
                        <td>
                            <h3 style="color: #333; font-size: 28px; font-weight: normal; margin: 0; text-transform: capitalize;">Invitation</h3>
                            <p style="text-align: left; color: #333; font-size: 16px; line-height: 28px;">Hello <?php echo $full_name; ?>,</p>
                            <p style="text-align: left;color: #333; font-size: 16px; line-height: 28px;">Bild It Superadmin has created your company account on Bild It App/Website. Please click on "Accept" button to complete your enrollment: </p>
                            <table class="body-action" align="center" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td align="center">
                                                    <table border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td>
                                                                <a style="border: 10px #dd4b39 solid;background:#dd4b39;color: white;text-decoration: none;" class="button button--green" href="<?php echo $url; ?>">Accept</a>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                
                                    </td>
                                </tr>
                            </table> 
                            <p style="text-align: left;color: #333; font-size: 16px; line-height: 28px;">If you have already accepted the request, please ignore this message.</p>  
                            <p style="text-align: left;color: #333; font-size: 16px; line-height: 28px;">Thanks,<br><?php echo SITE_NAME; ?> team</p>  
                 <a href="">
                   <img src="<?php echo $backend_assets; ?>img/forios.png"  alt="img" width="262" height="66">
                   
                </a>
                <a href="<?php echo android ?>" download="<?php echo android ?>">
                   <img src="<?php echo $backend_assets; ?>img/forandroid.png"  alt="img" width="262" height="66">
                </a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
           
            <td style="text-align: center;">
                <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#fff">
                    <tr>
                        <td style="padding: 10px;background: #23c466;color: #fff;">Copyright &copy; <?php echo date('Y'); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
<!-- </html> -->