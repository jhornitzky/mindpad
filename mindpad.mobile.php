<? 
require_once('connector.php');

$content = 'THE INTELLIGENT NOTEPAD<br><br>Start typing here...';
if (isLoggedIn()) {
	$rs = dbQuery("SELECT content FROM Pads WHERE userId = '". $_SESSION['mindpad.ID']. "'");
	if ($rs && dbNumRows($rs) > 0) {
		$rawContent = dbFetchObject($rs)->content;
		$content = unserialize($rawContent);
		logAudit('user content: '.$content);
	}
}
?>
<html>
<head>
<title>mindpad - The nexos-powered save-everything mind dumper</title>

<meta name="viewport"
	content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/>
<meta name="apple-touch-fullscreen" content="YES" />

<!-- jQuery! -->
<script type="text/javascript" src="jQuery-Min.js"></script>
<style>
html, body {
	font-family: arial,helvetica,clean,sans-serif;
}

input[type=text], input[type=password] {
	border:1px solid #000;
}

a {
	text-decoration:none;
	color:#AAA;
	border-bottom:1px dotted #AAA;
}

a:hover {
	text-decoration:underline;
	color:#444;
}

a#loginLink:hover {
	text-decoration:none;
}

textarea {
	border:1px solid #808080;
    margin: 0;
    padding: 0;
}

div#response {
	display:none;
	padding-top:10px;
	padding-bottom:10px;
}

.actions {
	background-color:black;
	background: -moz-linear-gradient(center top , #888888, #575757) repeat scroll 0 0 transparent;
    border: 1px solid #555555;
    color: #E9E9E9;
    border-radius: 2em;
    -moz-border-radius: 2em;
    -webkit-border-radius: 2em;
    float:left;
    padding:0.5em;
    font-size:1.1em;
    margin-left:0.5em;
}

.actions a {
	color:inherit;
}
</style>
</head>

<body class='yui-skin-sam' style="width:90%; margin: 0 auto;">

<div class="head" style="margin-top:1em;width:100%">
	<h1 style="padding:0; margin:0"><img src="mindpad.jpg" style="width:212px; height:48px;" title="A place for thought"/></h1>
	<div style="width:100%; padding-bottom:3px;">
	<? if (!isLoggedIn()) { ?>
	<form id="loginForm" class="authenticate" onsubmit="mindpad.authenticate(); return false;" style="font-size:12px;padding:0;margin:0;padding-bottom:2px;">
		<input type="hidden" name="action" value="login"/>
		<input type="submit" style="display:none;"/>
		<table style="padding:0;margin:0" cellpadding="0" cellspacing="0">
		<tr>
			<td>
				<input name='username' type="text" placeholder='mindname'/><br/>
				<input name='password' type="password" placeholder='password'/>
			</td>
			<td style="text-align:left; vertical-align:middle; padding-left:2px;">
				<input id="loginLink" style="font-size:0.9em;" type="submit" value="sign in &raquo;"/><br/>
				<a href="register.php" style="font-size:0.8em; color:red;" onclick="mindpad.log(this);">... or sign up</a>
			</td>
		</tr>
		</table>
	</form>
	<? } else {?>
		<div style="float:left;">
			<span id="username" style="font-size:35px; color:#AAAAAA;"><?= $_SESSION['mindpad.username']?></span><br/>
		</div>
		<div class="actions" style="float:left">
			<a href="javascript:mindpad.log(this)" onclick="mindpad.save();" style="font-size:14px;">Save</a>
			<a id="logoutLink" href="logOut.php" onclick="mindpad.log(this)" style="font-size:14px">Logout</a>
		</div>
		<div style="clear:both;">
			<span id="lastSave" style="font-size:10px">Last saved never</span>
		</div>
	<? } ?>
	<div id="response" style="float:left;" class="responses"></div>
	</div>
</div>

<div class="body" style="display:none;">
	<textarea id="editor" style="width:100%; min-height:200px;"><?= $content; ?></textarea>
</div>
<div style="color:#AAA; font-size:12px;">
Use 2 fingers to scroll on Apple devices
</div>

<script type="text/javascript">

$(document).ready(function() {
	var html = $('#editor').val();
	var mobile = mindpad.cleanseHtmlToMobile(html);
	$('#editor').val(mobile);
	$(".body").fadeIn(2000);
	$('#editor').animate({height:($(document).height()*0.8)+'px'});
});

var isloggedIn = <?= (isLoggedIn()) ? 'true' : 'false';  ?>;

var mindpad = (function() {
    var tOut;
    var stripHTML = /<\S[^><]*>/g;

	function start() {}

	function cleanseHtmlToMobile(html) {
		html = html.replace(/<\s*\/?\s*span\s*.*?>/ig, '');
		var mobile = html.replace(/<br>/ig, '\n');
		return mobile;
	}

	function cleanseMobileToHtml(mobile) {
		var html = mobile.replace(/\n/g, '<br>');
		return html;
	}

	function save() {
		var mobile = $('#editor').val();
		var html = cleanseMobileToHtml(mobile);
		$.post("handler.php", {content:html, action:'save'}, function(data) {
			showResponse(data);
			$("#lastSave").hide();
			$("#lastSave").html('Last saved ' + getDateNow());
			$("#lastSave").fadeIn();
		});
	}

	var m_names = new Array("January", "February", "March",
			"April", "May", "June", "July", "August", "September",
			"October", "November", "December");

	function getDateNow() {
		var d = new Date();
		var dd = d.getDate();
		var MM = m_names[d.getMonth()];
		var yy = d.getFullYear();
		var hh = d.getHours();
	    var mm = d.getMinutes();
	    var ss = d.getSeconds();
		return dd+" "+MM+" "+yy+" "+hh+":"+mm+":"+ss;
	}

    function log(msg) {
  	  msg = msg || "log";
  	  try { 
  			console.log(args);
  			return true;
  	  } catch(e) {		
  			// newer opera browsers support posting erros to their consoles
  			try { 
  				opera.postError(args); 
  				return true;
  			} catch(e) { }
  	  }
  	}

  	function getMarkers() {
  	  	return markers;
  	}

  	function authenticate() {
  	  	$.post("handler.php", $('#loginForm').serialize(), function(data) {
  	  		showResponse(data);
  	  	});
  	}

  	var sTOut;

  	function showResponse(data) {
  	  	window.clearTimeout(sTOut);
		$("#response").html(data);
		$("#response").slideDown();
		sTOut = window.setTimeout(clearResponse, 5000);
  	}

	function clearResponse() {
		$("#response").slideUp();
	}
  	
  	return {
		start:start,
		markers:getMarkers,
		log:log,
		save:save,
		authenticate:authenticate,
		cleanseHtmlToMobile:cleanseHtmlToMobile,
		cleanseMobileToHtml:cleanseMobileToHtml
  	}
}());

</script>

</body>
</html>