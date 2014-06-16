<?php
 $answer_user = $_POST["q8"];
 if($answer_user == "Rutherford"){
        header("Location: http://localhost:80/Ninja_Cert/Session_2/q9.html");
     }else{
     header("Location: http://localhost:80/Ninja_Cert/Session_1/failure.php");
     }
