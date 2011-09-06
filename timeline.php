<?
require('simplediff.php');
require('Diff.php');
require_once 'Text/Diff/Renderer/inline.php';
require_once 'PhpDiff.php';
logAudit('doGrabTimeline');

//get the stuff
if (!isLoggedIn()) {
	echo "History has stuff that you have saved or done before (like sharing). Unfortunately since you are not logged in, you dont have any history!";
} else {
	$db = dbConnect();
	$sql = 'SELECT * FROM History WHERE History.padId = '. $_SESSION['mindpad.padId'] . ' ORDER BY lastUpdateTime DESC LIMIT 10';
	$padItems = dbQuery($sql);
	
	if ($padItems && dbNumRows($padItems) > 0) {
		$padItems = dbFetchAll($padItems);
		
		//Now loop thru and compare each record with the last 
		for($i = 0; $i < count($padItems); $i++) {  ?> 
			<div style="width:300px; float:left; margin-right:20px;margin-top:20px; min-height:10px;overflow-x:hidden;">
				<h1><?= prettyDate($padItems[$i]['lastUpdateTime'], 'd M') ?></h1>
				<div style="font-size:0.85em;">
				<?if ($i == (count($padItems) - 1)) {
					print_r(unserialize($padItems[$i]['content']));
				} else {
					$lines1 = explode("<br>",unserialize($padItems[$i]['content']));
					$lines1 = unserialize($padItems[$i]['content']);
					$lines2 = explode("<br>",unserialize($padItems[$i+1]['content']));
					$lines2 = unserialize($padItems[$i+1]['content']);
					echo PHPDiff2($lines1, $lines2, '<br>');
					/*
					$diffs = new Text_Diff('auto', array($lines1,$lines2));
					$renderer = new Text_Diff_Renderer_inline(
					    array(
					        'ins_prefix' => '%g',
					        'ins_suffix' => '%n',
					        'del_prefix' => '%r',
					        'del_suffix' => '%n',
					    )
					);
					echo $renderer->render($diffs);
					*/
				}?>
				</div>
			</div>
		<?}
	} else {?>
		<p>No history available for user</p>
	<?}
}
?>