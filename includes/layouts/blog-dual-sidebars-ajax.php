<?php
/**
 * Utter Dual Sidebars Ajax
 *
 * @package WordPress
 * @subpackage utter/inc
 * @version 1.0.0
 * @author utterwp <info@utterwp.com>
 * @license https://utterwp.com/
 * @link https://utterwp.com/
 * @since  1.0.0
 */

add_action( 'wp_ajax_utter_blog_dual_sidebars_ajax', 'utter_blog_dual_sidebars_ajax' );
add_action( 'wp_ajax_nopriv_utter_blog_dual_sidebars_ajax', 'utter_blog_dual_sidebars_ajax' );

/**
 * Dual Sidebars layout
 *
 * @since 1.0.0
 */
function utter_blog_dual_sidebars_ajax() {
	global $wp_embed, $post;

	$args = array();

	if ( ! empty( $_GET['cat'] ) ) { // Input var okay.
		$args['cat'] = sanitize_text_field( wp_unslash( $_GET['cat'] ) ); // Input var okay.
	}

	if ( ! empty( $_GET['tag'] ) ) { // Input var okay.
		$args['tag_id'] = sanitize_text_field( wp_unslash( $_GET['tag'] ) ); // Input var okay.
	}

	if ( ! empty( $_GET['year'] ) || ! empty( $_GET['month'] ) ) { // Input var okay.
		$date_query = array();
		if ( ! empty( $_GET['year'] ) ) { // Input var okay.
			$date_query['year'] = sanitize_text_field( wp_unslash( $_GET['year'] ) ); // Input var okay.
		}
		if ( ! empty( $_GET['month'] ) ) { // Input var okay.
			$date_query['month'] = sanitize_text_field( wp_unslash( $_GET['month'] ) ); // Input var okay.
		}
		$args['date_query'] = array( $date_query );
	}

	if ( ! empty( $_GET['post__not_in'] ) ) { // Input var okay.
		$args['post__not_in'] = json_decode( sanitize_text_field( wp_unslash( $_GET['post__not_in'] ) ), true ); // Input var okay.
	}

	$args['post_status'] = 'publish';
	$args['no_found_rows'] = 1;

	$the_query = new WP_Query( $args );

	$utter_settings = get_option( 'utter_settings', '' );
	$comment_icon = ( isset( $utter_settings->comment_icon ) && '' !== $utter_settings->comment_icon) ? '<i class="' . $utter_settings->comment_icon . '"></i>' : '';
	$separator_post_meta_icon = ( isset( $utter_settings->separator_post_meta_icon ) && '' !== $utter_settings->separator_post_meta_icon) ? '<i class="' . $utter_settings->separator_post_meta_icon . '"></i>' : '';

	$output = '';

	if ( $the_query -> have_posts() ) :
		while ( $the_query -> have_posts() ) :
			$the_query -> the_post();
			$custom = get_post_custom();
			$output .= '
		<div class="' . implode( ' ', get_post_class( 'post_wrapper clearfix' ) ) . '">
			<div class="post_content">';
			if ( isset( $custom['utter_selected_media'][0] ) && 'soundcloud' === $custom['utter_selected_media'][0] && isset( $custom['utter_soundcloud'][0] ) && '' !== $custom['utter_soundcloud'][0] ) {
				$output .= '<iframe width="100%" height="166" scrolling="no" frameborder="no" src="' . esc_url( 'https://w.soundcloud.com/player/?url=http%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F' . $custom['utter_soundcloud'][0] ) . '"></iframe>';
			} elseif ( isset( $custom['utter_selected_media'][0] ) && 'youtube' === $custom['utter_selected_media'][0] && isset( $custom['utter_youtube_id'][0] ) && '' !== $custom['utter_youtube_id'][0] ) {
				$output .= '<div class="videoWrapper-youtube"><iframe src="' . esc_url( 'http://www.youtube.com/embed/' . $custom['utter_youtube_id'][0] . '?showinfo=0&amp;autohide=1&amp;related=0' ) . '" frameborder="0" allowfullscreen></iframe></div>';
			} elseif ( isset( $custom['utter_selected_media'][0] ) && 'vimeo' === $custom['utter_selected_media'][0] && isset( $custom['utter_vimeo_id'][0] ) && '' !== $custom['utter_vimeo_id'][0] ) {
				$output .= '<div class="videoWrapper-vimeo"><iframe src="' . esc_url( 'http://player.vimeo.com/video/' . $custom['utter_vimeo_id'][0] . '?title=0&amp;byline=0&amp;portrait=0' ) . '" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>';
			} else {
				$output .= get_the_post_thumbnail( null, 'full' );
			}
			$output .= '
				<div class="post_content_inner_wrapper">
					<h2><a href="' . esc_url( get_the_permalink() ) . '">' . get_the_title() . '</a></h2>
					<div class="post_author">' . esc_html_e( 'By ', 'utter-blog-dual-sidebars' ) . '
						<span>' . get_the_author_posts_link() . '</span>
						' . wp_kses_post( $separator_post_meta_icon ) . '
						<span class="post_date_inner">' . get_the_date( 'd F, Y' ) . '</span>
						' . wp_kses_post( $separator_post_meta_icon ) . '
						<span class="post_category">';
							$categories = get_the_category();
							$separator = ', ';
							$cat_out = '';
			if ( $categories ) {
				foreach ( $categories as $category ) {
					$cat_out .= '<a href="' . get_category_link( $category->term_id ) . '" title="' . esc_attr( sprintf( __( 'View all posts in %s' ), $category->name ) ) . '">' . $category->cat_name . '</a>' . $separator;
				}
				$output .= '' . trim( $cat_out, $separator ) . '';
			}
			$output .= '	</span>
					</div>
					<div class="post_content_text">' . get_the_content( '' ) . '</div>
					<div class="post-readmore">
						<a href="' . esc_url( get_the_permalink() ) . '" class="more-link">' . esc_html__( 'Read More', 'utter-blog-dual-sidebars' ) . '</a>
						<p class="post_meta_tags">
							<a href="' . esc_url( comments_link() ) . '" class="scroll comments_link">' . wp_kses_post( $comment_icon ) . '' . intval( get_comments_number() ) . '</a>
						</p>
					</div>
				</div>
			</div>
		</div>';

	endwhile;
	endif;
	wp_reset_postdata();
	die( $output ); // WPCS: xss ok.
}
