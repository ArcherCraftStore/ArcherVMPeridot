
<!DOCTYPE html>
<html xmlns:fb="http://ogp.me/ns/fb#" manifest="manifest.appcache">
<head>
<link rel="icon" href="favicon.ico"/>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>ArcherCraft Age IV Online Home</title>
 <meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content='width=device-width, initial-scale=1.0, user-scalable=no' name='viewport'>
<script src="https//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
<link rel="stylesheet" href="htjsdocs/jquery-mobile-theme-062536-0/themes/my-custom-theme.css" />
  <link rel="stylesheet" href="htjsdocs/jquery-mobile-theme-062536-0/themes/jquery.mobile.icons.min.css" />
  <link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.3/jquery.mobile.structure-1.4.3.min.css" />
<script type="text/javascript" src="script.js"></script>
<link href="_styles/css/stylesheet.css" rel="stylesheet" type="text/css"/>
<?php

    $function = $_POST['function'];
    
    $log = array();
    
    switch($function) {
    
       case 'getState' :
           if (file_exists('chat.txt')) {
               $lines = file('chat.txt');
           }
           $log['state'] = count($lines);
           break;
      
       case 'update' :
          $state = $_POST['state'];
          if (file_exists('chat.txt')) {
             $lines = file('chat.txt');
          }
          $count =  count($lines);
          if ($state == $count){
             $log['state'] = $state;
             $log['text'] = false;
          } else {
             $text= array();
             $log['state'] = $state + count($lines) - $state;
             foreach ($lines as $line_num => $line) {
                 if ($line_num >= $state){
                       $text[] =  $line = str_replace("\n", "", $line);
                 }
             }
             $log['text'] = $text;
          }
            
          break;
       
       case 'send' :
       	 $nickname = htmlentities(strip_tags($_POST['nickname']));
	     $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
	     $message = htmlentities(strip_tags($_POST['message']));
	     if (($message) != "\n") {
	       if (preg_match($reg_exUrl, $message, $url)) {
	          $message = preg_replace($reg_exUrl, '<a href="'.$url[0].'" target="_blank">'.$url[0].'</a>', $message);
	       }
	          fwrite(fopen('chat.txt', 'a'), "<span>". $nickname . "</span>" . $message = str_replace("\n", " ", $message) . "\n");
	     }
         break;
    }
    echo json_encode($log);
?>

<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<script src="SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>
<script src="Scripts/swfobject_modified.js" type="text/javascript"></script>
<script src="SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryCollapsiblePanel.css" rel="stylesheet" type="text/css">
<script>
var battery = navigator.battery || navigator.mozBattery || navigator.webkitBattery;

function updateBatteryStatus() {
  console.log("Battery status: " + battery.level * 100 + " %");

  if (battery.charging) {
    console.log("Battery is charging");
  }
}

battery.addEventListener("chargingchange", updateBatteryStatus);
battery.addEventListener("levelchange", updateBatteryStatus);
updateBatteryStatus();
</script>

<link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="Scripts/js/net.js"></script>

<script type="text/javascript">
function signInCallback(authResult) {
  if (authResult['code']) {

    // Hide the sign-in button now that the user is authorized, for example:
    $('#signinButton').attr('style', 'display: none');

    // Send the code to the server
    $.ajax({
      type: 'POST',
      url: 'plus.php?storeToken',
      contentType: 'application/octet-stream; charset=utf-8',
      success: function(result) {
        // Handle or verify the server response if necessary.

        // Prints the list of people that the user has allowed the app to know
        // to the console.
        console.log(result);
        if (result['profile'] && result['people']){
          $('#results').html('Hello ' + result['profile']['displayName'] + '. You successfully made a server side call to people.get and people.list');
        } else {
          $('#results').html('Failed to make a server-side call. Check your configuration and console.');
        }
      },
      processData: false,
      data: authResult['code']
    });
  } else if (authResult['error']) {
    // There was an error.
    // Possible error codes:
    //   "access_denied" - User denied access to your app
    //   "immediate_failed" - Could not automatially log in the user
    // console.log('There was an error: ' + authResult['error']);
  }
}
</script>

