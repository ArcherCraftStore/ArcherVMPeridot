<?php
 $answer_user = $_POST["q6"];
 if($answer_user == "J.J Thompson"){
        header("Location: http://localhost:80/Ninja_Cert/Session_2/q7.html");
     }else{
     header("Location: http://localhost:80/Ninja_Cert/Session_1/failure.php");
     }
