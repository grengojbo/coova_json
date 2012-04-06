<?

    include "includes.php";
    session_start();
    $ap_login_data = file_get_contents($settings['nas_url_info'].urlencode($_SESSION['nasid']));   
    $ap_login_array = json_decode($ap_login_data, true, 5);
    $heigh          = 100;
    $width          = 200;
    $img_scaler     = $settings['url_image_scaler'].'?height='.$height.'&width='.$width.'&image=';
    
  //  echo "koos";
?>
<!DOCTYPE html>
<html>
    <head>
        <title>About Page <? echo $_SESSION['views'] ?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">  
        <link rel="stylesheet" href="css/jquery.mobile.css" />
        <script type="text/javascript" src="js/jquery.min.js"></script>
        <script type="text/javascript" src="js/jquery.mobile.js"></script>  
    </head>
    <body>
        <div data-role="page" id="about" data-add-back-btn="true" >
            <div data-role="header" data-theme="b">
                <h1>About us</h1>
            </div>
            <div data-role="content">
                <ul data-role="listview" data-inset="true" data-divider-theme="a" style="max-width:400px; text-align:center;">
			        <li data-role="list-divider">About</li>
                    <li><p class="center"><img src="<? echo $img_scaler.$ap_login_array['json']['info']['icon_file_name'] ?>" /></p></li>
                    <li><strong>Phone</strong><span class='col_right'><? echo $ap_login_array['json']['info']['phone'] ?></span></li>
                    <li><strong>Fax</strong><span class='col_right'><? echo $ap_login_array['json']['info']['fax'] ?></span></li>
                    <li><strong>Mobile</strong><span class='col_right'><? echo $ap_login_array['json']['info']['cell'] ?></span></li>
			<?

				//Form the address wit the new fields
				$address = $ap_login_array['json']['info']['street_no']." ".$ap_login_array['json']['info']['street']."<br>".$ap_login_array['json']['info']['town_suburb']."<br>".$ap_login_array['json']['info']['city'] ;
	
			?>

                    <li style="height: 60px;"><strong>Address</strong><span class='col_right'><? echo $address ?></span></li>
                    <li><a href=" <? echo $ap_login_array['json']['info']['url'] ?>"><? echo $ap_login_array['json']['info']['url'] ?></a></li>
                    <li><a href="mailto: <? echo $ap_login_array['json']['info']['email'] ?>"><? echo $ap_login_array['json']['info']['email'] ?></a></li>
		        </ul>
            </div>
        </div>
    </body>
</html>


