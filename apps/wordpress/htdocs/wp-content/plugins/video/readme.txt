=== Plugin Name ===
Contributors: automattic, niallkennedy, josephscott, hailin, pento
Plugin Name: VideoPress
Tags: video, videopress, wpvideo, WordPress.com
Text Domain: video
Requires at least: 3.0
Tested up to: 3.5
Stable tag: 1.5.6

Manage and embed videos hosted on VideoPress. Requires a WordPress.com blog with the VideoPress premium upgrade.

== Description ==

Once you've downloaded the plugin, head over to [VideoPress.com](http://videopress.com/?ref=plugpage "VideoPress WordPress.com upgrade") to sign up for an account.

Add VideoPress videos stored on WordPress.com to your self-hosted WordPress.org site through the use of simple shortcodes. Edit video metadata directly from your blog's administrative interface.

The VideoPress plugin allows blog administrators to upload new videos to their WordPress.com video account and manage existing videos from the convenience of their self-hosted WordPress.org blog's administrative interface. Authors can add any VideoPress video into a blog post through the use of a simple shortcode and customizations.

This plugin allows you to click a button to login to your WordPress.com video blog, retrieve a video's `[videopress xyz]` shortcode, and insert it into your post. 
You can specify the width and height of the video container with w= h= shortcode attributes. You can restrict playback to only Freedom-loving formats using a shortcode parameter of `freedom=true`. You can autoplay a video by setting a shortcode parameter of `autoplay=true`. You can force a Flash video embed by setting a shortcode parameter of `flashonly=true`. The following are all valid shortcodes:

* `[videopress xyz]` uses your default theme content_width if present, otherwise uses 400px as width.
* `[videopress xyz w=640]` specifies the width of video container to be 640px while preserving the aspect ratio.
* `[videopress xyz w=640 h=360]` specifies both width and height of the video container.
* `[videopress xyz h=300]` specifies the height of the video container to be 300px, while preserving the aspect ratio.
* `[videopress xyz freedom=true]` display videos in only Freedom-loving formats (currently Ogg with Theora video and Vorbis audio).

You will need an account on WordPress.com with the VideoPress upgrade in order to use this plugin. If you don't have already have a WordPress.com account signing up is easy and only takes a few minutes. Visit the "Upgrades" section of wp-admin on your new WordPress.com blog and purchase a [VideoPress upgrade](http://videopress.com/?ref=plugpage "VideoPress WordPress.com upgrade").

Video preview elements and HTML5 video are directly inserted into your WordPress post or page, enabling custom styling through CSS or inclusion in your site statistics.

== Installation ==

Installing should be very easy and take fewer than five minutes.

1. Upload `video.zip` into your blog's plugins directory (typically `/wp-content/plugins/`)
2. Unzip the `video.zip` file
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Manage and embed videos on the post edit screen by clicking on the new media icon (looks like a camcorder).
5. Edit blog-level settings such as the Freedom video option through your site's media settings.

== Frequently Asked Questions ==

= Do I need a WordPress.com account to use this plugin? =

Yes.  This plugin relies on a WordPress.com site with the VideoPress premium upgrade to host uploaded videos, transcoded video files, and related information.

= Can I use this plugin to host videos on my own server? =

No.  This plugin requires a WordPress.com blog with the VideoPress upgrade and has no support for hosting videos on your own server.

= My site visitors report playback issues when I set the Freedom playback preference. =

Freedom has its price.

= How can I alter Flash runtime parameters? =

Hook into the `video_flash_params` filter to alter the Flash parameters defined in your page markup. Note: changing `allownetworking` or `allowscriptaccess` values may break your video playback experience. Changing window mode may negatively impact your viewers' playback performance.

= The embedded Flash file hovers above all other page elements regardless of z-index =

