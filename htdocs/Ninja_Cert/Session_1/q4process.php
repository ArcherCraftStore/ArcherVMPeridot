<?php
 $answer_user = $_POST["q4"];
 if($answer_user == "1.295 g"){
        header("Location: http://localhost:80/Ninja_Cert/Session_2/q5.html");
     }else{
     header("Location: failure.php");
     }
