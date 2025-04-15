<?php
// my toolbox.
ini_set('display_errors', 0); // set 1 for development.
error_reporting(E_ALL);

// init functions.
// http://stackoverflow.com/a/3005240
function munge($str)
{
  $str = mb_convert_encoding($str,'UTF-32','UTF-8');
  $t = unpack("N*",$str);
  $t = array_map(function($n){ return "&#$n;"; },$t);

  return implode("",$t);
}
function lines2arr($str)
{
  return explode("\n",str_replace("\r","",$str));
}
function arr2lines($arr)
{
  return implode(PHP_EOL,$arr);
}

// process form.
$thestr = isset($_POST['thestr'])? $_POST['thestr']:null;
$salt = isset($_POST['salt'])? $_POST['salt']:null;
$perline = isset($_POST['perline']);
if(isset($_POST['op']) && !empty($_POST['op']))
{
if(get_magic_quotes_gpc())
{
  $thestr = stripslashes($thestr);
  $salt = stripslashes($salt);
}
switch($_POST['op'])
{
  case 'URL Encode':
    if($perline)  $thestr = arr2lines(array_map('rawurlencode',lines2arr($thestr)));
    else          $thestr = rawurlencode($thestr);
    break;
  case 'URL Decode':
    if($perline)  $thestr = arr2lines(array_map('rawurldecode',lines2arr($thestr)));
    else          $thestr = rawurldecode($thestr);
    break;
  case 'Base64 Encode':
    if($perline)  $thestr = arr2lines(array_map('base64_encode',lines2arr($thestr)));
    else          $thestr = base64_encode($thestr);
    break;
  case 'Base64 Decode':
    if($perline)  $thestr = arr2lines(array_map('base64_decode',lines2arr($thestr)));
    else          $thestr = base64_decode($thestr);
    break;
  case 'HTML Entity Encode':
    $thestr = htmlentities(htmlentities($thestr));  // double because of textarea.
    break;
  case 'HTML Entity Decode':
    $thestr = htmlentities(html_entity_decode($thestr));  // textarea issues.
    break;
  case 'Quoted Printable Encode':
    if($perline)  $thestr = arr2lines(array_map('quoted_printable_encode',lines2arr($thestr)));
    else          $thestr = quoted_printable_encode($thestr);
    break;
  case 'Quoted Printable Decode':
    if($perline)  $thestr = arr2lines(array_map('quoted_printable_decode',lines2arr($thestr)));
    else          $thestr = quoted_printable_decode($thestr);
    break;
  case 'JSON Encode':
    try {
      $thestr = json_encode($thestr, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: $thestr;
    } catch (Exception $e) {
      $thestr = 'ERROR: '. $e->getMessage();
    }
    break;
  case 'JSON Decode':
    try {
      $result = json_decode($thestr, true);
      $thestr = json_last_error() !== JSON_ERROR_NONE
          ? 'ERROR: '. json_last_error_msg()
          : $result;
    } catch (Exception $e) {
      $thestr = 'ERROR: '. $e->getMessage();
    }
    break;
  case 'Seconds':
    $thestr = time();
    break;
  case 'Date->Seconds':
    $thestr = strtotime($thestr);
    break;
  case 'Seconds->Date':
    $thestr = date('Y-m-d H:i:s',$thestr);
    break;
  case 'String Reverse':
    $thestr = strrev($thestr);
    break;
  case 'String ROT13':
    $thestr = str_rot13($thestr);
    break;
  case 'Uppercase':
    $thestr = strtoupper($thestr);
    break;
  case 'Lowercase':
    $thestr = strtolower($thestr);
    break;
  case 'Uppercase Words':
    $thestr = ucwords($thestr);
    break;
  case 'IP->Long':
    $thestr = ip2long($thestr);
    break;
  case 'Long->IP':
    $thestr = long2ip($thestr);
    break;
  case 'ASCII Value':
    $thestr = ord($thestr);
    break;
  case 'Strip Tags':
    $thestr = strip_tags($thestr);
    break;
  case 'Unicode Munge':
    $thestr = htmlentities(munge($thestr)); // htmlentities to force show actual unicodes.
    break;
  case 'Encrypt':
    $thestr = encrypt($thestr,$salt);
    break;
  case 'Decrypt':
    $thestr = decrypt($thestr,$salt);
    break;
}
}

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>My Toolbox</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="My toolbox">
  <style>
    body{
      font: 0.8em Arial;
    }
    textarea, .controls{
      width: 98%;
      min-width: 300px;
      max-width: 2000px;
    }
    textarea{
      height: 300px;
      height: 50vh;
      min-height: 215px;
      background-color: #eee;
      border: 1px solid #000;
    }
    .controls{
      line-height: 26px;
      margin-top: 12px;
    }
    .controls input{
      cursor: pointer;
    }
    .controls input:hover{
      border-bottom-color: #fc0;
    }
  </style>
  <script>
    function updateLength(obj) {
      const l = obj.value.length;
      document.getElementById('stringlen').innerHTML = (l > 0) ? ` (${l})` : '';
    }
  </script>
</head>
<body>
<form method="post">
  <textarea name="thestr" onKeyDown="updateLength(this);" onKeyUp="updateLength(this);" autofocus><?=$thestr; ?></textarea>
  <span id="stringlen"><?=$thestr!==''? ' ('. strlen($thestr) .')':''; ?></span>
  <div class="controls"> 
    <input type="submit" name="op" value="Seconds">
    &nbsp;&nbsp;
    <input type="submit" name="op" value="Date->Seconds">
    <input type="submit" name="op" value="Seconds->Date">
    &nbsp;&nbsp;
    <input type="submit" name="op" value="IP->Long">
    <input type="submit" name="op" value="Long->IP">
    <br />
    <input type="submit" name="op" value="URL Encode">
    <input type="submit" name="op" value="URL Decode">
    &nbsp;&nbsp;
    <input type="submit" name="op" value="Base64 Encode">
    <input type="submit" name="op" value="Base64 Decode">
    &nbsp;&nbsp;
    <input type="submit" name="op" value="HTML Entity Encode">
    <input type="submit" name="op" value="HTML Entity Decode">
    &nbsp;&nbsp;
    <input type="submit" name="op" value="Quoted Printable Encode">
    <input type="submit" name="op" value="Quoted Printable Decode">
    &nbsp;&nbsp;
    <input type="submit" name="op" value="JSON Encode">
    <input type="submit" name="op" value="JSON Decode">
    <br />
    <input type="submit" name="op" value="Uppercase">
    <input type="submit" name="op" value="Lowercase">
    <input type="submit" name="op" value="Uppercase Words">
    &nbsp;&nbsp;
    <input type="submit" name="op" value="String Reverse">
    <input type="submit" name="op" value="String ROT13">
    &nbsp;&nbsp;
    <input type="submit" name="op" value="ASCII Value">
    &nbsp;&nbsp;
    <input type="submit" name="op" value="Strip Tags">
    &nbsp;&nbsp;
    <input type="submit" name="op" value="Unicode Munge">
    <br />
    <label><input type="checkbox" name="perline" value="1"<?=($perline)? ' checked':''; ?>> One Per Line</label>
    <br /><br />
    <label>Salt: <input type="text" name="salt" value="<?=htmlentities($salt,ENT_COMPAT); ?>"></label>
    <input type="submit" name="op" value="Encrypt">
    <input type="submit" name="op" value="Decrypt">
  </div>
</form>
</body>
</html>
