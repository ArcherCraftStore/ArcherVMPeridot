<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/ArcherCraft_Age_IV_Online/db_server_login.php');
?>
<!DOCTYPE HTML>
<html>
<head>

<title>
<?php
$doc_title="ArcherCraft Online Server";
echo $doc_title;
?>
</title>
<?php
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Text to send if user hits Cancel button';
    exit;
} else {
    echo "<p class=\"USERTICKER\">Hello ,  {$_SERVER['PHP_AUTH_USER']}.</p>";
}
    ?>
<meta content='width=device-width, initial-scale=1.0, user-scalable=no' name='viewport'>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
 <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
<script>
$(function(){
  $("#tabs").tabs();
});
</script>
<link rel="stylesheet" type="text/css" href="_styles/css/servergamestyles.css"/>

</head>
<body>
<h1>

<div id="tabs">
<ul>
<li><a href="#tabs-serverpane">View Open Games</a></li>
<li><a href="#tabs-new">New Game</a></li>
</ul>
<div id="tabs-serverpane">
<table>
<thead>
<th class="GameHeader">
Server Name
</th>
<th class="GameHeader">
Game Type</th>
<th class="GameHeader">
Maximum User Count</th>
<th class="GameHeader">
MacroServer Name</th>
</thead>
<tbody>
<?php


$result = mysql_query("SELECT * FROM `games`");
if (!$result) {
    $message  = 'Invalid query: ' . mysql_error() . "\n";
    die($message);
}
 while ($row = mysql_fetch_row($result)) {
    echo "<tr class=\"ServerGame\">";
  echo "<td class=\"SGameData\">".$row[0] ."</td>";
  echo "<td class=\"SGameData\">" . $row[1]."</td>";
  echo "<td class=\"SGameData\">".$row[2]."</td>";
  echo "<td class=\"SGameData\">".$row[3]."</td>";
  echo "</tr>";
}

$name = $_POST['name'];
$mu = $_POST['mu'];
$server = $_POST['server'];
$type=$_POST['type'];

$sql="INSERT INTO Games (name, MAXUSERS, server, type ) VALUES ('$name','$mu','$server','$type')";
$result = mysql_query($sql);
mysql_close($link);
?>


</tbody>
</table>
</div>
<div id="tabs-new">
<form action="<?php echo  htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
Name: <input type="text" name="name">
MAXUSERS: <input type="text" name="mu">
SERVER: <input type="text" name="server">
TYPE: <input type="text"  name="type">
<input type="submit" value="Start Game!" >
</form>

</div>
</body>
</html>