<?php
/**
 * Class WPCONTRIBUTORS_Content_Filter_Test
 *
 * @package Wordpress_Contributors
 */
class WPCONTRIBUTORS_Content_Filter_Test extends WP_UnitTestCase {

	function test_constructor() {
		$display_contributors = new WPCONTRIBUTORS_Content_Filter();

		$action_hooked = has_action( 'the_content', [ $display_contributors, 'wpcontributors_display_contributors' ] );

		$this->assertTrue( 10 === $action_hooked );
	}

	function test_wpcontributors_display_contributors(){
		global $wp_query;

		// Set the $content value to a dummy content and initialize the class'WPCONTRIBUTORS_Filter_Post_Content'
		$content = 'Lorem Ipsum is simply dummy text.';
		$display_contributors = new WPCONTRIBUTORS_Content_Filter();

		// Create a dummy post using the 'WP_UnitTest_Factory_For_Post' class
		$post_id = $this->factory->post->create( [
			'post_status' => 'publish',
			'post_title'  => 'Lorem',
			'post_content' => 'Lorem Ipsum',
		] );

		// Create two Dummy user ids.
		$user_ids = $this->factory->user->create_many( 2 );

		// Call the update_post_meta to store the array of two user ids created above into 'wpcontributors_post_contributor_ids' post meta key.
		update_post_meta( $post_id, 'wpcontributors_post_contributor_ids', $user_ids );

		// Reset the $wp_query global post variable and create a new WP Query.
		$wp_query = new WP_Query( [
			'post__in' => [ $post_id ],
			'posts_per_page' => 1,
		] );

		// Run the WordPress loop through this query and call our wpcontributors_display_contributors() to add the $content to each post content.
		if ( $wp_query->have_posts() ) {
			while( $wp_query->have_posts() ) {
				$wp_query->the_post();

				$wp_query->is_single = true;

				$filtered_output = $display_contributors->wpcontributors_display_contributors( $content );

				/**
				 * Check if the 'wpcontributors_avatar-username' ( which a classname we used while creating the content )
				 * is present in the $filtered_output returned by the above function.
				 * If the strpos() returns a position, which means our content was added, in which case our test is successful.
				 */
				$string_found = strpos( $filtered_output, 'wpcontributors_avatar-username' );
				$this->assertTrue( false !== $string_found );

			}
		}
	}
}