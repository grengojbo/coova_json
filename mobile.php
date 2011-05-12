<?
	$challenge = $_REQUEST['challenge'];
	$userurl   = $_REQUEST['userurl'];
	$res	   = $_REQUEST['res'];
	$qs        = $_SERVER["QUERY_STRING"];

	if($res == 'success'){

		header("Location: $userurl");
		print("\n</html>");
	}

	if($res == 'failed'){

		header("Location: fail.php?".$qs);
		print("\n</html>");

	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>Mobile Phone Login Page</title>
        <link href="img/logo.ico" type="image/x-icon" rel="icon"/><link href="img/logo.ico" type="image/x-icon" rel="shortcut icon"/>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="pragma" content="no-cache" />
        <meta http-equiv="expires" content="-1" />
    </head>
<body style="background:white;">
   <!-- FORM TO LOG IN -->
   <form action="login.php" method="post">
    <input type="hidden" name="challenge" value="<? echo($challenge) ?>" />
    <input type="hidden" name="userurl" value="<? echo($userurl) ?>" />
   <table>
    <tr>
        <td>Username</td>
        <td>
        <input type="text" name="username" />
        </td>
    </tr>
    <tr>
        <td>Password</td>
        <td>
        <input type="password" name="password" />
        </td>
    </tr>
    <tr>
        <td></td>
        <td>
        <input type="submit" value="OK" />
        </td>
    </tr>
    </table>
</form>
</body>
</html>

