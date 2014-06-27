<?php 
/**
 * Main plugin file.
 * This plugin adds useful admin links and resources for BuddyPress to the WordPress Toolbar / Admin Bar.
 *
 * @package   BuddyPress Toolbar
 * @author    David Decker
 * @copyright Copyright (c) 2012-2013, David Decker - DECKERWEB
 * @link      http://deckerweb.de/twitter
 *
 * @credits   Inspired and based on the plugin "WooThemes Admin Bar Addition" by Remkus de Vries @defries.
 * @link      http://remkusdevries.com/
 * @link      http://twitter.com/defries
 *
 * Plugin Name: BuddyPress Toolbar
 * Plugin URI: http://genesisthemes.de/en/wp-plugins/buddypress-toolbar/
 * Description: This plugin adds useful admin links and resources for BuddyPress to the WordPress Toolbar / Admin Bar.
 * Version: 1.6.0
 * Author: David Decker - DECKERWEB
 * Author URI: http://deckerweb.de/
 * License: GPL-2.0+
 * License URI: http://www.opensource.org/licenses/gpl-license.php
 * Text Domain: buddypress-toolbar
 * Domain Path: /languages/
 *
 * Copyright (c) 2012-2013 David Decker - DECKERWEB
 *
 *     This file is part of BuddyPress Toolbar,
 *     a plugin for WordPress.
 *
 *     BuddyPress Toolbar is free software:
 *     You can redistribute it and/or modify it under the terms of the
 *     GNU General Public License as published by the Free Software
 *     Foundation, either version 2 of the License, or (at your option)
 *     any later version.
 *
 *     BuddyPress Toolbar is distributed in the hope that
 *     it will be useful, but WITHOUT ANY WARRANTY; without even the
 *     implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR
 *     PURPOSE. See the GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with WordPress. If not, see <http://www.gnu.org/licenses/>.
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
 * Setting constants.
 *
 * @since 1.0.0
 */
/** Plugin directory */
define( 'BPTB_PLUGIN_DIR', dirname( __FILE__ ) );

/** Plugin base directory */
define( 'BPTB_PLUGIN_BASEDIR', dirname( plugin_basename( __FILE__ ) ) );


add_action( 'init', 'ddw_bptb_init' );
/**
 * Load the text domain for translation of the plugin.
 * Load admin helper functions - only within 'wp-admin'.
 * 
 * @since 1.0.0
 *
 * @uses  load_plugin_textdomain()
 * @uses  is_admin()
 */
function ddw_bptb_init() {

	/** Set filter for WordPress languages directory */
	$bptb_wp_lang_dir = BPTB_PLUGIN_BASEDIR . '/../../languages/buddypress-toolbar/';
	$bptb_wp_lang_dir = apply_filters( 'bptb_filter_wp_lang_dir', $bptb_wp_lang_dir );

	/** Set filter for plugin's languages directory */
	$bptb_lang_dir = BPTB_PLUGIN_BASEDIR . '/languages/';
	$bptb_lang_dir = apply_filters( 'bptb_filter_lang_dir', $bptb_lang_dir );

	/** First look in WordPress' "languages" folder = custom & update-secure! */
	load_plugin_textdomain( 'buddypress-toolbar', false, $bptb_wp_lang_dir );

	/** Then look in plugin's "languages" folder = default */
	load_plugin_textdomain( 'buddypress-toolbar', false, $bptb_lang_dir );

	/** Include admin helper functions */
	if ( is_admin() ) {
		require_once( BPTB_PLUGIN_DIR . '/includes/bptb-admin.php' );
	}

	/** Define constants and set defaults for removing all or certain sections */
	if ( ! defined( 'BPTB_DISPLAY' ) ) {
		define( 'BPTB_DISPLAY', TRUE );
	}

	if ( ! defined( 'BPTB_EXTENSIONS_DISPLAY' ) ) {
		define( 'BPTB_EXTENSIONS_DISPLAY', TRUE );
	}

	if ( ! defined( 'BPTB_MANAGE_GROUPS_DISPLAY' ) ) {
		define( 'BPTB_MANAGE_GROUPS_DISPLAY', TRUE );
	}

	if ( ! defined( 'BPTB_THEME_DISPLAY' ) ) {
		define( 'BPTB_THEME_DISPLAY', TRUE );
	}

	if ( ! defined( 'BPTB_RESOURCES_DISPLAY' ) ) {
		define( 'BPTB_RESOURCES_DISPLAY', TRUE );
	}

	if ( ! defined( 'BPTB_DE_DISPLAY' ) ) {
		define( 'BPTB_DE_DISPLAY', TRUE );
	}

	if ( ! defined( 'BPTB_TRANSLATIONS_DISPLAY' ) ) {
		define( 'BPTB_TRANSLATIONS_DISPLAY', TRUE );
	}

	if ( ! defined( 'BPTB_REMOVE_BPMEDIA_TOOLBAR' ) ) {
		define( 'BPTB_REMOVE_BPMEDIA_TOOLBAR', FALSE );
	}

}  // end of function ddw_bptb_init