<script>navigator.wifi.onenabled = function() {
  alert( "Wifi has been enabled" );
};</script>
<script>

   $(function(){
   $('#progressbar').progressbar({value: false});
   $('#progressbar').progressbar({value: 0});
   $('#tabs').tabs();
   $("#progressbar").progressbar({value: 14});
   $( "#dialog" ).dialog();
    $('#progressbar').progressbar({value: 28});
    $('#menu').menu();
     $('#progressbar').progressbar({value: 42});
     $('#tabs2').tabs();
      $('#progressbar').progressbar({value:42+14});
  $('#header').resizable();
  var availableTags = [
      "ActionScript",
      "AppleScript",
      "Asp",
      "BASIC",
      "C",
      "C++",
      "Clojure",
      "COBOL",
      "ColdFusion",
      "Erlang",
      "Fortran",
      "Groovy",
      "Haskell",
      "Java",
      "JavaScript",
      "Lisp",
      "Perl",
      "PHP",
      "Python",
      "Ruby",
      "Scala",
      "Scheme"
    ];
    $( "#ServerName" ).autocomplete({
      source: availableTags
    });
    $('#mode').tabs();
    $('#progressbar').progressbar({value: 42+28});
    $("#tabs-2").sortable();
    $('#progressbar').progressbar({value: 100});
  });
  $(document).ready(function() {
    $('input').focus(function() {
        $(this).css('outline-color','#FF0000');
    });
});
</script>
 <style>
  .ui-menu { width: 150px; }
  </style>
<script>
function signinCallback(authResult) {
  if (authResult['status']['signed_in']) {
    // Update the app to reflect a signed in user
    // Hide the sign-in button now that the user is authorized, for example:
    document.getElementById('signinButton').setAttribute('style', 'display: none');
  } else {
    // Update the app to reflect a signed out user
    // Possible error values:
    //   "user_signed_out" - User is signed-out
    //   "access_denied" - User denied access to your app
    //   "immediate_failed" - Could not automatically log in the user
    console.log('Sign-in state: ' + authResult['error']);
  }
}</script>
<script>
function startTime()
{
var today=new Date();
var h=today.getHours();
var m=today.getMinutes();
var s=today.getSeconds();
// add a zero in front of numbers<10
m=checkTime(m);
s=checkTime(s);
document.getElementById('txt').innerHTML=h+":"+m+":"+s;
t=setTimeout(function(){startTime()},500);
}

function checkTime(i)
{
if (i<10)
  {
  i="0" + i;
  }
return i;
}
</script>
<meta name="google-translate-customization" content="dec471cf79e9b7db-a9897a76a5c17c06-ga0ea6e27b18d2483-24">
<script>
function Init(){
  startTime();
 setInterval('chat.update()', 1000);
}
</script>
<script>
function orsc(e){
 var wsocket = new WebSocket("ws://localhost/socket");
  wsocket.onopen = function(e){
    wsocket.send("Server Loaded");
  }
  wsocket.onmessage = function(event) {
  var f = document.getElementById("chatbox").contentDocument;
  var text = "";
  var msg = JSON.parse(event.data);
  var time = new Date(msg.date);
  var timeStr = time.toLocaleTimeString();
  
  switch(msg.type) {
    case "id":
      clientID = msg.id;
      setUsername();
      break;
    case "username":
      text = "<b>User <em>" + msg.name + "</em> signed in at " + timeStr + "</b><br>";
      break;
    case "message":
      text = "(" + timeStr + ") <b>" + msg.name + "</b>: " + msg.text + "<br>";
      break;
    case "rejectusername":
      text = "<b>Your username has been set to <em>" + msg.name + "</em> because the name you chose is in use.</b><br>"
      break;
    case "userlist":
      var ul = "";
      for (i=0; i < msg.users.length; i++) {
        ul += msg.users[i] + "<br>";
      }
      document.getElementById("userlistbox").innerHTML = ul;
      break;
  }
  
  if (text.length) {
    f.write(text);
    document.getElementById("chatbox").contentWindow.scrollByPages(1);
  }
};
}
  </script>
<script src="chat.js"></script>
<script>

  // ask user for name with popup prompt
  var name = prompt("Enter your chat name:", "Guest");
 
  // default name is 'Guest'
  if (!name || name === ' ') {
    name = "Guest";
  }
  
  // strip tags
  name = name.replace(/(<([^>]+)>)/ig,"");
  
  // display name on page
  $("#name-area").html("You are: <span>" + name + "</span>");
  
  // kick off chat
  var chat =  new Chat();

  $(function() {
  
     chat.getState();
     
     // watch textarea for key presses
     $("#sendie").keydown(function(event) {
     
         var key = event.which;
   
         //all keys including return.
         if (key >= 33) {
           
             var maxLength = $(this).attr("maxlength");
             var length = this.value.length;
             
             // don't allow new content if length is maxed out
             if (length >= maxLength) {
                 event.preventDefault();
             }
         }
                                                                                                     });
     // watch textarea for release of key press
     $('#sendie').keyup(function(e) {
                
        if (e.keyCode == 13) {
        
              var text = $(this).val();
              var maxLength = $(this).attr("maxlength");
              var length = text.length;
               
              // send
              if (length <= maxLength + 1) {
                chat.send(text, name);
                $(this).val("");
              } else {
                $(this).val(text.substring(0, maxLength));
              }
        }
     });
  });
