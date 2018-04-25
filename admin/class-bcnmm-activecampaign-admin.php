<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Bcnmm_Activecampaign
 * @subpackage Bcnmm_Activecampaign/admin
 * @author     Albert Perez <albertperez@protonmail.com>
 */


class Bcnmm_Activecampaign_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;
	private $option_name = 'bcnmm_activecampaign';

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */

	private $version;
	/*		
		* The version of this plugin.
		*
		* @since    1.0.0
		* @access   private
		* @var      api_url, api_key, ac (object class)   The current version of this plugin.
	*/		
	private $api_key;
	private $list_id;
	private $form_id;
	private $credentials;	
	private $track_actid;
	private $track_key;	

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		if (!empty(get_option( $this->option_name . '_url', false ))) {
			$this->api_url = get_option( $this->option_name . '_url', false);
		}
		if (!empty(get_option( $this->option_name . '_key', false ))) {
			$this->api_key = get_option( $this->option_name . '_key', false);
		}
		
		if (!empty($this->api_url) && !empty($this->api_key)) {
			$ac = new ActiveCampaign($this->api_url, $this->api_key);			

			if (!(int)$ac->credentials_test()) {				
				$this->credentials = false;
			} else {
				$this->credentials = true;			

				if (!empty(get_option( $this->option_name . '_list_id', false))) {
					$this->list_id = get_option( $this->option_name . '_list_id', false);	
				}
				if (!empty(get_option( $this->option_name . '_form_id', false ))) {
					$this->form_id = get_option( $this->option_name . '_form_id', false);
				}
				if (!empty(get_option( $this->option_name . '_track_actid', false ))) {
					$this->track_actid = get_option( $this->option_name . '_track_actid', false);
				}
				if (!empty(get_option( $this->option_name . '_track_key', false ))) {
					$this->track_key = get_option( $this->option_name . '_track_key', false);
				}
				
			}
			
		}		
	}


	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Bcnmm_Activecampaign_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bcnmm_Activecampaign_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/bcnmm-activecampaign-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Bcnmm_Activecampaign_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bcnmm_Activecampaign_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/bcnmm-activecampaign-admin.js', array( 'jquery' ), $this->version, false );

	}	

	public function add_options_page() {
		
		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'BCNMM AC Settings', $this->plugin_name ),
			__( 'BCNMM AC', $this->plugin_name ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_options_page' )
		);
	}

	public function  settings_page() {
		echo 'This is the page content';
	}
	/**
	 * Render the options page for plugin
	 *
	 * @since  1.0.0
	 */
	public function display_options_page() {

		include_once 'partials/bcnmm-activecampaign-admin-display.php';

	}

	public function register_setting() {

		register_setting( $this->plugin_name, $this->option_name . '_url', "strval" );
		register_setting( $this->plugin_name, $this->option_name . '_key', "strval" );
		
		// Add a General section
		add_settings_section( 
			$this->option_name . '_credentials', 
			__( 'Credentials', $this->plugin_name ),
			array( $this, $this->option_name . '_credentials_cb' ),
			$this->plugin_name
		);
		

		add_settings_field(
			$this->option_name . '_url',
			__( 'Active Campaign API URL', $this->plugin_name ),
			array( $this, $this->option_name . '_url_cb' ),
			$this->plugin_name,
			$this->option_name . '_credentials',
			array( 'label_for' => $this->option_name . '_url' )
		);

		add_settings_field(
			$this->option_name . '_key',
			__( 'Active Campaign API KEY', $this->plugin_name ),
			array( $this, $this->option_name . '_key_cb' ),
			$this->plugin_name,
			$this->option_name . '_credentials',
			array( 'label_for' => $this->option_name . '_key' )
		);	


		

		if ($this->credentials) {

			register_setting( $this->plugin_name, $this->option_name . '_list_id', "intval" );
			register_setting( $this->plugin_name, $this->option_name . '_form_id', "intval" );
			register_setting( $this->plugin_name, $this->option_name . '_track_actid', "intval" );
			register_setting( $this->plugin_name, $this->option_name . '_track_key', "strval" );

			add_settings_section( 
				$this->option_name . '_list', 	// $id,
				__( 'List', $this->plugin_name), // $title,
				array($this, $this->option_name . '_list_cb'), // $callback,
				$this->plugin_name . "_credentials_valid" // $page
			);
			
			add_settings_field(
				$this->option_name . '_list_id',
				__( 'Active Campaign List', $this->plugin_name ),
				array( $this, $this->option_name . '_list_id_cb' ),
				$this->plugin_name . '_credentials_valid',
				$this->option_name . '_list',
				array( 'label_for' => $this->option_name . '_list_id' )
			);	

			add_settings_field(
				$this->option_name . '_form_id',
				__( 'Active Campaign Form', $this->plugin_name ),
				array( $this, $this->option_name . '_form_id_cb' ),
				$this->plugin_name . '_credentials_valid',
				$this->option_name . '_list',
				array( 'label_for' => $this->option_name . '_form_id' )
			);	

			add_settings_section( 
				$this->option_name . '_tracking', 	// $id,
				__( 'Tracking', $this->plugin_name), // $title,
				array($this, $this->option_name . '_tracking_cb'), // $callback,
				$this->plugin_name . "_credentials_valid" // $page
			);

			add_settings_field(
				$this->option_name . '_track_actid',
				__( 'Active Campaign Tracking Account ID', $this->plugin_name ),
				array( $this, $this->option_name . '_track_actid_cb' ),
				$this->plugin_name . '_credentials_valid',
				$this->option_name . '_tracking',
				array( 'label_for' => $this->option_name . '_track_actid' )
			);	

			add_settings_field(
				$this->option_name . '_track_key',
				__( 'Active Campaign Tracking KEY', $this->plugin_name ),
				array( $this, $this->option_name . '_track_key_cb' ),
				$this->plugin_name . '_credentials_valid',
				$this->option_name . '_tracking',
				array( 'label_for' => $this->option_name . '_track_key' )
			);	



		}		
	}	

	

	public function bcnmm_activecampaign_credentials_cb() {		
		echo '<p>' . __( 'Please change the settings accordingly.', $this->plugin_name ) . '</p>';
	}

	/**
	 * Render the url  input for this plugin
	 *
	 * @since  1.0.0
	 */
	public function bcnmm_activecampaign_url_cb() {		
		echo '<input type="text" class="regular-text" name="' . $this->option_name . '_url' . '" id="' . $this->option_name . '_url' . '" value="' . $this->api_url . '">';
	}

	/**
	 * Render the key  input for this plugin
	 *
	 * @since  1.0.0
	 */
	public function bcnmm_activecampaign_key_cb() {
		echo '<input type="text" class="regular-text" name="' . $this->option_name . '_key' . '" id="' . $this->option_name . '_key' . '" value="' . $this->api_key . '"> ';
	}


	public function bcnmm_activecampaign_list_cb() {		
		echo '<p>' . __( 'Please change the settings accordingly.', $this->plugin_name ) . '</p>';
	}
	/**
	 * Render the list  input for this plugin
	 *
	 * @since  1.0.0
	 */
	public function bcnmm_activecampaign_list_id_cb() {
		
		if ($this->credentials)	{			
			$ac = new ActiveCampaign($this->api_url, $this->api_key);
			$data =  array(	'ids' => 'all' );
			$lists = $ac->api("list/list", $data);			

			echo "<select id='" . $this->option_name . "_list_id'" . " name='" . $this->option_name . "_list_id'>";
			
			echo "<option selected='selected'></option>";			
			foreach($lists as $list) {				
				if (isset($list->name)) {
					$selected = ($list->id == $this->list_id) ? 'selected="selected"' : '';
					echo "<option value=" . $list->id . " $selected>" . $list->name . " | ID:" . $list->id . "</option>";
				}				
			}
			echo "</select>";

		}
		else {
			
		}		
	}

	public function bcnmm_activecampaign_form_id_cb() {
		
		if ($this->credentials)	{			
			$ac = new ActiveCampaign($this->api_url, $this->api_key);
			$data =  array(/*	'ids' => 'all' */);
			$forms = $ac->api("form/getforms", $data);					

			echo "<select id='" . $this->option_name . "_form_id'" . " name='" . $this->option_name . "_form_id'>";
			
			echo "<option selected='selected'></option>";			
			foreach($forms as $form) {				
				if (isset($form->name)) {
					$selected = ($form->id == $this->form_id) ? 'selected="selected"' : '';
					echo "<option value=" . $form->id . " $selected>" . $form->name . " | ID:" . $form->id . "</option>";
				}				
			}
			echo "</select>";

		}
		else {
			
		}		
	}

	public function bcnmm_activecampaign_tracking_cb() {		
		echo '<p>' . __( 'Please change the settings accordingly.', $this->plugin_name ) . '</p>';
	}

	public function bcnmm_activecampaign_track_actid_cb() {
		echo '<input type="text" name="' . $this->option_name . '_track_actid' . '" id="' . $this->option_name . '_track_actid' . '" value="' . $this->track_actid . '"> ';
	}

	public function bcnmm_activecampaign_track_key_cb() {
		echo '<input type="text" class="regular-text" name="' . $this->option_name . '_track_key' . '" id="' . $this->option_name . '_track_key' . '" value="' . $this->track_key . '"> ';
	}

	/*
	public function add_codes_cpt_page () {
		   		    	
	}

	public function register_codes_cpt() {

		$cap_type 	= 'post';
		$plural 	= 'Magnets';
		$single 	= 'Magnet';
		$cpt_name 	= 'magnet';
		$opts['can_export']								= TRUE;
		$opts['capability_type']						= $cap_type;
		$opts['description']							= '';
		$opts['exclude_from_search']					= FALSE;
		$opts['has_archive']							= FALSE;
		$opts['hierarchical']							= FALSE;
		$opts['map_meta_cap']							= TRUE;
		$opts['menu_icon']								= 'dashicons-filter';
		$opts['menu_position']							= 2;
		$opts['public']									= TRUE;
		$opts['publicly_querable']						= TRUE;
		$opts['query_var']								= TRUE;
		$opts['register_meta_box_cb']					= '';
		$opts['rewrite']								= FALSE;
		$opts['show_in_admin_bar']						= TRUE;
		$opts['show_in_menu']							= TRUE;
		$opts['show_in_nav_menu']						= TRUE;
		$opts['show_ui']								= TRUE;
		$opts['supports']								= array( 'title', 'editor', 'thumbnail' );
		$opts['taxonomies']								= array();
		$opts['capabilities']['delete_others_posts']	= "delete_others_{$cap_type}s";
		$opts['capabilities']['delete_post']			= "delete_{$cap_type}";
		$opts['capabilities']['delete_posts']			= "delete_{$cap_type}s";
		$opts['capabilities']['delete_private_posts']	= "delete_private_{$cap_type}s";
		$opts['capabilities']['delete_published_posts']	= "delete_published_{$cap_type}s";
		$opts['capabilities']['edit_others_posts']		= "edit_others_{$cap_type}s";
		$opts['capabilities']['edit_post']				= "edit_{$cap_type}";
		$opts['capabilities']['edit_posts']				= "edit_{$cap_type}s";
		$opts['capabilities']['edit_private_posts']		= "edit_private_{$cap_type}s";
		$opts['capabilities']['edit_published_posts']	= "edit_published_{$cap_type}s";
		$opts['capabilities']['publish_posts']			= "publish_{$cap_type}s";
		$opts['capabilities']['read_post']				= "read_{$cap_type}";
		$opts['capabilities']['read_private_posts']		= "read_private_{$cap_type}s";
		$opts['labels']['add_new']						= esc_html__( "Add New {$single}", $this->plugin_name );
		$opts['labels']['add_new_item']					= esc_html__( "Add New {$single}", $this->plugin_name );
		$opts['labels']['all_items']					= esc_html__( $plural, $this->plugin_name );
		$opts['labels']['edit_item']					= esc_html__( "Edit {$single}" , $this->plugin_name );
		$opts['labels']['menu_name']					= esc_html__( $plural, $this->plugin_name );
		$opts['labels']['name']							= esc_html__( $plural, $this->plugin_name );
		$opts['labels']['name_admin_bar']				= esc_html__( $single, $this->plugin_name );
		$opts['labels']['new_item']						= esc_html__( "New {$single}", $this->plugin_name );
		$opts['labels']['not_found']					= esc_html__( "No {$plural} Found", $this->plugin_name );
		$opts['labels']['not_found_in_trash']			= esc_html__( "No {$plural} Found in Trash", $this->plugin_name );
		$opts['labels']['parent_item_colon']			= esc_html__( "Parent {$plural} :", $this->plugin_name );
		$opts['labels']['search_items']					= esc_html__( "Search {$plural}", $this->plugin_name );
		$opts['labels']['singular_name']				= esc_html__( $single, $this->plugin_name );
		$opts['labels']['view_item']					= esc_html__( "View {$single}", $this->plugin_name );
		$opts['rewrite']['ep_mask']						= EP_PERMALINK;
		$opts['rewrite']['feeds']						= FALSE;
		$opts['rewrite']['pages']						= TRUE;
		$opts['rewrite']['slug']						= esc_html__( strtolower( $plural ), $this->plugin_name );
		$opts['rewrite']['with_front']					= FALSE;
		$opts = apply_filters( 'magnets-cpt-options', $opts );
		register_post_type( strtolower( $cpt_name ), $opts );

	}
	*/

}
