<?php
/**
 * Custom functions for creating admin menu settings for the plugin.
 *
 * @package Wordpress_Contributors
 */

class WPCONTRIBUTORS_Settings {
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'wpcontributors_create_menu' ) );
	}

	/**
	 * Creates Menu for contributors Plugin in the dashboard.
	 */
	public function wpcontributors_create_menu() {

		$menu_plugin_title = __( 'Contributors', 'wordpress-contributors' );

		add_menu_page( __(
			'WPCONTRIBUTORS Plugin Settings',
			'wordpress-contributors' ),
			$menu_plugin_title,
			'administrator',
			__FILE__,
			array( $this, 'wpcontributors_plugin_settings_page_content' ),
			'dashicons-groups'
		);

		// Call register settings function.
		add_action( 'admin_init', array( $this, 'register_wpcontributors_plugin_settings' ) );
	}

	/**
	 * Register settings.
	 */
	public function register_wpcontributors_plugin_settings() {
		register_setting( 'wpcontributors-plugin-settings-group', 'wpcontributors_post_types' );
	}

	/**
	 * Settings Page Content for contributors Plugin.
	 */
	public function wpcontributors_plugin_settings_page_content() {

		// Check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$cpt_array = $this->wpcontributors_get_cpt();

		/**
		 * Check if the user have submitted the settings.
		 */
		if ( isset( $_GET['settings-updated'] ) ) {

			// Add settings saved message with the class of "updated".
			add_settings_error( 'wpcontributors_messages', 'wpcontributors_message', __( 'Settings Saved', 'wordpress-contributors' ), 'updated' );
		}

		settings_errors( 'wpcontributors_messages' );

		include_once WPCONTRIBUTORS_TEMPLATE_PATH . 'settings-template.php';
	}

	/**
	 * Returns all the registered custom post types.
	 */
	public function wpcontributors_get_cpt() {
		
		$args = array( '_builtin' => false, );
		$cpt_array = get_post_types( $args );
		return ( ! empty( $cpt_array ) ) ? $cpt_array : array();
	}
}

new WPCONTRIBUTORS_Settings();
