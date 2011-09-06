<?php
//Based on http://code.google.com/apis/contacts/docs/3.0/developers_guide_protocol.html#client_login
logAudit('doGetCaliasData');

$inData = $_REQUEST['content'];
$key = 'pacyuj7gmze57bg3w8grcg5x';
$calaisUrl = 'http://api.opencalais.com/tag/rs/enrich';

//create post request
$ch = curl_init($calaisUrl);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $inData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('x-calais-licenseID: '.$key, 'content-type: text/html', 'outputformat: application/json'));
$data = curl_exec($ch);
$parsed = json_decode($data, true);
curl_close($ch);

unset($parsed['doc']); //Throw away info

return $parsed;
?>