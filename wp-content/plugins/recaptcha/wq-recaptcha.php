<?php

/*

Plugin Name: Webqam recaptcha

Description: ajout Google Recaptcha V3

Version: 1.0

Author: Webqam

Author URI: https://www.webqam.fr/

 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}
/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('WQRECAPTCHA_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-WQRecaptcha-activator.php
 */
function activate_WQRecaptcha()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-WQRecaptcha-activator.php';
    WQRecaptcha_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-WQRecaptcha-deactivator.php
 */
function deactivate_WQRecaptcha()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-WQRecaptcha-deactivator.php';
    WQRecaptcha_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_WQRecaptcha');
register_deactivation_hook(__FILE__, 'deactivate_WQRecaptcha');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-WQRecaptcha.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_WQRecaptcha() {
    $plugin = new WQRecaptcha();
	$plugin->run();
}
run_WQRecaptcha();