<?
require_once('connector.php');

$content = '<br>THE INTELLIGENT NOTEPAD<br>
<br>As you type mindpad will do the formatting so you can focus on what you\'re thinking
<br>
<br>If you need help click the help tab to the right
<br>
<br>powered by <a href="http://nexos.ravex.net.au">nexOS</a> from <a href="http://leafcut.com">Leafcutter</a>';

if (isLoggedIn()) {
	$rs = dbQuery("SELECT * FROM Pads WHERE userId = '". $_SESSION['mindpad.ID']. "'");
	if ($rs && dbNumRows($rs) > 0) {
		$row = dbFetchObject($rs);
		$rawContent = $row->content;
		$content = unserialize($rawContent);
		$_SESSION['mindpad.padId'] = $row->padId; 
	}
}
?>
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

img {
	border:none;
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
.yui-skin-sam .yui-editor-container, .yui-skin-sam .yui-editor-container iframe {
	-moz-border-radius:8px;
	border-radius:8px;
	-webkit-border-radius:8px;
}

.yui-skin-sam .yui-editor-editable-container {
	padding-top:8px;
	padding-bottom:8px;
}

.yui-skin-sam .yui-toolbar-container .yui-toolbar-subcont {
	border-bottom:none;
}

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

.yui-skin-sam .yui-toolbar-container .yui-toolbar-timeline span.yui-toolbar-icon {
    background-image: url( time.png );
    background-position: 0 1px;
    left: 5px;
}
.yui-skin-sam .yui-toolbar-container .yui-button-timeline-selected span.yui-toolbar-icon {
    background-image: url( time.png );
    background-position: 0 1px;
    left: 5px;
}

.yui-skin-sam .yui-toolbar-container .yui-toolbar-executecode span.yui-toolbar-icon {
    background-image: url( execute.png );
    background-position: 0 1px;
    left: 5px;
}
.yui-skin-sam .yui-toolbar-container .yui-button-executecode-selected span.yui-toolbar-icon {
    background-image: url( execute.png );
    background-position: 0 1px;
    left: 5px;
}

.marker, .yui-editor-editable{
	border-radius:1px;
	-moz-border-radius:1px;
	-webkit-border-radius:1px;
}

.historyMarker {
	background-color:#AAA;
	color:white;
	font-size:1.1em;
	cursor:pointer;
	border-radius:2px;
	-moz-border-radius:2px;
	-webkit-border-radius:2px;
	margin-bottom:2px;
	padding-left:5px;
}

.historyMarker:hover {
	background-color:grey;
}

.marker {
	height:13px;
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
	border:1px solid #808080;
	padding-left:5px;
	border-radius:4px 0 0 4px;
	-moz-border-radius:4px 0 0 4px;
	-webkit-border-radius:4px 0 0 4px;
}

.marker:hover {
	z-index:1;
	border:1px solid #000;
}

#options {
	margin-top:6px;
	height:20px;
	background: -moz-linear-gradient(center top , #FFFFFF, #EDEDED) repeat scroll 0 0 transparent;
    border: 1px solid #B7B7B7;
    color: #606060;
    -moz-border-radius: 2em 2em 2em 2em;
    text-align:center;
    width:100%;
}

#options > a {
	padding:2px;
	cursor:pointer;
	overflow:hidden;
	font-size:14px;
}

.actions {
	background: -moz-linear-gradient(center top , #888888, #575757) repeat scroll 0 0 transparent;
    border: 1px solid #555555;
    color: #E9E9E9;
    -moz-border-radius: 2em 2em 2em 2em;
    float:left;
    padding:0.5em;
    font-size:1.1em;
}

.actions a {
	color:inherit;
}

#options > div:hover {
	background-color:#444;
	z-index:1;
}

.rightCol p {
	font-size:12px;
}

.timeline {
	position:absolute;
	top:0px;
	left:0px;
	height:98%;
	width:98%;
	padding:1%;
	background-color:#EEE;
	background-color:rgba(255,255,255,0.9);
}

.timeline .control {
	cursor:pointer	
}
</style>
</head>

<body class='yui-skin-sam' style="width:75%; margin: 0 auto;">

