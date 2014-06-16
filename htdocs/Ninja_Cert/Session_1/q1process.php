<?php
 $answer_user = $_POST["q1"];
 if($answer_user == "0.302 meters"){
        header("Location: q2.html");
     }else{
     header("Location: failure.php");
     }
?>