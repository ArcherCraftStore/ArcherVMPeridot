=== Plugin Name ===

Contributors: neilgee
Donate link: http://coolestguidesontheplanet.com/
Tags: modals, pop ups, windows, bootstrap
Requires at least: 3.8
Tested up to: 3.9.1
Stable tag: 4.3
Plugin Name: Bootstrap Modals
Plugin URI: http://coolestguidesontheplanet.com/use-bootstrap-modals-wordpress-themes/
Description: Using Bootstrap Modals in WordPress
Author: Neil Gee
Version: 1.0.0
Author URI:http://coolestguidesontheplanet.com
License:           GPL-2.0+
License URI:       http://www.gnu.org/licenses/gpl-2.0.txt

This plugin adds Bootstrap v3.1.1 Modal functionality to WordPress. All you need to do is add the Modal HTML mark up code, a copy of this is in the readme.txt


== Description ==

This plugin adds Bootstrap v3.1.1 Modal functionality to WordPress.

It adds just the Bootstrap Javascript Plugin for Modals and associated CSS.  

This does not bring in any other Boostrap javascript or CSS functionality.

There is sample HTML mark up code in the readme.txt for a selector and modal target element.

== Installation ==

This section describes how to install the plugin:

1. Upload the `bootstrap-modals` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Usage ==
Use either the Bootstrap API markup or Javascript to trigger the modal windows, this can be found here: http://getbootstrap.com/javascript/#modals

There is also further usage information here: http://coolestguidesontheplanet.com/bootstrap/modal.php

There is no WP-Admin interface, mark up needs to be directly applied to post/page or widget area.

Here is a simple HTML Modal MarkUp
<code>
<!-- Button trigger modal -->
<a class="btn btn-primary btn-lg" href="#myModal1" data-toggle="modal">Launch demo modal</a>

<!-- Modal -->
<div id="myModal1" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" type="button" data-dismiss="modal">×</button>
					<h4 class="modal-title">My Title in a Modal Window</h4>
			</div>
			<div class="modal-body">This is the body of a modal...</div>
			<div class="modal-footer">This is the footer of a modal...</div>
			</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</code>

== Changelog ==

= 1.0.0 * Initial release.