<?
    //The main mobile page that is called will establish a session variable which specifies the AP's NAS id
    include "includes.php";

    if (array_key_exists('nasid',$_GET)){ 
        $nasid = $_GET['nasid'];
    }else{
        $nasid = 'Residence';
    }  
    
    //Set a session variable containing the nasid
    session_start();
    $_SESSION['nasid']=$nasid;

    $ap_login_data  = file_get_contents($settings['nas_url_info'].urlencode($_SESSION['nasid']));   
    $ap_login_array = json_decode($ap_login_data, true, 5);
    $height         = 100;
    $width          = 50;
    $img_scaler     = $settings['url_image_scaler'].'?height='.$height.'&width='.$width.'&image=';
  //  print_r($ap_login_array);

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Hotspot @ <? echo $nasid ?></title>
         <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"> 
        <link rel="stylesheet" href="css/jquery.mobile.css" />
        <link rel="stylesheet" href="css/generic_mobile.css" />
        <script type="text/javascript" src="js/jquery.min.js"></script>
        <script type="text/javascript" src="js/jquery.cookie.js"></script>
        <script type="text/javascript" src="js/jquery.mobile.js"></script>
        <script type="text/javascript" src="js/custom.js"></script>
        <script>

        //This is the events for the gallery pages if it is loaded
        $(document).delegate('[data-page-type="image"]', 'pageshow', function () {
            Gallery.p_url_img_scaler = '<? echo $settings['url_image_scaler'] ?>';
            Gallery.f_img_loader(this);
        });

        //This is the events for the status page if it is loaded
        $(document).delegate('#cnct_status', 'pageshow', function () {

            //This counter will count down and trigger a refresh when it comes to zero!
            if(Coova.p_counter == undefined){   //This is the first time so we will refresh the controls and start the counter
              //  console.log('first refresh....');
                Coova.f_refresh();
                Coova.p_counter = setInterval (function(){
                    Coova.p_time = Coova.p_time -1; 
                    $('#status_refresh').html(Coova.p_time); 
                    if(Coova.p_time == 0){      //Each time we reach null we refresh the screens
                       // console.log('Refresh page....');
                        Coova.p_time = Coova.p_refresh;
                        Coova.f_refresh();
                    }
                }, 1000 );
            }
        });

        $(document).delegate('#cnct_status', 'pagehide',function(event, ui){
           // console.log( 'This page was just shown: ');
            clearInterval(Coova.p_counter);
            Coova.p_counter = undefined;
            Coova.p_time = Coova.p_refresh //Reset the timer
        });

         //This is the events for the status page if it is loaded
        $(document).delegate('#cnct_login', 'pageshow', function () {
           // console.log("Do Cookie Check....");
            Coova.f_cookie_check();
        });

        //Submit of connect form
        $(document).delegate("a[name=btn_connect]", 'click',function(){
            Coova.f_connect();
        });

        //Submit the connect voucher form
        $(document).delegate("a[name=btn_connect_voucher]", 'click',function(){
            Coova.f_connect_voucher();
        });


        $(document).ready (doc_ready);

        function doc_ready(){
            //This is the login page's inteligence
            //If a click happens we need to see which page has to be displayed based on the feedback from Coova
            $('#connect').bind('click',function(event){
                Coova.f_refresh();               
            });
        }
        </script>
    </head>
    <body>
        <div data-role="page">
            <div data-role="header" data-theme="b">
                <h1>Hotspot @ <? echo $ap_login_array['json']['info']['name']?></h1>
            </div>
            <div data-role="content" id='c_home'>
                <div data-type='gallery'>
              <!--  <p class='center'><img src="img/hotel_room.jpeg" /></p> -->
                </div>
                <div>
                <div style="width:48px; height:48px; float: left">
                <img src="<? echo $img_scaler.$ap_login_array['json']['info']['icon_file_name'] ?>" >
                </div>		
		        <ul data-role="listview" data-inset="true" style="margin-left:50px;">
			        <li data-role="list-divider"><? echo $ap_login_array['json']['info']['name']?></li>
			        <li><a href="gallery.php?photo_nr=0">Gallery</a></li>
			        <li><a href="about.php" >About</a></li>
		        </ul>
                </div> 
                <div >
                <div style="width:48px; height:48px; float: left">
                <img src="img/logo1.png" >
                </div>
                <ul data-role="listview" data-inset="true" data-divider-theme="a" data-theme="d" style="margin-left:50px;">   
			        <li data-role="list-divider"><? echo $settings['provider'] ?></li>
                    <li id='connect'><a href="#"><img src="img/connect.png" alt="Connect" class="ui-li-icon">Connect</a></li>
                    <li><a href="directory.php"><img src="img/map.png" alt="Map" class="ui-li-icon">Hotspot directory</a></li>
			        <li><a href="about_provider.php"><img src="img/info.png" alt="Info" class="ui-li-icon">About</a></li>
		        </ul> 
                </div>
            </div>
            <!-- Include the footer -->
           <? echo $footer ?>
        </div>
    </body>
</html>


