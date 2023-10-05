<?php
/**
 * Class WPCONTRIBUTORS_Meta_Box
 *
 * @package Wordpress_Contributors
 */

class WPCONTRIBUTORS_Meta_Box {
	/**
	 * WPCONTRIBUTORS_Meta_Box constructor.
	 */
	function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'wpcontributors_add_custom_box' ) );
		add_action( 'save_post', array( $this, 'wpcontributors_save_contributor_meta_data' ) );
	}

	/**
	 * Add meta box to the post editor.
	 */
	public function wpcontributors_add_custom_box() {
		$screens = get_option( 'wpcontributors_post_types' );
		if ( is_array( $screens ) && ! empty( $screens ) ) {
			foreach ( $screens as $screen ) {
				add_meta_box(
					'wpcontributors_box_id',
					__( 'Contributer(s)' ),
					array( $this, 'wpcontributors_custom_box_html' ),
					$screen
				);
			}
		}
	}

	/**
	 * Display the list if contributors you have selected on new post.
	 *
	 * @param {obj} $post Post variable.
	 */
	public function wpcontributors_custom_box_html( $post ) {
		// Add nonce field
	    wp_nonce_field(plugin_basename(__FILE__), 'wpcontributors_add_contributor_nonce_name');

	    // Get saved contributor IDs (if any)
	    $post_id = $post->ID;
	    $contributor_ids = $this->wpcontributors_get_contributors_ids($post_id);

	    // Get all WordPress users
	    $all_users = get_users();

	    echo '<div class="wpcontributors-ui-widget">';
	    echo '<label for="wpcontributors-selected-authors">Select Contributors:</label>';
	    echo '<br>';
	    echo '<select multiple="multiple" size="10" name="wpcontributors_post_authors[]" id="wpcontributors-selected-authors">';

	    foreach ($all_users as $user) {
	        $selected = in_array($user->ID, $contributor_ids) ? 'selected="selected"' : '';
	        echo '<option value="' . esc_attr($user->ID) . '" ' . $selected . '>' . esc_html($user->display_name) . '</option>';
	    }

	    echo '</select>';
	    echo '</div>';
	}

	/**
	 * Get an array of existing contributors ids if its already saved.
	 *
	 * @param int $post_id Post Id.
	 *
	 * @return array $existing_contributors_ids existing contributors ids array for the given post id, if its already saved.
	 */
	public function wpcontributors_get_contributors_ids( $post_id ) {

		$existing_contributors_ids = get_post_meta( $post_id, 'wpcontributors_post_contributor_ids' );
		$existing_contributors_ids = ( is_array( $existing_contributors_ids ) && ! empty( $existing_contributors_ids ) ) ? $existing_contributors_ids[0] : array();
		return $existing_contributors_ids;
	}

	/**
	 * Save an array of user ids for the contributor authors of the post.
	 *
	 * @param int $post_id Post Id.
	 */
	public function wpcontributors_save_contributor_meta_data( $post_id ) {

		if (isset($_POST['post_type']) && 'post' === $_POST['post_type'] && !current_user_can('edit_post', $post_id)) {
        	return;
	    }

	    // Check the nonce value.
	    if (!isset($_POST['wpcontributors_add_contributor_nonce_name']) || !wp_verify_nonce($_POST['wpcontributors_add_contributor_nonce_name'], plugin_basename(__FILE__))) {
	        return;
	    }

	    $contributors_ids_array = (!empty($_POST['wpcontributors_post_authors']) ? array_map('intval', $_POST['wpcontributors_post_authors']) : array());

	    // Remove duplicates
	    $contributors_ids_array = array_unique($contributors_ids_array);

	    // Save the selected contributors
	    update_post_meta($post_id, 'wpcontributors_post_contributor_ids', $contributors_ids_array);

	}
}

new WPCONTRIBUTORS_Meta_Box();