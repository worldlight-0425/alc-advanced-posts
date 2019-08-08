<?php
/**
 * Recent Posts
 *
 * @author    Dan Fisher
 * @package   Alchemists Advanced Posts
 * @since     1.1.0
 * @version   1.1.0
 */


// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}


/**
 * Widget class.
 */

class Alchemists_Widget_Recent_Posts extends WP_Widget {


	/**
	 * Constructor.
	 *
	 * @access public
	 */
	function __construct() {

		$widget_ops = array(
			'classname' => 'recent-posts',
			'description' => esc_html__( 'Display your posts.', 'alc-advanced-posts' ),
		);
		$control_ops = array(
			'id_base' => 'recent-posts-widget'
		);

		parent::__construct( 'recent-posts-widget', 'ALC - Recent Posts', $widget_ops, $control_ops );

	}


	/**
	 * Outputs the widget content.
	 */

	function widget( $args, $instance ) {

		extract( $args );

		$title        = apply_filters( 'widget_title', isset( $instance['title'] ) ? $instance['title'] : '' );
		$number       = isset( $instance['number'] ) ? $instance['number'] : 4;
		$orderby      = isset( $instance['orderby'] ) ? $instance['orderby'] : 'date';
		$popularity   = isset( $instance['popularity'] ) ? $instance['popularity'] : 'likes';
		$cat          = isset( $instance['cat'] ) ? $instance['cat'] : '';
		$show_thumb   = isset( $instance['show_thumb'] ) ? true : false;
		$numbered     = isset( $instance['numbered'] ) ? true : false;
		$layout_style = isset( $instance['layout_style'] ) ? $instance['layout_style'] : 'small';
		$excerpt_size = isset( $instance['excerpt_size'] ) ? $instance['excerpt_size'] : 20;

		echo wp_kses_post( $before_widget );

		if( $title ) {
			echo wp_kses_post( $before_title ) . esc_html( $title ) . wp_kses_post( $after_title );
		}
		?>


		<?php

		if ( $orderby == 'meta_value_num' ) {

			$popularity_meta_key = '';
			if ( $popularity == 'likes' ) {
				$popularity_meta_key = '_post_like_count';
			} else {
				$popularity_meta_key = 'post_views_count';
			}

			$args = array(
				'post_type'           => 'post',
				'posts_per_page'      => $number,
				'no_found_rows'       => true,
				'orderby'             => $orderby,
				'cat'                 => $cat,
				'meta_key'            => $popularity_meta_key,
				'post_status'         => 'publish',
				'ignore_sticky_posts' => true,
			);
		} else {
			$args = array(
				'post_type'           => 'post',
				'posts_per_page'      => $number,
				'no_found_rows'       => true,
				'orderby'             => $orderby,
				'cat'                 => $cat,
				'post_status'         => 'publish',
				'ignore_sticky_posts' => true,
			);
		}

		$alchemists_data = get_option('alchemists_data');
		$categories_toggle = isset( $alchemists_data['alchemists__posts-categories'] ) ? $alchemists_data['alchemists__posts-categories'] : 1;

		// Post list class
		$posts_list_classes = array(
			'posts',
			'posts--simple-list',
		);
		$post_thumb_size = 'alchemists_thumbnail-xs';

		if ( $layout_style == 'large' ) {
			array_push( $posts_list_classes, 'posts--simple-list--lg', 'posts--simple-list--lg--clean' );
			$post_thumb_size = 'alchemists_thumbnail';
		} elseif ( $layout_style == 'xsmall' ) {
			$posts_list_classes[] = 'posts--simple-list--xs';
		}

		if ( $layout_style != 'large' ) {
			if ( $numbered ) {
				$posts_list_classes[] = 'posts--simple-list-numbered';
			}
		}

		// Start the Loop
		$wp_query = new WP_Query( $args );
		if ( $wp_query->have_posts() ) : ?>

		<div class="<?php echo esc_attr( implode( ' ', $posts_list_classes ) ); ?>">
			<?php while ($wp_query->have_posts()) : $wp_query->the_post();

			// get post category class
			$post_class = alchemists_post_category_class(); ?>

			<div class="posts__item <?php echo esc_attr( $post_class ); ?>">

				<?php if ( has_post_thumbnail() && $show_thumb ) { ?>
					<figure class="posts__thumb posts__thumb--hover">
						<a href="<?php the_permalink(); ?>">
							<?php the_post_thumbnail( $post_thumb_size, array( 'class' => '' )); ?>
						</a>
					</figure>
				<?php } ?>

				<div class="posts__inner">

					<?php if ( $categories_toggle ) : ?>
						<?php alchemists_post_category_labels(); ?>
					<?php endif; ?>

					<h6 class="posts__title" title="<?php the_title_attribute(); ?>"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
					<time datetime="<?php esc_attr( the_time('c') ); ?>" class="posts__date">
						<?php the_time( get_option('date_format') ); ?>
					</time>

					<?php if ( $layout_style == 'large') { ?>
					<div class="posts__excerpt">
						<?php echo alchemists_string_limit_words( get_the_excerpt(), $excerpt_size); ?>
					</div>
					<?php } ?>

				</div>

				<?php if ( $layout_style == 'large') { ?>
				<div class="posts__footer">
					<div class="post-author">
						<figure class="post-author__avatar">
							<?php echo get_avatar( get_the_author_meta('email'), '24' ); ?>
						</figure>
						<div class="post-author__info">
							<h4 class="post-author__name">
								<?php the_author(); ?>
							</h4>
						</div>
					</div>
					<div class="post__meta meta">
						<?php
							if ( function_exists( 'get_simple_likes_button') ) {
								echo get_simple_likes_button( get_the_ID() );
							}
						?>
						<?php alchemists_entry_comments(); ?>
					</div>
				</div>
				<?php } ?>

			</div>

			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		</div>

		<?php endif; ?>


		<?php echo wp_kses_post( $after_widget );
	}