</script>

</head>

<body id="indexphp" onload="Init()" onoffline="alert('You are Offline');" ononline="alert('You are Offline')" onreadystatechange="orsc()">
<meta name="google-signin-clientid" content="422061338001-pinuesnvn14109qjthcb7o6f39bi84cb.apps.googleusercontent.com
" />
 
<div id="dialog" title="ArcherChat">
<div id="page-wrap">

    <h2>jQuery/PHP Chat</h2>
    
    <p id="name-area"></p>
    
    <div id="chat-wrap"><div id="chat-area"></div></div>
    
    <form id="send-message-area" onsubmit="sendChat($(':input#sendie').val(),name);">
        <p>Your message: </p>
        <textarea id="sendie" maxlength = '100'></textarea>
        <input type="submit" value="Send">
    </form>
</div>
</div>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script><div id="mode"><ul><li><a href="#mode-client">Main</a></li><li><a href="#mode-server">Server Mode</a></li><li><a href="#Login">Login</a></ul><div id="mode-client"><header id="header">
<img src="_static/media/img/ACOIV.png" width="103" height="58"><h1  id="title">
<?php
if(isset($_POST["ServerName"])){
// Check for safe mode
if( ini_get('safe_mode') ){

switch($_POST["ServerName"]):
  case "main":
    $dsnu = strtoupper("aco4567");
     echo "ArcherCraft Age VI Online : ".$dsnu."SAFE MODE : ".$_SERVER['PHP_AUTH_USER'];
     break;
  case "":
    echo "ArcherCraft Age VI Online SAFE MODE ".$_SERVER['PHP_AUTH_USER'];
    break;
  default:
    $snu = strtoupper($_POST["ServerName"]);
     echo "ArcherCraft Age VI Online : ".$snu."SAFE MODE ".$_SERVER['PHP_AUTH_USER'];
     break;
endswitch;

    // Do it the safe mode way
}else{
    switch($_POST["ServerName"]):
  case "main":
    $dsnu = strtoupper("aco4567");
     echo "ArcherCraft Age VI Online : ".$dsnu."SAFE MODE ".$_SERVER['PHP_AUTH_USER'];
     break;
  case "":
    echo "ArcherCraft Age VI Online SAFE MODE ".$_SERVER['PHP_AUTH_USER'];
    break;
  default:
    $snu = strtoupper($_POST["ServerName"]);
     echo "ArcherCraft Age VI Online : ".$snu."SAFE MODE ".$_SERVER['PHP_AUTH_USER'];
     break;
endswitch;
}
}else{
  echo "ArcherCraft Age VI Online ".$_SERVER['PHP_AUTH_USER'];
}
  
?></h1>
<div id="resources"><?php


?></div>
<div id="txt"></div>
</header></div>
<div>	<?php
	// Create an array and push on the names
	if(isset($_POST['ServerName'])){
	$names = array();
    // of your closest family and friends
    array_push($names ,$_POST['ServerName']);
    array_push($names, "Derrick");
	// Sort the list
    sort($names);
	// Randomly select a winner!
     $winner = strtoupper($names[rand(0, count($names)-1)]);
	// Print the winner's name in ALL CAPS
	print $winner;
	}else{
	  echo 'You must have a Server Name to Play';
	}
	?></div>
<nav><fb:share-button href="http://edmodo.com" type="button_count"></fb:share-button><a href="https://twitter.com/WeldonHenson4" class="twitter-follow-button" data-show-count="false">Follow @WeldonHenson4</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script><a href="https://twitter.com/share" class="twitter-share-button" data-url="https://archercraft.blogspot.com" data-via="WeldonHenson4">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></nav>



<div class="g-follow" data-annotation="bubble" data-height="20" data-href="//plus.google.com/107221287904114524007" data-rel="publisher"><script type="text/javascript" src="https://apis.google.com/js/platform.js"></script>
<g:hangout render="createhangout"></g:hangout></div>
<div><script>
  (function() {
    var cx = '008504857794623096248:l5zi_fb3fyq';
    var gcse = document.createElement('script');
    gcse.type = 'text/javascript';
    gcse.async = true;
    gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
        '//www.google.com/cse/cse.js?cx=' + cx;
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(gcse, s);
  })();
