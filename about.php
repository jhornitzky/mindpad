<?require_once('connector.php');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>mindpad - The nexos-powered save-everything mind dumper</title>

<!-- jQuery! -->
<script type="text/javascript" src="jQuery-Min.js"></script>
<!-- Utility Dependencies -->
<script src="http://yui.yahooapis.com/2.8.2r1/build/yahoo-dom-event/yahoo-dom-event.js"></script>
<script src="http://yui.yahooapis.com/2.8.2r1/build/element/element-min.js"></script>
<!-- Needed for Menus, Buttons and Overlays used in the Toolbar -->
<script src="http://yui.yahooapis.com/2.8.2r1/build/container/container_core-min.js"></script>
<script src="http://yui.yahooapis.com/2.8.2r1/build/menu/menu-min.js"></script>
<script src="http://yui.yahooapis.com/2.8.2r1/build/button/button-min.js"></script>
<!-- Source file for Rich Text Editor-->
<script src="http://yui.yahooapis.com/2.8.2r1/build/editor/editor-min.js"></script>
<!-- Skin CSS file -->
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.8.2r1/build/assets/skins/sam/skin.css">

<style>
/* Custom styles */
html, body {
	font-family: arial,helvetica,clean,sans-serif;
}

input[type=text], input[type=password] {
	border:1px solid #000;
}

a {
	text-decoration:none;
	color:#AAA;
}

a:hover {
	text-decoration:underline;
	color:#444;
}

#loginLink {
	border:none; background-color:transparent; background:none;font-size:25px;border:none;padding:none;
	color:#AAA;
}

#loginLink:hover {
	text-decoration:none;
	color:#444;
}

textarea {
    border: 0;
    margin: 0;
    padding: 0;
}

div#response {
	display:none;
	padding-top:10px;
	padding-bottom:10px;
}

/* YUI overrides */
.editor-hidden, .yui-editor-container iframe.editor-hidden {
	display:none;
    /*visibility: hidden;*/
    top: -9999px;
    left: -9999px;
    position: absolute;
}
#editor_toolbar.yui-toolbar-container fieldset  {
	position:absolute;
	bottom:-2em;
	opacity:0.5;
}

.yui-toolbar-container fieldset:hover, .yui-editor-container fieldset:hover {
	opacity:1.0;
}

.yui-skin-sam .yui-toolbar-container .yui-toolbar-editcode span.yui-toolbar-icon {
    background-image: url( html_editor.gif );
    background-position: 0 1px;
    left: 5px;
}
.yui-skin-sam .yui-toolbar-container .yui-button-editcode-selected span.yui-toolbar-icon {
    background-image: url( html_editor.gif );
    background-position: 0 1px;
    left: 5px;
}

.marker {
	height:12px;
	background-color:red;
	color:#FFF;
	padding-left:2px;
	padding-right:2px;
	font-weight:bold;
	font-size:10px;
	cursor:pointer;
	display:block;
	position:absolute;
	overflow:hidden;
	border-bottom:1px solid #808080;
}

.marker:hover {
	z-index:1;
	border:1px solid #808080;
}

#options {
	margin-top:6px;
	height:16px;
	border-bottom:1px solid rgb(128, 128, 128);
}

#options > div {
	float:left;
	height:12px;
	background-color:#AAA;
	padding:2px;
	font-weight:bold;
	cursor:pointer;
	overflow:hidden;
	font-size:12px;
	color:#FFF;
	margin-right:2px;
}

#options > div:hover {
	background-color:#444;
	z-index:1;
}

.rightCol p {
	font-size:12px;
}
</style>
</head>

<body class='yui-skin-sam' style="width:75%; margin: 0 auto; min-width:800px;">

<div class="head" style="margin-top:1em; height:50px; width:100%; position:relative;">
	<div style="width:40%; float:left">
		<div style="float:left">
			<h1 style="padding:0; margin:0">about<br/><a href="index.php"><img src="mindpad.jpg" style="width:212px; height:48px; border:none" title="A place for thought"/></a></h1>
		</div>
		<div style="float:right; text-align:right">
		</div>
	</div>
	<div style="width:60%; float:right; padding-bottom:2px; text-align:right; position:absolute; right:0; top:0;">
		<!-- <img src="frax.png" />
		<span><a href="index.php">Try mindpad &gt;&gt;</a></span>
		<p><a href="index.php">Sign up for free &gt;&gt;</a></p>-->
	</div>
</div>

<div class="body" style="clear:both;">
	<div class="leftCol" style="width:100%; float:left; padding-top:1em; position:relative;">
		<p style="font-size:1.4em;">Mindpad lets you think by letting you write.</p>
		<div class="leftCol" style="width:55%; float:left; position:relative;">
			<p style="margin-top:none; padding-top:none">Its simple. Just type away. No distractions.</p>
			<p>As you type, text is formatted and processed automatically.</p>
			<p>Headings are automatically found and indexed, allowing you to move around quickly.</p>
			<p>Other formatting like list items and sections is also found and formatted.</p>
			<p>Analysis is provided so that you can see what you're thinking and find related content quickly.</p>
			<p>Watch the video to see more, <a href="register.php" style="color:red; border-bottom:1px dotted #AAA; font-size:1.2em">sign up</a> or just <a href="index.php" style="color:red; border-bottom:1px dotted #AAA; font-size:1.2em">jump in!</a> <span style="font-size:0.8em;">(mobiles too)</span></p>
		</div>
		<div class="rightCol" style="width:43%; float:left; position:relative; padding-left:2%; padding-top:1em; padding-right:none;">
			<div style="position:absolute; top:-210px; width:97%; height:250px; background-position:bottom center; background-repeat:no-repeat; background-image:url('frax.png');"></div>
			<iframe title="YouTube video player" style="border:1px solid #AAA; border-top:none;" width="100%" height="248" src="http://www.youtube.com/embed/dlUW774hv4o" frameborder="0" allowfullscreen></iframe>
		</div>
	</div>
</div>

</body>
</html>