add_action( 'admin_bar_menu', 'ddw_bptb_admin_bar_menu', 98 );
/**
 * Add new menu items to the WordPress Toolbar / Admin Bar.
 * 
 * @since 1.0.0
 *
 * @global mixed $wp_admin_bar, $locale, $bptb_is_bpmg
 */
function ddw_bptb_admin_bar_menu() {

	global $wp_admin_bar, $locale, $bptb_is_bpmg;

	/**
	 * Allows for filtering the general user role/capability to display main & sub-level items
	 *
	 * Default role: 'administrator'
	 *
	 * @since 1.2.0
	 */
	$bptb_filter_capability = apply_filters( 'bptb_filter_capability_all', 'administrator' );

	/**
	 * Required BuddyPress/ WordPress cabability to display new admin bar entry
	 * Only showing items if toolbar / admin bar is activated and user is logged in!
	 *
	 * @since 1.0.0
	 */
	if ( ! is_user_logged_in()
		|| ! is_admin_bar_showing()
		|| ! current_user_can( $bptb_filter_capability )	// allows for custom filtering the required role/capability
		|| ! BPTB_DISPLAY	// allows for custom disabling
	) {
		return;
	}


	/** Set unique prefix */
	$prefix = 'ddw-buddypress-';
	
	/** Create parent menu item references */
	$bpbar = $prefix . 'admin-bar';					// root level
		$bpsupport = $prefix . 'bpsupport';				// sub level: bp support
		$bpcodex = $prefix . 'bpcodex';					// sub level: bp codex
		$bpsites = $prefix . 'bpsites';					// sub level: bp sites
		$bpactivity = $prefix . 'bpactivity';				// sub level: bp activity
		$bpsettings = $prefix . 'bpsettings';				// sub level: bp settings
			$spages = $prefix . 'spages';					// third level: component's front pages
			$s_bpmedia = $prefix . 's_bpmedia';				// third level: bp media plugin
		$bppfields = $prefix . 'bppfields';				// sub level: bp profile fields
			$bppfieldsbpuatl = $prefix . 'bppfieldsbpuatl';			// third level plugin extension: bp u.acc. type lite
		$users = $prefix . 'users';					// sub level: users
			$usersinviteanyone = $prefix . 'usersinviteanyone';		// third level: invite anyone
			$usersbpprofilesstats = $prefix . 'usersbpprofilesstats';	// third level plugin extension: bp profiles stats
		$bpmggroup = $prefix . 'bpmggroup';				// sub level: "manage groups" group ("hook" place)
			$managegroups = $prefix . 'managegroups';		// sub level: manage groups
				$bpmgbpgm = $prefix . 'bpmgbpgm';			// third level mg: bp group management
				$bpmgbpge = $prefix . 'bpmgbpge';			// third level mg: bp groups extras
				$bpmgcollabpress = $prefix . 'bpmgcollabpress';		// third level mg: collabpress
		$extensions = $prefix . 'extensions';				// sub level: extensions
			$extwangguard = $prefix . 'extwangguard';			// third level plugin extension: wangguard
			$extbpgm = $prefix . 'extbpgm';					// third level plugin extension: bp group management
			$extbpge = $prefix . 'extbpge';					// third level plugin extension: bp groups extras
			$extbpdocs = $prefix . 'extbpdocs';				// third level plugin extension: bp docs
			$extbpuatl = $prefix . 'extbpuatl';				// third level plugin extension: bp u.acc. type lite
			$extbpprofilesstats = $prefix . 'extbpprofilesstats';		// third level plugin extension: bp profiles stats
			$extbuddystream = $prefix . 'extbuddystream';			// third level plugin extension: buddystream
			$extinviteanyone = $prefix . 'extinviteanyone';			// third level plugin extension: invite anyone
			$extcollabpress = $prefix . 'extcollabpress';			// third level plugin extension: collabpress
			$exteventsmanager = $prefix . 'exteventsmanager';		// third level plugin extension: events manager
			$extseopress = $prefix . 'extseopress';				// third level plugin extension: seopress
			$extbpprofilesearch = $prefix . 'extbpprofilesearch';		// third level plugin extension: bp profile search
			$extbpcheckins = $prefix . 'extbpcheckins';			// third level plugin extension: bp checkins
				$extbpcheckinsplaces = $prefix . 'extbpcheckinsplaces';		// fourth level: bp checkins places
				$extbpcheckinsfoursquare = $prefix . 'extbpcheckinsfoursquare';	// fourth level: bp checkins foursquare
			$extbpmedia = $prefix . 'extbpmedia';				// third level plugin extension: bp media
			$extachievementsapp = $prefix . 'extachievementsapp';	// third level plugin extension: achievements app
			$extbuddybox = $prefix . 'extbuddybox';				// third level plugin extension: buddybox
		$extgroup = $prefix . 'extgroup';				// sub level: extend group ("hook" place)
			$pgroup = $prefix . 'pgroup';				// sub level: plugin group ("hook" place)
			$tgroup = $prefix . 'tgroup';				// sub level: theme group ("hook" place)
				$bservicessettings = $prefix . 'bservicessettings';	// third level theme: business services
				$threeonesevensettings = $prefix . 'threeonesevensettings';	// third level theme: 3oneseven themes
		$bpgroup = $prefix . 'bpgroup';					// sub level: bp group (resources)
			$translations = $prefix . 'translations';				// third level: translations download


	/** Make the "BuddyPress" name filterable within menu items */
	$bptb_buddypress_name = apply_filters( 'bptb_filter_buddypress_name', __( 'BuddyPress', 'buddypress-toolbar' ) );

	/** Make the "BuddyPress" name's tooltip filterable within menu items */
	$bptb_buddypress_name_tooltip = apply_filters( 'bptb_filter_buddypress_name_tooltip', _x( 'BuddyPress', 'Translators: For the tooltip', 'buddypress-toolbar' ) );


	/** For the Codex search */
	$bptb_search_codex = __( 'Search Codex', 'buddypress-toolbar' );
	$bptb_go_button = '<input type="submit" value="' . __( 'GO', 'buddypress-toolbar' ) . '" class="bptb-search-go"  /></form>';


	/** Display these items also when BuddyPress plugin is not installed */
	if ( BPTB_RESOURCES_DISPLAY ) {

		/** Include plugin file with resources links */
		require_once( BPTB_PLUGIN_DIR . '/includes/bptb-resources.php' );

	}  // end-if constant check for displaying resources


	/** Display language specific links only for these locales: de_DE, de_AT, de_CH, de_LU */
	if ( BPTB_DE_DISPLAY && ( get_locale() == 'de_DE' || get_locale() == 'de_AT' || get_locale() == 'de_CH' || get_locale() == 'de_LU' ) ) {

		/** German BP forum */
		$bpgroup_menu_items['bp-forum-de'] = array(
			'parent' => $bpgroup,
			'title'  => __( 'German Support Forum', 'buddypress-toolbar' ),
			'href'   => 'http://forum.wpde.org/buddypress/',
			'meta'   => array( 'title' => __( 'German Support Forum', 'buddypress-toolbar' ) )
		);

		/** German BP language packs */
		$bpgroup_menu_items['bp-languages-de'] = array(
			'parent' => $bpgroup,
			'title'  => __( 'German language files', 'buddypress-toolbar' ),
			'href'   => 'http://deckerweb.de/sprachdateien/buddypress/',
			'meta'   => array( 'title' => __( 'German language files for BuddyPress and more BP extensions', 'buddypress-toolbar' ) )
		);

	}  // end-if German locales


	/** Translate BuddyPress section - only display for non-English locales */
	if ( BPTB_TRANSLATIONS_DISPLAY && ( empty( $locale ) || !( get_locale() == 'en_US' || get_locale() == 'en_GB' || get_locale() == 'en_NZ' || get_locale() == 'en' ) ) ) {

		/** Translations Forum */
		$bpgroup_menu_items['translations'] = array(
			'parent' => $bpgroup,
			'title'  => __( 'Translations Forum', 'buddypress-toolbar' ),
			'href'   => 'http://buddypress.org/support/forum/localization/',
			'meta'   => array( 'title' => _x( 'Translations Forum', 'Translators: For the tooltip', 'buddypress-toolbar' ) )
		);

		/** Language Packs Download */
		if ( !( get_locale() == 'de_DE' || get_locale() == 'de_AT' || get_locale() == 'de_CH' || get_locale() == 'de_LU' ) ) {

			$bpgroup_menu_items['translations-download'] = array(
				'parent' => $translations,
				'title'  => __( 'Language Packs Download', 'buddypress-toolbar' ),
				'href'   => 'http://codex.buddypress.org/user/translations/',
				'meta'   => array( 'title' => _x( 'Language Packs Download', 'Translators: For the tooltip', 'buddypress-toolbar' ) )
			);

		}  // end-if locale check

	}  // end-if translate bp


	/** Check for active BP version to define settings links */
	if ( class_exists( 'BuddyPress' ) ) {

		/** Main settings link */
		$bp_aurl_main = is_multisite() ? network_admin_url( 'settings.php?page=bp-components' ) : admin_url( 'options-general.php?page=bp-components' );
		/** Profile fields */
		$bp_aurl_pfields = network_admin_url( 'users.php?page=bp-profile-setup' );

	} else {

		/** Main settings link */
		$bp_aurl_main = network_admin_url( 'admin.php?page=bp-general-settings' );
		/** Profile fields */
		$bp_aurl_pfields = network_admin_url( 'admin.php?page=bp-profile-setup' );

	}  // end-if BP check


	/** Show these items only if BuddyPress plugin is actually installed */
	if ( ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'buddypress/bp-loader.php' ) ) || class_exists( 'BP_Core' ) ) {

		/** Check for BP v1.6+ - if true, display "Moderate Activity" link */
		if ( class_exists( 'BuddyPress' ) && ( bp_current_user_can( 'bp_moderate' ) && current_user_can( 'manage_options' ) ) ) {

			$menu_items['bpactivity'] = array(
				'parent' => $bpbar,
				'title'  => __( 'Moderate Activity', 'buddypress-toolbar' ),
				'href'   => network_admin_url( 'admin.php?page=bp-activity' ),
				'meta'   => array( 'target' => '', 'title' => __( 'Moderate Activity', 'buddypress-toolbar' ) )

			);

		}  // end-if BP moderate activity check

		/** BP Settings section */
		if ( current_user_can( 'manage_options' ) ) {

			$menu_items['bpsettings'] = array(
				'parent' => $bpbar,
				'title'  => esc_attr__( $bptb_buddypress_name ) . ' ' . __( 'Settings', 'buddypress-toolbar' ),
				'href'   => $bp_aurl_main,
				'meta'   => array( 'target' => '', 'title' => esc_attr__( $bptb_buddypress_name_tooltip ) . ' ' . __( 'Settings', 'buddypress-toolbar' ) )
			);

			$menu_items['spages'] = array(
				'parent' => $bpsettings,
				'title'  => __( 'Pages for Components', 'buddypress-toolbar' ),
				'href'   => network_admin_url( 'admin.php?page=bp-page-settings' ),
				'meta'   => array( 'target' => '', 'title' => __( 'Pages for Components', 'buddypress-toolbar' ) )
			);

			/** Frontend component root pages */
			/** Activity */
			if ( function_exists( 'bp_is_active' ) && bp_is_active( 'activity' ) && function_exists( 'bp_get_activity_root_slug' ) ) {

				$menu_items['spages-activity'] = array(
					'parent' => $spages,
					'title'  => __( 'Activity Root Page', 'buddypress-toolbar' ),
					'href'   => get_home_url() . '/' . bp_get_activity_root_slug(),
					'meta'   => array( 'target' => '', 'title' => _x( 'Frontend: Activity Root Page', 'Translators: For the tooltip', 'buddypress-toolbar' ) )
				);

			} // end-if activity check

			/** Blogs/Sites */
			if ( is_multisite() && ( function_exists( 'bp_is_active' ) && bp_is_active( 'blogs' ) && function_exists( 'bp_get_blogs_root_slug' ) ) ) {

				$menu_items['spages-blogs'] = array(
					'parent' => $spages,
					'title'  => __( 'Blogs Root Page', 'buddypress-toolbar' ),
					'href'   => get_home_url() . '/' . bp_get_blogs_root_slug(),
					'meta'   => array( 'target' => '', 'title' => _x( 'Frontend: Blogs Root Page', 'Translators: For the tooltip', 'buddypress-toolbar' ) )
				);

			} // end-if blogs/sites check

			/** Groups */
			if ( function_exists( 'bp_is_active' ) && bp_is_active( 'groups' ) && function_exists( 'bp_get_groups_root_slug' ) ) {

				$menu_items['spages-groups'] = array(
					'parent' => $spages,
					'title'  => __( 'Groups Root Page', 'buddypress-toolbar' ),
					'href'   => get_home_url() . '/' . bp_get_groups_root_slug(),
					'meta'   => array( 'target' => '', 'title' => _x( 'Frontend: Groups Root Page', 'Translators: For the tooltip', 'buddypress-toolbar' ) )
				);

			} // end-if groups check

			/** Members */
			if ( function_exists( 'bp_is_active' ) && bp_is_active( 'members' ) && function_exists( 'bp_get_members_root_slug' ) ) {

				$menu_items['spages-members'] = array(
					'parent' => $spages,
					'title'  => __( 'Members Root Page', 'buddypress-toolbar' ),
					'href'   => get_home_url() . '/' . bp_get_members_root_slug(),
					'meta'   => array( 'target' => '', 'title' => _x( 'Frontend: Members Root Page', 'Translators: For the tooltip', 'buddypress-toolbar' ) )
				);

			} // end-if members check

			/** BP Settings continued... */
			$menu_items['s-moresettings'] = array(
				'parent' => $bpsettings,
				'title'  => __( 'Community &amp; Groups', 'buddypress-toolbar' ),
				'href'   => network_admin_url( 'admin.php?page=bp-settings' ),
				'meta'   => array( 'target' => '', 'title' => __( 'Community &amp; Groups', 'buddypress-toolbar' ) )
			);

		} // end-if cap check

		/** Forum Setup link only for BuddyPress prior v1.6 */
		if ( ! class_exists( 'BuddyPress' ) && ( function_exists( 'bp_is_active' ) && bp_is_active( 'forums' ) && function_exists( 'bp_get_forums_root_slug' ) ) && current_user_can( 'manage_options' ) ) {

			/** Forum setup settings */
			$menu_items['s-forums'] = array(
				'parent' => $bpsettings,
				'title'  => __( 'Forums Setup', 'buddypress-toolbar' ),
				'href'   => network_admin_url( 'admin.php?page=bb-forums-setup' ),
				'meta'   => array( 'target' => '', 'title' => __( 'Forums Setup', 'buddypress-toolbar' ) )
			);

			/** Frontend component root page */
			$menu_items['spages-forums'] = array(
				'parent' => $spages,
				'title'  => __( 'Forums Root Page', 'buddypress-toolbar' ),
				'href'   => get_home_url() . '/' . bp_get_forums_root_slug(),
				'meta'   => array( 'target' => '', 'title' => _x( 'Frontend: Forums Root Page', 'Translators: For the tooltip', 'buddypress-toolbar' ) )
			);

		} // end-if BP check

		/** BP Settings section continued */
		if ( current_user_can( 'edit_theme_options' ) ) {

			$menu_items['s-bpwidgets'] = array(
				'parent' => $bpsettings,
				'title'  => esc_attr__( $bptb_buddypress_name ) . ' ' . __( 'Widgets', 'buddypress-toolbar' ),
				'href'   => admin_url( 'widgets.php' ),
				'meta'   => array( 'target' => '', 'title' => esc_attr__( $bptb_buddypress_name_tooltip ) . ' ' . __( 'Widgets', 'buddypress-toolbar' ) )
			);

		} // end-if cap check

		/** Profile Fields section */
		if ( current_user_can( 'manage_options' ) ) {

			$menu_items['bppfields'] = array(
				'parent' => $bpbar,
				'title'  => __( 'Profile Fields/Groups', 'buddypress-toolbar' ),
				'href'   => $bp_aurl_pfields,
				'meta'   => array( 'target' => '', 'title' => __( 'Profile Fields/Groups', 'buddypress-toolbar' ) )
			);

			$menu_items['bppfields-add'] = array(
				'parent' => $bppfields,
				'title'  => __( 'Add new Field Group', 'buddypress-toolbar' ),
				'href'   => network_admin_url( 'admin.php?page=bp-profile-setup&mode=add_group' ),
				'meta'   => array( 'target' => '', 'title' => __( 'Add new Field Group', 'buddypress-toolbar' ) )
			);

		} // end-if cap check

		/** Users section */
		if ( current_user_can( 'edit_users' ) ) {

			$menu_items['users'] = array(
				'parent' => $bpbar,
				'title'  => __( 'Users', 'buddypress-toolbar' ),
				'href'   => network_admin_url( 'users.php' ),
				'meta'   => array( 'target' => '', 'title' => __( 'Users', 'buddypress-toolbar' ) )
			);

		}  // end-if can 'edit_users'

		if ( current_user_can( 'add_users' ) ) {

			$menu_items['u-add-user'] = array(
				'parent' => $users,
				'title'  => __( 'Add new User', 'buddypress-toolbar' ),
				'href'   => network_admin_url( 'user-new.php' ),
				'meta'   => array( 'target' => '', 'title' => __( 'Add new User', 'buddypress-toolbar' ) )
			);

		}  // end-if can 'add_users'

		/** Check for active bbPress 2.x forums - if true, display settings link */
		if ( ! function_exists( 'ddw_bbpress_aba_admin_bar_menu' ) && class_exists( 'bbPress' ) && current_user_can( 'manage_options' ) ) {

			$menu_items[ 'bpbbpress' ] = array(
				'parent' => $bpbar,
				'title'  => __( 'bbPress Forums', 'buddypress-toolbar' ),
				'href'   => admin_url( 'options-general.php?page=bbpress' ),
				'meta'   => array( 'target' => '', 'title' => __( 'bbPress Forums', 'buddypress-toolbar' ) )
			);

		}  // end-if bbPres 2.x check

		/** Manage Groups special items */
		if ( function_exists( 'bp_is_active' )
			&& bp_is_active( 'groups' )
			&& function_exists( 'bp_get_groups_root_slug' )
			&& bp_current_user_can( 'bp_moderate' )
		) {

			$bptb_groups_url = function_exists( 'bp_groups_add_admin_menu' ) ? network_admin_url( 'admin.php?page=bp-groups' ) : get_home_url() . '/' . bp_get_groups_root_slug();

			$menu_items[ 'managegroups' ] = array(
				'parent' => $bpmggroup,
				'title'  => __( 'Manage Groups', 'buddypress-toolbar' ),
				'href'   => $bptb_groups_url,
				'meta'   => array( 'target' => '', 'title' => __( 'Manage Groups', 'buddypress-toolbar' ) . ' &raquo;' )
			);

			if ( function_exists( 'bp_groups_add_admin_menu' ) ) {
				
				$menu_items[ 'managegroups-add' ] = array(
					'parent' => $managegroups,
					'title'  => __( 'Add new Group', 'buddypress-toolbar' ),
					'href'   => trailingslashit( bp_get_root_domain() . '/' . bp_get_groups_root_slug() . '/create' ),
					'meta'   => array( 'target' => '', 'title' => __( 'Add new Group', 'buddypress-toolbar' ) . ' &raquo;' )
				);

				$menu_items[ 'managegroups-front' ] = array(
					'parent' => $managegroups,
					'title'  => __( 'View Groups Base', 'buddypress-toolbar' ),
					'href'   => trailingslashit( bp_get_root_domain() . '/' . bp_get_groups_root_slug() ),
					'meta'   => array( 'target' => '', 'title' => __( 'View Groups Base', 'buddypress-toolbar' ) . ' &raquo;' )
				);

			}  // end-if groups admin check

		}  // end-if BP group check

		/**
		 * Display last main item in the menu for active extensions/plugins
		 * ATTENTION: This is where plugins/extensions hook in on the sub-level hierarchy
		 *
		 * @since 1.0.0
		 */
		if ( BPTB_EXTENSIONS_DISPLAY && current_user_can( 'activate_plugins' ) ) {

			$menu_items['extensions'] = array(
				'parent' => $pgroup,
				'title'  => __( 'Active Extensions', 'buddypress-toolbar' ),
				'href'   => network_admin_url( 'plugins.php' ),
				'meta'   => array( 'target' => '', 'title' => __( 'Active Extensions', 'buddypress-toolbar' ) )
			);

		}  // end-if constant & cap check for displaying extensions

	} else {

		/** If BuddyPress is not active, to avoid PHP notices */
		$menu_items = $bpgroup_menu_items;

		/** If BuddyPress is not active and no icon filter is active, then display no icon */
		if ( ! has_filter( 'bptb_filter_main_icon' ) ) {
			add_filter( 'bptb_filter_main_item_icon_display', '__bptb_no_icon_display' );
		}

		/** If BuddyPress is not active "void" main settings link */
		$bp_aurl_main = false;

	}  // end-if BP conditional


	/**
	 * Display links to active BuddyPress specific themes settings' pages
	 *
	 * @since 1.0.0
	 */
		/** Include plugin file with theme support links */
		require_once( BPTB_PLUGIN_DIR . '/includes/bptb-themes.php' );


	/**
	 * Display links to active BuddyPress specific plugins/extensions settings' pages
	 *
	 * @since 1.0.0
	 */
		/** Include plugin file with plugin support links */
		require_once( BPTB_PLUGIN_DIR . '/includes/bptb-plugins.php' );


	/** Allow menu items to be filtered, but pass in parent menu item IDs */
	$menu_items = (array) apply_filters( 'ddw_bptb_menu_items', $menu_items,
																$bpgroup_menu_items,
																$prefix,
																$bpbar, 
																	$bpsupport,
																	$bpcodex,
																	$bpsites,
																	$bpactivity,
																	$bpsettings, 
																$users,
																$usersinviteanyone,
																$usersbpprofilesstats,
																$bppfields,
																$bppfieldsbpuatl, 
																	$extensions,
																		$extwangguard,
																		$extbpge,
																		$extbpdocs,
																		$extbpuatl,
																		$extbpprofilesstats, 
																		$extbuddystream,
																		$extinviteanyone,
																		$extcollabpress,
																		$exteventsmanager,
																		$extseopress, 
																		$extbpprofilesearch,
																		$extbpcheckins,
																		$extbpcheckinsplaces,
																		$extbpcheckinsfoursquare,
																		$extbpmedia,
																		$extachievementsapp,
																		$extbuddybox,
																$bpmggroup,
																$managegroups,
																$bpmgbpgm,
																$bpmgbpge,
																$bpmgcollabpress, 
																	$extgroup,
																	$pgroup,
																	$tgroup,
																$bservicessettings,
																$threeonesevensettings, 
																	$bpgroup,
																	$translations
	);  // end of array


	/**
	 * Add the BuddyPress top-level menu item
	 *
	 * @since 1.0.0
	 *
	 * @param $bptb_main_item_title
	 * @param $bptb_main_item_title_tooltip
	 * @param $bptb_main_item_icon_display
	 */
		/** Filter the main item name */
		$bptb_main_item_title = apply_filters( 'bptb_filter_main_item', __( 'BuddyPress', 'buddypress-toolbar' ) );

		/** Filter the main item name's tooltip */
		$bptb_main_item_title_tooltip = apply_filters( 'bptb_filter_main_item_tooltip', _x( 'BuddyPress', 'Translators: For the tooltip', 'buddypress-toolbar' ) );

		/** Filter the main item icon's class/display */
		$bptb_main_item_icon_display = apply_filters( 'bptb_filter_main_item_icon_display', 'icon-buddypress' );

		$wp_admin_bar->add_menu( array(
			'id'    => $bpbar,
			'title' => esc_attr__( $bptb_main_item_title ),
			'href'  => $bp_aurl_main,
			'meta'  => array(
						'class' => esc_attr( $bptb_main_item_icon_display ),
						'title' => esc_attr__( $bptb_main_item_title_tooltip ) )
		) );


	/** Loop through the menu items */
	foreach ( $menu_items as $id => $menu_item ) {
		
		/** Add in the item ID */
		$menu_item['id'] = $prefix . $id;

		/** Add meta target to each item where it's not already set, so links open in new window/tab */
		if ( ! isset( $menu_item['meta']['target'] ) ) {
			$menu_item['meta']['target'] = '_blank';
		}

		/** Add class to links that open up in a new window/tab */
		if ( '_blank' === $menu_item['meta']['target'] ) {

			if ( ! isset( $menu_item['meta']['class'] ) ) {

				$menu_item['meta']['class'] = '';

			}

			$menu_item['meta']['class'] .= $prefix . 'bptb-new-tab';

		}

		/** Add menu items */
		$wp_admin_bar->add_menu( $menu_item );

	}  // end foreach menu items


	/**
	 * Action Hook 'bptb_custom_main_items'
	 * allows for hooking other main items in
	 *
	 * @since 1.2.0
	 */
	do_action( 'bptb_custom_main_items' );


	/** "Manage Groups" Group: Main Entry */
	if ( function_exists( 'bp_group_class' ) ) {
		$bptb_is_bpmg = 'bpmg_yes';
	}

	if ( BPTB_MANAGE_GROUPS_DISPLAY && ( ( function_exists( 'bp_is_active' ) && bp_is_active( 'groups' ) ) &&  $bptb_is_bpmg == 'bpmg_yes' ) ) {

		$wp_admin_bar->add_group( array(
			'parent' => $bpbar,
			'id'     => $bpmggroup,
		) );

	}  // end-if groups check


	/** Extend Group: Main Entry */
	$wp_admin_bar->add_group( array(
		'parent' => $bpbar,
		'id'     => $extgroup,
	) );

		/** Plugin Group: Main Entry */
		$wp_admin_bar->add_group( array(
			'parent' => $extgroup,
			'id'     => $pgroup,
		) );


		/**
		 * Action Hook 'bptb_custom_extension_items'
		 * allows for hooking other extension-related items in
		 *
		 * @since 1.2.0
		 */
		do_action( 'bptb_custom_extension_items' );


		/** Theme Group: Main Entry */
		if ( BPTB_THEME_DISPLAY && ( current_user_can( 'manage_options' ) || current_user_can( 'edit_theme_options' ) || current_user_can( 'switch_themes' ) ) ) {

			$wp_admin_bar->add_group( array(
				'parent' => $extgroup,
				'id'     => $tgroup,
			) );

		}  // end-if constant check for displaying theme group


		/**
		 * Action Hook 'bptb_custom_theme_items'
		 * allows for hooking other theme-related items in
		 *
		 * @since 1.2.0
		 */
		do_action( 'bptb_custom_theme_items' );


	/** BuddyPress Group: Main Entry */
	$wp_admin_bar->add_group( array(
		'parent' => $bpbar,
		'id'     => $bpgroup,
		'meta'   => array( 'class' => 'ab-sub-secondary' )
	) );


	/** BuddyPress Group: Loop through the group menu items */
	foreach ( $bpgroup_menu_items as $id => $bpgroup_menu_item ) {
		
		/** BuddyPress Group: Add in the item ID */
		$bpgroup_menu_item['id'] = $prefix . $id;

		/** BuddyPress Group: Add meta target to each item where it's not already set, so links open in new window/tab */
		if ( ! isset( $bpgroup_menu_item['meta']['target'] ) ) {

			$bpgroup_menu_item['meta']['target'] = '_blank';

		}

		/** BuddyPress Group: Add class to links that open up in a new window/tab */
		if ( '_blank' === $bpgroup_menu_item['meta']['target'] ) {

			if ( ! isset( $bpgroup_menu_item['meta']['class'] ) ) {

				$bpgroup_menu_item['meta']['class'] = '';

			}

			$bpgroup_menu_item['meta']['class'] .= $prefix . 'bptb-new-tab';

		}

		/** BuddyPress Group: Add menu items */
		$wp_admin_bar->add_menu( $bpgroup_menu_item );

	}  // end foreach BuddyPress Group


	/**
	 * Action Hook 'bptb_custom_group_items'
	 * allows for hooking other BuddyPress Group items in
	 *
	 * @since 1.2.0
	 */
	do_action( 'bptb_custom_group_items' );

}  // end of main function