Modern versions of the Adobe Flash player separate content into layers handled by specific components of a computer, tablet, or smartphone. This optimization by task allows your viewers to experience smooth video playback using fewer system resources (and battery) than explicit layer controls. See [Abode's Flash embed wmode support by browser](http://kb2.adobe.com/cps/127/tn_12701.html#main_Browser_support_for_Window_Mode__wmode__values) for more information.

= My visitors are unable to view some videos when Flash is not installed (such as iPhone) =

Some video publishers restrict video embedding and sharing features. HTML5 video includes a full URI to a video file in the page markup, a feature that causes some concerns among publishers attempting to limit playback locations and video distribution. Restricted embeds are included on the page using Flash markup.

== Screenshots ==

1. Edit video metadata and a video to your self-hosted WordPress.org blog from your blog's administrative interface.
2. Embed VideoPress videos in blog posts with a shortcode.
3. Video preview display with video preview image, video title, and play button.
4. VideoPress Flash player includes a sharing menu, HD toggle, and full-screen support.
5. Native HTML5 <video> playback demonstrated on iOS 5. Includes AirPlay support for sending videos to local compatible screens.

== Changelog ==

= 1.5.6 =
* Fix IE9 and IE8 compatibility for the "Insert into Post" admin interface
* Updated add media button style

= 1.5.5 =
* Allow sites to default to HD playback
* Fixed a bug where inserting a video from Thickbox results in a white screen

= 1.5.4 =
* Minor translation text bug
* The HTML5 player now works outside of the Loop
* Fix autoplay playback in IE8
* Fixed some videos taking a long time to load

= 1.5.3 =
* Added support for playing un-shared videos on iOS and other Flash-free devices. This can be disable if stronger protection from downloading is required.
* Minor text changes
* Updated wp-admin media upload icon

= 1.5.2 =
* Fixed compatibility with the latest version of Jetpack
* Fixed a minor HTML validation issue
* Fixed a JavaScript error in WordPress 3.0

= 1.5.1 =
* Changed links to the new VideoPress.com
* Added post activation help message

= 1.5 =
* Added a lightweight video preview image and interactive elements. A video is only loaded onto the page after direct user action.
* Supports HTML 5 <video> playback as a fallback when Flash is not available or does not meet minimum version requirements.
* Improved caching for multisite installs
* Added backwards compatibility for WordPress 3.0
* Added backwards compatibility for PHP 4.3

= 1.3 =
* Requires WordPress 3.2
* New object-oriented references improve caching and namespaces
* Reduced memory footprint
* Supports SSL assets on page (fewer mixed content warnings)
* Improved error messages
* Improved support for pages and custom post types
* Supports GPU-accelerated Flash playback

= 1.2.2 =
* Improve compatibility with WordPress multi-site installations running WordPress 3.1.

= 1.2.1 =
* Properly escape maxwidth parameter appended to query string
* Cache video response to reduce latency and HTTP roundtrip
* Improved HTTP error handling
* Do not include SWFObject JavaScript for blogs with blog-level freedom settings

= 1.2 =
* New JSON-based data feed for faster load times and server-side intelligence
* Updated minimum Flash version to 10.0 to match the new VideoPress player
* Supports HTML5 Ogg video playback
* Supports autoplay in Flash and HTML5 formats
* Flash parameters may be overridden through the `video_flash_params` filter.

= 1.1.2 =
* Improves support for private videos restricted by playback domain
* Includes SWFObject JavaScript handlers for Flash required version detection and DOM manipulation across browsers.

= 1.1.1 =
* Fixes render issue in some versions of Internet Explorer

= 1.1 =
* XML-based parsing
* Display error message to blog users with edit_posts capability when an embed fails for a known reason such as restricted embed domains.
* Double-baked objects for standards compatibility with all blogs.

= 1.0 = 
* up the ver number to update plugin dir

= 0.2.1 = 
* add site flashvar

= 0.2.0 =
* Fixed various width and height issues, and handles default width height correctly

= 0.1.1 =
* Fix the problem with users getting redirected outside of the iframe

= 0.1.0 =
* Initial release

== Upgrade Notice ==

= 1.5 =
HTML5 <video> support, lightweight preview, improved compatibility.

= 1.3 =
Improved reliability and Flash playback performance.

= 1.2.2 =
Improve compatibility with WordPress 3.1 under multi-site.

= 1.2.1 =
Bug fixes and improved performance. Caching, improved error handling, and proper support of width preferences.

= 1.2 =
Upgrades minimum Flash version to 10.0. New JSON-based feed for faster load times. Supports HTML5 Ogg video playback. Supports autoplay in Flash and HTML formats.

= 1.1.1 =
Fixes render issues on some versions of Internet Explorer.

= 1.1 =
Improved embed codes for browsers and feed using standards-compliant markup. Cached VideoPress XML data is now stored with your WordPress install. Displays warning message for failed video embeds to blog users capable of editing posts.
