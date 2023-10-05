<?php
/**
 * Plugin Settings
 *
 * @package WordPress Contributors
 */
?>
<div class="wpcontributors-settings-wrapper">
	<div class="wpcontributors-settings-header">
		<h2><?php echo esc_html__( 'Contributor Plugin Settings', 'wordpress-contributors' ); ?></h2>
		<p><?php echo esc_html__( 'Choose post type to enable the contributor box.', 'wordpress-contributors' ); ?></p>
	</div>
	<form method="post" class="wpcontributors-settings-form" action="options.php">
		<?php
		settings_fields( 'wpcontributors-plugin-settings-group' );
		do_settings_sections( 'wpcontributors-plugin-settings-group' );
		$option_val_array = get_option( 'wpcontributors_post_types' );
		$default_post_type = 'post';
		array_push( $cpt_array, $default_post_type );
		?>
		<?php
			if ( is_array( $cpt_array ) && ! empty( $cpt_array ) ) {
			    if ( ! is_array( $option_val_array ) ) {
			        $option_val_array = array();
			    }
				foreach ( $cpt_array as $posttype ) {
					$checked = ( in_array( $posttype, $option_val_array ) ) ? 'checked' : '';
					?>
					<div class="wpcontributors-form-group">
						<label for="wpcontributors-<?php echo esc_attr( $posttype ); ?>" class="wpcontributors-label">
							<input class="wpcontributors-form-control" id="wpcontributors-<?php echo esc_attr( $posttype ); ?>" type="checkbox" name="wpcontributors_post_types[]" value="<?php echo esc_attr( $posttype ); ?>" <?php echo esc_attr( $checked ); ?>>
							<?php echo esc_html( ucfirst( $posttype ) ); ?>
						</label>
					</div>
					<?php
				}
			}
		?>
		<div class="wpcontributors-save-btn-container"><?php submit_button(); ?></div>
	</form>
</div>