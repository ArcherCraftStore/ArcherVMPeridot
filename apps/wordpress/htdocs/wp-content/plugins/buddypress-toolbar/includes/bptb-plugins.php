<?php
/**
 * Display links to active BuddyPress specific plugins/extensions settings' pages
 *
 * @package    BuddyPress Toolbar
 * @subpackage Plugin/Extension Support
 * @author     David Decker - DECKERWEB
 * @copyright  Copyright (c) 2012-2013, David Decker - DECKERWEB
 * @license    http://www.opensource.org/licenses/gpl-license.php GPL-2.0+
 * @link       http://genesisthemes.de/en/wp-plugins/buddypress-toolbar/
 * @link       http://deckerweb.de/twitter
 *
 * @since      1.0.0
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
 * Setting default values for some variables
 *
 * @since 1.2.0
 */
$bptb_is_bpmg = 'default';


/**
 * BP Menus (free, by modemlooper)
 *
 * @since 1.5.0
 */
if ( function_exists( 'bp_profile_menu_init' ) && current_user_can( 'edit_theme_options' ) ) {

	$menu_items['s-menus'] = array(
		'parent' => $bpsettings,
		'title'  => esc_attr__( $bptb_buddypress_name ) . ' ' . __( 'Menus', 'buddypress-toolbar' ),
		'href'   => admin_url( 'nav-menus.php' ),
		'meta'   => array( 'target' => '', 'title' => esc_attr__( $bptb_buddypress_name ) . ' ' . __( 'Menus', 'buddypress-toolbar' ) )
	);

}  // end-if BP Menus


/**
 * BP Extended Settings (free, by modemlooper)
 *
 * @since 1.4.0
 */
if ( function_exists( 'bp_extended_settings_init' ) && current_user_can( 'manage_options' ) ) {

	/** Entries at "Extensions" level submenu */
	$menu_items['ext-bpextended'] = array(
		'parent' => $extensions,
		'title'  => __( 'BP Extended Settings', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=bp-extended-settings' ),
		'meta'   => array( 'target' => '', 'title' => __( 'BP Extended Settings', 'buddypress-toolbar' ) )
	);

	/** Entries at "BuddyPress Settings" level submenu */
	$menu_items['s-bpextended'] = array(
		'parent' => $bpsettings,
		'title'  => __( 'BP Extended Settings', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=bp-extended-settings' ),
		'meta'   => array( 'target' => '', 'title' => __( 'BP Extended Settings', 'buddypress-toolbar' ) )
	);
}  // end-if BP Extended Settings


/**
 * BuddyPress ScholarPress Courseware (free, by ScholarPress Dev Crew)
 *
 * @since 1.0.0
 */
if ( ( ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'buddypress-courseware/courseware.php' ) ) || class_exists( 'BPSP_Courses' ) ) && current_user_can( 'manage_options' ) ) {

	$menu_items['ext-bpcourseware'] = array(
		'parent' => $extensions,
		'title'  => __( 'BuddyPress ScholarPress Courseware', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=bp-courseware' ),
		'meta'   => array( 'target' => '', 'title' => __( 'BuddyPress ScholarPress Courseware', 'buddypress-toolbar' ) )
	);

}  // end-if BP Courseware


/**
 * BP Chechkins (free, by Imath)
 *
 * @since 1.4.0
 */
if ( defined( 'BP_CHECKINS_PLUGIN_VERSION' ) && current_user_can( 'manage_options' ) ) {

	/** Include plugin file with BP Checkins items stuff */
	require_once( BPTB_PLUGIN_DIR . '/includes/bptb-plugins-bpcheckins.php' );

}  // end-if BP Chechkins


/**
 * BP Group Organizer (free, by David Dean)
 *
 * @since 1.0.0
 */
if ( ( ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'bp-group-organizer/index.php' ) ) || function_exists( 'bp_group_organizer_admin' ) ) && current_user_can( 'manage_options' ) ) {

	/** Entries at "Extensions" level submenu */
	$menu_items['ext-bpgrouporganizer'] = array(
		'parent' => $extensions,
		'title'  => __( 'BP Group Organizer', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=group_organizer' ),
		'meta'   => array( 'target' => '', 'title' => __( 'BP Group Organizer', 'buddypress-toolbar' ) )
	);

	/** Entries at "Manage Groups" level submenu */
	/** Activate display */
	$bptb_is_bpmg = 'bpmg_yes';
	$menu_items['bpmg-bpgrouporganizer'] = array(
		'parent' => $managegroups,
		'title'  => __( 'BP Group Organizer', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=group_organizer' ),
		'meta'   => array( 'target' => '', 'title' => __( 'BP Group Organizer', 'buddypress-toolbar' ) )
	);

}  // end-if BP Group Organizer


/**
 * BP Group Management (free, by Boone Gorges)
 *
 * @since 1.2.0
 */
if ( ( ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'bp-group-management/bp-group-management.php' ) ) || function_exists( 'bp_group_management_init' ) ) && current_user_can( 'manage_options' ) ) {

	/** Entries at "Extensions" level submenu */
	$menu_items['extbpgm'] = array(
		'parent' => $extensions,
		'title'  => __( 'BP Group Management', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=bp-group-management' ),
		'meta'   => array( 'target' => '', 'title' => __( 'BP Group Management', 'buddypress-toolbar' ) )
	);

	$menu_items['extbpgm-settings'] = array(
		'parent' => $extbpgm,
		'title'  => __( 'Settings', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=bp-group-management&action=settings' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Settings', 'buddypress-toolbar' ) )
	);

	/** Entries at "Manage Groups" level submenu */
	/** Activate display */
	$bptb_is_bpmg = 'bpmg_yes';
	$menu_items['bpmgbpgm'] = array(
		'parent' => $managegroups,
		'title'  => __( 'Group Management', 'buddypress-toolbar' ),
		'href'   => is_multisite() ? network_admin_url( 'admin.php?page=bp-group-management' ) : admin_url( 'edit.php?post_type=gpages' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Group Management', 'buddypress-toolbar' ) )
	);

	$menu_items['bpmgbpgm-settings'] = array(
		'parent' => $bpmgbpgm,
		'title'  => __( 'Settings', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=bp-group-management&action=settings' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Settings', 'buddypress-toolbar' ) )
	);

}  // end-if BP Group Management


/**
 * BP Group Hierarchy (free, by David Dean)
 *
 * @since 1.0.0
 */
if ( ( ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'bp-group-hierarchy/index.php' ) ) || class_exists( 'BP_Groups_Hierarchy' ) ) && current_user_can( 'manage_options' ) ) {

	/** Entries at "Extensions" level submenu */
	$menu_items['ext-bpgrouphierarchy'] = array(
		'parent' => $extensions,
		'title'  => __( 'BP Group Hierarchy', 'buddypress-toolbar' ),
		'href'   => admin_url( 'admin.php?page=bp_group_hierarchy_settings' ),
		'meta'   => array( 'target' => '', 'title' => __( 'BP Group Hierarchy', 'buddypress-toolbar' ) )
	);

	/** Entries at "Manage Groups" level submenu */
	/** Activate display */
	$bptb_is_bpmg = 'bpmg_yes';
	$menu_items['bpmg-bpgrouphierarchy'] = array(
		'parent' => $managegroups,
		'title'  => __( 'BP Group Hierarchy', 'buddypress-toolbar' ),
		'href'   => admin_url( 'admin.php?page=bp_group_hierarchy_settings' ),
		'meta'   => array( 'target' => '', 'title' => __( 'BP Group Hierarchy', 'buddypress-toolbar' ) )
	);

}  // end-if BP Group Hierarchy


/**
 * BuddyPress Groups Extras (free, by slaFFik)
 *
 * @since 1.1.0
 */
if ( ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'buddypress-groups-extras/bpge.php' ) ) || function_exists( 'bpge_load' ) ) {

	/** Groups Extras admin */
	if ( current_user_can( 'manage_options' ) ) {
		/** Entries at "Extensions" level submenu */
		$menu_items['extbpge'] = array(
			'parent' => $extensions,
			'title'  => __( 'BP Groups Extras', 'buddypress-toolbar' ),
			'href'   => network_admin_url( 'admin.php?page=bpge-admin' ),
			'meta'   => array( 'target' => '', 'title' => __( 'BP Groups Extras', 'buddypress-toolbar' ) )
		);

		/** Entries at "Manage Groups" level submenu */
		/** Activate display */
		$bptb_is_bpmg = 'bpmg_yes';
		$menu_items['bpmgbpge'] = array(
			'parent' => $managegroups,
			'title'  => __( 'BP Groups Extras', 'buddypress-toolbar' ),
			'href'   => network_admin_url( 'admin.php?page=bpge-admin' ),
			'meta'   => array( 'target' => '', 'title' => __( 'BP Groups Extras', 'buddypress-toolbar' ) )
		);

	}  // end-if cap check

	/** Edit Groups pages */
	if ( current_user_can( 'edit_pages' ) ) {

		/** Entries at "Extensions" level submenu */
		$menu_items['extbpge-gpages'] = array(
			'parent' => $extbpge,
			'title'  => __( 'Groups Pages', 'buddypress-toolbar' ),
			'href'   => admin_url( 'edit.php?post_type=gpages' ),
			'meta'   => array( 'target' => '', 'title' => __( 'Groups Pages', 'buddypress-toolbar' ) )
		);

		/** Entries at "Manage Groups" level submenu */
		/** Activate display */
		$bptb_is_bpmg = 'bpmg_yes';
		$menu_items['extbpge-gpages'] = array(
			'parent' => $bpmgbpge,
			'title'  => __( 'Groups Pages', 'buddypress-toolbar' ),
			'href'   => admin_url( 'edit.php?post_type=gpages' ),
			'meta'   => array( 'target' => '', 'title' => __( 'Groups Pages', 'buddypress-toolbar' ) )
		);

	}  // end-if cap check

}  // end-if BP Groups Extras


/**
 * BuddyPress Group Default Avatar (free, by Vernon Fowler)
 *
 * @since 1.1.0
 */
if ( ( ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'buddypress-default-group-avatar/buddypress-default-group-avatar.php' ) ) || function_exists( 'BPDGA_default_get_group_avatar' ) ) && current_user_can( 'manage_options' ) ) {

	/** Entries at "Extensions" level submenu */
	$menu_items[ 'ext-bpgroupavatar' ] = array(
		'parent' => $extensions,
		'title'  => __( 'Group Default Avatar', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=group_avatar' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Group Default Avatar', 'buddypress-toolbar' ) )
	);

	/** Entries at "Manage Groups" level submenu */
	/** Activate display */
	$bptb_is_bpmg = 'bpmg_yes';
	$menu_items[ 'bpmg-bpgroupavatar' ] = array(
		'parent' => $managegroups,
		'title'  => __( 'Group Default Avatar', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=group_avatar' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Group Default Avatar', 'buddypress-toolbar' ) )
	);

}  // end-if BP Group Default Avatar


/**
 * BuddyPress Group Email Subscription (free, by Deryk Wenaus + boonebgorges)
 *
 * @since 1.1.0
 */
if ( ( ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'buddypress-group-email-subscription/bp-activity-subscription.php' ) ) || function_exists( 'ass_loader' ) ) && current_user_can( 'manage_options' ) ) {

	/** Entries at "Extensions" level submenu */
	$menu_items[ 'ext-bpgroupemailsubs' ] = array(
		'parent' => $extensions,
		'title'  => __( 'Group Email Subscription', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=ass_admin_options' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Group Email Subscription', 'buddypress-toolbar' ) )
	);

	/** Entries at "Manage Groups" level submenu */
	/** Activate display */
	$bptb_is_bpmg = 'bpmg_yes';
	$menu_items[ 'bpmg-bpgroupemailsubs' ] = array(
		'parent' => $managegroups,
		'title'  => __( 'Group Email Subscription', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=ass_admin_options' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Group Email Subscription', 'buddypress-toolbar' ) )
	);

}  // end-if BP Group Email Subscription


/**
 * BP GTM System (free, by slaFFik, valant)
 *
 * @since 1.1.0
 */
if ( ( ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'bp-gtm-system/bp-gtm.php' ) ) || function_exists( 'bp_gtm_init' ) ) && current_user_can( 'manage_options' ) ) {

	/** Entries at "Extensions" level submenu */
	$menu_items[ 'ext-bpgtm' ] = array(
		'parent' => $extensions,
		'title'  => __( 'BP GTM System', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=bp-gtm-admin' ),
		'meta'   => array( 'target' => '', 'title' => __( 'BP GTM System', 'buddypress-toolbar' ) )
	);

	/** Entries at "Manage Groups" level submenu */
	/** Activate display */
	$bptb_is_bpmg = 'bpmg_yes';
	$menu_items[ 'bpmg-bpgtm' ] = array(
		'parent' => $managegroups,
		'title'  => __( 'BP GTM System', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=bp-gtm-admin' ),
		'meta'   => array( 'target' => '', 'title' => __( 'BP GTM System', 'buddypress-toolbar' ) )
	);

}  // end-if BP GTM System


/**
 * BuddyPress Relate Groups to Blogs (free, by spurge)
 *
 * @since 1.5.0
 */
if ( defined( 'BP_RELATE_GROUPS_TO_BLOGS_IS_INSTALLED' ) && current_user_can( 'manage_options' ) && is_multisite() ) {

	/** Entries at "Extensions" level submenu */
	$menu_items[ 'ext-bprelategroupsblogs' ] = array(
		'parent' => $extensions,
		'title'  => __( 'Relate Groups to Blogs', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'settings.php?page=relate-groups-to-blogs' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Relate Groups to Blogs', 'buddypress-toolbar' ) )
	);

	/** Entries at "Manage Groups" level submenu */
	/** Activate display */
	$bptb_is_bpmg = 'bpmg_yes';
	$menu_items[ 'bpmg-bprelategroupsblogs' ] = array(
		'parent' => $managegroups,
		'title'  => __( 'Relate Groups to Blogs', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'settings.php?page=relate-groups-to-blogs' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Relate Groups to Blogs', 'buddypress-toolbar' ) )
	);

}  // end-if BP Relate Groups to Blogs


/**
 * BuddyPress Group Folders (free, by Rudolf Enberg)
 *
 * @since 1.6.0
 */
if ( defined( 'BP_GFOLD_DIR' ) && current_user_can( 'edit_plugins' ) ) {

	/** Entries at "Extensions" level submenu */
	$menu_items[ 'ext-bpgroupfolders' ] = array(
		'parent' => $extensions,
		'title'  => __( 'Group Folders', 'buddypress-toolbar' ),
		'href'   => admin_url( 'options-general.php?page=bp-gfold-admin' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Group Folders', 'buddypress-toolbar' ) )
	);

	/** Entries at "Manage Groups" level submenu */
	/** Activate display */
	$bptb_is_bpmg = 'bpmg_yes';
	$menu_items[ 'bpmg-bpgroupfolders' ] = array(
		'parent' => $managegroups,
		'title'  => __( 'Group Folders', 'buddypress-toolbar' ),
		'href'   => admin_url( 'options-general.php?page=bp-gfold-admin' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Group Folders', 'buddypress-toolbar' ) )
	);

}  // end-if BP Group Folders


/**
 * BuddyPress Portfolio (free, by Nicolas Crocfer)
 *
 * @since 1.3.0
 */
if ( ( ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'buddypress-portfolio/loader.php' ) ) || defined( 'BP_PORTFOLIO_VERSION' ) ) && current_user_can( 'manage_options' ) ) {

	/** Set $bp global */
	global $bp;

	/** Set "Portfolio" root slug */
	$bptb_portfolio_root_slug = $bp->pages->portfolio->slug;

	/** Entries at "Extensions" level submenu */
	$menu_items[ 'ext-bpportfolio' ] = array(
		'parent' => $extensions,
		'title'  => __( 'BuddyPress Portfolio', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=bp-portfolio-settings' ),
		'meta'   => array( 'target' => '', 'title' => __( 'BuddyPress Portfolio', 'buddypress-toolbar' ) )
	);

	/** Entries at "Components Frontend Pages" level submenu */
	$menu_items[ 'spages-portfolio' ] = array(
		'parent' => $spages,
		'title'  => __( 'Portfolio Roots Page', 'buddypress-toolbar' ),
		'href'   => get_home_url() . '/' . $bptb_portfolio_root_slug,
		'meta'   => array( 'target' => '', 'title' => _x( 'Frontend: Portfolio Roots Page', 'Translators: For the tooltip', 'buddypress-toolbar' ) )
	);
}  // end-if BP Portfolio


/**
 * BuddyBox (free, by Imath)
 *
 * @since 1.6.0
 */
if ( class_exists( 'BuddyBox' ) && current_user_can( 'manage_options' ) ) {

	/** Entries at "Extensions" level submenu */
	$menu_items[ 'extbuddybox' ] = array(
		'parent' => $extensions,
		'title'  => __( 'BuddyBox Files (All)', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=buddybox-files' ),
		'meta'   => array( 'target' => '', 'title' => __( 'BuddyBox Files (All)', 'buddypress-toolbar' ) )
	);

		$menu_items[ 'extbuddybox-files' ] = array(
			'parent' => $extbuddybox,
			'title'  => __( 'Files', 'buddypress-toolbar' ),
			'href'   => network_admin_url( 'admin.php?page=buddybox-files&buddybox_type=buddybox-file' ),
			'meta'   => array( 'target' => '', 'title' => __( 'Files', 'buddypress-toolbar' ) )
		);

		$menu_items[ 'extbuddybox-folders' ] = array(
			'parent' => $extbuddybox,
			'title'  => __( 'Folders', 'buddypress-toolbar' ),
			'href'   => network_admin_url( 'admin.php?page=buddybox-files&buddybox_type=buddybox-folder' ),
			'meta'   => array( 'target' => '', 'title' => __( 'Folders', 'buddypress-toolbar' ) )
		);

		$menu_items[ 'extbuddybox-settings' ] = array(
			'parent' => $extbuddybox,
			'title'  => __( 'Settings', 'buddypress-toolbar' ),
			'href'   => bp_core_do_network_admin() ? network_admin_url( 'settings.php?page=buddybox' ) : admin_url( 'options-general.php?page=buddybox' ),
			'meta'   => array( 'target' => '', 'title' => __( 'Settings', 'buddypress-toolbar' ) )
		);

		$menu_items[ 'extbuddybox-about' ] = array(
			'parent' => $extbuddybox,
			'title'  => __( 'About/ Info', 'buddypress-toolbar' ),
			'href'   => network_admin_url( 'index.php?page=buddybox-about' ),
			'meta'   => array( 'target' => '', 'title' => __( 'About/ Info', 'buddypress-toolbar' ) )
		);

}  // end-if BuddyBox


/**
 * BuddyPress Docs (free, by Boone Gorges)
 *
 * @since 1.2.0
 */
if ( ( ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'buddypress-docs/loader.php' ) ) || defined( 'BP_DOCS_VERSION' ) ) && current_user_can( $bptb_filter_capability ) ) {

	/** Entries at "Extensions" level submenu */
	$menu_items['extbpdocs'] = array(
		'parent' => $extensions,
		'title'  => __( 'BuddyPress Docs', 'buddypress-toolbar' ),
		'href'   => admin_url( 'edit.php?post_type=bp_doc' ),
		'meta'   => array( 'target' => '', 'title' => __( 'BuddyPress Docs', 'buddypress-toolbar' ) )
	);

	$menu_items['extbpdocs-add'] = array(
		'parent' => $extbpdocs,
		'title'  => __( 'Add new BP-Doc', 'buddypress-toolbar' ),
		'href'   => admin_url( 'post-new.php?post_type=bp_doc' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Add new BP-Doc', 'buddypress-toolbar' ) )
	);

	$menu_items['extbpdocs-tags'] = array(
		'parent' => $extbpdocs,
		'title'  => __( 'Docs Tags', 'buddypress-toolbar' ),
		'href'   => admin_url( 'edit-tags.php?taxonomy=bp_docs_tag&post_type=bp_doc' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Docs Tags', 'buddypress-toolbar' ) )
	);

	$menu_items['extbpdocs-items'] = array(
		'parent' => $extbpdocs,
		'title'  => __( 'Associated Items', 'buddypress-toolbar' ),
		'href'   => admin_url( 'edit-tags.php?taxonomy=bp_docs_associated_item&post_type=bp_doc' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Associated Items', 'buddypress-toolbar' ) )
	);

}  // end-if BP Docs


/**
 * Breadcrumbs Everywhere (free, by Betsy Kimak)
 * Requires plugin version 1.1 or higher!
 *
 * @since 1.2.0
 */
if ( ( ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'breadcrumbs-everywhere/loader.php' ) ) || defined( 'CRUMBS_PLUGIN_NAME' ) ) && current_user_can( 'manage_options' ) ) {

	/** Entries at "Extensions" level submenu */
	$menu_items['ext-breadcrumbseverywhere'] = array(
		'parent' => $extensions,
		'title'  => __( 'Breadcrumbs Everywhere', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=crumbs-settings' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Breadcrumbs Everywhere', 'buddypress-toolbar' ) )
	);

}  // end-if Breadcrumbs Everywhere


/**
 * Buddypress User Account Type Lite (free, by Rimon Habib)
 *
 * @since 1.1.0
 */
if ( ( ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'buddypress-user-account-type-lite/buddypress-user-account-type.php' ) ) || function_exists( 'buat_activate' ) ) && current_user_can( 'administrator' ) ) {

	/** Backwards compatibility */
	if ( function_exists( 'buat_get_all_users' ) ) {
		$bptb_bpuatl_aurl = network_admin_url( 'admin.php?page=bp-user-account-type' );
	} else {
		$bptb_bpuatl_aurl = network_admin_url( 'admin.php?page=user-account-type' );
	}

	/** Entries at "Extensions" level submenu */
	$menu_items['extbpuatl'] = array(
		'parent' => $extensions,
		'title'  => __( 'User Account Type Lite', 'buddypress-toolbar' ),
		'href'   => $bptb_bpuatl_aurl,
		'meta'   => array( 'target' => '', 'title' => __( 'User Account Type Lite', 'buddypress-toolbar' ) )
	);

	/** "Roles" item, since plugin v2.2 */
	if ( function_exists( 'buat_get_all_users' ) ) {

		$menu_items['extbpuatl-roles'] = array(
			'parent' => $extbpuatl,
			'title'  => __( 'Roles', 'buddypress-toolbar' ),
			'href'   => network_admin_url( 'admin.php?page=bp-user-account-type-role' ),
			'meta'   => array( 'target' => '', 'title' => __( 'Roles', 'buddypress-toolbar' ) )
		);

	}  // end-if plugin version check

	/** Entries at "Profile Fields" level submenu */
	$menu_items['bppfieldsbpuatl'] = array(
		'parent' => $bppfields,
		'title'  => __( 'User Account Type Lite', 'buddypress-toolbar' ),
		'href'   => $bptb_bpuatl_aurl,
		'meta'   => array( 'target' => '', 'title' => __( 'User Account Type Lite', 'buddypress-toolbar' ) )
	);

	/** "Roles" item, since plugin v2.2 */
	if ( function_exists( 'buat_get_all_users' ) ) {

		$menu_items['bppfieldsbpuatl-roles'] = array(
			'parent' => $bppfieldsbpuatl,
			'title'  => __( 'Roles', 'buddypress-toolbar' ),
			'href'   => network_admin_url( 'admin.php?page=bp-user-account-type-role' ),
			'meta'   => array( 'target' => '', 'title' => __( 'Roles', 'buddypress-toolbar' ) )
		);

	}  // end-if plugin version check

}  // end-if BP User Account Type Lite


/**
 * CD BuddyPress Avatar Bubble (free, by slaFFik + valant)
 *
 * @since 1.2.0
 */
if ( ( ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'cd-bp-avatar-bubble/cd-avatar-bubble.php' ) ) || defined( 'CD_AB_VERSION' ) ) && current_user_can( 'manage_options' ) ) {

	/** Entries at "Extensions" level submenu */
	$menu_items['ext-cdavatar'] = array(
		'parent' => $extensions,
		'title'  => __( 'CD Avatar Bubble', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=cd-ab-admin' ),
		'meta'   => array( 'target' => '', 'title' => __( 'CD Avatar Bubble', 'buddypress-toolbar' ) )
	);

	/** Entries at "Users" level submenu */
	$menu_items['u-cdavatar'] = array(
		'parent' => $users,
		'title'  => __( 'CD Avatar Bubble', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=cd-ab-admin' ),
		'meta'   => array( 'target' => '', 'title' => __( 'CD Avatar Bubble', 'buddypress-toolbar' ) )
	);

}  // end-if CD BuddyPress Avatar Bubble


/**
 * sxss Buddypress Shared Friends (free, by sxss)
 *
 * @since 1.2.0
 */
if ( ( ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'buddypress-shared-friends/sxss-shared-friends.php' ) ) || function_exists( 'sxss_sf_same_friends' ) ) && ( current_user_can( 'manage_options' ) || current_user_can( 'administrator' ) ) ) {

	/** Entries at "Extensions" level submenu */
	$menu_items['ext-bpsharedfriends'] = array(
		'parent' => $extensions,
		'title'  => __( 'Shared Friends', 'buddypress-toolbar' ),
		'href'   => admin_url( 'options-general.php?page=sxss_sf' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Shared Friends', 'buddypress-toolbar' ) )
	);

	/** Entries at "Users" level submenu */
	$menu_items['u-bpsharedfriends'] = array(
		'parent' => $users,
		'title'  => __( 'Shared Friends', 'buddypress-toolbar' ),
		'href'   => admin_url( 'options-general.php?page=sxss_sf' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Shared Friends', 'buddypress-toolbar' ) )
	);

}  // end-if BP Shared Friends


/**
 * BuddyPress Media (free, by rtCamp)
 *
 * @since 1.4.0
 */
if ( ( defined( 'BP_MEDIA_IS_INSTALLED' ) || defined( 'BP_MEDIA_VERSION' ) ) && current_user_can( 'manage_options' ) ) {

	/** Include plugin file with BuddyPress Media items stuff */
	require_once( BPTB_PLUGIN_DIR . '/includes/bptb-plugins-buddypressmedia.php' );

}  // end-if BP Media


/**
 * BuddyPress BP Gallery (free, by Caevan Sachinwalla)
 *
 * @since 1.5.0
 */
if ( function_exists( 'bp_album_install' ) && current_user_can( 'administrator' ) ) {

	$menu_items['ext-bpgallery'] = array(
		'parent' => $extensions,
		'title'  => __( 'BP Gallery Settings', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=bp-phototag-settings' ),
		'meta'   => array( 'target' => '', 'title' => __( 'BP Gallery Settings', 'buddypress-toolbar' ) )
	);

}  // end-if BP Gallery


/**
 * BuddyPress Custom Profile Menu (free, by Sensible Plugins) (free lite version!)
 *
 * @since 1.5.0
 */
if ( defined( 'SP_BPCPM_BASENAME' ) && current_user_can( 'manage_options' ) ) {

	$menu_items['ext-bpcpmenu'] = array(
		'parent' => $extensions,
		'title'  => __( 'Custom Profile Menu', 'buddypress-toolbar' ),
		'href'   => admin_url( 'options-general.php?page=SP_BPCPM_PluginSettings' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Custom Profile Menu', 'buddypress-toolbar' ) )
	);

}  // end-if BP Custom Profile Menu


/**
 * BuddyPress Mobile (free, by modemlooper)
 *
 * @since 1.4.0
 */
if ( class_exists( 'bpMobilePlugin' ) && current_user_can( 'manage_options' ) ) {

	$menu_items['ext-bpmobile'] = array(
		'parent' => $extensions,
		'title'  => __( 'Mobile Settings', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=bp-mobile' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Mobile Settings', 'buddypress-toolbar' ) )
	);

}  // end-if BP Mobile


/**
 * BP Profiles Statistics (premium, by slaFFik)
 * Requires plugin version 1.1 or higher!
 *
 * @since 1.2.0
 */
if ( ( ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'bp-profiles-stat/bp-profiles-stat.php' ) ) || defined( 'BPPS_VERSION' ) ) && current_user_can( 'edit_users' ) ) {

	/** Entries at "Extensions" level submenu */
	$menu_items['extbpprofilesstats'] = array(
		'parent' => $extensions,
		'title'  => __( 'Profiles Statistics', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'users.php?page=bpps_admin' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Profiles Statistics', 'buddypress-toolbar' ) )
	);

	$menu_items['extbpprofilesstats-profiles'] = array(
		'parent' => $extbpprofilesstats,
		'title'  => __( 'Profiles', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'users.php?page=bpps_admin&tab=bpps_admin_profiles' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Profiles', 'buddypress-toolbar' ) )
	);

	$menu_items['extbpprofilesstats-activitytime'] = array(
		'parent' => $extbpprofilesstats,
		'title'  => __( 'Activity Time', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'users.php?page=bpps_admin&tab=bpps_admin_activity_time' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Activity Time', 'buddypress-toolbar' ) )
	);

	$menu_items['extbpprofilesstats-support'] = array(
		'parent' => $extbpprofilesstats,
		'title'  => __( 'Support &amp; Author', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'users.php?page=bpps_admin&tab=bpps_admin_info' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Support &amp; Author', 'buddypress-toolbar' ) )
	);

	/** Entries at "Users" level submenu */
	$menu_items['usersbpprofilesstats'] = array(
		'parent' => $users,
		'title'  => __( 'Profiles Statistics', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'users.php?page=bpps-admin' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Profiles Statistics', 'buddypress-toolbar' ) )
	);

	$menu_items['usersbpprofilesstats-profiles'] = array(
		'parent' => $usersbpprofilesstats,
		'title'  => __( 'Profiles', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'users.php?page=bpps_admin&tab=bpps_admin_profiles' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Profiles', 'buddypress-toolbar' ) )
	);

	$menu_items['usersbpprofilesstats-activitytime'] = array(
		'parent' => $usersbpprofilesstats,
		'title'  => __( 'Activity Time', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'users.php?page=bpps_admin&tab=bpps_admin_activity_time' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Activity Time', 'buddypress-toolbar' ) )
	);

	$menu_items['usersbpprofilesstats-support'] = array(
		'parent' => $usersbpprofilesstats,
		'title'  => __( 'Support &amp; Author', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'users.php?page=bpps_admin&tab=bpps_admin_info' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Support &amp; Author', 'buddypress-toolbar' ) )
	);

}  // end-if BP Profiles Statistics


/**
 * BuddyStream (free, by Peter Hofman)
 *
 * @since 1.1.0
 */
if ( ( ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'buddystream/buddystream.php' ) ) || function_exists( 'buat_activate' ) ) && current_user_can( 'manage_options' ) ) {

	$menu_items['extbuddystream'] = array(
		'parent' => $extensions,
		'title'  => __( 'BuddyStream', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=buddystream_admin' ),
		'meta'   => array( 'target' => '', 'title' => __( 'BuddyStream', 'buddypress-toolbar' ) )
	);

	$menu_items['extbuddystream-dashboard'] = array(
		'parent' => $extbuddystream,
		'title'  => __( 'Dashboard', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=buddystream_admin' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Dashboard', 'buddypress-toolbar' ) )
	);

	$menu_items['extbuddystream-powercentral'] = array(
		'parent' => $extbuddystream,
		'title'  => __( 'Powercentral', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=buddystream_admin&settings=powercentral' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Powercentral', 'buddypress-toolbar' ) )
	);

	$menu_items['extbuddystream-settings'] = array(
		'parent' => $extbuddystream,
		'title'  => __( 'General Settings', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=buddystream_admin&settings=general' ),
		'meta'   => array( 'target' => '', 'title' => __( 'General Settings', 'buddypress-toolbar' ) )
	);

	$menu_items['extbuddystream-cronjob'] = array(
		'parent' => $extbuddystream,
		'title'  => __( 'Cronjob Settings', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=buddystream_admin&settings=cronjob' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Cronjob Settings', 'buddypress-toolbar' ) )
	);

	$menu_items['extbuddystream-logs'] = array(
		'parent' => $extbuddystream,
		'title'  => __( 'Logs', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=buddystream_admin&settings=log' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Logs', 'buddypress-toolbar' ) )
	);

	$menu_items['extbuddystream-tour'] = array(
		'parent' => $extbuddystream,
		'title'  => __( 'Start Tour', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=buddystream_admin&action=starttour' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Start Tour', 'buddypress-toolbar' ) )
	);

}  // end-if BuddyStream


/**
 * BuddyPress Achievements v1.x/ v2.x (free, by Paul Gibbs)
 *
 * @since  1.1.0
 */
if ( defined( 'ACHIEVEMENTS_DB_VERSION' ) && current_user_can( 'manage_options' ) ) {

	/** Include plugin file with BuddyPress Achievements v1.x/ v2.x items stuff */
	require_once( BPTB_PLUGIN_DIR . '/includes/bptb-plugins-achievements2x.php' );

}  // end-if BP Achievements v1.x/ v2.x


/**
 * Achievements (App) v3.x (free, by Paul Gibbs)
 *
 * @since  1.6.0
 *
 * @global mixed $bp
 */
if ( class_exists( 'DPA_Achievements_Loader' ) && current_user_can( 'manage_options' ) ) {

	/** Include plugin file with Achievements (App) v3.x items stuff */
	require_once( BPTB_PLUGIN_DIR . '/includes/bptb-plugins-achievementsapp.php' );

}  // end-if BP Achievements v1.x/ v2.x


/**
 * BP Polls (free, by Themekraft)
 *
 * @since 1.4.0
 */
if ( defined( 'VPL_ALL_POLLS_PAGE' ) && current_user_can( 'manage_options' ) && ! is_network_admin() ) {

	/** Set $bp global */
	global $bp;

	/** Set "Polls" root slug */
	$bptb_polls_root_slug = $bp->pages->all_polls->slug;

	/** Entries at "Extensions" level submenu */
	$menu_items['ext-bppolls'] = array(
		'parent' => $extensions,
		'title'  => __( 'BP Polls Settings', 'buddypress-toolbar' ),
		'href'   => admin_url( 'options-general.php?page=vpl-bp-polls' ),
		'meta'   => array( 'target' => '', 'title' => __( 'BP Polls Settings', 'buddypress-toolbar' ) )
	);

	/** Entries at "Components Frontend Pages" level submenu */
	$menu_items['spages-polls'] = array(
		'parent' => $spages,
		'title'  => __( 'Polls Roots Page', 'buddypress-toolbar' ),
		'href'   => get_home_url() . '/' . $bptb_polls_root_slug,
		'meta'   => array( 'target' => '', 'title' => _x( 'Frontend: Polls Roots Page', 'Translators: For the tooltip', 'buddypress-toolbar' ) )
	);

}  // end-if BP Polls


/**
 * BuddyPress MyMood (free, by Ayush)
 *
 * @since 1.2.0
 */
if ( ( ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'buddypress-mymood/loader.php' ) ) || defined( 'BP_MYMOOD_VERSION' ) ) && current_user_can( 'manage_options' ) && ! is_network_admin() ) {

	$menu_items['ext-bpmymood'] = array(
		'parent' => $extensions,
		'title'  => __( 'MyMood Settings', 'buddypress-toolbar' ),
		'href'   => admin_url( 'admin.php?page=buddypress-mymood/buddypress-mymood_admin.php' ),
		'meta'   => array( 'target' => '', 'title' => __( 'MyMood Settings', 'buddypress-toolbar' ) )
	);

}  // end-if BP MyMood


/**
 * Invite Anyone (free, by Boone Gorges)
 *
 * @since 1.1.0
 */
if ( ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'invite-anyone/invite-anyone.php' ) ) || function_exists( 'invite_anyone_init' ) ) {

	/** Entries at "Extensions" level submenu */
	if ( current_user_can( 'manage_options' ) ) {
		$menu_items['extinviteanyone'] = array(
			'parent' => $extensions,
			'title'  => __( 'Invite Anyone Settings', 'buddypress-toolbar' ),
			'href'   => network_admin_url( 'admin.php?page=invite-anyone&subpage=general-settings' ),
			'meta'   => array( 'target' => '', 'title' => __( 'Invite Anyone Settings', 'buddypress-toolbar' ) )
		);

		$menu_items['extinviteanyone-access'] = array(
			'parent' => $extinviteanyone,
			'title'  => __( 'Access Control', 'buddypress-toolbar' ),
			'href'   => network_admin_url( 'admin.php?page=invite-anyone&subpage=access-control' ),
			'meta'   => array( 'target' => '', 'title' => __( 'Access Control', 'buddypress-toolbar' ) )
		);

		$menu_items['extinviteanyone-cloudsponge'] = array(
			'parent' => $extinviteanyone,
			'title'  => __( 'CloudSponge', 'buddypress-toolbar' ),
			'href'   => network_admin_url( 'admin.php?page=invite-anyone&subpage=cloudsponge' ),
			'meta'   => array( 'target' => '', 'title' => __( 'CloudSponge', 'buddypress-toolbar' ) )
		);

		$menu_items['extinviteanyone-manage'] = array(
			'parent' => $extinviteanyone,
			'title'  => __( 'Manage Invitations', 'buddypress-toolbar' ),
			'href'   => network_admin_url( 'admin.php?page=invite-anyone&subpage=manage-invitations' ),
			'meta'   => array( 'target' => '', 'title' => __( 'Manage Invitations', 'buddypress-toolbar' ) )
		);

		$menu_items['extinviteanyone-stats'] = array(
			'parent' => $extinviteanyone,
			'title'  => __( 'Stats', 'buddypress-toolbar' ),
			'href'   => network_admin_url( 'admin.php?page=invite-anyone&subpage=stats' ),
			'meta'   => array( 'target' => '', 'title' => __( 'Stats', 'buddypress-toolbar' ) )
		);

	}  // end-if cap check

	/** Entries at "Users" level submenu */
	if ( is_super_admin() ) {

		$menu_items['usersinviteanyone'] = array(
			'parent' => $users,
			'title'  => __( 'BP Invitations', 'buddypress-toolbar' ),
			'href'   => admin_url( 'edit.php?post_type=ia_invites' ),
			'meta'   => array( 'target' => '', 'title' => __( 'BP Invitations', 'buddypress-toolbar' ) )
		);

		$menu_items['usersinviteanyone-add'] = array(
			'parent' => $usersinviteanyone,
			'title'  => __( 'Add new Invitation', 'buddypress-toolbar' ),
			'href'   => admin_url( 'post-new.php?post_type=ia_invites' ),
			'meta'   => array( 'target' => '', 'title' => __( 'Add new Invitation', 'buddypress-toolbar' ) )
		);

		$menu_items['usersinviteanyone-invitess'] = array(
			'parent' => $usersinviteanyone,
			'title'  => __( 'Invitees', 'buddypress-toolbar' ),
			'href'   => admin_url( 'edit-tags.php?taxonomy=ia_invitees&post_type=ia_invites' ),
			'meta'   => array( 'target' => '', 'title' => __( 'Invitees', 'buddypress-toolbar' ) )
		);

		$menu_items['usersinviteanyone-invitedgroups'] = array(
			'parent' => $usersinviteanyone,
			'title'  => __( 'Invited Groups', 'buddypress-toolbar' ),
			'href'   => admin_url( 'edit-tags.php?taxonomy=ia_invited_groups&post_type=ia_invites' ),
			'meta'   => array( 'target' => '', 'title' => __( 'Invited Groups', 'buddypress-toolbar' ) )
		);

	}  // end-if cap check

}  // end-if Invite Anyone


