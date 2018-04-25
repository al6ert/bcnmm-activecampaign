<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/al6ert
 * @since      1.0.0
 *
 * @package    Bcnmm_Activecampaign
 * @subpackage Bcnmm_Activecampaign/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Bcnmm_Activecampaign
 * @subpackage Bcnmm_Activecampaign/includes
 * @author     Albert Perez <albertperez@protonmail.com>
 */
class Bcnmm_Activecampaign_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		// ROOT constant to match the old ROOT constant.
		define( 'BCNMM_ROOT', 'YOUR WORK TREE' );
		$line = "define('BCNMM_ROOT', '" . 'YOUR WORK TREE' . "');";
		Bcnmm_Activecampaign_Activator::replace_config_line('define *\( *\'BCNMM_ROOT\'', $line );		
	}


	/**
	 * Helper function for writing to the wp-config.php file,
	 * taken from WP Super Cache.
	 *
	 * @access public
	 * @return boolean
	 */
	public static function replace_config_line( $old, $new, $file = '' ) {

		if ( $file === '' ) {
			if ( file_exists( ABSPATH . 'wp-config.php') ) {
				$file = ABSPATH . 'wp-config.php';
			} else {
				$file = dirname(ABSPATH) . '/wp-config.php';
			}
		}

		if ( @is_file( $file ) == false ) {
			return false;
		}
		if (!is_writeable( $file ) ) {
			return false;
		}

		$found = false;
		$lines = file($file);
		foreach( (array)$lines as $line ) {
		 	if ( preg_match("/$old/", $line)) {
				$found = true;
				break;
			}
		}
		if ($found) {
			/*
			$fd = fopen($file, 'w');
			foreach( (array)$lines as $line ) {
				if ( !preg_match("/$old/", $line))
					fputs($fd, $line);
				else {
					fputs($fd, "$new // Added by bcnmm\n");
				}
			}
			fclose($fd);
			*/
			return true;
		}
		$fd = fopen($file, 'w');
		$done = false;
		foreach( (array)$lines as $line ) {
			if ( $done || !preg_match('/^(if\ \(\ \!\ )?define|\$|\?>/', $line) ) {
				fputs($fd, $line);
			} else {
				fputs($fd, "$new // Added by bcnmm\n");
				fputs($fd, $line);
				$done = true;
			}
		}
		fclose($fd);
		return true;

	}

}
