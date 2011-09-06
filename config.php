<?
//DB
$dbUser = "root";
$dbPass = "return";
$dbURL = "localhost";
$dbSchema = "mindpad";
$dbType = "pdo_mysql";
$dbConfig = array(
	'host' => $dbURL,
	'username'   => $dbUser,
	'password'   => $dbPass,
	'dbname'     => $dbSchema
);

//PATHS
$serverUrl = "http://localhost";
$serverRoot = "/xnet_dev/xnetos/apps/mindpad/";

//OTHER
$loglevel=0; //0-5, with 0 being lowest
$salt="987654321123456789XYZ";
?>