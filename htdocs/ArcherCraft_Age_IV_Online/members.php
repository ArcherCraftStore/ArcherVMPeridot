 <?php

 // Connects to your Database

 mysql_connect("localhost", "archercraft_admn", "aco1234$") or die(mysql_error());

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
 			   greetings($info['username']);

 			 echo returnName($info['username'])."'s  Area<p>";
 			 
         
  
 echo "My Content<p>";
 
  echo "Number of Letters in My Name: ".strlen(returnName($info['username']))."<p> ";
  

aboutMe($info['username'],$info['age']);
 echo "\n <a href=logout.php>Logout</a>";

 			}

 		}

 		}

 else

 

 //if the cookie does not exist, they are taken to the login screen

 {

 header("Location: login.php");

 }

        function displayName(){
            echo $username;
        }
       
        function returnName($name){
            return $name;
        }
         function greetings($name){
              echo "Greetings,  $name !<p>";
          }
          function aboutMe($name,$age){
          echo "Hello! My name is $name, and I am $age years old.";
      }
          
 ?>