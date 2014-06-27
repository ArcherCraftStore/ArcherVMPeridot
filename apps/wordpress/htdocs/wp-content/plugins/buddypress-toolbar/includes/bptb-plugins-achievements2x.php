<?php
/**
 * Display links to active extensions specific settings' pages: BuddyPress Achievements - versions 1.x/ 2.x.
 *
 * @package    BuddyPress Toolbar
 * @subpackage Plugin/Extension Support
 * @author     David Decker - DECKERWEB
 * @copyright  Copyright (c) 2012-2013, David Decker - DECKERWEB
 * @license    http://www.opensource.org/licenses/gpl-license.php GPL-2.0+
 * @link       http://genesisthemes.de/en/wp-plugins/buddypress-toolbar/
 * @link       http://deckerweb.de/twitter
 *
 * @since      1.1.0
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
 * Support for: BuddyPress Achievements v1.x/ v2.x (free, by Paul Gibbs)
 *
 * @since 1.1.0
 */
	/** Set $bp global */
	global $bp;

	/** Set "Achievements" root slug */
	$bptb_achievements_root_slug = $bp->achievements->root_slug;

	/** Entries at "Extensions" level submenu */
	$menu_items['ext-achievements'] = array(
		'parent' => $extensions,
		'title'  => __( 'Achievements', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=achievements' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Achievements', 'buddypress-toolbar' ) )
	);

	/** Entries at "Components Frontend Pages" level submenu */
	$menu_items['spages-achievements'] = array(
		'parent' => $spages,
		'title'  => __( 'Achievements Roots Page', 'buddypress-toolbar' ),
		'href'   => get_home_url() . '/' . $bptb_achievements_root_slug,
		'meta'   => array( 'target' => '', 'title' => _x( 'Frontend: Achievements Roots Page', 'Translators: For the tooltip', 'buddypress-toolbar' ) )
	);