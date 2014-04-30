<?php
// my toolbox.

$url_str = $_POST['url_str'];
if(get_magic_quotes_gpc())
{
	$url_str = stripslashes($url_str);
}
if(!empty($_POST))
{
	switch($_POST['op'])
	{
		case 'URL Encode':
			$url_str = rawurlencode($url_str);
			break;
		case 'URL Decode':
			$url_str = rawurldecode($url_str);
			break;
		case 'Base64 Encode':
			$url_str = base64_encode($url_str);
			break;
		case 'Base64 Decode':
			$url_str = base64_decode($url_str);
			break;
		case 'HTML Entity Encode':
			$url_str = htmlentities(htmlentities($url_str));	// double because of textarea.
			break;
		case 'HTML Entity Decode':
			$url_str = html_entity_decode($url_str);
			break;
		case 'Quoted Printable Encode':
			$url_str = quoted_printable_encode($url_str);
			break;
		case 'Quoted Printable Decode':
			$url_str = quoted_printable_decode($url_str);
			break;
		case 'Seconds':
			$url_str = time();
			break;
		case 'Date->Seconds':
			$url_str = strtotime($url_str);
			break;
		case 'Seconds->Date':
			$url_str = date('Y-m-d H:i:s',$url_str);
			break;
		case 'String Reverse':
			$url_str = strrev($url_str);
			break;
		case 'Uppercase':
			$url_str = strtoupper($url_str);
			break;
		case 'Lowercase':
			$url_str = strtolower($url_str);
			break;
		case 'IP->Long':
			$url_str = ip2long($url_str);
			break;
		case 'Long->IP':
			$url_str = long2ip($url_str);
			break;
	}
}

?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>My Toolbox</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script>
		function updlen(obj)
		{
			var l = obj.value.length;
			document.getElementById('stringlen').innerHTML = (l>0)? ' ('+ l +')':'';
		}
	</script>
</head>
<body>
<form method="post">
	<textarea name="url_str" style="width:660px; height:300px; background-color:#eee; border:1px solid #000;" onKeyDown="updlen(this);" onKeyUp="updlen(this);"><?=$url_str; ?></textarea>
	<span id="stringlen"><?=($url_str!=='')? ' ('. strlen($url_str) .')':''; ?></span>
	<br>
	<input type="submit" name="op" value="URL Encode">
	<input type="submit" name="op" value="URL Decode">
	&nbsp;&nbsp;
	<input type="submit" name="op" value="Base64 Encode">
	<input type="submit" name="op" value="Base64 Decode">
	<br>
	<input type="submit" name="op" value="HTML Entity Encode">
	<input type="submit" name="op" value="HTML Entity Decode">
	&nbsp;&nbsp;
	<input type="submit" name="op" value="Quoted Printable Encode">
	<input type="submit" name="op" value="Quoted Printable Decode">
	<br>
	<input type="submit" name="op" value="Seconds">
	&nbsp;&nbsp;
	<input type="submit" name="op" value="Date->Seconds">
	<input type="submit" name="op" value="Seconds->Date">
	&nbsp;&nbsp;
	<input type="submit" name="op" value="IP->Long">
	<input type="submit" name="op" value="Long->IP">
	<br>
	<input type="submit" name="op" value="Uppercase">
	<input type="submit" name="op" value="Lowercase">
	&nbsp;&nbsp;
	<input type="submit" name="op" value="String Reverse">
</form>
</body>
</html>
