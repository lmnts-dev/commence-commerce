<?php

/**
 * Plugin Name: Integration for Elementor forms - Clickup
 * Description: Easily connect and send data to Clickup from elementor forms.
 * Author: Webtica
 * Author URI: https://webtica.be/
 * Version: 1.0.4
 * Elementor tested up to: 3.8.0
 * Elementor Pro tested up to: 3.8.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

//load plugins functionallity and settings
require dirname(__FILE__).'/init-clickup-integration-action.php';
require dirname(__FILE__).'/includes/settings.php';

//Check if Elementor pro is installed
function webtica_clickup_integration_check_elementor_pro_is_active() {
	if ( !is_plugin_active('elementor-pro/elementor-pro.php') ) {
		echo "<div class='error'><p><strong>Clickup Elementor integration</strong> requires <strong> Elementor Pro plugin to be installed and activated</strong> </p></div>";
	}
}
add_action('admin_notices', 'webtica_clickup_integration_check_elementor_pro_is_active');