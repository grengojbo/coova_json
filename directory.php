<?
    include "includes.php";

    $nas_list        = file_get_contents($settings['url_nas_list']);   
    $nas_list_array  = json_decode($nas_list, true, 5);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Hotspot directory</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
 
        <link rel="stylesheet" href="css/jquery.mobile.css" />
        <link rel="stylesheet" href="css/generic_mobile.css" />

        <script type="text/javascript" src="js/jquery.min.js"></script>
        <script type="text/javascript" src="js/jquery.mobile.js"></script>

    </head>
    <body>
        <div data-role="page" data-add-back-btn="true" id='directory'>
           
            <div data-role="header" data-theme="b">
                <h1>Hotspot directory</h1>
            </div>
            <!-- Feedback message -->
            <div data-role="collapsible-set" style="margin: 10px; max-width:400px; text-align:center;">
            <?

                //----- Build the page by looping through the list of NAS devices that has goe data (lon & lat)
                foreach($nas_list_array['maps']['items'] as $item){

			$address = $item['r_street_no']." ".$item['r_street']."<br>".$item['r_town']."<br>".$item['r_city'];

                    if($item['available'] == 1){

                        print "<div data-role=\"collapsible\" data-theme=\"f\" data-content-theme=\"f\">\n";
                    }else{

                        print "<div data-role=\"collapsible\" data-theme=\"g\" data-content-theme=\"g\">\n";
                    }

                    print "<h3>".$item['name']."</h3>\n";

                        print "<ul style='text-align:left;'>\n";
                        print "<li><b>Description </b>".$item['description']."</li>\n";
                        print "<li><b>Address </b><br>".$address."</li>\n";
                        print "<li><b>Phone </b>".$item['r_phone']."</li>\n";
                        print "<li><b>Website </b><a href='".$item['r_url']."' >".$item['r_url']."</a></li>\n";
                        print "</ul>\n";

                    print "<div>\n";
                    print "</div>\n";
                    print "</div>\n"; 
			//print_r($item);
                }
            ?>
            <!-- END  -->            
            </div>

            <!-- Include the footer -->
           <? echo $footer ?>
        </div>
    </body>
</html>


