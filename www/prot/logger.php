<html>
<head>
<title>Logger</title>
<script>
<!--
function go() {
	url=document.getElementById('url').value;
	divlog=document.getElementById('log');
	divlog.innerHTML = url + "<br />\n" + divlog.innerHTML;
	document.getElementById("response").src=url;
	return false;
}

function goto(url) {
	document.getElementById('url').value=url;
	divlog=document.getElementById('log');
	divlog.innerHTML = url + "<br />\n" + divlog.innerHTML;
	document.getElementById("response").src=url;
	return false;
}

function set(url) {
	document.getElementById('url').value=url;
	return false;
}
-->
</script>
<style type="text/css">
<!--
p:{
	margin:0;
	padding:0;
	color:red;
}
-->
</style>
</head>
<body onload="document.getElementById('url').focus(); document.getElementById('url').value='rem/login.php?debug=get&user=jm&pass=tenbucks'">
<iframe style="position:fixed;bottom:0;right:0;width:900px;font-family:fixed;" id="response"></iframe>

<form onsubmit="return go();">
<input id="url" type="text" size="90" value="waiting" /><br \>
login :
<input type="button" value="jm" onclick="goto('rem/login.php?debug=get&user=jm&pass=tenbucks')" />
<input type="button" value="jmmm" onclick="goto('rem/login.php?debug=get&user=jmmm&pass=tenbucks')" />
<input type="button" value="elie" onclick="goto('rem/login.php?debug=get&user=elie&pass=eliepass')" />
<input type="button" value="bob" onclick="goto('rem/login.php?debug=get&user=bob2000&pass=bob2000pass')" /><br />
<input type="button" value="update" onclick="goto('rem/update.php')" />
<input type="button" value="list" onclick="goto('rem/list.php')" /> <br />
<input type="button" value="join" onclick="set('rem/join.php?id=1')" /> :
<input type="button" value="12" onclick="goto('rem/join.php?id=12')" />
<input type="button" value="13" onclick="goto('rem/join.php?id=13')" />
<input type="button" value="14" onclick="goto('rem/join.php?id=14')" />
<input type="button" value="15" onclick="goto('rem/join.php?id=15')" />
<input type="button" value="16" onclick="goto('rem/join.php?id=16')" />
<input type="button" value="17" onclick="goto('rem/join.php?id=17')" />
<input type="button" value="18" onclick="goto('rem/join.php?id=18')" />
<input type="button" value="19" onclick="goto('rem/join.php?id=19')" />
<input type="button" value="create" onclick="set('rem/create.php?type=whist22&room=tiroom')" />
</form>

<h3>Console</h3>
<div id="log">
</div>


</body>
</html>
