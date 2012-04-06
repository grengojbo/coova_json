<?

    $uamsecret  = 'greatsecret';            //Change this to be the same as your chilli's configuration
	$username   = $_POST['username'];
	$password   = $_POST['password'];
	$challenge  = $_POST['challenge'];
	$redir	    = $_POST['userurl'];

    //--Add a remember me cookie---
    if( array_key_exists('remember',$_POST)){
        $Year = (2592000*12) + time();
        setcookie("hs[username]",   $username, $Year);
        setcookie('hs[password]',        $password, $Year);
    }

    //--There is a bug that keeps the logout in a loop if userurl is http%3a%2f%2f1.0.0.0 ---/
    //--We need to remove this and replace it with something we want
    if (preg_match("/1\.0\.0\.0/i", $redir)) {

        $default_site = 'google.com';
        $pattern = "/1\.0\.0\.0/i";
        $redir = preg_replace($pattern, $default_site, $redir);
    }

	$enc_pwd    = return_new_pwd($password,$challenge,$uamsecret);
	$server_ip 	= '10.1.0.1';
	$port		= '3990';
	//$dir		= '/json/logon';
	$dir		= '/logon';
    $target     = "http://$server_ip".':'.$port.$dir."?username=$username&password=$enc_pwd&userurl=$redir";
   // print($target);

	header("Location: $target");

	//Function to do the encryption thing of the password
	function return_new_pwd($pwd,$challenge,$uamsecret){
	        $hex_chal   = pack('H32', $challenge);                  //Hex the challenge
	        $newchal    = pack('H*', md5($hex_chal.$uamsecret));    //Add it to with $uamsecret (shared between chilli an this script)
	        $response   = md5("\0" . $pwd . $newchal);              //md5 the lot
	        $newpwd     = pack('a32', $pwd);                //pack again
	        $password   = implode ('', unpack('H32', ($newpwd ^ $newchal))); //unpack again
	        return $password;
    	}

?>
