<!DOCTYPE HTML>
<html>
<head>
<title>VM Home</title>
<link rel="stylesheet" type="text/css" href="style.css"/>
<meta content='width=device-width, initial-scale=1.0, user-scalable=no' name='viewport'>
<script src="https//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
<script src="script.js"></script>
<script>
$(function(){
  $("#forw").click(function(){
  window.location.assign("archerapps1.php");
});
});

</script>
</head>
<body>
<div class="container">

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

	
<div class="container">

    

		<div class="slider">
			<ul class="screen">
				<li>
					<div class="module purple double img w">
						<h2 class="title">Home</h2>
						<p class="sub-heading">Welcome to the New Windows 8 UI</p>
					</div>
					<div class="module orange double img bing">
						<p class="title">Search</p>
						<form>
							<input type="text" placeholder="Search bing...">
							<input type="button" class="submit" value="submit">
						</form>
					</div>
					<div class="module yellow double img not">
						<p class="title">Notifications</p>
						<div class="img msg">
							<p class="sub-heading">View your notifications (3)</p>
						</div>
					</div>
					<div class="module red single img youtube">
						<p class="title">Video</p>
					</div>
					<div class="module yellow double img market">
						<p class="title">Drupal Market</p>
						<a href="https://drupal.org/download"><p class="sub-heading">Download the latest Modules and Themes for <strong>Drupal 7!</strong></p></a>
					</div>
					<div class="module yellow double img market">
						<p class="title">Wordpress Market</p>
						<a href="https://wordpress.org/plugins/"><p class="sub-heading">Download the latest plugins for <strong>Wordpress 3</strong></p></a>
					</div>
					<div class="module yellow double img market">
						<p class="title">Market</p>
						<p class="sub-heading">Download the latest apps for Windows 8 and Windows Phone</p>
					</div>
					<div class="module darkblue single img intel">
						<p class="title">Partners</p>
					</div>
					<div class="module pink double img mail">
						<p class="title">Mail</p>
						<p class="sub-heading">You have no new Mail</p>
						<p class="third-heading">:-(</p>
					</div>
					<div class="module green single img xbox">
						<p class="title">Xbox</p>
					</div>
					<a href="http://localhost:80/drupal"><div class="module blue double img twitter">
						<p class="title">Drupal</p>
						<p class="sub-heading"><i>"I Just got a nice Drupal App! #Drupal #ArcherDrupal"</i></p>
					</div>
					</a>
					<div class="module green double img excel">
						<p class="title">Drupal</p>
						<p class="sub-heading">Drupal is now available for the ArcherVM!</p>
					</div>
					<div class="module blue single img net">
						<p class="title">Internet</p>
					</div>
				</li>

				<li>
					<div class="module red double img w">
						<p class="title">Home</p>
					</div>
					<div class="module green double img android">
						<p class="title">Competitors</p>
					</div>
					<div class="module darkblue double img apple">
						<p class="title">Competitors</p>
					</div>
					<div class="module red double img power">
						<p class="title">Products</p>
					</div>
					<div class="module blue double img hp">
						<p class="title">Partners</p>
					</div>
					<div class="module blue single img ps">
						<p class="title">Engine</p>
					</div>
					<div class="module red single img fl">
						<p class="title">Engine</p>
					</div>
					<div class="module pink single img wo">
						<p class="title">XP</p>
					</div>
					<div class="module red single img birds">
						<p class="title">Games</p>
					</div>
					<div class="module blue single img drop">
						<p class="title">Products</p>
					</div>
					<div class="module orange double img access">
						<p class="title">Products</p>
					</div>
					<div class="module darkblue single img app">
						<p class="title">App</p>
					</div>
				</li>

				<li>
					<div class="module blue single img w">
						<p class="title">Home</p>
					</div>
					<div class="module red single img bing">
						<p class="title">Search</p>
					</div>
					<div class="module purple double img not">
						<p class="title">Notifications</p>
					</div>
					<div class="module green double img youtube">
						<p class="title">YouTube</p>
					</div>
					<div class="module yellow single img market">
						<p class="title">Market</p>
					</div>
					<div class="module darkblue single img intel">
						<p class="title">Partners</p>
					</div>
					<div class="module pink single img mail">
						<p class="title">Mail</p>
					</div>
					<div class="module orange single img xbox">
						<p class="title">Xbox</p>
					</div>
					<div class="module pink single img twitter">
						<p class="title">Social</p>
					</div>
					<div class="module blue single img xbox">
						<p class="title">Games</p>
					</div>
					<div class="module orange single img android">
						<p class="title">Android</p>
					</div>
					<div class="module pink double img intel">
						<p class="title">Intel</p>
					</div>
					<div class="module red double img youtube">
						<p class="title">YouTube</p>
					</div>
					<div class="module midblue single img mail">
					</div>
	</div>
	</ul>
	<div id="screen-nav">
			<button data-dir="prev" id="back"><</button>
			<button data-dir="next" id="forw">></button>
		</div>

	</div>
	 
					</body>
					</html>