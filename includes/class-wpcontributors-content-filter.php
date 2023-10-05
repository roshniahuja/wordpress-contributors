<?php
/**
 * Class WPCONTRIBUTORS_Content_Filter
 * Filters post content and display the list auth authors.
 *
 * @package WordPress Contributors
 */

class WPCONTRIBUTORS_Content_Filter {
	/**
	 * WPCONTRIBUTORS_Content_Filter constructor.
	 */
	function __construct() {
		add_filter( 'the_content', array( $this, 'wpcontributors_display_contributors_list' ) );
	}

	/**
	 * Generate the list of Contributors with their name, avatar and other details.
	 *
	 * @param string $content Content of post.
	 *
	 * @return string $new_content Content with Contributors list.
	 */
	public function wpcontributors_display_contributors_list( $content ) {
		if ( ! is_single() && ! is_author() && ! is_category() && ! is_tag() ) {
			return;
		}

		$post_id              = get_the_ID();
		$contributor_ids      = get_post_meta( $post_id, 'wpcontributors_post_contributor_ids', true );
		$contributors_content = '';

		if ( is_array( $contributor_ids ) && ! empty( $contributor_ids ) ) {
			$contributors_content =
				'<div class="wpcontributors_contributors-wrapper">' .
					'<h3 class="wpcontributors_contributors-title">' . __( 'List of Contributors' ) . '</h3>' .
					'<div class="wpcontributors_contributors-container">';

			foreach ( $contributor_ids as $id ) {
				$user_data  = get_userdata( $id );
				$user_name  = $user_data->user_nicename;
				$author_url = get_author_posts_url( $id );
				$avatar_img = ( ! is_category() && ! is_tag() ) ? get_avatar( $id, 50 ) : '';

				$contributors_content .=
					'<div class="wpcontributors_avatar_container">' .
						'<a href="' . esc_url( $author_url ) . '">' .
							$avatar_img .
							'<span class="wpcontributors_avatar-username">' . strtoupper( $user_name ) . '</span>' .
						'</a>' .
					'</div>';
			}
			$contributors_content .=
					'</div>' .
				'</div>';
		}

		$new_content = $content . $contributors_content;
		return $new_content;
	}
}

new WPCONTRIBUTORS_Content_Filter();
