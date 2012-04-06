<?
    include "includes.php";

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Connect to Internet</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
 
        <link rel="stylesheet" href="css/jquery.mobile.css" />
        <link rel="stylesheet" href="css/generic_mobile.css" />

        <script type="text/javascript" src="js/jquery.min.js"></script>
        <script type="text/javascript" src="js/jquery.mobile.js"></script>

    </head>
    <body>
        <div data-role="page" data-add-back-btn="true" id='cnct_login'>
            <div data-role="header" data-theme="b">
                <h1>Connect</h1>
            </div>
            <!-- Feedback message -->
            <div data-role="collapsible-set" style="margin: 10px; max-width:400px; text-align:center;">
                <div data-role="collapsible" data-collapsed="false" data-theme="a" data-content-theme="c">
	                <h3>Account Login</h3>
                    <!-- Form to connect -->
                    <div id='connect_fb' class='warn'>
                    </div>
                    <div data-role="fieldcontain">
                        <label for="Username">Username</label>
                        <input type="text" name="Username" id="Username" required="required">
                        <label for="Password">Password</label> 
                        <input type="password" name="Password" id="Password" required="required">
                        <input type="checkbox" name="remember" id="remember" class="custom" />       
                        <label for="remember">Auto Login</label>
                    </div>
                    <a href="#" data-role="button" name='btn_connect'>Login</a> 
                </div>
<!--
                <div data-role="collapsible" data-theme="a" data-content-theme="c">
	                <h3>Voucher Login</h3>
                    <div id='connect_fb_voucher' class='warn'>
                    </div>
                     <div data-role="fieldcontain">
                        <label for="voucher_password">Password</label> 
                        <input type="password" name="voucher_password" id="voucher_password" required="required">
                        <input type="checkbox" name="voucher_remember" id="voucher_remember" class="custom" />       
                        <label for="voucher_remember">Auto Login</label>
                    </div>
                    <a href="#" data-role="button" name='btn_connect_voucher'>Login</a>
                </div> 
-->
                    
                <!-- END Form to connect -->            
            </div>

            <!-- Include the footer -->
           <? echo $footer ?>
        </div>
    </body>
</html>


