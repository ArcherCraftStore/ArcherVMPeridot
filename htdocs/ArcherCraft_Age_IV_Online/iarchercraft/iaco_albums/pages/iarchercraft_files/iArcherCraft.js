// Created by iWeb 3.0.4 local-build-20140130

function createMediaStream_id4()
{return IWCreatePhotocast("file://localhost/Users/Student/Documents/ArcherCraft_Age_IV_Online/iArcherCraft/iACO_Albums/Pages/iArcherCraft_files/rss.xml",true);}
function initializeMediaStream_id4()
{createMediaStream_id4().load('file://localhost/Users/Student/Documents/ArcherCraft_Age_IV_Online/iArcherCraft/iACO_Albums/Pages',function(imageStream)
{var entryCount=imageStream.length;var headerView=widgets['widget1'];headerView.setPreferenceForKey(imageStream.length,'entryCount');NotificationCenter.postNotification(new IWNotification('SetPage','id4',{pageIndex:0}));});}
function layoutMediaGrid_id4(range)
{createMediaStream_id4().load('file://localhost/Users/Student/Documents/ArcherCraft_Age_IV_Online/iArcherCraft/iACO_Albums/Pages',function(imageStream)
{if(range==null)
{range=new IWRange(0,imageStream.length);}
IWLayoutPhotoGrid('id4',new IWPhotoGridLayout(3,new IWSize(182,182),new IWSize(182,35),new IWSize(218,232),27,27,0,new IWSize(18,18)),new IWPhotoFrame([IWCreateImage('iArcherCraft_files/Hardcover_bevel_01.png'),IWCreateImage('iArcherCraft_files/Hardcover_bevel_02.png'),IWCreateImage('iArcherCraft_files/Hardcover_bevel_03.png'),IWCreateImage('iArcherCraft_files/Hardcover_bevel_06.png'),IWCreateImage('iArcherCraft_files/Hardcover_bevel_09.png'),IWCreateImage('iArcherCraft_files/Hardcover_bevel_08.png'),IWCreateImage('iArcherCraft_files/Hardcover_bevel_07.png'),IWCreateImage('iArcherCraft_files/Hardcover_bevel_04.png')],null,0,0.500000,0.000000,0.000000,0.000000,0.000000,17.000000,17.000000,17.000000,17.000000,403.000000,295.000000,403.000000,295.000000,null,null,null,0.100000),imageStream,range,null,null,1.000000,{backgroundColor:'rgb(0, 0, 0)',reflectionHeight:100,reflectionOffset:2,captionHeight:100,fullScreen:0,transitionIndex:2},'../../Media/slideshow.html','widget1','widget2','widget3')});}
function relayoutMediaGrid_id4(notification)
{var userInfo=notification.userInfo();var range=userInfo['range'];layoutMediaGrid_id4(range);}
function onStubPage()
{var args=window.location.href.toQueryParams();parent.IWMediaStreamPhotoPageSetMediaStream(createMediaStream_id4(),args.id);}
if(window.stubPage)
{onStubPage();}
setTransparentGifURL('../../Media/transparent.gif');function hostedOnDM()
{return false;}
function onPageLoad()
{IWRegisterNamedImage('comment overlay','../../Media/Photo-Overlay-Comment.png')
IWRegisterNamedImage('movie overlay','../../Media/Photo-Overlay-Movie.png')
loadMozillaCSS('iArcherCraft_files/iArcherCraftMoz.css')
adjustLineHeightIfTooBig('id1');adjustFontSizeIfTooBig('id1');adjustLineHeightIfTooBig('id2');adjustFontSizeIfTooBig('id2');adjustLineHeightIfTooBig('id3');adjustFontSizeIfTooBig('id3');NotificationCenter.addObserver(null,relayoutMediaGrid_id4,'RangeChanged','id4')
Widget.onload();fixupAllIEPNGBGs();fixAllIEPNGs('../../Media/transparent.gif');fixupIECSS3Opacity('id5');initializeMediaStream_id4()
performPostEffectsFixups()}
function onPageUnload()
{Widget.onunload();}
