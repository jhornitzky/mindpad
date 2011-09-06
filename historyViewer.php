<?php 
require_once('connector.php');
if(!isLoggedIn()) {
	die('Need to be logged in to view history');
} else {
	$db = dbConnect();
	$items = dbQuery("SELECT * FROM History WHERE historyId = " . $_REQUEST['id']); 
	if ($item = dbFetchArray($items)) {
		echo unserialize($item['content']);
	}
} 
?>