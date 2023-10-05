<?php
/**
 * Class WPCONTRIBUTORS_Meta_Box_Test
 *
 * @package Wordpress_Contributors
 */

class WPCONTRIBUTORS_Meta_Box_Test extends WP_UnitTestCase {

	/**
	 * Test function for Constructor Function.
	 */
	function test_constructor() {
		$add_meta_box = new WPCONTRIBUTORS_Meta_Box();

		// Check if both actions are registered.
		$meta_action_hooked = has_action( 'add_meta_boxes', [ $add_meta_box, 'wpcontributors_add_custom_box' ] );
		$post_action_hooked = has_action( 'save_post', [ $add_meta_box, 'wpcontributors_save_contributor_meta_data' ] );

		$actions_registered = ( 10 === $meta_action_hooked && 10 === $post_action_hooked ) ? 'registered' : 'not registered';

		$this->assertTrue( 'registered' === $actions_registered );
	}

	/**
	 * Test function for adding meta boxes on add new post and edit post screens
	 */
	function test_wpcontributors_add_custom_box() {
		global $wp_meta_boxes;
		$option_value = array( 'post', 'book' );

		// Create option key 'wpcontributors_post_types' with the $option_value as its value.
		add_option( 'wpcontributors_post_types', $option_value );

		$add_meta_box = new WPCONTRIBUTORS_Meta_Box();
		$add_meta_box->wpcontributors_add_custom_box();

		// Check if the two meta boxes are added on default 'post' and custom post type 'book' screens.
		$add_post_screen_id = $wp_meta_boxes['post']['advanced']['default']['wpcontributors_box_id']['id'];
		$edit_post_screen_id = $wp_meta_boxes['book']['advanced']['default']['wpcontributors_box_id']['id'];
		$meta_boxes_added = ( 'wpcontributors_box_id' === $add_post_screen_id && 'wpcontributors_box_id' === $edit_post_screen_id );

		$this->assertTrue( $meta_boxes_added );
	}

	/**
	 * Test function for adding custom meta box html.
	 */
	function test_wpcontributors_custom_box_html() {
		global $wp_query;
		global $post;

		$add_meta_box = new WPCONTRIBUTORS_Meta_Box();

		// Create two Dummy user ids.
		$user_ids = $this->factory->user->create_many( 2 );

		// Create a dummy post using the 'WP_UnitTest_Factory_For_Post' class and give the post author's user ud as 2.
		$post_id = $this->factory->post->create( [
			'post_status' => 'publish',
			'post_title'  => 'Lorem',
			'post_content' => 'Lorem Ipsum',
			'post_author' => 2,
			'post_type' => 'post'
		] );

		// Create a custom query for the post with the above created post id.
		$wp_query = new WP_Query( [
			'post__in' => [ $post_id ],
			'posts_per_page' => 1,
		] );

		// Run the WordPress loop through this query to set the global $post.
		if ( $wp_query->have_posts() ) {
			while( $wp_query->have_posts() ) {
				$wp_query->the_post();
			}
		}

		// Set the array of user ids to post meta with meta key 'wpcontributors_post_contributor_ids', with the above created post id.
		update_post_meta( $post_id, 'wpcontributors_post_contributor_ids', $user_ids );

		// Store the echoed value of the wpcontributors_custom_box_html() into $custom_box_html using output buffering.
		ob_start();
		$add_meta_box->wpcontributors_custom_box_html( $post );
		$custom_box_html = ob_get_clean();

		// Validate the output string contains the class names we are expecting.
		$author_string = strpos( $custom_box_html, 'wpcontributors-author' );
		$contributor_string = strpos( $custom_box_html, 'wpcontributors-selected-name' );


		$custom_box_html_output = ( $author_string && $contributor_string );

		$this->assertTrue( $custom_box_html_output );

        wp_reset_postdata();
	}

	/**
	 * Test for getting contributors id.
	 */
	function test_wpcontributors_get_contributors_ids() {
		$add_meta_box = new WPCONTRIBUTORS_Meta_Box();

		// Create two Dummy user ids.
		$this->factory->user->create_many( 2 );

		// Create a dummy post using the 'WP_UnitTest_Factory_For_Post' class and give the post author's user ud as 2.
		$post_id = $this->factory->post->create( [
			'post_status' => 'publish',
			'post_title'  => 'Lorem',
			'post_content' => 'Lorem Ipsum',
			'post_author' => 2,
		] );

		// Set the array of user ids to post meta with meta key 'wpcontributors_post_contributor_ids', with the above created post id.
		update_post_meta( $post_id, 'wpcontributors_post_contributor_ids', array( 2, 3 ) );

		$contributors_id = $add_meta_box->wpcontributors_get_contributors_ids( $post_id );
		$contributors_id_validity = ( 2 === $contributors_id[0] && 3 === $contributors_id[1] );
		$this->assertTrue( $contributors_id_validity );
	}

}