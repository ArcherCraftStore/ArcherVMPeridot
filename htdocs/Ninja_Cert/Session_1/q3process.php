<?php
 $answer_user = $_POST["q3"];
 if($answer_user == "7.590 kg/L"){
        header("Location: q4.html");
     }else{
     header("Location: failure.php");
     }
