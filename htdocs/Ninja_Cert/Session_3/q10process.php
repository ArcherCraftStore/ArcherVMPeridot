<?php
 $ansuser = $_POST["q10"];
 if($ansuser == 40){
   header("Locaton: q11.html");
 }else{
   header("Location: http://localhost/Ninja_Cert/failure.php");
 }