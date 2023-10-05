<?php
/**
 * Class WPCONTRIBUTORS_Data_Handler class.
 *
 * @package Wordpress_Contributors
 */
class WPCONTRIBUTORS_Data_Handler {
	/**
	 * WPCONTRIBUTORS_Data_Handler constructor.
	 */
	function __construct() {
		add_action( 'wp_ajax_wpcontributors_ajax_hook', array( $this, 'wpcontributors_get_users_data' ) );
	}

	/**
	 * Get the data for matched users searched by the query string.
	 *
	 */
	function wpcontributors_get_users_data() {

		// If nonce verification fails die.
		$nonce = ( ! empty( $_POST['security'] ) ) ? wp_unslash( $_POST['security'] ) : '';
		if ( ! wp_verify_nonce( $nonce, 'wpcontributors_nonce_action_name' ) ) {
			wp_die();
		}
		$query = ( ! empty( $_POST['query'] ) ) ? sanitize_text_field( wp_unslash( $_POST['query'] ) ) : '';
		$string_length = 2;
		$logged_in_user_id = get_current_user_id();
		$user_id_array_to_be_excluded = ( $logged_in_user_id ) ? array( $logged_in_user_id ) : array();
		$users_found = array();

		if ( ! empty( $query ) && $string_length < strlen( $query ) ) {
			/**
			 * Perform query to get users by their name or email except the currently logged in user from the search.
			 */
			$users = new WP_User_Query(
				array(
					'search'         => '*' . esc_attr( $query ) . '*',
					'search_columns' => array(
						'user_nicename',
						'user_email',
					),
					'exclude' => $user_id_array_to_be_excluded,
				)
			);

			$users_found = $users->get_results();
		}

		wp_send_json_success(
			array(
				'users' => $users_found,
			)
		);
	}
}

new WPCONTRIBUTORS_Data_Handler();
