<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://utterwp.com/
 * @since      1.0.0
 *
 * @package    Utter_Blog_Dual_Sidebars
 * @subpackage Utter_Blog_Dual_Sidebars/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Utter_Blog_Dual_Sidebars
 * @subpackage Utter_Blog_Dual_Sidebars/includes
 * @author     Utterwp <info@utterwp.com>
 */
class Utter_Blog_Dual_Sidebars_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'utter-blog-dual-sidebars',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
