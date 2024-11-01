<?php
/*
Plugin Name: WP Pay Per View Divi
Plugin URI:  https://wppayperview.com
Description: Allows Divi users to add WP Pay Per View videos to their Divi website as a Divi module.
Version:     1.0.11
Author: WP Pay Per View
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wppv-divi
Domain Path: /languages
Requires at least: 3.9
Tested up to: 6.5

WP Pay Per View Divi is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

WP Pay Per View Divi is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with WP Pay Per View Divi. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

define('WPPV_DIVI_VERSION', '1.0.11.42');

if ( ! function_exists( 'wppv_divi_initialize_extension' )):
function wppv_divi_is_local_debug() {
	return strpos(home_url(), 'http://pc2pp.com') !== false || strpos(home_url(), 'http://wppv.com') !== false; 
}
if(wppv_divi_is_local_debug() && !defined('WPPV_DEBUG')) {
	define('WPPV_DEBUG', true);
}
/**
 * Creates the extension's main class instance.
 *
 * @since 1.0.0
 */
function wppv_divi_initialize_extension() {
	if(defined("WP_PAY_PER_VIEW_VER")) {
		require_once plugin_dir_path( __FILE__ ) . 'includes/WpPayPerViewDivi.php';
	} else {
		add_action( 'admin_notices', 'wppv_div_wppv_required_notices' );
	}
}
add_action( 'divi_extensions_init', 'wppv_divi_initialize_extension' );

function wppv_div_wppv_required_notices() {
	$notice_string = __('Wp Pay Per View Divi plugin is enabled but not effective. It requires <a href="https://wppayperview.com" target="_blank">WP Pay Per View</a> plugin in order to work.', 'wppv_divi');
	$html = sprintf( '<div class="notice notice-error"><p>%s</p></div>', $notice_string );
	$allowed_html = array(
		'div'      => array(
			'class'  => array(),
		),
		'p'     => array(),
		'a'      => array(
			'href'  => array(),
			'target' => array(),
		),
	);
	echo wp_kses($html, $allowed_html);
}

endif;
