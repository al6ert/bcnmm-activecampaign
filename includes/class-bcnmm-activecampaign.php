<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/al6ert
 * @since      1.0.0
 *
 * @package    Bcnmm_Activecampaign
 * @subpackage Bcnmm_Activecampaign/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Bcnmm_Activecampaign
 * @subpackage Bcnmm_Activecampaign/includes
 * @author     Albert Perez <albertperez@protonmail.com>
 */
class Bcnmm_Activecampaign {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Bcnmm_Activecampaign_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'BCNMM_ACTIVECAMPAIGN_VERSION' ) ) {
			$this->version = BCNMM_ACTIVECAMPAIGN_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'bcnmm-activecampaign';		

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Bcnmm_Activecampaign_Loader. Orchestrates the hooks of the plugin.
	 * - Bcnmm_Activecampaign_i18n. Defines internationalization functionality.
	 * - Bcnmm_Activecampaign_Admin. Defines all hooks for the admin area.
	 * - Bcnmm_Activecampaign_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		
		// COMPOSER AUTOLOAD CLASSES
		//require_once BCNMM_ROOT . 'vendor/autoload.php';		

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bcnmm-activecampaign-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bcnmm-activecampaign-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-bcnmm-activecampaign-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-bcnmm-activecampaign-public.php';

		$this->loader = new Bcnmm_Activecampaign_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Bcnmm_Activecampaign_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Bcnmm_Activecampaign_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );		

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Bcnmm_Activecampaign_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_options_page' );
		//$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_codes_cpt_page');

		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_setting' );
		//$this->loader->add_action( 'init', $plugin_admin, 'register_codes_cpt' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Bcnmm_Activecampaign_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		// add tracking code
		$this->loader->add_action( 'wp_head', $plugin_public, 'add_tracking_code' );
		

		/**
		 * adding simple subcribe simple form and functionality
		 * 
		*/
		// add simple form shortcodes
		$this->loader->add_shortcode( 'bcnmm_ac_form', $plugin_public, 'bcnmm_activecampaign_simple_form' );
		// add post simple form hook
		$this->loader->add_action( 'admin_post_simple_form_submitted', $plugin_public, 'bcnmm_activecampaign_simple_form_submitted' );
		$this->loader->add_action( 'admin_post_nopriv_simple_form_submitted', $plugin_public, 'bcnmm_activecampaign_simple_form_submitted' );
		
		

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Bcnmm_Activecampaign_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