</script>
<gcse:search></gcse:search></div>
<div id="tabs">
<ul><li><a href="#tabs-1">Nav</a></li></ul>
<div id="tabs-1">
<ul id="MenuBar1" class="MenuBarHorizontal">
  <li><a class="MenuBarItemSubmenu" href="#">Play</a>
    <ul>
      <li><a href="Play.html">Age I</a></li>
      <li><a href="#">Item 1.2</a></li>
      <li><a href="#">Item 1.3</a></li>
    </ul>
  </li>
  <li><a href="#">Item 2</a></li>
  <li><a class="MenuBarItemSubmenu" href="#">Item 3</a>
    <ul>
      <li><a class="MenuBarItemSubmenu" href="#">Item 3.1</a>
        <ul>
          <li><a href="http://www.adobe.com/go/getflashplayer">Get Flash Player </a></li>
          <li><a href="#">Item 3.1.2</a></li>
        </ul>
      </li>
      <li><a href="#">Item 3.2</a></li>
      <li><a href="#">Item 3.3</a></li>
    </ul>
  </li>
  <li><a href="#">Item 4</a></li>
</ul>
<br>
<br>
<br>
<br>
</div>

</div>
 

<div id="tabs2"><ul><li><a href="#tabs2-2">Menu</a></li><li><a href="#tabs2-1">Main</a></li><li><a href="#tabs2-3">Pane</a></li><li><a href="tabs2-4">Runner</a></li><li><a href="#gist">Gist</a></li><li><a href="#login_ind_user">User Login</a></li></ul><div id="tabs2-2"><ul id="menu">
  <li ><a href="index.html">Mobile</a></li>
  <li><a href="nativestore.htm">Native Store</a></li>
  <li><a href="#">Play</a><ul><li><a href="Play.html">Age 1</a></li>
  </ul></li><li><a href="#">Addyston</a></li>
  <li>
    <a href="#">Delphi</a>
    <ul>
      <li class="ui-state-disabled"><a href="#">Ada</a></li>
      <li><a href="#">Saarland</a></li>
      <li><a href="#">Salzburg</a></li>
    </ul>
  </li>
  <li><a href="#">Saarland</a></li>
  <li>
    <a href="#">Salzburg</a>
    <ul>
      <li>
        <a href="#">Delphi</a>
        <ul>
          <li><a href="#">Ada</a></li>
          <li><a href="#">Saarland</a></li>
          <li><a href="#">Salzburg</a></li>
        </ul>
      </li>
      <li>
        <a href="#">Delphi</a>
        <ul>
          <li><a href="#">Ada</a></li>
          <li><a href="#">Saarland</a></li>
          <li><a href="#">Salzburg</a></li>
        </ul>
      </li>
      <li><a href="#">Perch</a></li>
    </ul>
  </li>
  <li class="ui-state-disabled"><a href="#">Amesville</a></li>
