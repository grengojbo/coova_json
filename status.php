<?
    include "includes.php";

    //This page should never be called directly ....

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Connected</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
 
        <link rel="stylesheet" href="css/jquery.mobile.css" />
        <link rel="stylesheet" href="css/generic_mobile.css" />

        <script type="text/javascript" src="js/jquery.min.js"></script>
        <script type="text/javascript" src="js/jquery.mobile.js"></script>

        <script>
      
        </script>

    </head>
    <body>
        <div data-role="page" id='cnct_status'>
            <div data-role="header" data-theme="b">
                <h1>Connected</h1>
            </div>

<!-- Content Start -->
<div data-role="content" data-theme="b">
    <ul data-role="listview" data-inset="true" data-divider-theme="a" data-theme="d">
        <!--<li><a href="http://10.1.0.1:3990/logoff"><img src="img/disconnect.png" alt="Disconnect" class="ui-li-icon">Disconnect</a></li>-->
        <li><a id='a_url_original' href="#" target="_blank"><img src="img/link.png" alt="URL" class="ui-li-icon"><span id='span_url_original'></span></a></li>
        <li><a href="http://1.0.0.0"><img src="img/disconnect.png" alt="Disconnect" class="ui-li-icon">Disconnect</a></li>
    </ul>

    <div data-role="collapsible-set" style="margin: 10px; max-width:400px; text-align:center;">
        <div data-role="collapsible" data-collapsed="false" data-theme="b" data-content-theme="b">
            <h3>Usage</h3>
            <div id='div_time'>
                <h3>Time</h3>
                <div id="lbl_slider-time"></div>
                <br style='clear:both'>
                <input type="range" name="slider" id="slider-time" value="25" min="0" max="100"  /><b>%</b>
            </div>
            <div id='div_data'>
                <h3>Data</h3>
                <div id="lbl_slider-data"></div>
                <br style='clear:both'>
                <input type="range" name="slider" id="slider-data" value="25" min="0" max="100" /><b>%</b>
	        </div>
        </div>
	    <div data-role="collapsible" data-theme="a" data-content-theme="c">
	        <h3>Session Detail</h3>
	        <ul data-role="listview" data-inset="true" data-divider-theme="a">
                <li><b>Idle Time</b><span class='col_right' id='acct_it'></span></li>
                <li><b>Session Time</b><span class='col_right' id='acct_st'></span></li>
                <li><b>Data in</b><span class='col_right' id='acct_di'></span></li>
                <li><b>Data out</b><span class='col_right' id='acct_do'></span></li>
                <li><b>Data total</b><span class='col_right' id='acct_dt'></span></li>
		    </ul>
	    </div>
    </div>
    <p ><strong>  Refreshing in </strong><span id='status_refresh' class='info'> </span><strong> seconds.</strong></p>
</div>
<!-- Content End -->
            <!-- Include the footer -->
           <? echo $footer ?>
        </div>
    </body>
</html>


