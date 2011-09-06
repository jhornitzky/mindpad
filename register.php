<? 
require_once('connector.php');
logAudit('Registering');

$msg = '';

if (isset($_POST['action'])) { //Someone sent a register request
	unset($_POST['action']); //Clean it so it doesn't end up in any query
	require_once("user.service.php");
	
	if (checkUsernameExists($_POST['username']))
	{
		$msg = "Username Exists. Pick a different one";
	}
	else if (!strlen($_POST['username']) > 0)
	{
		$msg = "Username Required";
	}
	else if (!strlen($_POST['password']) > 0)
	{
		$msg = "Password Required";
	}
	else if(registerUser($_POST)) {
		header("Location: index.php");
		$msg = "Created user " . $_POST['username'] . "... Redirecting to mindpad";
		echo $msg;
		die();
	} else {
		$msg = "Failure creating user... you should check your fields";
	}
}
?>
<html>
<head>
<title>mindpad - register</title>
<meta name="viewport"
	content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/>
<meta name="apple-touch-fullscreen" content="YES" />
<style>
/* Custom styles */
html, body {
	font-family: arial,helvetica,clean,sans-serif;
}

input[type=text], input[type=password] {
	border:1px solid #000;
}

a {
	text-decoration:none;
	color:#AAA;
}

a:hover {
	text-decoration:underline;
	color:#444;
}

a#loginLink:hover {
	text-decoration:none;
}

</style>
</head>

<body style="width:100%; margin: 0 auto; text-align:center">

<div class="head" style="margin-top:1em; width:100%">
	<a href="index.php" style="color:#FFF;background-color:#FFF;border:none;"><img src="mindpad.jpg" style="width:212px; height:48px;"/></a>
	<p>Register to experience mindpadding</p>
	<div style="color:red"><?= $msg; ?></div>
</div>

<div class="body">
<form id="registerForm" action="register.php" method="post">
  <table id="regForm" cellpadding="3" cellspacing="3" style="margin: 0 auto;">
    <tr>
      <th style="text-align:right">First Name:</th>
      <td><input name="fName" type="text"  /></td>
    </tr>
    <tr>
      <th style="text-align:right">Last Name:</th>
      <td><input name="lName" type="text" /></td>
    </tr>
    <tr>
      <th style="text-align:right">Mindname:</th>
      <td><input name="username" type="text"/></td> 
    </tr>
    <tr>
      <th style="text-align:right">Password:</th>
      <td ><input name="password" type="password"/></td>
    </tr>
    <tr>
      <th style="text-align:right">eMail:</th>
      <td><input name="email" type="text" /></td>
    </tr>
    <tr>
    	<td colspan="2" style="text-align:center"><input type="submit" name="action" value="Register" style="border: 1px solid red; font-size:2.0em;margin-top:0.5em;"/></td>
    </tr>
  </table>
</form>
</div>

</body>
</html>