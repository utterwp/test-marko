<?php
/**
 * Blog Dual Sidebars Layout
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * @package WordPress
 * @subpackage utter/layouts
 * @version 1.0.0
 * @author utterwp <info@utterwp.com>
 * @license https://utterwp.com/
 * @link https://utterwp.com/
 * @since  1.0.0
 */

$cat_id = get_query_var( 'cat' );
$utter_settings = get_option( 'utter_settings', '' );
$comment_icon = ( isset( $utter_settings->settings->comment_icon ) && '' !== $utter_settings->settings->comment_icon) ? '<i class="' . $utter_settings->settings->comment_icon . '"></i>' : '';
$separator_post_meta_icon = ( isset( $utter_settings->separator_post_meta_icon ) && '' !== $utter_settings->separator_post_meta_icon) ? '<i class="' . $utter_settings->separator_post_meta_icon . '"></i>' : '';
?>
<section class="blog blog_dual_sidebars">
	<div class="container">
		<div class="row">
			<!-- Blog Sidebar Left START -->
			<?php $cat_id = get_query_var( 'cat' );
			if ( is_home() || empty( $cat_id ) ) {
				$selected_cat_sidebar_primary = get_option( 'utter_setting_blog_first_sidebar', true );
			} else {
				$selected_cat_sidebar_primary = get_term_meta( $cat_id, 'selected_cat_sidebar_primary', true );
			}
			?>
			<aside class="span3 sidebar sidebar_left">
				<?php
				if ( isset( $selected_cat_sidebar_primary ) && '' !== $selected_cat_sidebar_primary ) {
					$selected_sidebar = $selected_cat_sidebar_primary;
				} else {
					$selected_sidebar = esc_html__( 'Primary Sidebar', 'utter-blog-dual-sidebars' );
				}
				dynamic_sidebar( $selected_sidebar );
				?>
			</aside>
			<!-- Blog Sidebar Left END -->

			<div class="blog_category_index span6" data-action="utter_blog_dual_sidebars_ajax">
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post();
						$custom = get_post_custom(); ?>
					<div <?php post_class( 'post_wrapper clearfix' ); ?>>
						<div class="post_content">
							<?php
							if ( isset( $custom['utter_selected_media'][0] ) && 'soundcloud' === $custom['utter_selected_media'][0] && isset( $custom['utter_soundcloud'][0] ) && '' !== $custom['utter_soundcloud'][0] ) {
								echo '<iframe width="100%" height="166" scrolling="no" frameborder="no" src="' . esc_url( 'https://w.soundcloud.com/player/?url=http%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F' . $custom['utter_soundcloud'][0] ) . '"></iframe>';
							} elseif ( isset( $custom['utter_selected_media'][0] ) && 'youtube' === $custom['utter_selected_media'][0] && isset( $custom['utter_youtube_id'][0] ) && '' !== $custom['utter_youtube_id'][0] ) {
								echo '<div class="videoWrapper-youtube"><iframe src="' . esc_url( 'http://www.youtube.com/embed/' . $custom['utter_youtube_id'][0] . '?showinfo=0&amp;autohide=1&amp;related=0' ) . '" frameborder="0" allowfullscreen></iframe></div>';
							} elseif ( isset( $custom['utter_selected_media'][0] ) && 'vimeo' === $custom['utter_selected_media'][0] && isset( $custom['utter_vimeo_id'][0] ) && '' !== $custom['utter_vimeo_id'][0] ) {
								echo '<div class="videoWrapper-vimeo"><iframe src="' . esc_url( 'http://player.vimeo.com/video/' . $custom['utter_vimeo_id'][0] . '?title=0&amp;byline=0&amp;portrait=0' ) . '" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>';
							} else {
								echo get_the_post_thumbnail( null, 'full' );
							}
							?>
							<div class="post_content_inner_wrapper">
								<h2>
									<?php $post_title = ( '' !== $post->post_excerpt ) ? get_the_excerpt() : get_the_title(); ?>
									<a href="<?php the_permalink( ); ?>"><?php echo wp_kses_post( $post_title ); ?></a>
								</h2>
								<div class="post_author"><?php esc_html_e( 'By ', 'utter-blog-dual-sidebars' ); ?>
									<span><?php the_author_posts_link( ); ?></span>
									<?php echo wp_kses_post( $separator_post_meta_icon ); ?>
									<span class="post_date_inner"><?php echo get_the_date( 'd F, Y' ); ?></span>
									<?php echo wp_kses_post( $separator_post_meta_icon ); ?>
									<span class="post_category"><?php the_category( ', ' )?></span>
								</div>
								<div class="post_content_text"><?php the_content( '' ); ?></div>
								<div class="post-readmore">
									<a href="<?php echo esc_url( get_permalink( ) )?>" class="more-link"><?php esc_html_e( 'Read More', 'utter-blog-dual-sidebars' )?></a>
									<p class="post_meta_tags">
										<a href="<?php echo esc_url( comments_link( ) ); ?>" class="scroll comments_link">
										<?php echo wp_kses_post( $comment_icon ) . intval( get_comments_number( ) ); ?>
										</a>
									</p>
								</div>
							</div>
						</div>
					</div>
				<?php endwhile;
				else : ?>
					<p><?php esc_html_e( 'No posts were found. Sorry!', 'utter-blog-dual-sidebars' ); ?></p>
				<?php endif; ?>

			<!-- Load More Posts START -->
				<?php
				$cat_id      	   = get_query_var( 'cat' );
				$tag_id      	   = get_query_var( 'tag_id' );
				$year      	   	   = get_query_var( 'year' );
				$month      	   = get_query_var( 'monthnum' );
				$author      	   = get_query_var( 'author' );
				$category_settings = get_term_meta( $cat_id, 'blog_pagination', true );
				$reading_settings  = get_option( 'utter_setting_blog_load_new_posts', 'pagination' );
				$load_posts 	   = ( ! empty( $category_settings ) && '' !== $category_settings ) ? $category_settings : $reading_settings;

				if ( isset( $load_posts ) && '' !== $load_posts ) {
					// Load Posts on scroll.
					if ( 'load_on_scroll' === $load_posts ) {
					?>
					<div id="posts_loader" data-category="<?php echo esc_attr( $cat_id ); ?>" data-tag="<?php echo esc_attr( $tag_id ); ?>" data-year="<?php echo esc_attr( $year ); ?>" data-month="<?php echo esc_attr( $month ); ?>" data-author="<?php echo esc_attr( $author ); ?>"></div>
					<?php
					// Load Posts on button click.
					} elseif ( 'ajax_button' === $load_posts ) { ?>
					<div id="utter_load_posts_button"></div>
					<?php
					// Load Posts with next pages.
					} else {
						global $wp_query;
						$total_pages = $wp_query->max_num_pages;

						if ( $total_pages > 1 ) {
							$current_page = max( 1, get_query_var( 'paged' ) );
							$permalink_structure = get_option( 'permalink_structure' );
							$format = empty( $permalink_structure ) ? '&amp;paged=%#%' : 'page/%#%/'; ?>
							<section class="pagination_simple clearfix">
								<div class="pagination_wrapper">
							<?php
							$pagination_output = paginate_links( array(
								'base'      => get_pagenum_link( 1 ) . '%_%',
								'format'    => $format,
								'current'   => $current_page,
								'total'     => $total_pages,
								'prev_text' => esc_html__( '&laquo; Previous', 'utter-blog-dual-sidebars' ),
								'next_text' => esc_html__( 'Next &raquo;', 'utter-blog-dual-sidebars' ),
								'type'      => 'array',
							) );
							foreach ( $pagination_output as $link ) {
								$link_parts = array();
								$link_exploded = explode( '?', $link,2 );
								$link_parts[] = ' ' . $link_exploded[0];
								if ( isset( $link_exploded[1] ) ) {
									$link_parts[] = str_replace( '?', '&amp;', $link_exploded[1] );
								}
								$link_echo = implode( '?', $link_parts );
								echo wp_kses( $link_echo, utter_allowed_tags() );
							}
							?>
								</div>
							</section>
							<?php
						}
					}
				}
				?>
			<!-- Load More Posts END -->

			</div>

			<!-- Blog Sidebar Right START -->
			<?php $cat_id = get_query_var( 'cat' );
			if ( is_home() || empty( $cat_id ) ) {
				$selected_cat_sidebar_secondary = get_option( 'utter_setting_blog_second_sidebar', true );
			} else {
				$selected_cat_sidebar_secondary = get_term_meta( $cat_id, 'selected_cat_sidebar_secondary', true );
			}
			?>
			<aside class="span3 sidebar sidebar_right">
				<?php
				if ( isset( $selected_cat_sidebar_secondary ) && '' !== $selected_cat_sidebar_secondary ) {
					$selected_sidebar = $selected_cat_sidebar_secondary;
				} else {
					$selected_sidebar = esc_html__( 'Secondary Sidebar', 'utter-blog-dual-sidebars' );
				}
				dynamic_sidebar( $selected_sidebar );
				?>
			</aside>
			<!-- Blog Sidebar Right END -->
		</div>
	</div>
</section>
