<!DOCTYPE HTML>
<html>
<head>
<title>My Apps</title>
<link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>
<?php

 // Connects to your Database

 mysql_connect("localhost", "acoadmin", "aco1234$") or die(mysql_error());

 mysql_select_db("acoserver_acoserver") or die(mysql_error());

 
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
 			  
	echo "<div class=\"header\">";
		echo "<h1>Start</h1>";
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

 header("Location: login.php");

 }

 ?>
		}
		?>
<div class='spacer'>
			<a href="javascript://" class='wide blue'>
				<i class="icon-home"></i>
				<h2>Show Desktop</h2>
			</a>
			<a href="http://localhost/phpmyadmin" class='wide orange'>
				<i class="icon-cog"></i>
				<h2>Settings</h2>
			</a>
			<a href="javascript://" class='box redgay'>
				<i class="icon-camera"></i>
				<h2>Camera</h2>
			</a>
			<a href="javascript://" class='box lime'>
				<i class="icon-heart"></i>
				<h2>Favorite</h2>
			</a>
			<a href="javascript://" class='box bluefish'>
				<i class="icon-twitter"></i>
				<h2>Twitter</h2>
			</a>
			<a href="javascript://" class='box yellow'>
				<i class="icon-map-marker"></i>
				<h2>Places</h2>
			</a>
			<a href="http://learn.code.org/sh/25626904" class='wide magenta gallery'>
				<h2>Gallery Item 1</h2>
			</a>
			<a href="http://learn.code.org/sh/174979" class='wide magenta gallery'>
				<h2>Gallery Item 2</h2>
			</a>
			<a href="javascript://" class='box redgay'>
				<i class="icon-globe"></i>
				<h2>Browser</h2>
			</a>
			<a href="javascript://" class='box orange'>
				<i class="icon-envelope-alt"></i>
				<h2>E-mail</h2>
			</a>
			<a href="javascript://" class='wide blue cal_e'>
				<h1><?php echo date("d"); ?></h1><p><?php echo date("l"); ?></p>
				<h2 class="top cal_i">None</h2>
				<i class="icon-calendar"></i>
			</a>
			<a href="https://skydrive.live.com" class='box lime'>
				<i class="icon-cloud"></i>
				<h2>SkyDrive</h2>
			</a>
		</div>

		<div class='spacer spacer3x'>
			<a href="javascript://" class='box blue fb'>
				<i class="icon-facebook"></i>
				<h2>Facebook</h2>
			</a>
			<a href="javascript://" class='box bluefish'>
				<i class="icon-facetime-video"></i>
				<h2>FaceTime</h2>
			</a>
			<a href="javascript://" class='box redgay'>
				<i class="icon-tasks"></i>
				<h2>Task</h2>
			</a>
			<a href="http://localhost/wordpress" class='box magenta'>
				<i class="icon-list-alt"></i>
				<h2>Blog</h2>
			</a>
			<a href="javascript://" class='wide yellow'>
				<i class="icon-play"></i>
				<h2>Media</h2>
			</a>
		</div>
			<div id="screen-nav">
				<button data-dir="prev" id="back"><</button>
			</div>
</body>
</html>