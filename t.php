<?
  /*
  ini_set('error_reporting', E_ALL);
  error_reporting(E_ALL);
  ini_set('log_errors',TRUE);
  ini_set('html_errors',FALSE);
  ini_set('error_log','/var/log/chilli.log');
  ini_set('display_errors',FALSE);
  */
	$challenge = $_REQUEST['challenge'];
	$userurl   = $_REQUEST['userurl'];
	$res	   = $_REQUEST['res'];
	$qs        = $_SERVER["QUERY_STRING"];

    $uamip     = $_REQUEST['uamip'];
    $uamport   = $_REQUEST['uamport'];

    $key    = '123456789';
    $value  = '1-00-00-00';
    $profile= 'Free30min';
    // $profile= 'Test5min';
    $expires= '2010-07-01';
    $precede= 'sms';
    $realm  = 'OceanPLaza';

    $hotspot_portal = "http://127.0.0.1";
    // $default_site = urlencode('http://oceanplaza.com.ua');
    $default_site = 'http://oceanplaza.com.ua';
    $uamsecret  = 'greatsecret';

    $gen_voucher = $hotspot_portal."/c2/yfi_cake/third_parties/json_create_voucher/?".
                    "key=".$key.'&voucher_value='.$value.'&profile='.urlencode($profile).'&realm='.urlencode($realm);
                    #"key=".$key.'&voucher_value='.$value.'&profile='.urlencode($profile).'&expires='.$expires.'&precede='.$precede.'&realm='.urlencode($realm);
    //error_log(print_r("wget -q -O - '".$gen_voucher."'", TRUE));
    print_r("wget -q -O - '".$gen_voucher."'");
    $fb = exec("wget -q -O - '".$gen_voucher."'");
    //--- Sanitize the feedback a bit ------
    $fb = preg_replace("/^\(/","",$fb);
    $fb = preg_replace("/\)/","",$fb);
    $fb = preg_replace("/;/","",$fb);
    $json_array = json_decode($fb,true);

    if($json_array['json']['status'] == 'ok'){
        // echo "<b>Voucher id </b>".$json_array['voucher']['id']."<br>\n";
        // echo "<b>Voucher username </b>".$json_array['voucher']['username']."<br>\n";
        // echo "<b>Voucher password </b>".$json_array['voucher']['password']."<br>\n";
    }


    //--There is a bug that keeps the logout in a loop if userurl is http%3a%2f%2f1.0.0.0 ---/
    //--We need to remove this and replace it with something we want
    $userurl = $default_site;
    if (preg_match("/1\.0\.0\.0/i", $userurl)) {
        $pattern = "/1\.0\.0\.0/i";
        $userurl = preg_replace($pattern, $default_site, $userurl);
    }
    // $userurl = $default_site;
    //---------------------------------------------------------

	if($res == 'success'){

		header("Location: $userurl");
		print("\n</html>");
	}

	if($res == 'failed'){

		header("Location: fail.php?".$qs);
		print("\n</html>");

	}

    //-- cookie add on -------------------------------
    if($res == 'notyet'){

        if(isset($_COOKIE['hs'])){

                $dir        = '/logon';
                $userurl    = $_REQUEST['userurl'];
                $redir      = urlencode($userurl);

                $username   = $_COOKIE['hs']['username'];
                $password   = $_COOKIE['hs']['password'];
                $enc_pwd    = return_new_pwd($password,$challenge,$uamsecret);
                $target     = "http://$uamip".':'.$uamport.$dir."?username=$username&password=$enc_pwd&userurl=$redir";
                header("Location: $target");
                print("\n</html>");
        }
    }
    //Function to do the encryption thing of the password
    function return_new_pwd($pwd,$challenge,$uamsecret){
            $hex_chal   = pack('H32', $challenge);                  //Hex the challenge
            $newchal    = pack('H*', md5($hex_chal.$uamsecret));    //Add it to with $uamsecret (shared between chilli an this script)
            $response   = md5("\0" . $pwd . $newchal);              //md5 the lot
            $newpwd     = pack('a32', $pwd);                //pack again
            $password   = implode ('', unpack('H32', ($newpwd ^ $newchal))); //unpack again
            print_r("pwd: ".$pwd);
            print_r("challenge: ".$challenge);
            print_r("uamsecret: ".$uamsecret);
            print_r("hex_chal: ".$hex_chal);
            print_r("newchal: ".$newchal);
            print_r("response: ".$response);
            print_r("newpwd: ".$newpwd);

            print_r("password: ".$password);
            print_r("--------------------------------");
            return $password;
    }

    //-- End Cookie add on ------------

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ocean Plaza Wi-Fi</title>
<link rel="stylesheet" href="static/css/pure-min.css" media="screen">
<link rel="stylesheet" href="static/css/main.css" media="screen">

<link href="img/logo.ico" type="image/x-icon" rel="icon"/><link href="img/logo.ico" type="image/x-icon" rel="shortcut icon"/>
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="expires" content="-1" />

<script>

function submitInternet(){
    document.forms['login'].buttonClicked.value = 4;
    document.forms['login'].submit();
}

function submitADS(){
    //document.forms[0].userurl.value = "<? echo(urlencode($userurl)) ?>";
    document.forms['adlogin'].buttonClicked.value = 4;
    document.forms['adlogin'].submit();
}

