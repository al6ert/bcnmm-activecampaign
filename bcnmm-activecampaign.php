<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/al6ert
 * @since             1.0.0
 * @package           Bcnmm_Activecampaign
 *
 * @wordpress-plugin
 * Plugin Name:       Barcelona Multimedia with Active Campagin
 * Plugin URI:        http://albertperez.dev
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Albert Perez
 * Author URI:        https://github.com/al6ert
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bcnmm-activecampaign
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'BCNMM_ACTIVECAMPAIGN_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-bcnmm-activecampaign-activator.php
 */
function activate_bcnmm_activecampaign() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bcnmm-activecampaign-activator.php';
	Bcnmm_Activecampaign_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-bcnmm-activecampaign-deactivator.php
 */
function deactivate_bcnmm_activecampaign() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bcnmm-activecampaign-deactivator.php';
	Bcnmm_Activecampaign_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_bcnmm_activecampaign' );
register_deactivation_hook( __FILE__, 'deactivate_bcnmm_activecampaign' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-bcnmm-activecampaign.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_bcnmm_activecampaign() {

	$plugin = new Bcnmm_Activecampaign();
	$plugin->run();

}
run_bcnmm_activecampaign();