<div class="head" style="margin-top:1em; height:50px ;width:100%">
	<div style="width:70%; float:left">
	<div style="float:left">
		<h1 style="padding:0; margin:0"><a href="about.php" style="border:none"><img src="mindpad.jpg" style="width:212px; height:48px;border:none;" title="A place for thought"/></a></h1>
	</div>
	<div style="float:left; margin-left:0.5em; padding-top:8px"><img class="loadingBubble" style="visibility:hidden" src="ajax-loader.gif"/></div> 
	<div id="response" class="responses" style="float:left; font-size:12px; margin-left:8px"></div>
	<? if (isLoggedIn()) { ?>
	<div style="float:right; text-align:right; font-size:25px; color:#AAA;">
		<span id="username" style="font-size:25px; color:#AAAAAA;" title="the mind of <?= $_SESSION['mindpad.username']?>"><?= $_SESSION['mindpad.username']?></span><br/>
		<span id="lastSave" style="font-size:10px; color:#000;">Last saved never</span>
	</div>
	<? } ?>
	</div>
	<div style="width:28%; float:right; padding-bottom:2px;">
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
				<input id="loginLink" type="submit" value="sign in &raquo;"/><br/>
				<a href="register.php" onclick="mindpad.log(this);">... or sign up</a>
			</td>
		</tr>
		</table>
	</form>
	<? } else {?>
	<div style="height:40px;">
		<div class="actions">
			<span><a href="javascript:mindpad.log(this)" onclick="mindpad.save();">Save</a></span>
			<span><a id="logoutLink" href="logOut.php" onclick="mindpad.log(this)" style="margin-left:5px">Logout</a></span>
		</div>
	</div>
	<? } ?>
	<div id="options">
		<a onclick="mindpad.renderTagMarkers()">Outline</a>
		<a onclick="mindpad.analyze()">Analyze</a>
		<a onclick="mindpad.share()">Share</a>
		<a onclick="mindpad.history()">History</a>
		<a onclick="mindpad.getHelp()">Help</a>
	</div>
	</div>
</div>

<div class="body" style="display:none;">
	<div class="leftCol" style="width:70%; float:left; padding-top:1em; position:relative;">
		<textarea id="editor"><?= $content; ?></textarea>
	</div>
	<div class="rightCol" style="width:27%; float:right; position:relative; padding-top:5px;">
	</div>
</div>

<script type="text/javascript">
jQuery.cookie = function(name, value, options) {
    if (typeof value != 'undefined') { // name and value given, set cookie
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        // CAUTION: Needed to parenthesize options.path and options.domain
        // in the following expressions, otherwise they evaluate to undefined
        // in the packed version for some reason...
        var path = options.path ? '; path=' + (options.path) : '';
        var domain = options.domain ? '; domain=' + (options.domain) : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(JSON.stringify(value)), expires, path, domain, secure].join('');
    } else { // only name given, get cookie
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                // Does this cookie string begin with the name we want?
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = JSON.parse(decodeURIComponent(cookie.substring(name.length + 1)));
                    break;
                }
            }
        }
        return cookieValue;
    }
};

$(document).ready(function() {
	$(".body").fadeIn(2000);
	$(document).ajaxSend(function(e, xhr, opts) {
		$('.loadingBubble').css('visibility', 'visible');
	}).ajaxComplete(function() {
		$(".loadingBubble").css('visibility', 'hidden');
	});

	$(window).resize(function () { 	//FIXME resize
		//$('#editor_editor').find('iframe').height($(document).height()*0.75);
		//mindpad.renderTagMarkers();
	}); 
});

var keyLog = new Array();
var keyCount = 0;
var isloggedIn = <?= (isLoggedIn()) ? 'true' : 'false';  ?>;

