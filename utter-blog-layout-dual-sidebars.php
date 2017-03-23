<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://utterwp.com/
 * @since             1.0.0
 * @package           Utter_Blog_Dual_Sidebars
 *
 * @wordpress-plugin
 * Plugin Name:       Utter Blog Dual Sidebars
 * Plugin URI:        https://utterwp.com/downloads/blog-with-dual-sidebars-layout/
 * Description:       Blog layout with dual sidebars
 * Version:           1.0.0
 * Author:            Utterwp
 * Author URI:        https://utterwp.com/
 * License:           utterwp license
 * License URI:       https://utterwp.com/licensing
 * Text Domain:       utter-blog-dual-sidebars
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-utter-blog-layout-dual-sidebars.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_utter_blog_dual_sidebars() {

	$plugin = new Utter_Blog_Dual_Sidebars();
	$plugin->run();

}
run_utter_blog_dual_sidebars();
