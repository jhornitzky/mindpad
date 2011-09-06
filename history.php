<?php
logAudit('doGrabHistory');

if (!isLoggedIn()) {
	echo "History has stuff that you have saved or done before (like sharing). Unfortunately since you are not logged in, you dont have any history!";
} else {
	$contentString = serialize($_REQUEST['content']);
	$db = dbConnect();
	$sql = 'SELECT historyId, type, lastUpdateTime FROM History WHERE padId = '. $_SESSION['mindpad.padId'] . ' ORDER BY lastUpdateTime DESC LIMIT 10';
	$historyItems = dbQuery($sql);
	if ($historyItems && dbNumRows($historyItems) > 0) {
		while ($item = dbFetchArray($historyItems)) {
			if ($item['type'] == 'backup') {?>
				<div class="historyMarker" onclick="mindpad.goTo('historyViewer.php?id=<?= $item['historyId'] ?>')"><?= prettyDate($item['lastUpdateTime'], 'd M ga') ?></div>
			<?}
		} 
	} 
}
?>