<body style="font-family: 'Source Sans Pro', sans-serif; padding:0; margin:0;">
    <table style="max-width: 750px; margin: 0px auto; width: 100% ! important; background: #F3F3F3; padding:30px 30px 30px 30px;" width="100% !important" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td style="text-align: left; background: #fff;">
                <table width="100%" border="0" cellpadding="30" cellspacing="0">
                    <tr>
                        <td>
                            <img style="max-width: 125px; width: 100%;padding: 10px;"  src="<?= base_url(); ?>backend_assets/img/logo.png" >
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
                            <h3 style="color: #333; font-size: 28px; font-weight: normal; margin: 0; text-transform: capitalize;">Weather Alert</h3>
                            <p style="text-align: left;color: #333; font-size: 16px; line-height: 28px;">Please check weather alert for below customers: </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <!-- customer -->
        <?php if(!empty($customerNotification)){    
            for ($x=0; $x <sizeof($customerNotification) ; $x++) {          
        ?>
        <tr bgcolor="#fff">
            <td style="text-align: left;padding: 5px">
                <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#fff">
                    <tr>
                        <td style="padding: 10px;background: #cccccc;color: #000;"><strong><?= ucfirst($customerNotification[$x]['customerName']); ?> -<?= sizeof($customerNotification[$x]['addresses']); ?></strong> <span style="float:right;"><b><?= $customerNotification[$x]['email']; ?></b></span></td>
                        <!-- email -->
                    </tr>
                </table>
            </td>
        </tr>        
        <tr bgcolor="#fff">
            <td style="text-align: center; padding: 10px;">
                 <table width="100%" border="1" bordercolor="#fff" cellpadding="5" cellspacing="0" bgcolor="#fff">
                    <thead>
                        <tr  bgcolor="#EFF0F0">
                            <th>ID</th>
                            <th>Address</th>
                            <th>Date</th>
                            <th>Temperature</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $addresses = $customerNotification[$x]['addresses'];
                        foreach ($addresses as $y => $ad) {
                        ?>
                        <tr bgcolor="#EFF0F0">
                           <td><?= ($y+1); ?></td>
                           <td><span style="color:blue;"><?= $ad->address; ?></span></td>
                           <td><?= date("d/m/Y",strtotime($ad->alertDate)).' '.$ad->alertTime; ?></td>
                           <td><b style="color:red;"><?= number_format($ad->temperature,2).'° C'; ?></b></td>
                        </tr> 
                        <?php }?>
                    </tbody>
                    
                </table>
            </td>
        </tr>  
        <?php } } ?>       
        
        <!-- customer -->
                <tr>
            <td style="text-align: center;">
                <table width="100%" border="0" cellpadding="30" cellspacing="0" bgcolor="#fff">
                    <tr>
                        <td>
                            <p style="text-align: left;color: #333; font-size: 16px; line-height: 28px;">Please open dashboard for more details: </p>
                            <table class="body-action" align="center" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td align="center">
                                                    <table border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td>
                                                                <a style="border: 10px #305C86 solid;background:#305C86;color: white;text-decoration: none;" class="button button--green" href="<?= $url;?>">Open Dashboard</a>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table> 
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;">
                <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#fff">
                    <tr>
                        <td style="padding: 10px;background:#2A2725;color: #fff;">Copyright &copy; <?php echo date('Y'); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>