add_action( 'wp_head', 'ddw_bptb_admin_style' );
add_action( 'admin_head', 'ddw_bptb_admin_style' );
/**
 * Add the styles for new WordPress Toolbar / Admin Bar entry
 * 
 * @since 1.0.0
 * @version 1.1
 *
 * @param $bptb_main_icon
 */
function ddw_bptb_admin_style() {

	/** No styles if admin bar is disabled or user is not logged in or items are disabled via constant */
	if ( ! is_admin_bar_showing()
		|| ! is_user_logged_in()
		|| ! BPTB_DISPLAY
	) {
		return;
	}

	/** Add CSS styles to wp_head/admin_head */
	$bptb_main_icon = apply_filters( 'bptb_filter_main_icon', plugins_url( 'buddypress-toolbar/images/icon-bptb.png',
dirname( __FILE__ ) ) );

	?>
	<style type="text/css">
		#wpadminbar.nojs .ab-top-menu > li.menupop.icon-buddypress:hover > .ab-item,
		#wpadminbar .ab-top-menu > li.menupop.icon-buddypress.hover > .ab-item,
		#wpadminbar.nojs .ab-top-menu > li.menupop.icon-buddypress > .ab-item,
		#wpadminbar .ab-top-menu > li.menupop.icon-buddypress > .ab-item {
      			background-image: url(<?php echo esc_url_raw( $bptb_main_icon ); ?>);
			background-repeat: no-repeat;
			background-position: 0.85em 50%;
			padding-left: 30px;
		}
		#wp-admin-bar-ddw-buddypress-bp-forum-de > .ab-item:before,
		#wp-admin-bar-ddw-buddypress-bp-languages-de > .ab-item:before,
		#wp-admin-bar-ddw-buddypress-translations > .ab-item:before {
			color: #ff9900;
			content: '• ';
		}
		#wpadminbar .bptb-search-input,
		#wpadminbar .bptb-search-go {
			color: #21759b !important;
			text-shadow: none;
		}
		#wpadminbar .bptb-search-input,
		#wpadminbar .bptb-search-go {
			background-color: #fff;
			height: 18px;
			line-height: 18px;
			padding: 1px 4px;
		}
		#wpadminbar .bptb-search-go {
			-webkit-border-radius: 11px;
			   -moz-border-radius: 11px;
			        border-radius: 11px;
			font-size: 0.67em;
			margin: 0 0 0 2px;
		}
	</style>
	<?php

}  // end of function ddw_bptb_admin_style


/**
 * Helper functions for custom branding of the plugin
 *
 * @since 1.2.0
 */
	/** Include plugin file with special custom stuff */
	require_once( BPTB_PLUGIN_DIR . '/includes/bptb-branding.php' );


/**
 * Returns current plugin's header data in a flexible way.
 *
 * @since  1.5.0
 *
 * @uses   get_plugins()
 *
 * @param  $bptb_plugin_value
 * @param  $bptb_plugin_folder
 * @param  $bptb_plugin_file
 *
 * @return string Plugin data.
 */
function ddw_bptb_plugin_get_data( $bptb_plugin_value ) {

	/** Bail early if we are not in wp-admin */
	if ( ! is_admin() ) {
		return;
	}

	/** Include WordPress plugin data */
	if ( ! function_exists( 'get_plugins' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}

	$bptb_plugin_folder = get_plugins( '/' . plugin_basename( dirname( __FILE__ ) ) );
	$bptb_plugin_file = basename( ( __FILE__ ) );

	return $bptb_plugin_folder[ $bptb_plugin_file ][ $bptb_plugin_value ];

}  // end of function ddw_bptb_plugin_get_data
