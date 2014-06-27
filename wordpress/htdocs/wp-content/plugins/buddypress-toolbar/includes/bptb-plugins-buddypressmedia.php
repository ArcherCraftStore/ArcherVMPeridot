<?php
/**
 * Display links to active extensions specific settings' pages: BuddyPress Media.
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
 * Support for: BuddyPress Media (free, by rtCamp)
 *
 * @since 1.4.0
 */
	/** Entries at "Extensions" level submenu */
	$menu_items['extbpmedia'] = array(
		'parent' => $extensions,
		'title'  => __( 'Media Component Settings', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=bp-media-settings' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Media Component Settings', 'buddypress-toolbar' ) )
	);

	$menu_items['extbpmedia-addons'] = array(
		'parent' => $extbpmedia,
		'title'  => __( 'Add-Ons', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=bp-media-addons' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Add-Ons', 'buddypress-toolbar' ) )
	);

	$menu_items['extbpmedia-support'] = array(
		'parent' => $extbpmedia,
		'title'  => __( 'Support Resources', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=bp-media-support' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Support Resources', 'buddypress-toolbar' ) )
	);

	$menu_items['extbpmedia-convert'] = array(
		'parent' => $extbpmedia,
		'title'  => __( 'Convert Videos', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=bp-media-convert-videos' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Convert Videos', 'buddypress-toolbar' ) )
	);

	$menu_items['extbpmedia-importer'] = array(
		'parent' => $extbpmedia,
		'title'  => __( 'BP Album Importer', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=bp-media-importer' ),
		'meta'   => array( 'target' => '', 'title' => __( 'BP Album Importer', 'buddypress-toolbar' ) )
	);

	/** Entries at "BuddyPress Settings" level submenu */
	$menu_items['s_bpmedia'] = array(
		'parent' => $bpsettings,
		'title'  => __( 'Media Component Settings', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=bp-media-settings' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Media Component Settings', 'buddypress-toolbar' ) )
	);

	$menu_items['s_bpmedia-addons'] = array(
		'parent' => $s_bpmedia,
		'title'  => __( 'Add-Ons', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=bp-media-addons' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Add-Ons', 'buddypress-toolbar' ) )
	);

	$menu_items['s_bpmedia-support'] = array(
		'parent' => $s_bpmedia,
		'title'  => __( 'Support Resources', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=bp-media-support' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Support Resources', 'buddypress-toolbar' ) )
	);

	$menu_items['s_bpmedia-convert'] = array(
		'parent' => $s_bpmedia,
		'title'  => __( 'Convert Videos', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=bp-media-convert-videos' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Convert Videos', 'buddypress-toolbar' ) )
	);

	$menu_items['s_bpmedia-importer'] = array(
		'parent' => $s_bpmedia,
		'title'  => __( 'BP Album Importer', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=bp-media-importer' ),
		'meta'   => array( 'target' => '', 'title' => __( 'BP Album Importer', 'buddypress-toolbar' ) )
	);