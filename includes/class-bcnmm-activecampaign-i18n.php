<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/al6ert
 * @since      1.0.0
 *
 * @package    Bcnmm_Activecampaign
 * @subpackage Bcnmm_Activecampaign/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Bcnmm_Activecampaign
 * @subpackage Bcnmm_Activecampaign/includes
 * @author     Albert Perez <albertperez@protonmail.com>
 */
class Bcnmm_Activecampaign_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'bcnmm-activecampaign',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
