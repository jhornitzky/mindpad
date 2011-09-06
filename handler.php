<? 
require_once('connector.php'); 
require('handlerDefinitions.php');

if(isset($_REQUEST['action'])) {
	logInfo('doAction ' . $_REQUEST['action'] . ' for user ' . $_SESSION['mindpad.ID']);
	switch ($_REQUEST['action']) {
		case 'login':
			require_once("user.service.php");
			if (loginUser($_POST['username'],$_POST['password'])){?>
				<span>Logging in...</span>
				<script type="text/javascript">
					window.location.href = window.location.href;
				</script>
			<?} else {?>
				<span>Incorrect login credentials</span>
			<?}
			break;
		case 'save':
			logAudit('Saving data: ' . $_REQUEST['content']);
			if (!isset($_REQUEST['content']) || !isLoggedIn()) { 
				echo "No content for user!";
			} else {
				//Save data
				$contentString = serialize($_REQUEST['content']);
				$db = dbConnect();
				$sql = sprintf("UPDATE Pads SET content = '%s' WHERE padId = '" . $_SESSION['mindpad.padId'] . "'",
					cleanseString($db,$contentString)
				);
				$success = dbQuery($sql);
				if ($success)
					echo "Saved successfully"; //FIXME send date back
				else 
					echo "Save failed";
				
				//Make history backup if required
				$selectQ = 'SELECT historyId FROM History WHERE padId = "' . $_SESSION['mindpad.padId'] . '" AND DATE_SUB(CURDATE(), INTERVAL 1 DAY) <= lastUpdateTime';
				$result = dbQuery($selectQ);
				if (!($result && dbNumRows($result) > 0)) {
					//Then create a new back, 1 per day
					$sql = sprintf("INSERT INTO History (type, content, padId) VALUES ('%s','%s','%d')",
						'backup', cleanseString($db, $contentString), $_SESSION['mindpad.padId']
					);
					dbQuery($sql);
					echo "<br/>Made backup";
				} 
				dbClose($db);
			}
			break;
		case 'analyze':
			require('TextStatistics.php');
			$content = strip_tags($_REQUEST['content']);			
			//for text stats
			$textStat = new TextStatistics();			
			//Opencalais
			$calaisData = performBehaviour('calais.process');			
			require('analyze.php'); //render data
			break;
		case 'help':
			require('help.php');
			break;
		case 'share':
			require('share.php');
			break;
		case 'history':
			require('history.php');
			break;
		case 'cmd':
			require('shelpad.handler.php');
			break;
		case 'tLine':
			require('timeline.php');
			break;
	}
} else {
	echo "testing";
}
?>