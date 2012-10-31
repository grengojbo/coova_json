<?php

    //Settings Array
    $settings   = array();

    $settings['realm']                  = 'ri';    //This is used in the registed dialog (simply to display the realm) eg dvdwalt (@ri)
    $settings['provider']               = 'Your Friendly Hotspot Provider';    //This is simply listed on the landing page
    $settings['3rd_url_create_perm']    = 'http://127.0.0.1/c2/yfi_cake/third_parties/json_create_permanent/';  //URL of 3rd party web service
    $settings['3rd_key']                = '123456789';          //Key for security on 3rd party web service 
    $settings['3rd_realm_name']         = 'Residence+Inn';      //The default realm name used by 3rd party
    $settings['3rd_profile']            = 'Permanent+250M+CAP'; //The profiles the permanent user will be created with
    $settings['3rd_cap']                = 'hard';               //The cap the permanent user will be created with (hard/soft/pre-paid)
    
    $settings['nas_url_info']           = 'http://127.0.0.1/c2/yfi_cake/login_pages/json_login_info/';  //Info on the NAS
    $settings['url_image_scaler']       = '/c2/yfi_cake/webroot/files/image.php';  //Info on the NAS

    $settings['url_nas_list']           = 'http://127.0.0.1/c2/yfi_cake/nas/json_nas_map_public/full';  //List of NAS devices and their status

    //Some includes
    $footer =  "<div data-role=\"footer\" data-position=\"fixed\" data-theme=\"b\">\n".
                    "<h3>WiFi supplied by my friendly hotspot provider</h3>\n".
                "</div>\n";

?>

