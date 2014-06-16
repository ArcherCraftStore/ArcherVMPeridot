<!DOCTYPE HTML>
		<html>
		<head>
		<title>My NinjaCert Quiz</title>
		<link type="text/css" rel="stylesheet" href="quizindex.css"/>
		<link href="http://s3.amazonaws.com/codecademy-content/courses/ltp/css/shift.css" rel="stylesheet">
		</head>
		<body>
		<div class="nav"></div>
		<h1>Ninja Test</h1>
		<?php

 // Connects to your Database

 mysql_connect("localhost", "acoadmin", "aco1234$") or die(mysql_error());

 mysql_select_db("acoserver") or die(mysql_error());

 
 //checks cookies to make sure they are logged in

 if(isset($_COOKIE['ID_my_site']))

 {

 	$username = $_COOKIE['ID_my_site'];

 	$pass = $_COOKIE['Key_my_site'];

 	 	$check = mysql_query("SELECT * FROM users WHERE username = '$username'")or die(mysql_error());

 	while($info = mysql_fetch_array( $check ))

 		{

 

 //if the cookie has the wrong password, they are taken to the login page

 		if ($pass != $info['password'])

 			{ 			header("Location: login.php");

 			}

 

 //otherwise they are shown the admin area

 	else

 			{
 			  
	echo "<div class=\"jumbotron\">";
		echo "<h1>Start Your Ninja Certification Quiz!</h1>";
		echo " <div class=\"profile\">";
				echo"	<p class=\"name\">".$info['username']."</p>";
					echo "<p class=\"status\">Hello :)</p>";
				echo"</div>";
echo "</div>";
 			}

 		}

 		}

 else

 

 //if the cookie does not exist, they are taken to the login screen

 {

 header("Location: http://localhost/login.php");

 }

 ?>
 <button onclick="window.location.assign('//localhost/Ninja_Cert/Session_1/')">Take The Test</button>
