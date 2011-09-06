<? 
require_once('config.php');
require_once('db.connector.php');
require_once('debug.logger.php');
session_start();

function isLoggedIn()
{
	if (isset($_SESSION['mindpad.ID']))
	{ return true; }
	return false;
}

function getCommonErrorString($cause) {
	return "<span style='color:red;font-weight:bold;'>Error occurred:</span> " . $cause . "<br/> Please review your input in light of error. If problem exists please notify support thru feedback.";
}

function prettyDate($inDate, $format = 'ga, d M') {
	$date = date_create($inDate);
	return date_format($date, $format);
}
?>