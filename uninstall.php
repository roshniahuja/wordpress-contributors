<?php
/**
 * Uninstall file for the plugin.
 *
 * @package WordPress Contributors
 */

// If plugin is not being uninstalled, exit (do nothing).
if ( !defined('WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Delete post meta key for contributors while plugin is deleted.
global $wpdb;
$table = $wpdb->prefix.'postmeta';
$wpdb->delete ( $table, array( 'meta_key' => 'wpcontributors_post_contributor_ids') );

// Delete options 'wpcontributors_post_types'.
delete_option( 'wpcontributors_post_types' );
