<?php
/**
 * Plugin Name: WordPress Contributors
 * Description: This plugin allows to display multiple author to a post.
 * Version: 1.0.0
 * Author: Roshni Ahuja
 * Author URI: https://about.me/roshniahuja
 * Text Domain: wordpress-contributors
 *
 * @package WordPress Contributors
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Declare Constants.
define( 'WPCONTRIBUTORS_URI', plugins_url( 'wordpress-contributors' ) );
define( 'WPCONTRIBUTORS_PLUGIN_PATH', __FILE__ );
define( 'WPCONTRIBUTORS_TEMPLATE_PATH', plugin_dir_path( __FILE__ ) . 'templates/' );
define( 'WPCONTRIBUTORS_STYLE_URI', plugins_url( 'wordpress-contributors' ) . '/assets/css/' );
define( 'WPCONTRIBUTORS_SCRIPT_URI', plugins_url( 'wordpress-contributors' ) . '/assets/js/' );

// Load plugin class files.
require_once 'includes/class-wpcontributors-settings.php';
require_once 'includes/class-wpcontributors-scripts-handler.php';
require_once 'includes/class-wpcontributors-option-handler.php';
require_once 'includes/class-wpcontributors-meta-box.php';
require_once 'includes/class-wpcontributors-data-handler.php';
require_once 'includes/class-wpcontributors-content-filter.php';
