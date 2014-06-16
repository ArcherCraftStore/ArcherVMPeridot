<?php
 $answer_user = $_POST["q5"];
 if($answer_user == "Dalton"){
        header("Location: http://localhost:80/Ninja_Cert/Session_2/q6.html");
     }else{
     header("Location: http://localhost:80/Ninja_Cert/Session_1/failure.php");
     }
