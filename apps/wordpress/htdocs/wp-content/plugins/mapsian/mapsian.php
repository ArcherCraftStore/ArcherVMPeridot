<?php
/*
 Plugin Name: Mapsian for WordPress
 Plugin URI: http://mapsian.com/
 Description: Mapsian for WordPress will enable you to create as many maps and locations as you want by using google maps API V3. You can organize locations by groups and add groups to each map you created, making filterable, sortable, and customizable. Also you can display maps and locations on any WordPress posts and pages by simply using shortcodes.
 Version: 1.3.4
 Author: WPKorea
 Author URI: http://www.mapsian.com/
 License: GPL2

 /* Copyright 2014 WPKorea (email : wordpresskorea@gmail.com)
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/

/**
 *
 * @package mapsian 
 * @category Core
 * @author Bryan Lee
 */
 
define(PLUGINSURL, plugins_url('', __FILE__));

include_once 'ajax/ajaxs.php';                          // AJAX ALL
include_once 'functions/mapsian-functions.php';       	// Core Functions
include_once 'includes/mapsian-about.php';              // About page
include_once 'includes/mapsian-general-settings.php';   // General settings
include_once 'core/admin/setup-meta-maps.php';			// maps meta field configuration
include_once 'core/admin/setup-meta-locations.php';		// locations meta field configuration
include_once 'core/admin/setup-style.php';				// style css & javascripts for admin
include_once 'core/admin/setup-custom-post.php';		// identify custom post
include_once 'includes/mapsian-map-view.php';       	// map view 
include_once 'includes/mapsian-location-view.php';      // location view





/* Mapsian add actions ******************************************************/

add_action('admin_menu', 'register_mapsian_menu_page');         // Register menu items
add_action('save_post', 'save_mapsian_details');                // Update post meta when posts saved
add_action("template_redirect", 'mapsian_redirect');            // Page Redirection
add_action('admin_head', 'mapsian_posttype_admin_css');         // Hide 'view post' in locations
add_action('plugins_loaded', 'mapsian_language_init');          // Add Languages

/* Functions ***********************************************************************/

function register_mapsian_menu_page(){

    add_submenu_page( 'edit.php?post_type=maps', 'mapsian', __('Groups', 'mapsian'), 'administrator','edit-tags.php?taxonomy=mapsian_group&post_type=locations', '' );
    add_submenu_page('edit.php?post_type=maps', 'mapsian', __('General settings', 'mapsian'), 'administrator', 'm_setting', 'm_setting');
    add_submenu_page('edit.php?post_type=maps', 'mapsian', __('About', 'mapsian'), 'administrator', 'm_about', 'm_about');
	
}

function mapsian_language_init() {
    load_plugin_textdomain( 'mapsian', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}



function save_mapsian_details(){

    global $post;
    $post_id = sanitize_text_field( $_POST['ID'] );

    if(!$post_id) {
        return false;
    }

    if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
	   return $post_id;
    }

    $check_post_type = get_post_type($post_id);

    if($check_post_type == "locations"){
        $data = array();
        $data[0] = sanitize_text_field($_POST['mapsian_location_description']); 	// Description
        $data[1] = sanitize_text_field($_POST['mapsian_location_linkurl']);		// link URL
        $data[2] = sanitize_text_field($_POST['full_address']);					// Full address
        $data[3] = sanitize_text_field($_POST['mapsian_lat']);					// LAT
        $data[4] = sanitize_text_field($_POST['mapsian_lng']);					// LNG
        $data[5] = sanitize_text_field($_POST['full_address'])."/".get_the_title($post_id);
        $data = serialize($data);
        update_post_meta($post_id, 'location_detail', $data);
    }

    if($check_post_type == "maps"){
        $mapsize_data = array();
        $mapsize_data[0] = sanitize_text_field($_POST['mapsize_width']);     // Map size width
        $mapsize_data[1] = sanitize_text_field($_POST['mapsize_height']);    // Map size height
        $mapsize_data[2] = sanitize_text_field($_POST['mapsian_ui']);        // UI Style
        $mapsize_data = serialize($mapsize_data);
        update_post_meta($post_id, "map_size", $mapsize_data);
    }

}

function mapsian_redirect() {
    global $wp;
    $plugindir = dirname( __FILE__ );

    if ($wp->query_vars["post_type"] == 'maps' && $_REQUEST['action']!='AJAXfunctionCall' && !($_REQUEST['action'])) {

        $templatefilename = 'single-map.php';

        if (file_exists(TEMPLATEPATH . '/' . $templatefilename)) {
            $return_template = TEMPLATEPATH . '/' . $templatefilename;

        } else {
            $return_template = $plugindir . '/templates/' . $templatefilename;
        }

        do_mapsian_redirect($return_template);
    }

}

function do_mapsian_redirect($url) {
    global $post, $wp_query;
    if (have_posts()) {
        include($url);
        die();
    } 
    else {
        $wp_query->is_404 = true;
    }
}

function mapsian_posttype_admin_css() {
    global $post_type;
    if($post_type == 'locations') {
        echo '<style type="text/css">#view-post-btn,#edit-slug-box > a,#preview-action,#post-    preview,.updated p a{display: none !important;}</style>';
    }
}


