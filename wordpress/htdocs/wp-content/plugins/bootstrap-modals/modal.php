<?php
/*
Plugin Name: Bootstrap Modals
Plugin URI: http://coolestguidesontheplanet.com/use-bootstrap-modals-wordpress-themes/
Description: Using Bootstrap Modals in WordPress
Author: Neil Gee
Version: 1.0.0
Author URI:http://coolestguidesontheplanet.com
License:           GPL-2.0+
License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
*/



//Script-tac-ulous -> All the Scripts and Styles Registered and Enqueued, scripts first - then styles
function modal_bootstrap_scripts_styles() {

	wp_register_script ( 'modaljs' , plugins_url( '/js/bootstrap.min.js',  __FILE__), array( 'jquery' ), '3.1.1', true );
	wp_register_style ( 'modalcss' , plugins_url( '/css/bootstrap.css',  __FILE__), '' , '3.1.1', 'all' );
	

	wp_enqueue_script( 'modaljs' );
	wp_enqueue_style( 'modalcss' );
}

add_action( 'wp_enqueue_scripts', 'modal_bootstrap_scripts_styles' );