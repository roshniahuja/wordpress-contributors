<?php
/**
 * Class WPCONTRIBUTORS_Scripts_Handler.
 *
 * @package Wordpress_Contributors
 */
class WPCONTRIBUTORS_Scripts_Handler {
	/**
	 * WPCONTRIBUTORS_Scripts_Handler constructor.
	 */
	function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'wpcontributors_enqueue_scripts_dashboard' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'wpcontributors_enqueue_style_front_end' ) );
		add_action( 'admin_footer', array( $this, 'wpcontributors_contributors_templates' ) );
	}

	/**
	 * Register Styles and scripts for plugin for Dashboard
	 *
	 * @param {string} $hook Hook.
	 */
	function wpcontributors_enqueue_scripts_dashboard( $hook ) {

		// Apply the styles and scripts only on 'add new post' and 'edit post' page.
		if ( 'post-new.php' === $hook || 'post.php' === $hook ) {
		
			wp_enqueue_style( 'wpcontributors_post_meta_css' );
			
		}

		// Add style on WPCONTRIBUTORS Settings page.
		
		wp_register_style( 'wpcontributors_plugin_settings_css', WPCONTRIBUTORS_STYLE_URI . 'plugin-settings.css', '', '', false );
		wp_enqueue_style( 'wpcontributors_plugin_settings_css' );
	}

	/**
	 * Add style for post contributors sections on front end.
	 */
	function wpcontributors_enqueue_style_front_end() {
		wp_register_style( 'wpcontributors_post_contributors_css', WPCONTRIBUTORS_STYLE_URI . 'post-contributors.css', '', '', false );
		wp_enqueue_style( 'wpcontributors_post_contributors_css' );
	}

	/**
	 * Contributors suggestions template script
	 */
	function wpcontributors_contributors_templates() {
		?>
		<script type="text/html" id="tmpl-contributor-template">
			<div class="wpcontributors-selected-username">
				<span class="wpcontributors-selected-name">{{data.selectedUserName}}</span>
				<input type="hidden" class="wpcontributors-contributors-input" name="wpcontributors_post_authors[]" value="{{{data.selectedUserId}}}">
				<span class="dashicons dashicons-no-alt wpcontributors-remove-contributor-icon"></span>
			</div>
		</script>
		<?php
	}
}

new WPCONTRIBUTORS_Scripts_Handler();
