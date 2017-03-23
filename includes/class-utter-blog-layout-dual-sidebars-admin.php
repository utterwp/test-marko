<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://utterwp.com/
 * @since      1.0.0
 *
 * @package    Utter_Blog_Dual_Sidebars
 * @subpackage Utter_Blog_Dual_Sidebars/includes
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Utter_Blog_Dual_Sidebars
 * @subpackage Utter_Blog_Dual_Sidebars/includes
 * @author     Utterwp <info@utterwp.com>
 */
class Utter_Blog_Dual_Sidebars_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $utter_blog_dual_sidebars    The ID of this plugin.
	 */
	private $utter_blog_dual_sidebars;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param  string $utter_blog_dual_sidebars       The name of this plugin.
	 * @param  string $version    The version of this plugin.
	 */
	public function __construct( $utter_blog_dual_sidebars, $version ) {

		$this->utter_blog_dual_sidebars = $utter_blog_dual_sidebars;
		$this->version = $version;

	}

	/**
	 * Add layout fucntion to the existing layout array.
	 *
	 * @since  1.0.0
	 * @param  array $utter_blog_layout_array Blog settings array.
	 * @return  array Modified blog array.
	 * @access public
	 */
	public function add_layout( $utter_blog_layout_array ) {

		$left_sidebar = array(
			'name'			   		=> esc_html__( 'Dual Sidebars', 'utter-blog-dual-sidebars' ),
			'source'           		=> str_replace( WP_CONTENT_DIR, '', plugin_dir_path( __FILE__ ) . 'layouts/blog-layout-dual-sidebars.php' ),
			'source_ajax'      		=> str_replace( WP_CONTENT_DIR, '', plugin_dir_path( __FILE__ ) . 'layouts/blog-dual-sidebars-ajax.php' ),
			'layout_image'  		=> plugin_dir_url( __FILE__ ) . 'images/dual_sidebars.png',
			'left_sidebar'	   		=> 'yes',
			'right_sidebar'	   		=> 'yes',
		);

		$utter_blog_layout_array['layouts']['dual_sidebars'] = $left_sidebar;

		return $utter_blog_layout_array;

	}

	/**
	 * Blog layout settings check
	 *
	 * Check if the current layout is selected in the theme settings or specific page.
	 *
	 * @return boolean True if the selected layout matches the theme settings
	 * @since    1.0.0
	 */
	public function check_blog_layout_settings() {
		$blog_layout = get_option( 'utter_setting_blog_layout' );

		if ( isset( $blog_layout ) && '' === $blog_layout ){
			return false;
		}

		if ( is_category() ) {
			$cat_id = get_query_var( 'cat' );
			$selected_cat_layout = get_term_meta( $cat_id, 'selected_cat_layout', true );
			$blog_layout = ( isset( $selected_cat_layout ) && '' !== $selected_cat_layout ) ? $selected_cat_layout : $blog_layout;
		}

		if ( strpos( $blog_layout, $this->utter_blog_dual_sidebars ) ) {
			return true;
		} else {
			return false;
		}

	}

	/**
	 * Register the stylesheets.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		if ( self::check_blog_layout_settings() ) {
			wp_enqueue_style( $this->utter_blog_dual_sidebars, plugin_dir_url( __FILE__ ) . 'css/utter-blog-layout-dual-sidebars.css', array(), $this->version, 'all' );
		}
	}
}
