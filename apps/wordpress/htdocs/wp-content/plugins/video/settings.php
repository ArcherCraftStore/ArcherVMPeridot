<?php
/**
 * VideoPress settings. Customize player functionality.
 *
 * @package video
 * @category video
 * @author VideoPress
 * @link http://videopress.com/ VideoPress
 * @version 1.5
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Add video player settings to the blog's media settings page
 */
function video_player_customization_init() {
	if ( current_user_can('upload_files') ) {
		add_settings_section( 'video_player_customization', __('VideoPress player', 'video'), 'video_player_customization_callback', 'media' );
		add_settings_field( 'video_player_freedom', _x('Free formats', 'Free as in Freedom and open source', 'video'), 'video_player_freedom_callback', 'media', 'video_player_customization' );
		register_setting( 'media', 'video_player_freedom', 'video_player_option_sanitize' );

		add_settings_field( 'video_player_static', __('Static player', 'video'), 'video_player_static_callback', 'media', 'video_player_customization' );
		register_setting( 'media', 'video_player_static', 'video_player_option_sanitize' );

		add_settings_field( 'video_player_high_quality', __('Default quality setting'), 'video_player_high_quality_callback', 'media', 'video_player_customization' );
		register_setting( 'media', 'video_player_high_quality', 'video_player_option_sanitize' );
	}
}
add_action( 'admin_init', 'video_player_customization_init' );


/**
 * Fulfill the settings section callback requirement by returning nothing
 */
function video_player_customization_callback() {
	return;
}


/**
 * Display videos in only Freedom-loving formats.
 */
function video_player_freedom_callback() {
	if ( current_user_can('upload_files') ) {
		echo '<fieldset><legend class="screen-reader-text"><span>' . __( 'Only display video in formats free of patent claims and other restrictions on distribution and playback.', 'video' ) . '</span></legend>';
		echo '<div><input type="checkbox" name="video_player_freedom" id="video_player_freedom" value="1" ';
		if ( get_option( 'video_player_freedom', false ) )
			echo 'checked="checked" ';
		echo '/> <label for="video_player_freedom">' . _x('Only display videos in free software formats', 'Free as in Freedom and open source', 'video') . '</label></div>';
		echo '<div><small><em>' . __('Ogg file container with Theora video and Vorbis audio. Note that some browsers are unable to play free software video formats, including Internet Explorer and Safari.', 'video') . '</em></small></div>';
	}
}


/**
 * Prevent the dynamic player from being used if the owner prefers stronger download protection
 */
function video_player_static_callback() {
	if ( current_user_can('upload_files') ) {
		echo '<fieldset><legend class="screen-reader-text"><span>' . __( 'Prevent the dynamic player from being used if the video is marked as un-shared.', 'video' ) . '</span></legend>';
		echo '<div><input type="checkbox" name="video_player_static" id="video_player_static" value="1" ';
		if ( get_option( 'video_player_static', false ) )
			echo 'checked="checked" ';
		echo '/> <label for="video_player_static">' . __('Use the static Flash video player when playing un-shared videos', 'video') . '</label></div>';
		echo '<div><small><em>' . __('Enabling this option will give stronger protection from users downloading your videos, but will prevent your videos from playing on iOS and other Flash-free devices.', 'video') . '</em></small></div>';
	}
}

/**
 * Set a default high-quality playback option across all blog videos.
 */
function video_player_high_quality_callback() {
	if ( current_user_can('upload_files') ) {
		echo '<fieldset><legend class="screen-reader-text"><span>' . __('Load a higher quality video player by default if a higher quality than the default for a given player width is available.') . '</span></legend>';
		echo '<div><input type="checkbox" name="video_player_high_quality" id="video_player_high_quality" value="1" ';
		if ( (bool) get_option( 'video_player_high_quality', false ) )
			echo 'checked="checked" ';
		echo '/> <label for="video_player_high_quality">' . __('Display higher quality video by default.') . '</label></div>';
		echo '<div><small><em>(' . __('This setting may be overridden for individual videos') . ')</em></small></div>';
	}
}

/**
 * Convert checkbox value from int to bool
 *
 * @return bool true if checked, else false
 */
function video_player_option_sanitize( $checked ) {
	if ( $checked==1 )
		return true;
	else
		return false;
}
?>