<?php 
function performBehaviour($behaviour) {
	return require('behaviour/'.$behaviour.'.php');
}
?>