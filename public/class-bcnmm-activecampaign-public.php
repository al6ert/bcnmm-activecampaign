<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/al6ert
 * @since      1.0.0
 *
 * @package    Bcnmm_Activecampaign
 * @subpackage Bcnmm_Activecampaign/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Bcnmm_Activecampaign
 * @subpackage Bcnmm_Activecampaign/public
 * @author     Albert Perez <albertperez@protonmail.com>
 */


class Bcnmm_Activecampaign_Public {

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


	private $ac;	
	private $credentials;	
	private $track_actid;
	private $track_key;
	private $list_id;
	private $form_id;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		if (!empty(get_option( $this->option_name . '_url', false ))) {
			$api_url = get_option( $this->option_name . '_url', false);
		}
		if (!empty(get_option( $this->option_name . '_key', false ))) {
			$api_key = get_option( $this->option_name . '_key', false);
		}	
		if (!empty(get_option( $this->option_name . '_list_id', false ))) {
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
		if (!empty($api_url) && !empty($api_key)) {
		
			$this->ac = new ActiveCampaign($api_url, $api_key);			
			if (!(int)$this->ac->credentials_test()) {								
				$this->credentials = false;
			} else {				
				$this->credentials = true;			
			}


			
		}	
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/bcnmm-activecampaign-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/bcnmm-activecampaign-public.js', array( 'jquery' ), $this->version, false );

	}

	public function add_tracking_code() {
		
		if ($this->credentials) {

			$this->ac->track_actid = $this->track_actid;
			$this->ac->track_key = $this->track_key;

			$this->ac->track_email = (isset($_SESSION['EMAIL'])) ? $_SESSION['EMAIL'] : "albertperez@protonmail.com";

			$data = array(
  				"event" => "PageView",
  				"eventdata" => get_permalink(),
			);

			$response = $this->ac->api("tracking/log", $data);						
		}

	}	

	public function bcnmm_activecampaign_simple_form() {

		if ($this->credentials) {			
			//$form = $this->ac->api( "form/html?id=" . $this->form_id . '&extra=1');								
			//return $form;//'<p>'.var_dump($form).'</p>';
						
			ob_start(); 
			include(plugin_dir_path( __FILE__ ) . 'forms/bcnmm-activecampaign-simple-form.php');    		
    		return ob_get_clean();
		}
	}	

	public function bcnmm_activecampaign_simple_form_submitted() {
		
		// proof if the user has the requested capabilities
	    // https://developer.wordpress.org/plugins/security/checking-user-capabilities/
	    /*if ( ! current_user_can( 'beschlussdatenbank_save_request' ) )
	    	return; // if not return*/

	    // securing the input. NEVER TRUST USER INPUT! 
	    //https://developer.wordpress.org/plugins/security/securing-input/
	    $name = sanitize_text_field($_POST['name']);
	    $email = sanitize_text_field($_POST['email']);

	    /*$name = $_POST['name'];
	    $email = $_POST['email'];
*/
	    // do some sing with the form input data like save it in a database
	    global $wpdb; // https://codex.wordpress.org/Class_Reference/wpdb

	    $data = [
        	'name'							=> $name,
        	'email'							=> $email,
			'p['.$this->list_id.']'         => $this->list_id, // example list ID (REPLACE '123' WITH ACTUAL LIST ID, IE: p[5] = 5)
    		'status['.$this->list_id.']'    => 1, // 1: active, 2: unsubscribed (REPLACE '123' WITH ACTUAL LIST ID, IE: status[5] = 1)

        ];
        //echo var_dump($data);
        $response = $this->ac->api('contact/add', $data);
        echo '<pre>'.print_r($response).'</pre>';



        // after processing redirect back to the side
    	//https://codex.wordpress.org/Function_Reference/wp_redirect
    	// here hardcoded  just for showing purposes only 
    	// redirects to a side and add a Get value "message" that contains that the form is processed successful 
    	//wp_redirect( "http://www.development.mywebside.com/aformside/?page=Antragerstellen&message='".urlencode('Form successful processed!')."'" );
	}

}/*

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $processResume = new Bcnmm_Activecampaign_Public();
    $processResume->bcnmm_activecampaign_simple_form_submition();
}*/
