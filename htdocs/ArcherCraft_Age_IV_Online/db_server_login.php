<?php
require_once('DB.php');
echo "Connecting to Server...";
$link = mysql_connect('localhost:3306', 'archercraft_admn', 'aco1234$', 'acoserver');
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db('acoserver', $link);
?>