var mindpad = (function() {
    var Dom = YAHOO.util.Dom,
        Event = YAHOO.util.Event;

    var colHeight = $(document).height()*0.75; //height of text area and right col

    var loadHtml = "<html>\n<head>\n<title>{TITLE}</title>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\n" +
    "<base href=\"<?= $serverUrl.$serverRoot?>\">\n" +
    "<style>\n{CSS}\n</style>\n" +
    "<style>\n{HIDDEN_CSS}\n</style>\n" +
    "<style>\n {EXTRA_CSS}\n</style>\n" +
    "<link rel=\"stylesheet\" type=\"text/css\" href=\"editor.css\">\n" +
    "</head>\n" +
    "<body onload=\"document.body._rteLoaded = true;\">\n{CONTENT}\n</body>\n</html>\n";

    var myConfig = {
    	height: colHeight + 'px',
        width: '100%',
        animate: true,
        dompath: false,
        html: loadHtml,
        toolbar: {
    		collapse: true,
    		draggable: false,
    		buttons: [
    		    		{ group: 'textstyle',
	        			buttons: [
	            			{ type: 'push', label: 'Bold CTRL + SHIFT + B', value: 'bold' },
	            			{ type: 'push', label: 'Italic CTRL + SHIFT + I', value: 'italic' },
	            			{ type: 'push', label: 'Underline CTRL + SHIFT + U', value: 'underline' },
	            			{ type: 'separator' },
	            			{ type: 'color', label: 'Font Color', value: 'forecolor', disabled: true },
	           	 			{ type: 'color', label: 'Background Color', value: 'backcolor', disabled: true }
	        			]
	    				},
	    				{ type: 'separator' },
    	    			{ group: 'insertitem',
    	       	 			buttons: [
    	            			{ type: 'push', label: 'HTML Link CTRL + SHIFT + L', value: 'createlink', disabled: true },
    	            			{ type: 'push', label: 'Insert Image', value: 'insertimage' },
    	            			{ type: 'push', label: 'Edit HTML Code', value: 'editcode' },
    	            			{ type: 'push', label: 'Execute selected code', value: 'executecode' },
    	            			{ type: 'push', label: 'View a timeline of your posts', value: 'timeline' }
    	    	        	]
    	    			}
    		]
        }
    };

    var state = 'off';

    YAHOO.widget.Editor.prototype.markCaret = function() {
    	if (this.browser.gecko) {
        	try {
            	this._getSelection().getRangeAt(0).insertNode(this._getDoc().createTextNode(cc));
        	} catch (err) {}
        } else if (this.browser.opera) {
		    var span = this._getDoc().createElement('span');
		    this._getWindow().getSelection().getRangeAt(0).insertNode(span);
        } else if (this.browser.webkit || this.browser.ie) {
            this.execCommand('inserthtml', '<span id="cur"></span>');
        }
    }

     function selectMarker(m) {
    	var marker = markers[m];

		//grab iframe dimensions
    	var scrollHeight = $("#editor_editor").contents().height();
    	var frameHeight = $("#editor_editor").height();
    	if (scrollHeight < colHeight)
        	return false;

    	//Percentage of doc - (halfframe + headingsoffset)
		//var markerY = Math.round(marker.lineNo/lineCount*scrollHeight) - (frameHeight/2 + (marker.lineNo/lineCount*markers.length*6));
		var markerY = Math.round(marker.lineNo/lineCount*scrollHeight);
		markerY = markerY - (frameHeight/4);
		//markerY = markerY - (marker.lineNo/lineCount*markers.length*2);

		if (markerY < 0)
			markerY = 0;

		//Select text
		myEditor.focus();
    	var cur = myEditor._getDoc().getElementById('marker_' + marker.lineNo);
        if (cur != null)
        	myEditor._selectNode(cur);

    	//Scroll to text
    	$("#editor_editor").contents().scrollTop(markerY);
    }

    YAHOO.widget.Editor.prototype.focusCaret = function() {
        if (this.browser.gecko) {
            if (this._getWindow().find(cc)) {
                this._getSelection().getRangeAt(0).deleteContents();
            }
        } else if (this.browser.opera) {
            var sel = this._getWindow().getSelection();
            var range = this._getDoc().createRange();
            var span = this._getDoc().getElementsByTagName('span')[0];

            range.selectNode(span);
            sel.removeAllRanges();
            sel.addRange(range);
            span.parentNode.removeChild(span);
        } else if (this.browser.webkit || this.browser.ie) {
            this.focus();
            var cur = this._getDoc().getElementById('cur');
            if (cur != null) {
            	cur.id = '';
            	cur.innerHTML = '';
            	this._selectNode(cur);
            }
        }
    };

    var myEditor = new YAHOO.widget.Editor('editor', myConfig);

    myEditor.on('toolbarLoaded', function() {
    	this.toolbar.on('executecodeClick', function() {
    		exec();
        });

    	this.toolbar.on('timelineClick', function() {
			genTimeline();
        });
        
        this.toolbar.on('editcodeClick', function() {
            var ta = this.get('element'),
                iframe = this.get('iframe').get('element');

            if (state == 'on') {
                state = 'off';
                this.toolbar.set('disabled', false);
                YAHOO.log('Show the Editor', 'info', 'example');
                YAHOO.log('Inject the HTML from the textarea into the editor', 'info', 'example');
                this.setEditorHTML(ta.value);
                if (!this.browser.ie) {
                    this._setDesignMode('on');
                }

                Dom.removeClass(iframe, 'editor-hidden');
                Dom.addClass(ta, 'editor-hidden');
                this.show();
                this._focusWindow();
            } else {
                state = 'on';
                YAHOO.log('Show the Code Editor', 'info', 'example');
                this.cleanHTML();
                YAHOO.log('Save the Editors HTML', 'info', 'example');
                Dom.addClass(iframe, 'editor-hidden');
                Dom.removeClass(ta, 'editor-hidden');
                this.toolbar.set('disabled', true);
                this.toolbar.getButtonByValue('editcode').set('disabled', false);
                this.toolbar.selectButton('editcode');
                this.dompath.innerHTML = 'Editing HTML Code';
                this.hide();
            }
            return false;
        }, this, true);

        this.on('cleanHTML', function(ev) {
            YAHOO.log('cleanHTML callback fired..', 'info', 'example');
            this.get('element').value = ev.html;
        }, this, true);

        this.on('afterRender', function() {
        	//this.setEditorHTML(this.cleanHTML()); //FIXME
            var wrapper = this.get('editor_wrapper');
            wrapper.appendChild(this.get('element'));
            this.setStyle('width', '100%');
            this.setStyle('height', '100%');
            this.setStyle('visibility', '');
            this.setStyle('top', '');
            this.setStyle('left', '');
            this.setStyle('position', '');
            this.addClass('editor-hidden');
            if (!isloggedIn) {
            	window.setTimeout(override, 300); //FIXME this time seems safe but it might not be
            } else {
            	window.setTimeout(processChanges, 500);
            }
        }, this, true);
    }, myEditor, true);

    //ATTACH PARSE EVENTS
    myEditor.on('editorKeyUp', function(e) {
    	if (state == 'off') {
        	//Key log in editor
        	keyLog[keyCount] = e.ev.keyCode;
    		keyCount++;
    		delayProcess();
    		//Lang.later(100, this, this.highlight);
        }
    });

    function override() {
    	if (!isloggedIn) {
    		var data = $.cookie('mindpad');
    		if (data != undefined && data != null) {
    			myEditor.setEditorHTML(data.content);
    		}
    	}  
    	window.setTimeout(processChanges, 500);
    }

    var cc = '\u2009'; // carret char
    var tOut;

	function start() {
    	myEditor.render(); //SHOW
    	window.setInterval(poll, 1000);
	}

	function genTimeline() {
		var tLineString = '<div class="timeline"><div class="control" onclick="$(this).parent().remove()">close</div><div class="content"></div></div>';
		$('body').append(tLineString);
		$.post("handler.php", {action:'tLine'}, function(data) {
			$('.timeline .content').html(data);
		});
	}

	function save() {
		var html = myEditor.getEditorHTML();
		$.post("handler.php", {content:html, action:'save'}, function(data) {
			showResponse(data);
			$("#lastSave").hide();
			$("#lastSave").html('Last saved ' + getDateNow());
			$("#lastSave").fadeIn();
		});
		var dataObj = {content:html};
		$.cookie('mindpad', dataObj);
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

	function poll() {} //FIXME

    function delayProcess() {
    	window.clearTimeout(tOut);
    	tOut = window.setTimeout(processChanges, 1000);
    }

    //Render right column tag markers
    function renderTagMarkers() {
    	$('.body .rightCol').empty();
		log('Marker count: ' + markers.length);
    	for (var m = (markers.length-1); m >= 0; m--) {
			var marker = markers[m];
			var markerY = Math.round(marker.lineNo/lineCount*colHeight);
			if (marker.content.length > 30) {
				marker.content = marker.content.substring(0, 30);
				marker.content += '...';
			}
			var htmlMarker = "<div class='marker' style='margin-top:"+markerY+"px'" +
			" onclick='mindpad.selectMarker("+m+")'>" + marker.content + "</div>";
			$('.body .rightCol').append(htmlMarker);
	    }
    }

    function strip_tags (input, allowed) {
        // http://kevin.vanzonneveld.net
        // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
        // +   improved by: Luke Godfrey
        // +      input by: Pul
        // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
        // +   bugfixed by: Onno Marsman
        // +      input by: Alex
        // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
        // +      input by: Marc Palau
        // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
        // +      input by: Brett Zamir (http://brett-zamir.me)
        // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
        // +   bugfixed by: Eric Nagel
        // +      input by: Bobby Drake
        // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
        // +   bugfixed by: Tomasz Wesolowski
        // +      input by: Evertjan Garretsen
        // +    revised by: Rafał Kukawski (http://blog.kukawski.pl/)
        // *     example 1: strip_tags('<p>Kevin</p> <br /><b>van</b> <i>Zonneveld</i>', '<i><b>');
        // *     returns 1: 'Kevin <b>van</b> <i>Zonneveld</i>'
        // *     example 2: strip_tags('<p>Kevin <img src="someimage.png" onmouseover="someFunction()">van <i>Zonneveld</i></p>', '<p>');
        // *     returns 2: '<p>Kevin van Zonneveld</p>'
        // *     example 3: strip_tags("<a href='http://kevin.vanzonneveld.net'>Kevin van Zonneveld</a>", "<a>");
        // *     returns 3: '<a href='http://kevin.vanzonneveld.net'>Kevin van Zonneveld</a>'
        // *     example 4: strip_tags('1 < 5 5 > 1');
        // *     returns 4: '1 < 5 5 > 1'
        // *     example 5: strip_tags('1 <br/> 1');
        // *     returns 5: '1  1'
        // *     example 6: strip_tags('1 <br/> 1', '<br>');
        // *     returns 6: '1  1'
        // *     example 7: strip_tags('1 <br/> 1', '<br><br/>');
        // *     returns 7: '1 <br/> 1'
        allowed = (((allowed || "") + "").toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join(''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
        var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
            commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
        return input.replace(commentsAndPhpTags, '').replace(tags, function ($0, $1) {
            return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
        });
    }

  	//Replace span tags currently
	function reformat(html) {
		//html = html.replace(/(<([^>]+)>)/ig, '');
		//html = html.replace(/<\S[^><]*>/ig, '');
		//html = html.replace(/\n/ig, ''); //replace stray linebreaks
		//html = html.replace(/<\s*\/?\s*span\s*.*?>/ig, ''); //replace stray span
		html = strip_tags(html,'<i><b><a><img>');
		return html;
    }

	var lastHtml = '';
	var markers;
	var lineCount;

    //Parse and process field
    function processChanges() {
        window.clearTimeout(tOut);
    	var checkHtml = myEditor.getEditorHTML();

		//only execute if changes have been detected
		if (lastHtml != checkHtml) {
			lastHtml = checkHtml;
			myEditor.markCaret();

			//Iterate through editor HTML and process as approriate
			var toProcess = myEditor.getEditorHTML();

			//First split into lines via <br> tags
			var lines = toProcess.split('<br>');

			//Prepare marker array
			markers = new Array();

			//Process each line
			var skipMode = false;
			for (lineNo in lines) {
				var lineContent = lines[lineNo];
				lineContent = reformat(lineContent);

				//Skip mode start
				if (lineContent.match(/\[SOF\]/i)) {
					lineContent = '<span class="yui-tag-span yui-tag skipper"><pre>' + lineContent;
					skipMode = true;
				} else if (lineContent.match(/\[EOF\]/i)) {
					lineContent = lineContent + '</pre></span>';
					skipMode = false;
				//Skip if starts or ends with a span
				} else if (!skipMode) {//else do matching formatting
					if (lineContent.match(/[\!\-\@\#\$\%\^\&\*\(\)\[\]\/\\\=\+\?\>\<]{5,}/)) { //has 5 or more non alphas on the line in a row = line
						lineContent = '<span class="yui-tag-span yui-tag underline">' + lineContent + '</span>';
					} else if (lineContent.match(/^[A-Z][A-Z\s\W]{3,}$/)) { //MATCH ALPHA CAPS ONLY on line more than 3 in a row = heading
						log('Creating heading marker');
						markers[markers.length] = {lineNo:lineNo, content:lineContent}; //Mark line
						lineContent = '<span id="marker_'+lineNo+'" class="yui-tag-span yui-tag heading">' + lineContent + '</span>';
					} else if (lineContent.match(/^[^a-zA-Z\<][\S]+\b|\d\.\s/)) { //Non-word item with word boundary or number
						var contentItem = lineContent.match(/^[^a-zA-Z][\S]+\b|\d\.\s/);
						lineContent = lineContent.replace(contentItem, '<span class="yui-tag-span yui-tag bullet">' + contentItem + '</span>');
					}
        		}
    			lines[lineNo] = lineContent;
    			lineCount = lineNo;
			}

			//FIXME then group into sections and tag

			//Finally reassemble
			returnVal = lines.join('<br>');
			myEditor.setEditorHTML(returnVal);

			//and return cursor
			myEditor.focusCaret();

			//and render tag markers
			renderTagMarkers();
		}
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

  	function showResponse(data, dontClear) {
  	  	window.clearTimeout(sTOut);
		$("#response").html(data);
		$("#response").slideDown();
		
		if (dontClear == undefined)
			sTOut = window.setTimeout(clearResponse, 5000);
  	}

	function clearResponse() {
		$("#response").slideUp();
	}

	function analyzeAnim() {
		var html = $("#response").html();
		if (html != undefined) { 
			if (html.length > 12) 
				$("#response").html('Analyzing');
			else
				$("#response").html(html + '.');
			window.setTimeout(analyzeAnim, 750);
		}
	}

	function loadData(input, callback){
		//$('.body .rightCol').empty();
		$.post("handler.php", input, function(data) {
			$('.body .rightCol').html(data);
			if (callback != undefined) {
				callback();
			}
		});
	}
	
	function analyze() {
		showResponse('Analyzing', true);
		analyzeAnim();
		loadData({content:myEditor.getEditorHTML(), action:'analyze'}, function(){ clearResponse(); });
	}

	function help() {
		loadData({action:'help'});
	}

	function share() {
		loadData({action:'share'});
	}

	function history () {
		loadData({action:'history'});		
	}

	function exec() {
		var cmd = myEditor._getSelection();
		loadData({action:'cmd',cmd:encodeURI(cmd)});
	}

	//OLD SHELPAD

	var startPosition;
	var endPosition;
	var length;
	var selectedText;
	var curLineEnd;
	var curLineNo;
	var maxLineNo;
	var lineHeight = 14; //font size +line height

	function testPos() {
		var obj = $("textarea#cmdspace").getSelection();
		alert(obj.start + " " + obj.end + " " + obj.length + " " + obj.text);
	}
	
	function longExec() {
		var obj = $("textarea#cmdspace").getSelection();
		startPosition = obj.start;
		endPosition = obj.end;
		length = obj.length;
		selectedText = obj.text;

		var cmd = '';

		//first check if anything is selected... if it is try to execute
		if (length > 0) {
			cmd = selectedText;
		}
		//else tokenize into lines... get the cursor position and execute that line
		else {
			var lineSum = 0;
			var text = $("textarea#cmdspace").val();
			var lines = text.split("\n");
			var exec = '';
			maxLineNo = lines.length;

			//go through each line and count length to find cmd
			for (i in lines) {
				if (cmd != '')
					break;

				log('line: ' + lineSum + ' ' + endPosition + ' ' + (lineSum + lines[i].length));
				if (lineSum <= endPosition && endPosition  <= (lineSum + lines[i].length)) {
					cmd = lines[i];
					curLineEnd = lineSum + lines[i].length + 1;
					if (curLineEnd > text.length)
						curLineEnd = text.length;
					curLineNo = i;
				} else {
					lineSum += lines[i].length + 1; //1 is for the \n
				}
			}
		}

		execCmd(cmd);

		return false;
	}

	function execCmd(cmd) {
		$.get('shelpad.handler.php?cmd=' + encodeURI(cmd), function(data) {
			var text = $('textarea#cmdspace').val();

			//insert response data to line
			if (length > 0) {
				$('textarea#cmdspace').val(text.substring(0, endPosition) +  data + text.substring(endPosition, text.length));
				$('textarea#cmdspace').setCaretPos(endPosition);
			} else {
				$('textarea#cmdspace').val(text.substring(0, curLineEnd) +  data + text.substring(curLineEnd, text.length));
				$('textarea#cmdspace').setCaretPos(curLineEnd);
			}

			//scroll to line again
			log('goTo: ' + curLineNo*lineHeight);
			$('textarea#cmdspace').scrollTop(curLineNo*lineHeight);
		});
	}

	function goTo(place) {
		window.open(place);
	}

  	return {
		start : start,
		selectMarker : selectMarker,
		markers : getMarkers,
		processChanges : processChanges,
		log : log,
		save : save,
		authenticate : authenticate,
		analyze : analyze,
		getHelp : help,
		renderTagMarkers : renderTagMarkers,
		share : share,
		history : history,
		goTo : goTo,
		myEditor : myEditor
	}
}());
mindpad.start();
</script>

</body>
</html>