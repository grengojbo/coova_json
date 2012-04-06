<?
    include "includes.php";

?>
<!DOCTYPE html>
<html>
    <head>
        <title>About <? echo $settings['provider']; ?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
 
        <link rel="stylesheet" href="css/jquery.mobile.css" />
        <link rel="stylesheet" href="css/generic_mobile.css" />

        <script type="text/javascript" src="js/jquery.min.js"></script>
        <script type="text/javascript" src="js/jquery.mobile.js"></script>

    </head>
    <body>
        <div data-role="page" data-add-back-btn="true" id='about_provider'>
            <div data-role="header" data-theme="b">
                <h1>About <? echo $settings['provider']; ?></h1>
            </div>
            <!-- Feedback message -->
            <div data-role="collapsible-set" style="margin: 10px; max-width:400px; text-align:center;">
                 <div data-role="collapsible" data-collapsed="false" data-theme="a" data-content-theme="c">
	                <h3>Who are we?</h3>
                     <div>
			<p align='left'>
                       	We are the biggest provider of Wi-Fi Hotspots to the hotel industry in the world.<br>
			You can rest asure that this service you want to use has already been used by many people before you.<br>
			We have more that 10 years solid experience of provinding Wi-Fi Hotspots.
			</p>
                    </div>
                </div> 
                <div data-role="collapsible" data-theme="a" data-content-theme="c">
	                <h3>Contact US</h3>
                     <div>
                       We are just around the corner....
                    </div>
                </div> 
                    
                <!-- END Form to connect -->            
            </div>

            <!-- Include the footer -->
           <? echo $footer ?>
        </div>
    </body>
</html>