function loadAction(){
      var url = window.location.href;
      var args = new Object();
      var query = location.search.substring(1);
      var pairs = query.split("&");
      for(var i=0;i<pairs.length;i++){
          var pos = pairs[i].indexOf('=');
          if(pos == -1) continue;
          var argname = pairs[i].substring(0,pos);
          var value = pairs[i].substring(pos+1);
          args[argname] = unescape(value);
      }
      //alert( "AP MAC Address is " + args.ap_mac);
      //alert( "The Switch URL is " + args.switch_url);
      document.forms['login'].action = args.switch_url;

      // This is the status code returned from webauth login action
      // Any value of status code from 1 to 5 is error condition and user
      // should be shown error as below or modify the message as it suits
      // the customer
      if(args.statusCode == 1){
        alert("You are already logged in. No further action is required on your part.");
      }
      else if(args.statusCode == 2){
        alert("You are not configured to authenticate against web portal. No further action is required on your part.");
      }
      else if(args.statusCode == 3){
        alert("The username specified cannot be used at this time. Perhaps the username is already logged into the system?");
      }
      else if(args.statusCode == 4){
        alert("Wrong username and password. Please try again.");
      }
      else if(args.statusCode == 5){
        alert("The User Name and Password combination you have entered is invalid. Please try again.");
      }
}

</script>
</head>
<body topmargin="50" marginheight="50">
<form name="adlogin" action="login.php" method="post">

    <input TYPE="hidden" NAME="buttonClicked" SIZE="16" MAXLENGTH="15" value="0">
    <input TYPE="hidden" NAME="redirect_url" SIZE="255" MAXLENGTH="255" VALUE="">
    <input TYPE="hidden" NAME="err_flag" SIZE="16" MAXLENGTH="15" value="0">
    <input TYPE="hidden" NAME="info_flag" SIZE="16" MAXLENGTH="15" value="0">
    <input TYPE="hidden" NAME="info_msg" SIZE="32" MAXLENGTH="31" value="0">

    <input type="hidden" name="uamip" value="<? echo($uamip) ?>" />
                <input type="hidden" name="uamport" value="<? echo($uamport) ?>" />

                <input type="hidden" name="challenge" value="<? echo($challenge) ?>" />
                <input type="hidden" name="userurl" value="<? echo(urlencode($userurl)) ?>" />
                <input type="hidden" name="password" value="<? echo($json_array['voucher']['password']) ?>">
                <input type="hidden" name="username" value="<? echo($json_array['voucher']['username']) ?>">
                <!-- <input type="checkbox" name="remember" value="remember" /> -->

<div class="pure-g-r" id="layout">
<div class="pure-u-1 visible-phone" id="main-phone">
<div class="pure-u-1" id="wifi-ad">
<script>document.forms['adlogin'].userurl.value = "http:%3A%2F%2Fbbmedia.com.ua";
if (window.matchMedia('(min-width: 769px)').matches) {
//if (window.matchMedia('(max-width: 480px)').matches) {
  document.write ('<INPUT TYPE="image" SRC="static/img/bbm320x350.gif" WIDTH="320"  HEIGHT="350" BORDER="0" ALT="SUBMIT! phone">');
}
//if (window.matchMedia('(min-width: 769px)').matches) {
if (window.matchMedia('(max-width: 768px)').matches) {
  document.write ('<INPUT TYPE="image" SRC="static/img/bbm768x840.jpg" WIDTH="768"  HEIGHT="840" BORDER="0" ALT="SUBMIT! table">');
}
//if (window.matchMedia('(min-width: 769px)').matches) {
//  document.write ('<INPUT TYPE="image" SRC="static/img/bbm960x450.jpg" WIDTH="960"  HEIGHT="450" BORDER="0" ALT="SUBMIT! desctop">');
//}
</script>
</div>
</form>
<form name="login" action="login.php" method="post">

    <input TYPE="hidden" NAME="buttonClicked" SIZE="16" MAXLENGTH="15" value="0">
    <input TYPE="hidden" NAME="redirect_url" SIZE="255" MAXLENGTH="255" VALUE="">
    <input TYPE="hidden" NAME="err_flag" SIZE="16" MAXLENGTH="15" value="0">
    <input TYPE="hidden" NAME="info_flag" SIZE="16" MAXLENGTH="15" value="0">
    <input TYPE="hidden" NAME="info_msg" SIZE="32" MAXLENGTH="31" value="0">

    <input type="hidden" name="uamip" value="<? echo($uamip) ?>" />
                <input type="hidden" name="uamport" value="<? echo($uamport) ?>" />

                <input type="hidden" name="challenge" value="<? echo($challenge) ?>" />
                <input type="hidden" name="userurl" value="<? echo(urlencode($userurl)) ?>" />
                <input type="hidden" name="password" value="<? echo($json_array['voucher']['password']) ?>">
                <input type="hidden" name="username" value="<? echo($json_array['voucher']['username']) ?>">
                <!-- <input type="checkbox" name="remember" value="remember" /> -->

<div class="pure-u-1"><div  id="footer"><button class="pure-button pure-input-1-1 pure-button-primary" onclick="submitInternet();">Internet</button></div></div>
</div>
</form>
</body>
</html>