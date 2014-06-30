<?php
/**
 * Helper functions for custom branding & capabilities
 *
 * @package    BuddyPress Toolbar
 * @subpackage Branding
 * @author     David Decker - DECKERWEB
 * @copyright  Copyright (c) 2012-2013, David Decker - DECKERWEB
 * @license    http://www.opensource.org/licenses/gpl-license.php GPL-2.0+
 * @link       http://genesisthemes.de/en/wp-plugins/buddypress-toolbar/
 * @link       http://deckerweb.de/twitter
 *
 * @since      1.2.0
 */

/**
 * Prevent direct access to this file.
 *
 * @since 1.6.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Sorry, you are not allowed to access this file directly.' );
}

/**
 * Helper functions for returning a few popular roles/capabilities.
 *
 * @since  1.2.0
 *
 * @return role/capability
 */
	/**
	 * Helper function for returning 'editor' role/capability.
	 *
	 * @since  1.2.0
	 *
	 * @return 'editor' role
	 */
	function __bptb_role_editor() {

		return 'editor';
	}

	/**
	 * Helper function for returning 'edit_theme_options' capability.
	 *
	 * @since  1.2.0
	 *
	 * @return 'edit_theme_options' capability
	 */
	function __bptb_cap_edit_theme_options() {

		return 'edit_theme_options';
	}

	/**
	 * Helper function for returning 'manage_options' capability.
	 *
	 * @since  1.2.0
	 *
	 * @return 'manage_options' capability
	 */
	function __bptb_cap_manage_options() {

		return 'manage_options';
	}

	/**
	 * Helper function for returning 'install_plugins' capability.
	 *
	 * @since  1.2.0
	 *
	 * @return 'install_plugins' capability
	 */
	function __bptb_cap_install_plugins() {

		return 'install_plugins';
	}

/** End of role/capability helper functions */


/**
 * Helper functions for returning colored icons.
 *
 * @since  1.2.0
 *
 * @return colored icon image
 */
	/**
	 * Helper function for returning the blue icon.
	 *
	 * @since  1.2.0
	 *
	 * @return blue icon
	 */
	function __bptb_blue_icon() {

		return plugins_url( 'images/icon-buddypress-blue.png', dirname( __FILE__ ) );
	}

	/**
	 * Helper function for returning the brown icon.
	 *
	 * @since  1.2.0
	 *
	 * @return brown icon
	 */
	function __bptb_brown_icon() {

		return plugins_url( 'images/icon-buddypress-brown.png', dirname( __FILE__ ) );
	}

	/**
	 * Helper function for returning the gray icon.
	 *
	 * @since  1.2.0
	 *
	 * @return gray icon
	 */
	function __bptb_gray_icon() {

		return plugins_url( 'images/icon-buddypress-gray.png', dirname( __FILE__ ) );
	}

	/**
	 * Helper function for returning the green icon.
	 *
	 * @since  1.2.0
	 *
	 * @return green icon
	 */
	function __bptb_green_icon() {

		return plugins_url( 'images/icon-buddypress-green.png', dirname( __FILE__ ) );
	}

	/**
	 * Helper function for returning the khaki icon.
	 *
	 * @since  1.2.0
	 *
	 * @return khaki icon
	 */
	function __bptb_khaki_icon() {

		return plugins_url( 'images/icon-buddypress-khaki.png', dirname( __FILE__ ) );
	}

	/**
	 * Helper function for returning the orange icon.
	 *
	 * @since  1.2.0
	 *
	 * @return orange icon
	 */
	function __bptb_orange_icon() {

		return plugins_url( 'images/icon-buddypress-orange.png', dirname( __FILE__ ) );
	}

	/**
	 * Helper function for returning the pink icon.
	 *
	 * @since  1.2.0
	 *
	 * @return pink icon
	 */
	function __bptb_pink_icon() {

		return plugins_url( 'images/icon-buddypress-pink.png', dirname( __FILE__ ) );
	}

	/**
	 * Helper function for returning the red icon.
	 *
	 * @since  1.2.0
	 *
	 * @return red icon
	 */
	function __bptb_red_icon() {

		return plugins_url( 'images/icon-buddypress-red.png', dirname( __FILE__ ) );
	}

	/**
	 * Helper function for returning the turquoise icon.
	 *
	 * @since  1.2.0
	 *
	 * @return turquoise icon
	 */
	function __bptb_turquoise_icon() {

		return plugins_url( 'images/icon-buddypress-turquoise.png', dirname( __FILE__ ) );
	}

	/**
	 * Helper function for returning the yellow icon.
	 *
	 * @since  1.2.0
	 *
	 * @return yellow icon
	 */
	function __bptb_yellow_icon() {

		return plugins_url( 'images/icon-buddypress-yellow.png', dirname( __FILE__ ) );
	}

	/**
	 * Helper function for returning the yellow2 icon.
	 *
	 * @since  1.2.0
	 *
	 * @return yellowtwo icon
	 */
	function __bptb_yellowtwo_icon() {

		return plugins_url( 'images/icon-buddypress-yellow2.png', dirname( __FILE__ ) );
	}

	/**
	 * Helper function for returning the bp default icon.
	 *
	 * @since  1.2.0
	 *
	 * @return bp default icon
	 */
	function __bptb_default_icon() {

		return plugins_url( 'images/icon-buddypress.png', dirname( __FILE__ ) );
	}

	/**
	 * Helper function for returning a custom icon (icon-bptb.png) from stylesheet/theme "images" folder.
	 *
	 * @since  1.2.0
	 *
	 * @return bptb custom icon
	 */
	function __bptb_theme_images_icon() {

		return get_stylesheet_directory_uri() . '/images/icon-bptb.png';
	}

/** End of icon helper functions */


/**
 * Helper functions for returning icon class.
 *
 * @since  1.2.0
 *
 * @return icon class
 */
	/**
	 * Helper function for returning no icon class.
	 *
	 * @since 1.2.0
	 *
	 * @return int 0
	 */
	function __bptb_no_icon_display() {

		return NULL;
	}

/** End of icon class helper functions */


/**
 * Misc. helper functions
 *
 * @since 1.5.0
 */
	add_action( 'wp_before_admin_bar_render', 'ddw_bptb_remove_bpmedia_toolbar', 5 );
	/**
	 * Disable original toolbar items of "BuddyPress Media".
	 *
	 * @since 1.5.0
	 */
	function ddw_bptb_remove_bpmedia_toolbar() {

		if ( BPTB_REMOVE_BPMEDIA_TOOLBAR ) {

			remove_action( 'wp_before_admin_bar_render', 'bp_media_adminbar_settings_menu' );

		}  // end-if constant check

	}  // end of function

/** End of misc. helper functions */