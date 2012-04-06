<?
    include "includes.php";
    //The main mobile page will set the session variable 'nasid' to contain the name of the AP which contacted the login page.
    //We can then use this variable's value to get detail from the BD about the default realm assigned to this NAS
    //We start with the photo id of 0 (zero) to display the firts photo in the list then the next button will call photo_nr + 1 etc
    if (array_key_exists('photo_nr',$_GET)){ 
        $current_nr = $_GET['photo_nr'];
    }else{
        $current_nr = 0;
    }
    session_start();
    $ap_login_data = file_get_contents($settings['nas_url_info'].urlencode($_SESSION['nasid']));   
    $ap_login_array = json_decode($ap_login_data, true, 5);

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Photo <? echo $current_nr+1 ?> of <? echo count($ap_login_array['json']['photos']) ?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">  
        <link rel="stylesheet" href="css/jquery.mobile.css" />
        <script type="text/javascript" src="js/jquery.min.js"></script>
        <script type="text/javascript" src="js/jquery.mobile.js"></script>  
    </head>
    <body>
        <div data-role="page" data-add-back-btn="true" data-page-type='image'>
            <div data-role="header" data-theme="b">
               <!-- data-rel="back" -->
                <? 
                    if($current_nr > 0){
                        echo "<a href=\"index.html\" data-rel=\"back\" data-icon=\"arrow-l\">Previous</a>\n";
                    }
                ?>
                <h1>Photo <? echo $current_nr+1 ?> of <? echo count($ap_login_array['json']['photos']) ?></h1>
                <?  $next = $current_nr+1;
                    if($next < count($ap_login_array['json']['photos'])){
                        echo "<a href=\"gallery.php?photo_nr=$next\" data-icon=\"arrow-r\" class=\"ui-btn-right\">Next</a>\n";
                    }
                ?>
            </div>
            <div class='center' data-role="content">
                <div data-type='gallery'>
                    <h2><? echo $ap_login_array['json']['photos'][$current_nr]['title'] ?></h2>
                    <span><? echo $ap_login_array['json']['photos'][$current_nr]['description'] ?></span>
                </div>
               <img  alt="<? echo$ap_login_array['json']['photos'][$current_nr]['file_name'] ?>">     
            </div>
        <!-- Include the footer -->
           <? echo $footer ?>
        </div>
    </body>
</html>