/**
 * CollabPress (free, by WebDevStudios)
 *
 * @since 1.2.0
 */
if ( ( ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'collabpress/cp-loader.php' ) ) || defined( 'CP_DASHBOARD' ) ) && current_user_can( $cp_user_role ) ) {

	/** Include plugin file with CollabPress items stuff */
	require_once( BPTB_PLUGIN_DIR . '/includes/bptb-plugins-collabpress.php' );

}  // end-if CollabPress


/**
 * Events Manager (free, by Marcus Sykes)
 *
 * @since 1.1.0
 */
if ( ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'events-manager/events-manager.php' ) ) || class_exists( 'EM_Object' ) ) {

	/** Include plugin file with Events Manager items stuff */
	require_once( BPTB_PLUGIN_DIR . '/includes/bptb-plugins-eventsmanager.php' );

}  // end-if Events Manager


/**
 * BP Profile Search (free, by Andrea Tarantini)
 *
 * @since 1.3.0
 */
if ( ( ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'bp-profile-search/bps-main.php' ) ) || function_exists( 'bps_init' ) ) && current_user_can( 'manage_options' ) ) {

	$menu_items['extbpprofilesearch'] = array(
		'parent' => $extensions,
		'title'  => __( 'BP Profile Search', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=bp-profile-search' ),
		'meta'   => array( 'target' => '', 'title' => __( 'BP Profile Search', 'buddypress-toolbar' ) )
	);

	$menu_items['extbpprofilesearch-advanced'] = array(
		'parent' => $extbpprofilesearch,
		'title'  => __( 'Advanced Options', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=bp-profile-search&tab=options' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Advanced Options', 'buddypress-toolbar' ) )
	);

}  // end-if BP Profile Search


