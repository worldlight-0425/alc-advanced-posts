<?php
/**
 * Recent Posts
 *
 * @author    Dan Fisher
 * @package   Alchemists Advanced Posts
 * @since     1.1.0
 * @version   2.0.0
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

		if ( $title ) {
			echo wp_kses_post( $before_title ) . esc_html( $title ) . wp_kses_post( $after_title );
		}

		$alchemists_data    = get_option('alchemists_data');
		$post_likes         = isset( $alchemists_data['alchemists__blog-post-likes'] ) ? $alchemists_data['alchemists__blog-post-likes'] : true;
		$post_views         = isset( $alchemists_data['alchemists__blog-post-views'] ) ? $alchemists_data['alchemists__blog-post-views'] : true;
		$post_comments      = isset( $alchemists_data['alchemists__blog-post-comments'] ) ? $alchemists_data['alchemists__blog-post-comments'] : true;

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
		);
		$post_thumb_size = 'alchemists_thumbnail-xs';

		if ( $layout_style != 'xlarge' ) {
			$posts_list_classes[] = 'posts--simple-list';
		} else {
			$posts_list_classes[] = 'posts--tile';
			$post_thumb_size = 'alchemists_thumbnail-square';
		}

		if ( $layout_style == 'large' ) {
			array_push( $posts_list_classes, 'posts--simple-list--lg', 'posts--simple-list--lg--clean' );
			$post_thumb_size = 'alchemists_thumbnail';
		} elseif ( $layout_style == 'xsmall' ) {
			$posts_list_classes[] = 'posts--simple-list--xs';
		} elseif ( $layout_style == 'small-wide' ) {
			$post_thumb_size = 'alchemists_thumbnail-xs-wide';
		} elseif ( $layout_style == 'small-wide-alt' ) {
			$post_thumb_size = 'alchemists_thumbnail-xs-wide-alt';
		}

		if ( $layout_style != 'large' ) {
			if ( $numbered ) {
				$posts_list_classes[] = 'posts--simple-list-numbered';
			}
		}

		// Post classes
		$post_classes = array(
			'posts__item'
		);
		
		if ( $layout_style == 'xlarge' ) {
			array_push( $post_classes, 'posts__item--tile', 'card' );
		}

		// Featured Image classes
		$thumb_classes = array(
			'posts__thumb'
		);

		if ( $layout_style == 'xsmall' || $layout_style == 'small' || $layout_style == 'small-wide' || $layout_style == 'large' ) {
			$thumb_classes[] = 'posts__thumb--hover';
		}

		if ( $layout_style == 'xlarge' ) {
			if ( alchemists_sp_preset( 'football' ) ) {
				$thumb_classes[] = 'effect-duotone effect-duotone--base';
			} else {
				$thumb_classes[] = 'posts__thumb--overlay-dark';
			}
		}

		$thumb_classes = implode( ' ', $thumb_classes );

		// Start the Loop
		$wp_query = new WP_Query( $args );
		if ( $wp_query->have_posts() ) : ?>

		<div class="<?php echo esc_attr( implode( ' ', $posts_list_classes ) ); ?>">
			<?php
			while ($wp_query->have_posts()) : $wp_query->the_post();
				// get post category class
				$post_class = alchemists_post_category_class();
				$post_classes[] = $post_class;

				include ALCADVPOSTS_PLUGIN_DIR . '/widgets/widget-recent-posts/post-layout-' . $layout_style . '.php';

			endwhile;
			wp_reset_postdata();
			?>
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
				<option value="small" <?php echo ( 'small' == $instance['layout_style'] ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Small - 80x80', 'alc-advanced-posts' ); ?></option>
				<option value="small-wide" <?php echo ( 'small-wide' == $instance['layout_style'] ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Small - 90x68', 'alc-advanced-posts' ); ?></option>
				<option value="small-wide-alt" <?php echo ( 'small-wide-alt' == $instance['layout_style'] ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Small - 112x84', 'alc-advanced-posts' ); ?></option>
				<option value="xsmall" <?php echo ( 'xsmall' == $instance['layout_style'] ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Extra Small - 50x50', 'alc-advanced-posts' ); ?></option>
				<option value="large" <?php echo ( 'large' == $instance['layout_style'] ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Large - 380x270', 'alc-advanced-posts' ); ?></option>
				<option value="xlarge" <?php echo ( 'xlarge' == $instance['layout_style'] ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Extra Large - 400x400', 'alc-advanced-posts' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'excerpt_size' ) ); ?>"><?php esc_html_e( 'Excerpt size (number of words):', 'alc-advanced-posts' ); ?></label>
			<input class="tiny-text" type="number" id="<?php echo esc_attr( $this->get_field_id( 'excerpt_size' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'excerpt_size' ) ); ?>" step="1" min="1" size="3" value="<?php echo esc_attr( $instance['excerpt_size'] ); ?>" />
		</p>
		<?php

	}
}
