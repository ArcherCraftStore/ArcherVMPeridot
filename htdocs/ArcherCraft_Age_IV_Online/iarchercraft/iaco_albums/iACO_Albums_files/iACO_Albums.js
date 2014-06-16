// Created by iWeb 3.0.4 local-build-20140130

function createMediaStream_id3()
{return IWCreateMediaCollection("file://localhost/Users/Student/Documents/ArcherCraft_Age_IV_Online/iArcherCraft/iACO_Albums/iACO_Albums_files/rss.xml",true,9,["No photos yet","%d photo","%d photos"],["","%d clip","%d clips"]);}
function initializeMediaStream_id3()
{createMediaStream_id3().load('file://localhost/Users/Student/Documents/ArcherCraft_Age_IV_Online/iArcherCraft/iACO_Albums',function(imageStream)
{var entryCount=imageStream.length;var headerView=widgets['widget4'];headerView.setPreferenceForKey(imageStream.length,'entryCount');NotificationCenter.postNotification(new IWNotification('SetPage','id3',{pageIndex:0}));});}
function layoutMediaGrid_id3(range)
{createMediaStream_id3().load('file://localhost/Users/Student/Documents/ArcherCraft_Age_IV_Online/iArcherCraft/iACO_Albums',function(imageStream)
{if(range==null)
{range=new IWRange(0,imageStream.length);}
IWLayoutPhotoGrid('id3',new IWPhotoGridLayout(2,new IWSize(303,227),new IWSize(303,32),new IWSize(336,274),27,27,0,new IWSize(86,63)),new IWPhotoFrame([IWCreateImage('iACO_Albums_files/organic_ul.png'),IWCreateImage('iACO_Albums_files/organic_top.png'),IWCreateImage('iACO_Albums_files/organic_ur.png'),IWCreateImage('iACO_Albums_files/organic_right.png'),IWCreateImage('iACO_Albums_files/organic_lr.png'),IWCreateImage('iACO_Albums_files/organic_bottom.png'),IWCreateImage('iACO_Albums_files/organic_ll.png'),IWCreateImage('iACO_Albums_files/organic_left.png')],null,0,0.700000,0.000000,18.000000,0.000000,16.000000,76.000000,57.000000,45.000000,68.000000,30.000000,80.000000,144.000000,80.000000,null,null,null,0.500000),imageStream,range,(null),null,1.000000,null,'../Media/slideshow.html','widget4',null,'widget5',{showTitle:true,showMetric:true})});}
function relayoutMediaGrid_id3(notification)
{var userInfo=notification.userInfo();var range=userInfo['range'];layoutMediaGrid_id3(range);}
function onStubPage()
{var args=window.location.href.toQueryParams();parent.IWMediaStreamPhotoPageSetMediaStream(createMediaStream_id3(),args.id);}
if(window.stubPage)
{onStubPage();}
setTransparentGifURL('../Media/transparent.gif');function hostedOnDM()
{return false;}
function onPageLoad()
{IWRegisterNamedImage('comment overlay','../Media/Photo-Overlay-Comment.png')
IWRegisterNamedImage('movie overlay','../Media/Photo-Overlay-Movie.png')
loadMozillaCSS('iACO_Albums_files/iACO_AlbumsMoz.css')
adjustLineHeightIfTooBig('id1');adjustFontSizeIfTooBig('id1');adjustLineHeightIfTooBig('id2');adjustFontSizeIfTooBig('id2');NotificationCenter.addObserver(null,relayoutMediaGrid_id3,'RangeChanged','id3')
Widget.onload();fixupAllIEPNGBGs();fixAllIEPNGs('../Media/transparent.gif');fixupIECSS3Opacity('id4');initializeMediaStream_id3()
performPostEffectsFixups()}
function onPageUnload()
{Widget.onunload();}