/**
 * BuddyPress Share (free, by modemlooper)
 *
 * @since 1.4.0
 */
if ( function_exists( 'bp_share_it_init' ) && current_user_can( 'manage_options' ) ) {

	$menu_items['ext-bpshare'] = array(
		'parent' => $extensions,
		'title'  => __( 'BuddyShare Settings', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=bp-share-it' ),
		'meta'   => array( 'target' => '', 'title' => __( 'BuddyShare Settings', 'buddypress-toolbar' ) )
	);

}  // end-if BP Share


/**
 * BuddyPress Twitter (free, by Charl Kruger)
 *
 * @since 1.1.0
 */
if ( ( ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'buddypress-twitter/loader.php' ) ) || function_exists( 'bp_twittercj_init' ) ) && current_user_can( 'manage_options' ) ) {

	$menu_items['ext-bptwitter'] = array(
		'parent' => $extensions,
		'title'  => __( 'BP Twitter Settings', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=bp-twittercj' ),
		'meta'   => array( 'target' => '', 'title' => __( 'BP Twitter Settings', 'buddypress-toolbar' ) )
	);

}  // end-if BP Twitter


/**
 * BP Code Snippets (free, by imath)
 *
 * @since 1.1.0
 */
if ( ( ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'bp-code-snippets/bp-code-snippets.php' ) ) || function_exists( 'bp_code_snippets_init' ) ) && current_user_can( 'manage_options' ) ) {

	$menu_items['ext-bptwitter'] = array(
		'parent' => $extensions,
		'title'  => __( 'BP Code Snippets Manager', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=bp-cs-admin' ),
		'meta'   => array( 'target' => '', 'title' => __( 'BP Code Snippets Manager', 'buddypress-toolbar' ) )
	);

}  // end-if BP Code Snippets


/**
 * SeoPress (free, by Sven Lehnert + Sven Wagener at ThemeKraft)
 *
 * @since 1.2.0
 */
if ( ( ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'seopress/seopress.php' ) ) || class_exists( 'SP_CORE' ) ) && current_user_can( 'manage_options' ) ) {

	$menu_items['extseopress'] = array(
		'parent' => $extensions,
		'title'  => __( 'SeoPress Page Types', 'buddypress-toolbar' ),
		'href'   => admin_url( 'admin.php?page=seopress_seo' ),
		'meta'   => array( 'target' => '', 'title' => __( 'SeoPress Page Types', 'buddypress-toolbar' ) )
	);

	$menu_items['extseopress-options'] = array(
		'parent' => $extseopress,
		'title'  => __( 'Options', 'buddypress-toolbar' ),
		'href'   => admin_url( 'admin.php?page=seopress_seo' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Options', 'buddypress-toolbar' ) )
	);

	$menu_items['extseopress-tickets'] = array(
		'parent' => $extseopress,
		'title'  => __( 'Bug Tracker / Tickets', 'buddypress-toolbar' ),
		'href'   => 'https://github.com/Themekraft/SeoPress/issues',
		'meta'   => array( 'title' => __( 'Bug Tracker / Tickets', 'buddypress-toolbar' ) )
	);

}  // end-if SeoPress


/**
 * WangGuard (free, by WangGuard Team)
 *
 * @since 1.0.0
 */
if ( ( ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'wangguard/wangguard-admin.php' ) ) || function_exists( 'wangguard_init' ) ) && current_user_can( 'manage_options' ) ) {

	/** Entries at "Users" level submenu */
	$menu_items['userswangguard'] = array(
		'parent' => $users,
		'title'  => __( 'WangGuard Moderation Queue', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=wangguard_queue' ),
		'meta'   => array( 'target' => '', 'title' => __( 'WangGuard Moderation Queue', 'buddypress-toolbar' ) )
	);

	$menu_items['userswangguard-users'] = array(
		'parent' => $users,
		'title'  => __( 'WangGuard Users', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=wangguard_users' ),
		'meta'   => array( 'target' => '', 'title' => __( 'WangGuard Users', 'buddypress-toolbar' ) )
	);

	$menu_items['userswangguard-wizard'] = array(
		'parent' => $users,
		'title'  => __( 'WangGuard Wizard', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=wangguard_wizard' ),
		'meta'   => array( 'title' => __( 'WangGuard Wizard', 'buddypress-toolbar' ) )
	);

	$menu_items['userswangguard-stats'] = array(
		'parent' => $users,
		'title'  => __( 'WangGuard Statistics', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=wangguard_stats' ),
		'meta'   => array( 'title' => __( 'WangGuard Statistics', 'buddypress-toolbar' ) )
	);

	/** Entries at "Extensions" level submenu */
	$menu_items['extwangguard'] = array(
		'parent' => $extensions,
		'title'  => __( 'WangGuard Moderation Queue', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=wangguard_queue' ),
		'meta'   => array( 'target' => '', 'title' => __( 'WangGuard Moderation Queue', 'buddypress-toolbar' ) )
	);

	$menu_items['extwangguard-users'] = array(
		'parent' => $extwangguard,
		'title'  => __( 'WangGuard Users', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=wangguard_users' ),
		'meta'   => array( 'target' => '', 'title' => __( 'WangGuard Users', 'buddypress-toolbar' ) )
	);

	$menu_items['extwangguard-wizard'] = array(
		'parent' => $extwangguard,
		'title'  => __( 'Wizard', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=wangguard_wizard' ),
		'meta'   => array( 'title' => __( 'Wizard', 'buddypress-toolbar' ) )
	);

	$menu_items['extwangguard-stats'] = array(
		'parent' => $extwangguard,
		'title'  => __( 'Statistics', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=wangguard_stats' ),
		'meta'   => array( 'title' => __( 'Statistics', 'buddypress-toolbar' ) )
	);

	$menu_items['extwangguard-config'] = array(
		'parent' => $extwangguard,
		'title'  => __( 'Configuration', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'admin.php?page=wangguard_conf' ),
		'meta'   => array( 'title' => __( 'Configuration', 'buddypress-toolbar' ) )
	);

}  // end-if WangGuard


/**
 * Members (free, by Justin Tadlock)
 *
 * @since 1.0.0
 */
if ( ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'members/members.php' ) ) && current_user_can( 'edit_roles' ) ) {

	/** Entries at "Users" level submenu */
	/** Edit Roles */
	if ( current_user_can( 'edit_roles' ) ) {

		$menu_items['u-members'] = array(
			'parent' => $users,
			'title'  => __( 'Members: Adjust Roles &amp; Capabilities', 'buddypress-toolbar' ),
			'href'   => admin_url( 'users.php?page=roles' ),
			'meta'   => array( 'target' => '', 'title' => __( 'Members: Adjust Roles &amp; Capabilities', 'buddypress-toolbar' ) )
		);

	}  // end-if cap check

	/** Add new Role */
	if ( current_user_can( 'create_roles' ) ) {

		$menu_items['u-members-add'] = array(
			'parent' => $users,
			'title'  => __( 'Members: Add new Role', 'buddypress-toolbar' ),
			'href'   => admin_url( 'users.php?page=role-new' ),
			'meta'   => array( 'title' => __( 'Members: Add new Role', 'buddypress-toolbar' ) )
		);

	}  // end-if cap check

}  // end-if Members


/**
 * Members Import (free, by Manish Kumar Agarwal)
 *
 * @since 1.5.0
 */
if ( function_exists( 'memberimport_menu' ) && current_user_can( 'manage_options' ) ) {

	/** Entries at "Extensions" level submenu */
	$menu_items['ext-membersimport'] = array(
		'parent' => $extensions,
		'title'  => __( 'Members Import', 'buddypress-toolbar' ),
		'href'   => admin_url( 'users.php?page=members-import' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Members Import', 'buddypress-toolbar' ) )
	);

	/** Entries at "Users" level submenu */
	$menu_items['u-membersimport'] = array(
		'parent' => $users,
		'title'  => __( 'Members Import', 'buddypress-toolbar' ),
		'href'   => admin_url( 'users.php?page=members-import' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Members Import', 'buddypress-toolbar' ) )
	);
}  // end-if Members Import


/**
 * BuddyPress xProfiles ACL Lite (free, by NetTantra)
 *
 * @since 1.2.0
 */
if ( ( ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'buddypress-xprofiles-acl/buddypress-xprofiles-acl.php' ) ) || class_exists( 'BPxProfileACL' ) ) && current_user_can( 'administrator' ) ) {

	/** Entries at "Extensions" level submenu */
	$menu_items['ext-bpxpacl'] = array(
		'parent' => $extensions,
		'title'  => __( 'Assign Profile Groups to User Roles', 'buddypress-toolbar' ),
		'href'   => admin_url( 'options-general.php?page=bp-profiles-acl-manager' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Assign Profile Groups to User Roles', 'buddypress-toolbar' ) )
	);

	/** Entries at "Users" level submenu */
	$menu_items['bppfields-bpxpacl'] = array(
		'parent' => $bppfields,
		'title'  => __( 'Assign Profile Groups to User Roles', 'buddypress-toolbar' ),
		'href'   => admin_url( 'options-general.php?page=bp-profiles-acl-manager' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Assign Profile Groups to User Roles', 'buddypress-toolbar' ) )
	);
}  // end-if BP xProfiles ACL


/**
 * BBG Record Blog Roles Changes (free, by Boone B. Gorges, slaFFik)
 *
 * @since 1.2.0
 */
if ( ( ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'bbg-record-blog-role-changes/bbg-record-blog-role-changes.php' ) ) || class_exists( 'BBG_RBRC' ) ) && current_user_can( 'edit_users' ) ) {

	/** Entries at "Users" level submenu */
	$menu_items['u-bbgrbrc'] = array(
		'parent' => $users,
		'title'  => __( 'Blog Roles Changes', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'users.php?page=rbrc-admin' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Recorded Blog Roles Changes', 'buddypress-toolbar' ) )
	);

	/** Entries at "Extensions" level submenu */
	$menu_items['ext-bbgrbrc'] = array(
		'parent' => $extensions,
		'title'  => __( 'Blog Roles Changes', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'users.php?page=rbrc-admin' ),
		'meta'   => array( 'target' => '', 'title' => __( 'Recorded Blog Roles Changes', 'buddypress-toolbar' ) )
	);

}  // end-if BBG Record Blog Roles Changes


/**
 * BuddyPress Login Redirect (free, by Jatinder Pal Singh)
 *
 * @since 1.3.0
 */
if ( ( ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'buddypress-login-redirect/bp-login-redirect.php' ) ) || function_exists( 'bp_login_redirect' ) ) && current_user_can( 'manage_options' ) ) {

	/** Entries at "Extensions" level submenu */
	$menu_items['ext-bploginredirect'] = array(
		'parent' => $extensions,
		'title'  => __( 'BP Login Redirect', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'options-general.php?page=blrmenu' ),
		'meta'   => array( 'target' => '', 'title' => __( 'BP Login Redirect', 'buddypress-toolbar' ) )
	);

}  // end-if BP Login Redirect


/**
 * BP Profile as Homepage (free, by Jatinder Pal Singh)
 *
 * @since 1.3.0
 */
if ( ( ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'bp-profile-as-homepage/bp_profile_as_homepage.php' ) ) || function_exists( 'bp_profile_homepage' ) ) && current_user_can( 'manage_options' ) ) {

	/** Entries at "Extensions" level submenu */
	$menu_items['ext-bpprofileashp'] = array(
		'parent' => $extensions,
		'title'  => __( 'BP Profile as Homepage', 'buddypress-toolbar' ),
		'href'   => network_admin_url( 'options-general.php?page=bpahpmenu' ),
		'meta'   => array( 'target' => '', 'title' => __( 'BP Profile as Homepage', 'buddypress-toolbar' ) )
	);

}  // end-if BP Profile as Homepage


/**
 * BP Trunk Junc (free, by modemlooper)
 *
 * @since 1.5.0
 */
if ( class_exists( 'BP_Trunk_Junc' ) && current_user_can( 'update_plugins' ) ) {

	/** Entries at "Extensions" level submenu */
	$menu_items['ext-bptrunkjunc'] = array(
		'parent' => $extensions,
		'title'  => __( 'BP Trunk Junc', 'buddypress-toolbar' ),
		'href'   => admin_url( 'tools.php?page=bp_beta_tester' ),
		'meta'   => array( 'target' => '', 'title' => _x( 'BP Trunk Junc - Beta Tester', 'Translators: For the tooltip', 'buddypress-toolbar' ) )
	);

	/** Entries at "BuddyPress Settings" level submenu */
	$menu_items['s-bptrunkjunc'] = array(
		'parent' => $bpsettings,
		'title'  => __( 'BP Trunk Junc', 'buddypress-toolbar' ),
		'href'   => admin_url( 'tools.php?page=bp_beta_tester' ),
		'meta'   => array( 'target' => '', 'title' => _x( 'BP Trunk Junc - Beta Tester', 'Translators: For the tooltip', 'buddypress-toolbar' ) )
	);

}  // end-if BP Trunk Junc