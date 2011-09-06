<?
require_once('connector.php');
require_once('mobile.functions.php');

if (isMobile()) {
	require_once('mindpad.mobile.php');
} else {
	require_once('mindpad.desk.php');
}
?>