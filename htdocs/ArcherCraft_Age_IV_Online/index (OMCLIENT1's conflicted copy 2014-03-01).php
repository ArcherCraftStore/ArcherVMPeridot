<!DOCTYPE html>


<html  xmlns:fb="http://ogp.me/ns/fb#" manifest="manifest.appcache" itemscope itemtype="http://schema.org/{CONTENT_TYPE}">
<head>
<link rel="icon" href="favicon.ico"/>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js">
<script type="text/javascript" src="Scripts/js/script.js"></script>

<script type="text/javascript" src="https://www.dropbox.com/static/api/1/dropins.js" id="dropboxjs" data-app-key="ht5i6acvi9u056p"></script>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta itemprop="name" content="Facebook">
<meta itemprop="description" content="ArcherCraft is now on Facebook!">
<meta itemprop="image" content="https://lh4.googleusercontent.com/-6ce8LE_nK4w/AAAAAAAAAAI/AAAAAAAAAAk/q0BdACS7zEY/s80-c-k-a-no/photo.jpg">
<title>ArcherCraft Age IV Online Home</title>
<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<script src="SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>
<script src="Scripts/swfobject_modified.js" type="text/javascript"></script>
<link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryCollapsiblePanel.css" rel="stylesheet" type="text/css">
<link href="_styles/css/stylesheet.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>

<script>
window.addEventListener('load', function() {
  var status = document.getElementById("status");

  function updateOnlineStatus(event) {
    var condition = navigator.onLine ? "online" : "offline";
    alert('You are' + condition);
    status.className = condition;
    status.innerHTML = condition.toUpperCase();

    log.insertAdjacentHTML("beforeend", "Event: " + event.type + "; Status: " + condition);
  }

  window.addEventListener('online',  updateOnlineStatus);
  window.addEventListener('offline', updateOnlineStatus);
});</script>
<script src="SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css">
</head>
<body ><meta name="google-signin-clientid" content="422061338001-pinuesnvn14109qjthcb7o6f39bi84cb.apps.googleusercontent.com
" />
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
<meta name="google-signin-scope" content="https://www.googleapis.com/auth/plus.login" />
<meta name="google-signin-requestvisibleactions" content="http://schemas.google.com/AddActivity" />
<meta name="google-signin-cookiepolicy" content="single_host_origin" />
<script type="text/javascript">
 (function() {
   var po = document.createElement('script');
   po.type = 'text/javascript'; po.async = true;
   po.src = 'https://apis.google.com/js/client:plusone.js?onload=render';
   var s = document.getElementsByTagName('script')[0];
   s.parentNode.insertBefore(po, s);
 })();

   /* Executed when the APIs finish loading */
 function render() {

   // Additional params including the callback, the rest of the params will
   // come from the page-level configuration.
   var additionalParams = {
     'callback': signinCallback
   };

   // Attach a click listener to a button to trigger the flow.
   var signinButton = document.getElementById('signinButton');
   signinButton.addEventListener('click', function() {
     gapi.auth.signIn(additionalParams); // Will use page level configuration
   });
 }
</script>
<button id="signinButton">Sign in with Google</button><div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script><header><div id="meter"></div> <img src="_static/media/img/ACOIV.png" width="103" height="58"><h1 id="title">ArcherCraft Age IV Online</h1>
  <p>&nbsp;</p>
</header>
<nav><fb:share-button href="http://edmodo.com" type="button_count"></fb:share-button><a href="https://twitter.com/WeldonHenson4" class="twitter-follow-button" data-show-count="false">Follow @WeldonHenson4</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script><a href="https://twitter.com/share" class="twitter-share-button" data-url="https://archercraft.blogspot.com" data-via="WeldonHenson4">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></nav>
<nav><span id="signinButton">
  <span
    class="g-signin"
    data-callback="signinCallback"
    data-clientid="422061338001-rkre7pavpv2dsp2q15b2n0ot6n1ibp88.apps.googleusercontent.com

"
    data-cookiepolicy="single_host_origin"
    data-requestvisibleactions="http://schemas.google.com/AddActivity"
    data-scope="https://www.googleapis.com/auth/plus.login">
  </span><button
  class="g-interactivepost"
  data-contenturl="https://plus.google.com/pages/"
  data-contentdeeplinkid="/pages"
  data-clientid="422061338001-rkre7pavpv2dsp2q15b2n0ot6n1ibp88.apps.googleusercontent.com
"
  data-cookiepolicy="single_host_origin"
  data-prefilltext="Engage your users today, create a Google+ page for your business."
  data-calltoactionlabel="BROWSE
"
  data-calltoactionurl="http://plus.google.com/pages/create"
  data-calltoactiondeeplinkid="/pages/create">
  Tell your friends
</button>
</span><div class="g-follow" data-annotation="bubble" data-height="20" data-href="//plus.google.com/107221287904114524007" data-rel="publisher"><script type="text/javascript" src="https://apis.google.com/js/platform.js"></script>
<g:hangout render="createhangout"></g:hangout><script>(function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script><div class="g-plusone" data-annotation="inline" data-width="300"></div><div class="g-plus" data-action="share"></div>
<script type="text/javascript">gapi.plus.go();</script><!-- Place this tag in your head or just before your close body tag. -->


<!-- Place this tag where you want the widget to render. -->

</div>
</nav>
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
  <ul id="MenuBar1" class="MenuBarHorizontal">
   <li><a class="MenuBarItemSubmenu" href="#">Games</a>
      <ul>
        <li><a href="Play.html">Age 1</a></li>
        <li><a href="AgeII.htm">Age 2</a></li>
        <li><a href="AgeIII.htm">Age III</a></li>
        <li><a href="AgeIV.htm">Age IV</a>
        <ul>
        <li><a href="AgeIVPartII.htm">Age IV, Part II</a></li>
        <li><a href="AgeIVPart III.html">Age IV, Part III</a></li>
        </ul>
        </li>
      </ul>
    </li>
    <li><a href="#">Items</a></li>
    <li><a class="MenuBarItemSubmenu" href="#">The ArcherCraft Media Library</a> 
     <ul>        <li><a class="MenuBarItemSubmenu" href="#">PDFs</a>
         <ul>
            <li><a href="_static/media/pdf/chapter16.pdf">Chapter 16</a></li>
            <li><a href="#">Item 3.1.2</a></li>
          </ul>
        </li>
        <li><a href="#">Item 3.2</a></li>
        <li><a href="#">Item 3.3</a></li>
      </ul>
    </li>
    <li><a class="MenuBarItemSubmenu" href="#">Plugins</a>
    <ul>
    <li><a href="http://get.adobe.com/flashplayer/?fpchrome">Flash Player</a></li>
    <li><a href="http://get.adobe.com/reader/">Adobe Reader</a></li>
  </ul>
  </li>
<li><a class="MenuBarItemSubmenu" href="#">Services</a>
<ul><li><a href="mail.php">Mail</a></li></ul></li>
<li><a href="index.html">Mobile</a></li>
  </ul>
</nav>

<p>&nbsp; </p>
<div id="TabbedPanels1" class="TabbedPanels">
  <ul class="TabbedPanelsTabGroup">
    <li class="TabbedPanelsTab" tabindex="0">Main</li>
    <li class="TabbedPanelsTab" tabindex="1">Forum</li>
  </ul>
  <div class="TabbedPanelsContentGroup">
    <div class="TabbedPanelsContent">
    <table  border="1">
    <thead>
   <tr> <th class="Actionheader"></th></tr>
     <caption>
        Actions
     </caption>
      </thead>
     <tbody>
      <tr>
      <td width="555"><span class="ActionCell">
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
        </span></td>
        <td width="204" class="ActionCell" ><object id="FlashID2" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="550" height="400">
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
        <td width="9" class="ActionCell"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="550" height="400" id="FlashID3" title="Kaleidoscope">
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
        <td width="9" class="ActionCell"><object id="FlashID4" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="550" height="400">
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
        <td width="9" class="ActionCell">&nbsp;</td>
        <td width="9" class="ActionCell">&nbsp;</td>
        <td width="9" class="ActionCell">&nbsp;</td>
        <td width="9" class="ActionCell">&nbsp;</td>
       <td width="9" class="ActionCell">&nbsp;</td>
        <td width="16" class="ActionCell">&nbsp;</td>
      </tr>
      <tr>
        <td height="434" class="ActionCell">&nbsp;</td>
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
        <td class="ActionCell"><a href="//www.pinterest.com/pin/create/button/?url=http%3A%2F%2Farchercraft.blogspot.com&media=archercraft.blogspot.com%2Ffavicon.ico&description=The%20REAL%20Game%20of%20Thrones" data-pin-do="buttonPin" data-pin-config="above"><img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_gray_20.png" /></a>
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
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
        <td class="ActionCell">&nbsp;</td>
      </tr>
      </tbody>
    </table>
  </div>
  <div class="TabbedPanelsContent"><iframe id="forum_embed"
 src="https://groups.google.com/forum/embed/?place=forum/archercraft-2-online&showpopout=true&theme=default"
 scrolling="no"
 frameborder="0"
 width="900"
 height="700"></iframe></div>
<footer id="footer"></footer>
<script type="text/javascript">
var MenuBar1 = new Spry.Widget.MenuBar("MenuBar1", {imgDown:"SpryAssets/SpryMenuBarDownHover.gif", imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
var CollapsiblePanel1 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel1");
swfobject.registerObject("FlashID");
</script>
<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/platform.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>
   <!-- Place this asynchronous JavaScript just before your </body> tag -->
    <script type="text/javascript">
swfobject.registerObject("FlashID2");
swfobject.registerObject("FlashID3");
swfobject.registerObject("FlashID4");
    </script>
    <script type="text/javascript" src="https://apis.google.com/js/platform.js">
  {parsetags: 'explicit'}
</script>
<script type="text/javascript">
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
    </script>
</body>
</html>