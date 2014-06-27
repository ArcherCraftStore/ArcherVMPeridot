<?php
/**
 * Display links to active extensions specific settings' pages: Achievements (App) - version 3.x.
 *
 * @package    BuddyPress Toolbar
 * @subpackage Plugin/Extension Support
 * @author     David Decker - DECKERWEB
 * @copyright  Copyright (c) 2012-2013, David Decker - DECKERWEB
 * @license    http://www.opensource.org/licenses/gpl-license.php GPL-2.0+
 * @link       http://genesisthemes.de/en/wp-plugins/buddypress-toolbar/
 * @link       http://deckerweb.de/twitter
 *
 * @since      1.6.0
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
 * Support for: Achievements (App) v3.x (free, by Paul Gibbs)
 *
 * @since 1.6.0
 */

	/** Entries at "Extensions" level submenu */
	$menu_items[ 'extachievementsapp' ] = array(
		'parent' => $extensions,
		'title'  => __( 'Achievements', 'buddypress-toolbar' ),
		'href'   => admin_url( 'edit.php?post_type=achievement' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Achievements', 'buddypress-toolbar' ) )
	);

		$menu_items[ 'extachievementsapp-add' ] = array(
			'parent' => $extachievementsapp,
			'title'  => __( 'Add new Achievement', 'buddypress-toolbar' ),
			'href'   => admin_url( 'post-new.php?post_type=achievement' ),
			'meta'   => array( 'target' => '', 'title' => __( 'Add new Achievement', 'buddypress-toolbar' ) )
		);

		$menu_items[ 'extachievementsapp-user' ] = array(
			'parent' => $extachievementsapp,
			'title'  => __( 'Users', 'buddypress-toolbar' ),
			'href'   => admin_url( 'edit.php?post_type=achievement&page=achievements-users' ),
			'meta'   => array( 'target' => '', 'title' => __( 'Users', 'buddypress-toolbar' ) )
		);

		$menu_items[ 'extachievementsapp-plugins' ] = array(
			'parent' => $extachievementsapp,
			'title'  => __( 'Supported Plugins', 'buddypress-toolbar' ),
			'href'   => admin_url( 'edit.php?post_type=achievement&page=achievements-plugins' ),
			'meta'   => array( 'target' => '', 'title' => __( 'Supported Plugins', 'buddypress-toolbar' ) )
		);