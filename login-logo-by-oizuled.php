<?php
	 /*
	 Plugin Name: Login Logo Editor
	 Plugin URI: https://amplifyplugins.com
	 Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=VQMNHMR86QKNY
	 Description: A plugin to change the logo displayed on the admin login screen.
	 Version: 1.3.3
	 Author: AMP-MODE
	 Author URI: https://amplifyplugins.com
	 License: GPL2
	 */

	/*  Copyright 2013  Scott DeLuzio  (email : scott (at) amplifyplugins.com)

		This program is free software; you can redistribute it and/or modify
		it under the terms of the GNU General Public License, version 2, as
		published by the Free Software Foundation.

		This program is distributed in the hope that it will be useful,
		but WITHOUT ANY WARRANTY; without even the implied warranty of
		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
		GNU General Public License for more details.

		You should have received a copy of the GNU General Public License
		along with this program; if not, write to the Free Software
		Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	*/

/* Add language support */
function lle_text_domain() {
	load_plugin_textdomain( 'login_translate', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
}
add_action( 'init', 'lle_text_domain' );

/* Settings Page */

// Hook for adding admin menus
add_action( 'admin_menu', 'lle_add_settings_page' );

// action function for above hook
function lle_add_settings_page() {
	// Add a new submenu under Settings:
	add_options_page( __( 'Login Logo Options', 'login_translate' ), __( 'Login Logo', 'login_translate' ), 'manage_options', 'login_logo_editor', 'lle_settings_page' );
}

add_action( 'admin_init', 'lle_update_steps' );
/**
 * Note: This function is only necessary for the initial upgrade from 1.2.2 (or lower) to 1.3 (or higher)
 * @since 1.3
 */
function lle_update_steps(){
	// Removing old names
	$image	= get_option( 'oizuled-login-logo-img' );
	$link	= get_option( 'oizuled-login-logo-link' );
	$title	= get_option( 'oizuled-login-logo-title' );
	$css	= get_option( 'oizuled-login-logo-css' );

	if( $image ){
		update_option( 'lle_login_logo_img', $image );
		delete_option( 'oizuled-login-logo-img' );
	}
	if( $link ){
		update_option( 'lle_login_logo_link', $link );
		delete_option( 'oizuled-login-logo-link' );
	}
	if( $title ){
		update_option( 'lle_login_logo_title', $title );
		delete_option( 'oizuled-login-logo-title' );
	}
	if( $css ){
		update_option( 'lle_login_logo_img', $css );
		delete_option( 'oizuled-login-logo-css' );
	}
}

function lle_activate_plugin() {
	$wp_ver = get_bloginfo('version');
	if ( $wp_ver < 3.8 ) {
		add_option( 'lle_login_logo_img', plugins_url( 'wordpress-logo.png', __FILE__ ) );
	} else {
		add_option( 'lle_login_logo_img', plugins_url( 'w-logo-blue.png', __FILE__ ) );
	}
	add_option( 'lle_login_logo_link', 'http://wordpress.org' );
	add_option( 'lle_login_logo_title', 'Powered by WordPress' );
	add_option( 'lle_login_logo_css', '' );
}

function lle_deactivate_plugin() {
	delete_option( 'lle_login_logo_img' );
	delete_option( 'lle_login_logo_link' );
	delete_option( 'lle_login_logo_title' );
	delete_option( 'lle_login_logo_css' );
}

register_activation_hook( __FILE__, 'lle_activate_plugin' );
register_deactivation_hook( __FILE__, 'lle_deactivate_plugin' );

function lle_register_settings() {
	register_setting( 'lle-login-logo-option-group', 'lle_login_logo_img' );
	register_setting( 'lle-login-logo-option-group', 'lle_login_logo_link' );
	register_setting( 'lle-login-logo-option-group', 'lle_login_logo_title' );
	register_setting( 'lle-login-logo-option-group', 'lle_login_logo_css' );
}
add_action( 'admin_init', 'lle_register_settings' );

// Display the page content for the login logo submenu
function lle_settings_page() {
	include( dirname( __FILE__ ) . '/options.php');
}

/* Show Logo */
function lle_display_logo() {
	$logo		= get_option( 'lle_login_logo_img' );
	// Remove beginning '/' character if it exists. Otherwise getimagesize() function will fail.
	$logo		= ltrim( $logo, '/' );
	$css		= get_option( 'lle_login_logo_css' );
	/**
	 * This gets the dimensions of the image in an array
	 * 0 - width
	 * 1 - height
	 */
	$image		= getimagesize( $logo );
	$image_size	= '';
	/**
	 * getimagesize() will return false if dimensions cannot be obtained from the file provided.
	 * WordPress sets the background-image property of the link in order to display the logo.
	 * In this case we want to set the background-size to the width/height of the image provided.
	 * We also want to set the width/height of the link to be equal to the logo's width/height.
	 * Since the link is in a div element that is fixed to 320px wide, we need to adjust the positioning
	 * of our logo if the image is greater than 320px wide, otherwise the image will be aligned to the
	 * left side of the login box and stretch out past it. We'll take the 320px fixed width and subtract the
	 * width of the image, then divide by 2. This will be equal to half the difference between the
	 * 320 and the image width (a negative number), so we can set the left margin to this to center the image
	 * above the login box.
	 */
	if( $image ){
		$image_size = 'background-size: ' . $image[0] . 'px ' . $image[1] . 'px;
			width: ' . $image[0] . 'px;
			height: ' . $image[1] . 'px;';
		if( $image[0] > 320 ){
			$margin_left = ( 320 - $image[0] ) / 2;
			$image_size .= 'margin-left: ' . $margin_left . 'px;';
		}
	}
	// This should return similar CSS style to $image_size above with the width/height values you wish to use.
	$image_size = apply_filters( 'lle_logo_image_size_css', $image_size );
	echo '<style type="text/css">
		.login h1 a {
			background-image: url(' . $logo . ');
			' . $image_size . '
		}
		' . $css . '
		</style>';
}
add_action( 'login_head', 'lle_display_logo' );

add_filter( 'login_headerurl', 'lle_login_page_link' );
function lle_login_page_link( $url ) {
	return get_option( 'lle_login_logo_link' );
}

add_filter( 'login_headertext', 'lle_url_title' );
function lle_url_title() {
	return get_option( 'lle_login_logo_title' );
}

add_action( 'admin_enqueue_scripts', 'lle_logo_upload_script' );

function lle_logo_upload_script() {
	if ( isset( $_GET['page'] ) && 'login_logo_editor' == $_GET['page'] ) {
		wp_enqueue_media();
		wp_register_script( 'image-upload-js', plugin_dir_url( __FILE__ ) . '/image-upload.js', array( 'jquery' ) );
		wp_enqueue_script( 'image-upload-js' );
		wp_localize_script( 'image-upload-js', 'lle_script_translation', array(
			'title'		=> __( 'Choose Image', 'login_translate' ),
			'button'	=> __( 'Choose Image', 'login_translate' )
		));
	}
}

function lle_load_admin_styles( $hook ){
	//Only load on ?page=login_logo_editor
	if( 'settings_page_login_logo_editor' != $hook ){
		return;
	}
	wp_enqueue_style( 'lle_admin_styles', plugins_url( 'admin-style.css', __FILE__ ) );
}
add_action( 'admin_enqueue_scripts', 'lle_load_admin_styles' );