<?php
 $answer_user = $_POST["q2"];
 if($answer_user == "0.01612 gal"){
        header("Location: q3.html");
     }else{
     header("Location: failure.php");
     }
