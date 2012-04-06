<?
    $uamsecret = 'greatsecret';                         //Shared secret between chilli and uam json service

    header('Content-type: application/javascript');     //Output as JavaScript

    //We need 4 things
    $callback   = $_GET["callback"];
    $username   = $_GET["username"];
    $password   = $_GET["password"];
    $challenge  = $_GET["challenge"];
    
    $pappassword = return_new_pwd($password,$challenge,$uamsecret);

    print $callback."({'response':'".$pappassword."'})";

    //Function to do the encryption thing of the password
    function return_new_pwd($pwd,$challenge,$uamsecret){

        $hex_chal   = pack('H32', $challenge);                  //Hex the challenge
        $newchal    = pack('H*', md5($hex_chal.$uamsecret));    //Add it to with $uamsecret (shared between chilli an this script)
        $response   = md5("\0" . $pwd . $newchal);              //md5 the lot
        $newpwd     = pack('a32', $pwd);                        //pack again
        $md5pwd     = implode ('', unpack('H32', ($newpwd ^ $newchal))); //unpack again
        return $md5pwd;
    }
  
?>