</ul></div><div id="tabs2-3"><iframe src="pane.php" width="1000" height="1000"></iframe></div><div id="tabs2-1">
<div id="TabbedPanels1" class="TabbedPanels">
    <ul class="TabbedPanelsTabGroup">
      <li class="TabbedPanelsTab" tabindex="0">Main</li>
      <li class="TabbedPanelsTab" tabindex="0">Forum</li>
      <li class="TabbedPanelsTab" tabindex="0"> Server Info</li>
         <li class="TabbedPanelsTab" tabindex="0">Calendar</li>
    </ul>
    <div class="TabbedPanelsContentGroup">
      <div class="TabbedPanelsContent">
    <table  border="1">
    <thead>
   <tr> <th class="Actionheader"></th></tr>
    
      </thead>
     <tbody>
      <tr>
      <td class="ActionCell">
          <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="550" height="400" id="FlashID" title="intro1">
            <param name="movie" value="_static/media/swf/B4 henson weldon intro.swf">
            <param name="quality" value="high">
            <param name="wmode" value="opaque">
            <param name="swfversion" value="6.0.65.0">
            <!-- This param tag prompts users with Flash Player 6.0 r65 and higher to download the latest version of Flash Player. Delete it if you don’t want users to see the prompt. -->
            <param name="expressinstall" value="Scripts/expressInstall.swf">
            <!-- Next object tag is for non-IE browsers. So hide it from IE using IECC. -->
            <!--[if !IE]>-->
            <object type="application/x-shockwave-flash" data="_static/media/swf/B4 henson weldon intro.swf" width="550" height="400">
              <!--<![endif]-->
              <param name="quality" value="high">
              <param name="wmode" value="opaque">
              <param name="swfversion" value="6.0.65.0">
              <param name="expressinstall" value="Scripts/expressInstall.swf">
              <!-- The browser displays the following alternative content for users with Flash Player 6.0 and older. -->
              <div>
                <h4>Content on this page requires a newer version of Adobe Flash Player.</h4>
                <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" width="112" height="33" /></a></p>
              </div>
              <!--[if !IE]>-->
            </object>
            <!--<![endif]-->
          </object>
        </td>
        <td  class="ActionCell" ><object id="FlashID2" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="550" height="400">
          <param name="movie" value="_static/media/swf/b4 henson weldon frame to frame.swf">
          <param name="quality" value="high">
          <param name="wmode" value="opaque">
          <param name="swfversion" value="6.0.65.0">
          <!-- This param tag prompts users with Flash Player 6.0 r65 and higher to download the latest version of Flash Player. Delete it if you don’t want users to see the prompt. -->
          <param name="expressinstall" value="Scripts/expressInstall.swf">
          <!-- Next object tag is for non-IE browsers. So hide it from IE using IECC. -->
          <!--[if !IE]>-->
          <object type="application/x-shockwave-flash" data="_static/media/swf/b4 henson weldon frame to frame.swf" width="550" height="400">
            <!--<![endif]-->
            <param name="quality" value="high">
            <param name="wmode" value="opaque">
            <param name="swfversion" value="6.0.65.0">
            <param name="expressinstall" value="Scripts/expressInstall.swf">
            <!-- The browser displays the following alternative content for users with Flash Player 6.0 and older. -->
            <div>
              <h4>Content on this page requires a newer version of Adobe Flash Player.</h4>
              <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" width="112" height="33" /></a></p>
            </div>
            <!--[if !IE]>-->
          </object>
          <!--<![endif]-->
        </object></td>
        <td class="ActionCell"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="550" height="400" id="FlashID3" title="Kaleidoscope">
          <param name="movie" value="_static/media/swf/b4 henson weldon kaleidoscope.swf">
          <param name="quality" value="high">
          <param name="wmode" value="opaque">
          <param name="swfversion" value="6.0.65.0">
          <!-- This param tag prompts users with Flash Player 6.0 r65 and higher to download the latest version of Flash Player. Delete it if you don’t want users to see the prompt. -->
          <param name="expressinstall" value="Scripts/expressInstall.swf">
          <!-- Next object tag is for non-IE browsers. So hide it from IE using IECC. -->
          <!--[if !IE]>-->
          <object type="application/x-shockwave-flash" data="_static/media/swf/b4 henson weldon kaleidoscope.swf" width="550" height="400">
            <!--<![endif]-->
            <param name="quality" value="high">
            <param name="wmode" value="opaque">
            <param name="swfversion" value="6.0.65.0">
            <param name="expressinstall" value="Scripts/expressInstall.swf">
            <!-- The browser displays the following alternative content for users with Flash Player 6.0 and older. -->
            <div>
              <h4>Content on this page requires a newer version of Adobe Flash Player.</h4>
              <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" width="112" height="33" /></a></p>
            </div>
            <!--[if !IE]>-->
          </object>
          <!--<![endif]-->
        </object></td>
        <td  class="ActionCell"><object id="FlashID4" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="550" height="400">
          <param name="movie" value="_static/media/swf/b4 henson weldon movie clips.swf">
          <param name="quality" value="high">
          <param name="wmode" value="opaque">
          <param name="swfversion" value="6.0.65.0">
          <!-- This param tag prompts users with Flash Player 6.0 r65 and higher to download the latest version of Flash Player. Delete it if you don’t want users to see the prompt. -->
          <param name="expressinstall" value="Scripts/expressInstall.swf">
          <!-- Next object tag is for non-IE browsers. So hide it from IE using IECC. -->
          <!--[if !IE]>-->
          <object type="application/x-shockwave-flash" data="_static/media/swf/b4 henson weldon movie clips.swf" width="550" height="400">
            <!--<![endif]-->
            <param name="quality" value="high">
            <param name="wmode" value="opaque">
            <param name="swfversion" value="6.0.65.0">
            <param name="expressinstall" value="Scripts/expressInstall.swf">
            <!-- The browser displays the following alternative content for users with Flash Player 6.0 and older. -->
            <div>
              <h4>Content on this page requires a newer version of Adobe Flash Player.</h4>
              <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" width="112" height="33" /></a></p>
            </div>
            <!--[if !IE]>-->
          </object>
          <!--<![endif]-->
        </object></td>
        <td  class="ActionCell">&nbsp;</td>
        <td  class="ActionCell">&nbsp;</td>
        <td  class="ActionCell">&nbsp;</td>
        <td  class="ActionCell">&nbsp;</td>
        <td  class="ActionCell">&nbsp;</td>
      </tr>
     <tr>
     <td class="ActionCell">
    <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="550" height="400" id="FlashID5" title="bird">
      <param name="movie" value="_static/media/swf/b4 henson weldon bird.swf">
      <param name="quality" value="high">
      <param name="wmode" value="opaque">
      <param name="swfversion" value="6.0.65.0">
      <!-- This param tag prompts users with Flash Player 6.0 r65 and higher to download the latest version of Flash Player. Delete it if you don’t want users to see the prompt. -->
      <param name="expressinstall" value="Scripts/expressInstall.swf">
      <!-- Next object tag is for non-IE browsers. So hide it from IE using IECC. -->
      <!--[if !IE]>-->
      <object type="application/x-shockwave-flash" data="_static/media/swf/b4 henson weldon bird.swf" width="550" height="400">
        <!--<![endif]-->
        <param name="quality" value="high">
        <param name="wmode" value="opaque">
        <param name="swfversion" value="6.0.65.0">
        <param name="expressinstall" value="Scripts/expressInstall.swf">
        <!-- The browser displays the following alternative content for users with Flash Player 6.0 and older. -->
        <div>
          <h4>Content on this page requires a newer version of Adobe Flash Player.</h4>
          <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" width="112" height="33" /></a></p>
        </div>
        <!--[if !IE]>-->
      </object>
      <!--<![endif]-->
    </object></td>
          <td  class="ActionCell">&nbsp;</td>
          <td  class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        </tr>
        <tr>
      <td class="ActionCell">
    <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="550" height="400" id="FlashID6" title="video">
      <param name="movie" value="_static/media/swf/Video1.swf">
      <param name="quality" value="high">
      <param name="wmode" value="opaque">
      <param name="swfversion" value="6.0.65.0">
      <!-- This param tag prompts users with Flash Player 6.0 r65 and higher to download the latest version of Flash Player. Delete it if you don’t want users to see the prompt. -->
      <param name="expressinstall" value="Scripts/expressInstall.swf">
      <!-- Next object tag is for non-IE browsers. So hide it from IE using IECC. -->
      <!--[if !IE]>-->
      <object type="application/x-shockwave-flash" data="_static/media/swf/Video1.swf" width="550" height="400">
        <!--<![endif]-->
        <param name="quality" value="high">
        <param name="wmode" value="opaque">
        <param name="swfversion" value="6.0.65.0">
        <param name="expressinstall" value="Scripts/expressInstall.swf">
        <!-- The browser displays the following alternative content for users with Flash Player 6.0 and older. -->
        <div>
          <h4>Content on this page requires a newer version of Adobe Flash Player.</h4>
          <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" width="112" height="33" /></a></p>
        </div>
        <!--[if !IE]>-->
      </object>
      <!--<![endif]-->
    </object>
   </td>
   </tr>
        <tr>
          <td class="ActionCell"><object id="FlashID7" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="550" height="400">
      <param name="movie" value="_static/media/swf/b4 henson weldon basketball.swf">
      <param name="quality" value="high">
      <param name="wmode" value="opaque">
      <param name="swfversion" value="6.0.65.0">
      <!-- This param tag prompts users with Flash Player 6.0 r65 and higher to download the latest version of Flash Player. Delete it if you don’t want users to see the prompt. -->
      <param name="expressinstall" value="Scripts/expressInstall.swf">
      <!-- Next object tag is for non-IE browsers. So hide it from IE using IECC. -->
      <!--[if !IE]>-->
      <object type="application/x-shockwave-flash" data="_static/media/swf/b4 henson weldon basketball.swf" width="550" height="400">
        <!--<![endif]-->
        <param name="quality" value="high">
        <param name="wmode" value="opaque">
        <param name="swfversion" value="6.0.65.0">
        <param name="expressinstall" value="Scripts/expressInstall.swf">
        <!-- The browser displays the following alternative content for users with Flash Player 6.0 and older. -->
        <div>
          <h4>Content on this page requires a newer version of Adobe Flash Player.</h4>
          <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" width="112" height="33" /></a></p>
        </div>
        <!--[if !IE]>-->
      </object>
      <!--<![endif]-->
    </object>
 </td>
 <td  class="ActionCell">&nbsp;</td>
        <td  class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        </tr>

    
      <tr>
        <td class="ActionCell">&nbsp;</td>
        <td  class="ActionCell">&nbsp;</td>
        <td  class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
      </tr>
      <tr>
        <td  class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell"><fb:comments href="https://archercraft.blogspot.com" numposts="5" colorscheme="light"></fb:comments></td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
      </tr>
      <tr>
        <td class="ActionCell"></td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell"><div class="g-page" data-href="//plus.google.com/107221287904114524007" data-rel="publisher"></div>
          </td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell"><a class="twitter-timeline" href="https://twitter.com/WeldonHenson4" data-widget-id="432329643320815616">Tweets by @WeldonHenson4</a>
          <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
          
          </td>
        <td class="ActionCell">&nbsp;</td>
      </tr>
      <tr>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell"><object classid="clsid:166B1BCA-3F9C-11CF-8075-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/director/sw.cab#version=10,1,1,0" width="550" height="400">
          <param name="src" value="_static/media/swf/B4 henson weldon code snippet.swf">
          <embed src="_static/media/swf/B4 henson weldon code snippet.swf" pluginspage="http://www.adobe.com/shockwave/download/" width="550" height="400"></embed>
        </object></td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
      </tr>
      <tr>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
      </tr>
      <tr>
        <td class="ActionCell"><div class="g-post" data-href="https://plus.google.com/107221287904114524007/posts/5nM6Akv4QMc"></div></td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell"><a data-pin-do="embedBoard" href="http://www.pinterest.com/weldonhenson5/developers-circle/">Follow Weldon Henson's board Developers' Circle on Pinterest.</a>
          <!-- Please call pinit.js only once per page -->
          <script type="text/javascript" async src="//assets.pinterest.com/js/pinit.js"></script></td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
      </tr>
      <tr>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
      </tr>
      <tr>
        <td class="ActionCell"><a href="http://www.pinterest.com/pin/create/button/?url=http%3A%2F%2Farchercraft.blogspot.com&media=archercraft.blogspot.com%2Ffavicon.ico&descriptio=The%20REAL%20Game%20of%20Thrones" data-pin-do="buttonPin" data-pin-config="above"><img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_gray_20.png" /></a>
          <!-- Please call pinit.js only once per page -->
          <script type="text/javascript" async src="//assets.pinterest.com/js/pinit.js"></script></td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell"><a class="twitter-timeline" href="https://twitter.com/WeldonHenson4/favorites" data-widget-id="432330890484518912">Favorite Tweets by @WeldonHenson4</a>
          <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
          
          </td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
      </tr>
      <tr>
        <td class="ActionCell"><object width="250" height="250" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" id="gsPlaylist9564508765" name="gsPlaylist9564508765"><param name="movie" value="http://grooveshark.com/widget.swf" /><param name="wmode" value="window" /><param name="allowScriptAccess" value="always" /><param name="flashvars" value="hostname=grooveshark.com&playlistID=95645087&p=0&bbg=000000&bth=000000&pfg=000000&lfg=000000&bt=FFFFFF&pbg=FFFFFF&pfgh=FFFFFF&si=FFFFFF&lbg=FFFFFF&lfgh=FFFFFF&sb=FFFFFF&bfg=666666&pbgh=666666&lbgh=666666&sbh=666666" /><object type="application/x-shockwave-flash" data="http://grooveshark.com/widget.swf" width="250" height="250"><param name="wmode" value="window" /><param name="allowScriptAccess" value="always" /><param name="flashvars" value="hostname=grooveshark.com&playlistID=95645087&p=0&bbg=000000&bth=000000&pfg=000000&lfg=000000&bt=FFFFFF&pbg=FFFFFF&pfgh=FFFFFF&si=FFFFFF&lbg=FFFFFF&lfgh=FFFFFF&sb=FFFFFF&bfg=666666&pbgh=666666&lbgh=666666&sbh=666666" /><span><a href="http://grooveshark.com/search/playlist?q=ArcherCraft%20Online%20Weldon%20Henson" title="ArcherCraft Online by Weldon Henson on Grooveshark">ArcherCraft Online by Weldon Henson on Grooveshark</a></span></object></object></td>        <td class="ActionCell"><object width="250" height="40" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" id="gsSong3922916728" name="gsSong3922916728"><param name="movie" value="http://grooveshark.com/songWidget.swf" /><param name="wmode" value="window" /><param name="allowScriptAccess" value="always" /><param name="flashvars" value="hostname=grooveshark.com&songID=39229167&style=metal&p=0" /><object type="application/x-shockwave-flash" data="http://grooveshark.com/songWidget.swf" width="250" height="40"><param name="wmode" value="window" /><param name="allowScriptAccess" value="always" /><param name="flashvars" value="hostname=grooveshark.com&songID=39229167&style=metal&p=0" /><span><a href="http://grooveshark.com/search/song?q=2013%20Loud%20Speakers%20So%20We%20Put%20Our%20Hands%20Up%20Like%20The%20Ceiling%20Can't%20Hold%20Us" title="So We Put Our Hands Up Like The Ceiling Can&#39;t Hold Us by 2013 Loud Speakers on Grooveshark">So We Put Our Hands Up Like The Ceiling Can&#39;t Hold Us by 2013 Loud Speakers on Grooveshark</a></span></object></object></td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
      </tr>
      <tr>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell"><input type="dropbox-chooser" name="selected-file" id="db-chooser"/>
          
          <script type="text/javascript">
    // add an event listener to a Chooser button
    document.getElementById("db-chooser").addEventListener("DbxChooserSuccess",
        function(e) {
            window.location.assign(files[0].link);
        }, false);
