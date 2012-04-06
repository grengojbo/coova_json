<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
    <head>
    <title>YFi Hotspot Splash Page</title>
    <link href="img/logo.ico" type="image/x-icon" rel="icon"/><link href="img/logo.ico" type="image/x-icon" rel="shortcut icon"/>
    <?
        $login_url = urldecode($_SERVER["QUERY_STRING"]);
        $redir_url = preg_replace('/^loginurl=/','',$login_url);
    ?>
    <meta http-equiv="refresh" content="5; URL=<? print $redir_url; ?>"> 
    <style type="text/css">
        @import "css/generic.css";
    </style>
    </head>
    <body style="margin: 0pt auto; height:100%; background:white;">
        <div style="width:100%;height:80%;position:fixed;display:table;">
            <p style="display: table-cell; line-height: 2.5em; vertical-align:middle;text-align:center;color:grey;">
                <img src="img/logo.jpg" alt="logo" border="0" />
                <img src="img/busy.gif" alt="gif animation" /><br> redirecting...</p>
        </div>
    </body>
</html>
