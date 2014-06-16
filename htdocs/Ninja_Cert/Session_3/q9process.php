<?php 
 $ansuser = $_POST["q9"];
if($ansuser == "(-3x)(-3x) + 13x + 5"){
header("Location: q10.html");
}else{
header("Location: failure.php");
}