</script></td>
        <td class="ActionCell"></td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
      </tr>
      </tbody>
      
    </table>
    </div>
    <div class="TabbedPanelsContent"><iframe id="forum_embed"
  src="https://groups.google.com/forum/embed/?place=forum/archercraft-2-online&showsearch=true&showpopout=true&showtabs=false"
  scrolling="no"
  frameborder="0"
  width="900"
  height="700">
</iframe>
</div>
    <div class="TabbedPanelsContent"><?php phpinfo() ?></div><div class="TabbedPanelsContent"><iframe src="https://www.google.com/calendar/embed?src=g2pc2cvv8tlail952uu2f0jj1k%40group.calendar.google.com&ctz=America/New_York" style="border: 0" width="800" height="600" frameborder="0" scrolling="no"></iframe></div><div id="tabs2-4">  <img src="http://i1061.photobucket.com/albums/t480/ericqweinstein/mario.jpg"/><script>
    $(document).ready(function() {
    $(document).keydown(function(key) {
        switch(parseInt(key.which,10)) {
			// Left arrow key pressed
			case 37:
				$('img').animate({top: "+=10px"}, 'fast');
				break;
			// Up Arrow Pressed
			case 38:
case 37:
  $('img').animate({left: "-=10px"}, 'fast');				break;
			// Right Arrow Pressed
			case 39:
case 37:
 $('img').animate({left: "-=10px"}, 'fast');				break;
			// Down Array Pressed
			case 40:
case 37:
  $('img').animate({top: "+=10px"}, 'fast');				break;
		}
	});
});</script>
</div>
<div id="gist">   <script src="https://gist.github.com/ACOKing/9627170.js"></script>
</div>
<div id="login_ind_user">
  
