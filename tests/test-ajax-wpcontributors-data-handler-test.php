<?php
/**
 * Class Test_Ajax_WPCONTRIBUTORS__Data_Handler
 *
 * @package WordPress_Contributer
 */
class Ajax_WPCONTRIBUTORS_Data_Handler_Test extends WP_Ajax_UnitTestCase {

    public function setUp() {
        parent::setUp();

        $_SERVER['REQUEST_METHOD'] = 'POST';
    }

    /**
     * Helper to keep it DRY
     *
     * @param string $action Action.
     */
    protected function make_ajax_call( $action ) {
        // Make the request.
        try {
            $this->_handleAjax( $action );
        } catch ( WPAjaxDieContinueException $e ) {
            unset( $e );
        }
    }

    /**
     * Testing successful ajax_wpcontributors_get_users_data
     *
     */
    function test_ajax_wpcontributors_get_users_data() {
        // Create a user with nicename 'Roshni' , using WP_UnitTestCase factory.
        $user_id = $this->factory->user->create( [
            'user_nicename' => 'Roshni',
        ] );
        $_POST =  array(
            'action' => 'wpcontributors_ajax_hook',
            'security' => wp_create_nonce( 'wpcontributors_nonce_action_name' ),
            'post_type' => 'post',
            'query' => 'Roshni'
        );
        $this->make_ajax_call( 'wpcontributors_ajax_hook' );
        // Get the results.
        $response = json_decode( $this->_last_response, true );

        $this->assertTrue( $response['success'] );
    }
}