	/**
	 * Updates a particular instance of a widget.
	 */

	function update($new_instance, $old_instance) {

		$instance = $old_instance;

		$instance['title']        = strip_tags( $new_instance['title'] );
		$instance['number']       = $new_instance['number'];
		$instance['orderby']      = $new_instance['orderby'];
		$instance['popularity']   = $new_instance['popularity'];
		$instance['cat']          = $new_instance['cat'];
		$instance['show_thumb']   = $new_instance['show_thumb'];
		$instance['numbered']     = $new_instance['numbered'];
		$instance['layout_style'] = $new_instance['layout_style'];
		$instance['excerpt_size'] = $new_instance['excerpt_size'];

		return $instance;
	}


	/**
	 * Outputs the settings update form.
	 */

	function form( $instance ) {

		$defaults = array(
			'title'        => esc_html__( 'Recent Posts', 'alc-advanced-posts' ),
			'number'       => 4,
			'orderby'      => 'date',
			'popularity'   => 'likes',
			'cat'          => esc_html__( 'All', 'alc-advanced-posts' ),
			'show_thumb'   => 'on',
			'numbered'     => 'off',
			'layout_style' => 'small',
			'excerpt_size' => 20,
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'alc-advanced-posts' ); ?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of items to show:', 'alc-advanced-posts' ); ?></label>
			<input class="tiny-text" type="number" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" step="1" min="1" size="3" value="<?php echo esc_attr( $instance['number'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"><?php esc_html_e( 'Order by:', 'alc-advanced-posts' ); ?></label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>" class="widefat" style="width:100%;">
				<option value="date" <?php echo ( 'date' == $instance['orderby'] ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Date', 'alc-advanced-posts' ); ?></option>
				<option value="meta_value_num" <?php echo ( 'meta_value_num' == $instance['orderby'] ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Popularity', 'alc-advanced-posts' ); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'popularity' ) ); ?>"><?php esc_html_e( 'Popularity:', 'alc-advanced-posts' ); ?></label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'popularity' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'popularity' ) ); ?>" class="widefat" style="width:100%;">
				<option value="likes" <?php echo ( 'likes' == $instance['popularity'] ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Likes', 'alc-advanced-posts' ); ?></option>
				<option value="views" <?php echo ( 'views' == $instance['popularity'] ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Views', 'alc-advanced-posts' ); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'cat' ) ); ?>"><?php esc_html_e( 'Category:', 'alc-advanced-posts' ); ?></label>
			<?php wp_dropdown_categories( array(
				'show_option_all'    => esc_attr__( 'All', 'alc-advanced-posts' ),
				'orderby'            => 'ID',
				'order'              => 'ASC',
				'show_count'         => 0,
				'hide_empty'         => 0,
				'hide_if_empty'      => false,
				'echo'               => 1,
				'selected'           => $instance['cat'],
				'hierarchical'       => 1,
				'name'               => $this->get_field_name( 'cat' ),
				'id'                 => $this->get_field_id( 'cat' ),
				'class'              => 'widefat',
				'taxonomy'           => 'category',
			) ); ?>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_thumb'], 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_thumb' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_thumb' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_thumb' ) ); ?>"><?php esc_attr_e( 'Show thumbnail', 'alc-advanced-posts' ); ?></label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['numbered'], 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'numbered' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'numbered' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'numbered' ) ); ?>"><?php esc_attr_e( 'Numbered List?', 'alc-advanced-posts' ); ?></label>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'layout_style' ) ); ?>"><?php esc_html_e( 'Thumb size:', 'alc-advanced-posts' ); ?></label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'layout_style' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'layout_style' ) ); ?>" class="widefat" style="width:100%;">
				<option value="small" <?php echo ( 'small' == $instance['layout_style'] ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Small', 'alc-advanced-posts' ); ?></option>
				<option value="xsmall" <?php echo ( 'xsmall' == $instance['layout_style'] ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Extra Small', 'alc-advanced-posts' ); ?></option>
				<option value="large" <?php echo ( 'large' == $instance['layout_style'] ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Large', 'alc-advanced-posts' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'excerpt_size' ) ); ?>"><?php esc_html_e( 'Excerpt size (number of words):', 'alc-advanced-posts' ); ?></label>
			<input class="tiny-text" type="number" id="<?php echo esc_attr( $this->get_field_id( 'excerpt_size' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'excerpt_size' ) ); ?>" step="1" min="1" size="3" value="<?php echo esc_attr( $instance['excerpt_size'] ); ?>" />
		</p>
		<?php

	}
}
