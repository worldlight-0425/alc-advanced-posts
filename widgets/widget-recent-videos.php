<?php
/**
 * Recent Videos
 *
 * @author    Dan Fisher
 * @package   Alchemists Advanced Posts
 * @since     2.0.0
 * @version   2.0.0
 */


// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}


/**
 * Widget class.
 */

class Alchemists_Widget_Recent_Videos extends WP_Widget {


	/**
	 * Constructor.
	 *
	 * @access public
	 */
	function __construct() {

		$widget_ops = array(
			'classname' => 'recent-videos',
			'description' => esc_html__( 'Display your videos.', 'alc-advanced-posts' ),
		);
		$control_ops = array(
			'id_base' => 'recent-videos-widget'
		);

		parent::__construct( 'recent-videos-widget', 'ALC - Recent Videos', $widget_ops, $control_ops );

	}


	/**
	 * Outputs the widget content.
	 */

	function widget( $args, $instance ) {

		extract( $args );

		$title        = apply_filters( 'widget_title', isset( $instance['title'] ) ? $instance['title'] : '' );
		$number       = isset( $instance['number'] ) ? $instance['number'] : 4;
		$type         = isset( $instance['type'] ) ? $instance['type'] : 'post-format';
		$orderby      = isset( $instance['orderby'] ) ? $instance['orderby'] : 'date';
		$popularity   = isset( $instance['popularity'] ) ? $instance['popularity'] : 'likes';

		echo wp_kses_post( $before_widget );

		if ( $title ) {
			echo wp_kses_post( $before_title ) . esc_html( $title ) . wp_kses_post( $after_title );
		}

		$alchemists_data    = get_option('alchemists_data');
		$post_likes         = isset( $alchemists_data['alchemists__blog-post-likes'] ) ? $alchemists_data['alchemists__blog-post-likes'] : true;
		$post_views         = isset( $alchemists_data['alchemists__blog-post-views'] ) ? $alchemists_data['alchemists__blog-post-views'] : true;
		$post_comments      = isset( $alchemists_data['alchemists__blog-post-comments'] ) ? $alchemists_data['alchemists__blog-post-comments'] : true;

		$args = array(
			'posts_per_page'      => $number,
			'no_found_rows'       => true,
			'orderby'             => $orderby,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
		);

		if ( $type == 'custom-post-type-video' ) {
			$args['post_type'] = 'videos';
		} else {
			$args['post_type'] = 'post';
			$args['tax_query'][] = array(
				'taxonomy' => 'post_format',
				'field' => 'slug',
				'terms' => 'post-format-video'
			);
		}

		$alchemists_data = get_option('alchemists_data');
		$categories_toggle = isset( $alchemists_data['alchemists__posts-categories'] ) ? $alchemists_data['alchemists__posts-categories'] : 1;

		// Post list class
		$posts_list_classes = array(
			'posts',
			'posts--card-compact',
			'row'
		);
		$post_thumb_size = 'alchemists_thumbnail-alt';

		// Post classes
		$post_classes = array(
			'posts__item',
			'card'
		);

		// Featured Image classes
		$thumb_classes = array(
			'posts__thumb',
			'posts__thumb--video'
		);

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

				include ALCADVPOSTS_PLUGIN_DIR . '/widgets/widget-recent-videos/post-layout-video.php';

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
		$instance['type']         = $new_instance['type'];
		$instance['popularity']   = $new_instance['popularity'];

		return $instance;
	}


	/**
	 * Outputs the settings update form.
	 */

	function form( $instance ) {

		$defaults = array(
			'title'        => esc_html__( 'Recent Videos', 'alc-advanced-posts' ),
			'number'       => 4,
			'orderby'      => 'date',
			'type'         => 'post-format',
			'popularity'   => 'likes',
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
			<label for="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>"><?php esc_html_e( 'Source:', 'alc-advanced-posts' ); ?></label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'type' ) ); ?>" class="widefat" style="width:100%;">
				<option value="post-format" <?php echo ( 'post-format' == $instance['type'] ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Video Post Format', 'alc-advanced-posts' ); ?></option>
				<option value="custom-post-type-video" <?php echo ( 'custom-post-type-video' == $instance['type'] ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Video Custom Post Type', 'alc-advanced-posts' ); ?></option>
			</select>
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

		<?php

	}
}
