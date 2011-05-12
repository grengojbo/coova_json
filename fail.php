<?
    $userurl   = $_REQUEST['userurl'];
    $reason    = $_REQUEST['reply'];
?>
<html>
 <head>
    <title>YFi Hotspot</title>
    <link href="img/logo.ico" type="image/x-icon" rel="icon"/><link href="img/logo.ico" type="image/x-icon" rel="shortcut icon"/>
    <style type="text/css">
        @import "css/generic.css";
    </style>
    </head>
    <body>
    <div id="content">
        <div id="trans-border">
            <h3>Authentication Failure</h3>
            <b>Reason: </b><? echo($reason); ?><br>
            <a href="<? echo($userurl); ?>">Try Again</a>
        </div>
    </div>
    </body>
</html>
