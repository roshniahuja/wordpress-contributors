<?php
/**
 * Class WPCONTRIBUTORS_Option_Handler
 *
 * @package WordPress Contributors
 */
class WPCONTRIBUTORS_Option_Handler {
	/**
	 * WPCONTRIBUTORS_Option_Handler constructor.
	 * Registers activation hook for the plugin.
	 */
	public function __construct() {
		register_activation_hook( WPCONTRIBUTORS_PLUGIN_PATH, array( $this, 'wpcontributors_add_settings_option' ) );
	}

	/**
	 * Add the option key and value for default post.
	 */
	public function wpcontributors_add_settings_option() {
		$existing_option = get_option( 'wpcontributors_post_types' );
		if ( ! $existing_option ) {
			$option_val = array( 'post' );
			add_option( 'wpcontributors_post_types', $option_val );
		}
	}
}

new WPCONTRIBUTORS_Option_Handler();
