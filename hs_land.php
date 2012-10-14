<?
	$challenge = $_REQUEST['challenge'];
	$userurl   = $_REQUEST['userurl'];
	$res	   = $_REQUEST['res'];
	$qs        = $_SERVER["QUERY_STRING"];

    $uamip     = $_POST['uamip'];
    $uamport   = $_POST['uamport'];


    //--There is a bug that keeps the logout in a loop if userurl is http%3a%2f%2f1.0.0.0 ---/
    //--We need to remove this and replace it with something we want
    if (preg_match("/1\.0\.0\.0/i", $userurl)) {
        $default_site = 'google.com';
        $pattern = "/1\.0\.0\.0/i";
        $userurl = preg_replace($pattern, $default_site, $userurl);
    }
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

                $uamsecret  = 'greatsecret';
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
            return $password;
    }

    //-- End Cookie add on ------------

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>YFi Hotspot</title>
        <link href="img/logo.ico" type="image/x-icon" rel="icon"/><link href="img/logo.ico" type="image/x-icon" rel="shortcut icon"/>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="pragma" content="no-cache" />
        <meta http-equiv="expires" content="-1" />

        <script type="text/javascript" src="js/round_corners/shadedborder.js"></script>
        <script type="text/javascript" src="js/dojo/dojo.js" djConfig="parseOnLoad: true"></script>
        <style type="text/css">
            @import "css/generic.css";
        </style>
         <script language="javascript" type="text/javascript">
            var transBorder       = RUZEE.ShadedBorder.create({ corner:15, border:8, borderOpacity:0.4 });
        </script>
</head>

<body>
<div id="content">
    <div>
        <a href="mobile.php?<? echo($qs) ?>" class='link_space'>Mobile Phone Login</a> <a href="/yfi/uam_json.html?<? echo($qs) ?>" class='link_space'>JSON Login Page</a>
    </div>
    <div id="trans-border">
        <div class='transbox' style="margin-top: 10px; width: 280px;" id='workspace'>
            <img src="img/logo.jpg" alt="Logo" class='spaced' />
                <!-- FORM TO LOG IN -->
            <form name="login" action="login.php" method="post">
                <input type="hidden" name="uamip" value="<? echo($uamip) ?>" />
                <input type="hidden" name="uamport" value="<? echo($uamport) ?>" />

                <input type="hidden" name="challenge" value="<? echo($challenge) ?>" />
                <input type="hidden" name="userurl" value="<? echo(urlencode($userurl)) ?>" />

                <label for="username" id='l_username'>Username</label> <input class="input" name="username" type="text"/>
                <br />
                <label for="password" id='l_password'>Password </label> <input class="input" name="password" type="password"/>
                <br />
                <label for="language" id='l_language'>Language </label> <select id='sel_lang' class="input" onchange="changeLanguage();">
                                                            <option value='en' selected='selected' label='English'>English</option>
                                                            <option value='af_ZA' label='Afrikaans'>Afrikaans</option>
                                                            <option value='fr_FR' label='French'>French</option>
                                                            <option value='nl_NL' label='Netherlands'>Netherlands</option>
                                                            <option value='id_ID' label='Indoensian'>Indonesian</option>
                                                            <option value='es_ES' label='Spanish'>Spanish</option>
                                                    </select>
                <br />
                <!-- Add a remember me clause -->
                <label for="remember" id='l_remember'>Remember me</label><input type="checkbox" name="remember" value="remember" />
                <br />
                <!-- End Add a rememeber me clause -->
                <label>&nbsp; </label> <input type="submit" value="OK" class="button" />
                </form>
        </div> 
    </div>
  </div>

<script type="text/javascript">
  document.login.username.focus();
</script>

<!-- ____________ CUSTOM JS for SILDESHOW and fancy borders _______________ -->
<script language="javascript" type="text/javascript">
    transBorder.render('trans-border');
    dojo.addOnLoad(function() {
        console.log("Dojo Loaded OK");
        dojo.require('mikrotik.Translator');
        dojo.require('dojo.cookie');
        var intervalID  = setInterval(doInterval, (10*1000));
        var backgrounds = Array('1.jpg','2.jpg','3.jpg','4.jpg','5.jpg');
        var current_img = 0;

        //Set the language
        var l = dojo.cookie("language");
        if( l != undefined){
            dojo.byId('sel_lang').value = dojo.cookie("language");
            _translate(l);
        }

        function doInterval(){

            console.log("Change Graphic");
            if(backgrounds[current_img] != undefined){
                console.log("Changing bg to",backgrounds[current_img]);
                dojo.query(".sb-inner",dojo.byId('trans-border')).style('background','url(img/'+backgrounds[current_img]+')');
                current_img++;
            }else{
                current_img=0;
                console.log("Changing bg to",backgrounds[current_img]);
                dojo.query(".sb-inner",dojo.byId('trans-border')).style('background','url(img/'+backgrounds[current_img]+')');
            }
        }
    });

    function changeLanguage(){
        console.log("Change Languages");
        var lang        = dojo.byId('sel_lang').value;
        dojo.cookie("language",lang, {expires : 30});
        _translate(lang);
    }

    function _translate(lang){
        var tr                              = mikrotik.Translator;
        dojo.byId('l_username').innerHTML   = tr.tr({'module': 'Mikrotik','phrase':'Username','lang':lang});
        dojo.byId('l_password').innerHTML   = tr.tr({'module': 'Mikrotik','phrase':'Password','lang':lang});
        dojo.byId('l_language').innerHTML   = tr.tr({'module': 'Mikrotik','phrase':'Language','lang':lang});
        dojo.byId('l_remember').innerHTML   = tr.tr({'module': 'Mikrotik','phrase':'Remember me','lang':lang});

        dojo.query("option",dojo.byId('sel_lang')).forEach(function(item){
            console.log("Value is ",item.label, lang);
            item.innerHTML=tr.tr({'module': 'Mikrotik','phrase':item.label,'lang':lang});;
        });
    }
  </script>
</body>
</html>
