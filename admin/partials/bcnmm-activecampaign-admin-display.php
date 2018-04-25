<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/al6ert
 * @since      1.0.0
 *
 * @package    Bcnmm_Activecampaign
 * @subpackage Bcnmm_Activecampaign/admin/partials
 */

	

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->



<div class="wrap">
    <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
    <form action="options.php" method="post">
        <?php
            settings_fields( $this->plugin_name );            
            do_settings_sections( $this->plugin_name );
            
            if (!(int)$this->credentials) {
            	echo '<p><span>' . __( "Access denied: Invalid credentials (URL and/or API key).", $this->plugin_name ) . '</span><span class="dashicons dashicons-warning"></span></p>';
            } else {
            	echo '<p><span>' . __( "Credentials valid! Proceeding...", $this->plugin_name ) . '</span><span class="dashicons dashicons-yes"></span></p>';
            }
            
            do_settings_sections( $this->plugin_name . '_credentials_valid');

            submit_button();
        ?>
    </form>
</div>