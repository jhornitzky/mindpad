<?
/**
 * Functions for retrieving and adding users to the database
 */
require_once("connector.php");

//Get the user details for a user
function authenticateUser($username,$password)
{
	global $salt;
	$db = dbConnect();
	
	//FIXME remove these unencrypted bits
	$query = sprintf("SELECT ID, username FROM Users WHERE (username = '%s' AND password = '%s')", cleanseString($db, $username), cleanseString($db, $password));
	$result = dbQuery($db, $query);
	if (dbNumRows($result) == 0) {
		$pass = sha1($salt.$password);
		$query = sprintf("SELECT ID, username FROM Users WHERE (username = '%s' AND password = '%s')", cleanseString($db, $username), $pass); 
		$result = dbQuery($db, $query);
	}
	dbClose($db);
	return $result;
}

function loginUser($username,$password)
{
	$rs = authenticateUser($username,$password);
	if (dbNumRows($rs) == 1)
	{
		$userObj = dbFetchObject($rs);
		$_SESSION['mindpad.ID'] = $userObj->ID;
		$_SESSION['isAuthen'] = true;
		$_SESSION['mindpad.username'] = $userObj->username;
		dbQuery("UPDATE Users SET LastLoginTime = '".date('Y-m-d H:i:s')."', LastLoginIP = '".$_SERVER['REMOTE_ADDR']."' WHERE ID = '".$userObj->ID."';");
		return true;
	}
	return false;
}

function registerUser($postArgs) {
	global $serverRoot, $salt;
	
	$link = dbConnect();
	
	$pass = sha1($salt.$postArgs['password']);

	//Now prepare and run this query
	$sql = sprintf("INSERT INTO Users (`username`, `password`, `firstName`, `lastName`, `email`) VALUES ('%s','%s','%s','%s','%s')",
		cleanseString($link,$postArgs['username']),
		$pass,
		cleanseString($link,$postArgs['fName']),
		cleanseString($link,$postArgs['lName']),
		cleanseString($link,$postArgs['email'])
	);
	
	logAudit('Register user with SQL: ' . $sql);
	$created = dbQuery($link, $sql);
	
	//Get the inserted ID
	$newUserId = dbInsertedId($link);

	//Create a new pad
	$sql = "INSERT INTO Pads (userId) VALUES ('$newUserId')";
	$created = dbQuery($link, $sql);
	
	//Tidy up
	dbClose($link);
	
	/*
	//Send confirmation
	$message = '<html>
				<head>
				  <title>xNET OS ~ :: ~ Credentials</title>
				</head>
				<body>
				  <p>xNET OS ~ Login Credentials</p>
				  <table>
					<tr>
					  <th>username:</th><td>'.$postArgs["username"].'</td>
					</tr>
					<tr>
					  <th>password:</th><td>'.$postArgs["password"].'</td>
					</tr>
				  </table>
				</body>
				</html>';

	$headers = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'From: nexOS@nexos.ravex.net.au' . "\r\n";

	mail($postArgs['email'], "xNET OS ~ Credentials", $message, $headers);
	*/
	
	//Now login the user for the first time
	if ($created) {
		$userObj = getUserInfo($newUserId);
		$_SESSION['mindpad.ID'] = $userObj->ID;
		$_SESSION['isAuthen'] = true;
		$_SESSION['mindpad.username'] = $userObj->username;
		dbQuery("UPDATE Users SET LastLoginTime = '".date('Y-m-d H:i:s')."', LastLoginIP = '".$_SERVER['REMOTE_ADDR']."' WHERE ID = '".$userObj->ID."';");
		return true;
	} else {
		return false;
	}
}

function checkUsernameExists($username) {
	//First open a connection
	$link = dbConnect();

	//Now prepare and run this query
	$sql = sprintf("SELECT * FROM Users WHERE username = '%s' ",
	cleanseString($link,$username));

	$result = dbQuery($link,$sql);

	$found=false;
	if (dbNumRows($result) > 0) {
		$found=true;
	}

	//Tidy up
	dbRelease($result);
	dbClose($link);

	return $found;
}

//Delete a user from the DB
function deleteUser($username) {
	//First open a connection
	$link = dbConnect();

	//Now prepare and run this query
	$sql = sprintf("DELETE FROM Users WHERE id = '%s'",
	cleanseString($link,$username));

	$result = dbQuery($link,$sql);

	//FIXME Error checks here
	dbClose($link);
}


function getUserInfo($id)
{
	$rs = dbQuery("SELECT * FROM Users WHERE (ID = '".$id."')");
	$row = dbFetchObject($rs);
	return $row;
}

function getAllUsers() {
	return dbQuery("SELECT * FROM Users");
}
?>