</div>
</div>
<footer>
<div id="progressbar"></div>
<?php include("footer.php"); ?>
<div id="google_translate_element"></div><script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: 'es'}, 'google_translate_element');
}
</script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script><div id="log"></div></footer>
      </div>
</div>
</div>
<div id="mode-server">
	
<form name="server info" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
<div class="ui-widget">
  <label for="ServerName">Server Name (ACOSID) : </label>
  <input id="ServerName" name="ServerName" type="text">
</div>
<input type="submit" value="Create ArcherCraft Server">
</form>
    
</div>
<div id="Login">

 
</div>
</div>
<p>&nbsp;</p>
<script type="text/javascript">
var MenuBara = new Spry.Widget.MenuBar("MenuBara", {imgDown:"SpryAssets/SpryMenuBarDownHover.gif", imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
swfobject.registerObject("FlashID");
</script>

 <script type="text/javascript">
swfobject.registerObject("FlashID2");
swfobject.registerObject("FlashID3");
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
    </script>
   <script type="text/javascript">
swfobject.registerObject("FlashID5");
swfobject.registerObject("FlashID6");
swfobject.registerObject("FlashID7");
var MenuBar1 = new Spry.Widget.MenuBar("MenuBar1", {imgDown:"SpryAssets/SpryMenuBarDownHover.gif", imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
   </script>
            </div>
    
</body>
</html>