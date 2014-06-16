<?php
 $answer_user = $_POST["q7"];
 if($answer_user == "Dalton"){
        header("Location: http://localhost:80/Ninja_Cert/Session_2/q8.html");
     }else{
     header("Location: http://localhost:80/Ninja_Cert/Session_1/failure.php");
     }
