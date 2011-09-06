<? 
require_once('connector.php');
logAudit('doCheckRemind');

//Check if reminders need to be sent
$db = dbConnect();
$selectQ = 'SELECT Pads.*, Users.* FROM Pads, Users WHERE DATE_SUB(CURDATE(), INTERVAL 1 DAY) >= Pads.lastUpdateTime AND Pads.userId = Users.ID';
$result = dbQuery($selectQ);
if ($result && dbNumRows($result) > 0) {
	logAudit('results found');
    while ($padUser = dbFetchObject($result)) {
    	logAudit('fetching padUser');
        //Then send a hello email to the user
        echo 'will send reminders';
        session_write_close();
        $headers = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: mindpad@i4think.com' . "\r\n";
        $msg = file_get_contents('missingyou.php');
        mail($padUser->email, 'mindpad misses you', $msg, $headers);
    }
} else {
	logAudit('no results found');
}
dbClose($db);
?>