<?php
/**
 * Display links to active extensions specific settings' pages: BP Checkins.
 *
 * @package    BuddyPress Toolbar
 * @subpackage Plugin/Extension Support
 * @author     David Decker - DECKERWEB
 * @copyright  Copyright (c) 2012-2013, David Decker - DECKERWEB
 * @license    http://www.opensource.org/licenses/gpl-license.php GPL-2.0+
 * @link       http://genesisthemes.de/en/wp-plugins/buddypress-toolbar/
 * @link       http://deckerweb.de/twitter
 *
 * @since      1.4.0
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
 * Support for: BP Checkins (free, by Imath)
 *
 * @since 1.4.0
 */
	/** "Settings" string */
	$bptb_checkins_settings_slug = __( 'Settings', 'buddypress-toolbar' ) . ': ';

	/** Entries at "Extensions" level submenu */
	/** Settings */
	$menu_items['extbpcheckins'] = array(
		'parent' => $extensions,
		'title'  => __( 'BP Checkins', 'buddypress-toolbar' ),
		'href'   => is_multisite() ? network_admin_url( 'settings.php?page=bp-checkins-admin' ) : admin_url( 'options-general.php?page=bp-checkins-admin' ),
		'meta'   => array( 'target' => '', 'title' => __( 'BP Checkins', 'buddypress-toolbar' ) )
	);

	/** Check for activated 'Checkins & Places Component' */
	$bptb_cip_component_activated = (int)bp_get_option( 'bp-checkins-activate-component' );

	if ( $bptb_cip_component_activated == 1 ) {

		/** 'Places' post type - only if active 'Checkins & Places Component' */
		if ( ! is_network_admin() ) {

			$menu_items['extbpcheckinsplaces'] = array(
				'parent' => $extbpcheckins,
				'title'  => __( 'Community Places', 'buddypress-toolbar' ),
				'href'   => admin_url( 'edit.php?post_type=places' ),
				'meta'   => array( 'target' => '', 'title' => __( 'Community Places', 'buddypress-toolbar' ) )
			);

			$menu_items['extbpcheckinsplaces-add'] = array(
				'parent' => $extbpcheckins,
				'title'  => __( 'Add new Place', 'buddypress-toolbar' ),
				'href'   => admin_url( 'post-new.php?post_type=places' ),
				'meta'   => array( 'target' => '', 'title' => __( 'Add new Place', 'buddypress-toolbar' ) )
			);

			$menu_items['extbpcheckinsplaces-categories'] = array(
				'parent' => $extbpcheckins,
				'title'  => __( 'Places Categories', 'buddypress-toolbar' ),
				'href'   => admin_url( 'edit-tags.php?taxonomy=places_category&post_type=places' ),
				'meta'   => array( 'target' => '', 'title' => __( 'Places Categories', 'buddypress-toolbar' ) )
			);

		}  // end-if !is_network_admin

		/** Entries at "Components Frontend Pages" level submenu
		 *
		 * @uses function bp_get_checkins_root_slug() by BP Checkins plugin
		 */
		$menu_items['spages-checkins'] = array(
			'parent' => $spages,
			'title'  => __( 'Checkins Roots Page', 'buddypress-toolbar' ),
			'href'   => get_home_url() . '/' . bp_get_checkins_root_slug(),
			'meta'   => array( 'target' => '', 'title' => _x( 'Frontend: Checkins Roots Page', 'Translators: For the tooltip', 'buddypress-toolbar' ) )
		);

	}  // end-if places component check

	/** Activity Checkins */
	$menu_items['extbpcheckins-activity'] = array(
		'parent' => $extbpcheckins,
		'title'  => __( 'Activity Checkins', 'buddypress-toolbar' ),
		'href'   => is_multisite() ? network_admin_url( 'settings.php?page=bp-checkins-admin' ) : admin_url( 'options-general.php?page=bp-checkins-admin' ),
		'meta'   => array( 'target' => '', 'title' => $bptb_checkins_settings_slug . __( 'Activity Checkins', 'buddypress-toolbar' ) )
	);

	/** Checkins & Places Component */
	$menu_items['extbpcheckins-components'] = array(
		'parent' => $extbpcheckins,
		'title'  => __( 'Checkins &amp; Places Component', 'buddypress-toolbar' ),
		'href'   => is_multisite() ? network_admin_url( 'settings.php?page=bp-checkins-admin&tab=component' ) : admin_url( 'options-general.php?page=bp-checkins-admin&tab=component' ),
		'meta'   => array( 'target' => '', 'title' => $bptb_checkins_settings_slug . __( 'Checkins &amp; Places Component', 'buddypress-toolbar' ) )
	);

	/** Foursquare API Settings */
	$menu_items['extbpcheckinsfoursquare'] = array(
		'parent' => $extbpcheckins,
		'title'  => __( 'Foursquare API Settings', 'buddypress-toolbar' ),
		'href'   => is_multisite() ? network_admin_url( 'settings.php?page=bp-checkins-admin&tab=foursquare' ) : admin_url( 'options-general.php?page=bp-checkins-admin&tab=foursquare' ),
		'meta'   => array( 'target' => '', 'title' => $bptb_checkins_settings_slug . __( 'Foursquare API Settings', 'buddypress-toolbar' ) )
	);

	/** Foursquare Import Logs
	 *
	 * @uses function bp_checkins_is_foursquare_ready() by BP Checkins plugin
	 */
	if ( bp_checkins_is_foursquare_ready() ) {

		$menu_items['extbpcheckinsfoursquare-logs'] = array(
			'parent' => $extbpcheckins,
			'title'  => __( 'Foursquare Import Logs', 'buddypress-toolbar' ),
			'href'   => is_multisite() ? network_admin_url( 'settings.php?page=foursquare-logs' ) : admin_url( 'tools.php?page=foursquare-logs' ),
			'meta'   => array( 'target' => '', 'title' => $bptb_checkins_settings_slug . __( 'Foursquare Import Logs', 'buddypress-toolbar' ) )
		);

	}  // end-if foursquare import logs check