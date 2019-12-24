<?php
/**
 * Posts in Tabs
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

class Alchemists_Widget_Top_Posts extends WP_Widget {


	/**
	 * Constructor.
	 *
	 * @access public
	 */
	function __construct() {

		$widget_ops = array(
			'classname' => 'widget-tabbed',
			'description' => esc_html__( 'Newest, Most Commented and Popular posts.', 'alc-advanced-posts' ),
		);
		$control_ops = array(
			'id_base' => 'tabbed-widget'
		);

		parent::__construct( 'tabbed-widget', 'ALC - Top Posts', $widget_ops, $control_ops );

	}


	/**
	 * Outputs the widget content.
	 */

	function widget( $args, $instance ) {

		extract( $args );

		$title                 = apply_filters( 'widget_title', isset( $instance['title'] ) ? $instance['title'] : '' );
		$posts_newest_count    = isset( $instance['posts_newest_count'] ) ? $instance['posts_newest_count'] : 3;
		$posts_commented_count = isset( $instance['posts_commented_count'] ) ? $instance['posts_commented_count'] : 3;
		$posts_popular_count   = isset( $instance['posts_popular_count'] ) ? $instance['posts_popular_count'] : 3;
		$popularity            = isset( $instance['popularity'] ) ? $instance['popularity'] : 'likes';
		$show_thumb            = isset( $instance['show_thumb'] ) ? true : false;
		$thumb_size            = isset( $instance['thumb_size'] ) ? $instance['thumb_size'] : 'thumb-80x80';
		$show_excerpt          = isset( $instance['show_excerpt'] ) ? true : false;
		$excerpt_size          = isset( $instance['excerpt_size'] ) ? $instance['excerpt_size'] : 20;

		echo wp_kses_post( $before_widget );

		if( $title ) {
			echo wp_kses_post( $before_title ) . esc_html( $title ) . wp_kses_post( $after_title );
		}

		$alchemists_data = get_option('alchemists_data');
		$categories_toggle = isset( $alchemists_data['alchemists__posts-categories'] ) ? $alchemists_data['alchemists__posts-categories'] : 1;

		$post_thumb_size = 'alchemists_thumbnail-xs';

		if ( 'thumb-112x84' == $thumb_size ) {
			$post_thumb_size = 'alchemists_thumbnail-xs-wide-alt';
		}

		// Generate unique ID
		$unique_id = rand( 0, 9999 );
		?>

		<div class="widget-tabbed__tabs">
			<!-- Widget Tabs -->
			<ul class="nav nav-tabs nav-justified widget-tabbed__nav" role="tablist">
				<li class="nav-item">
					<a href="#widget-tabbed-sm-newest-<?php echo esc_attr( $unique_id ); ?>" class="nav-link active" role="tab" data-toggle="tab"><?php esc_html_e( 'Newest', 'alc-advanced-posts' ); ?></a>
				</li>
				<li class="nav-item">
					<a href="#widget-tabbed-sm-commented-<?php echo esc_attr( $unique_id ); ?>" class="nav-link" role="tab" data-toggle="tab"><?php esc_html_e( 'Most Commented', 'alc-advanced-posts' ); ?></a>
				</li>
				<li class="nav-item">
					<a href="#widget-tabbed-sm-popular-<?php echo esc_attr( $unique_id ); ?>" class="nav-link" role="tab" data-toggle="tab"><?php esc_html_e( 'Popular', 'alc-advanced-posts' ); ?></a>
				</li>
			</ul>

			<!-- Widget Tab panes -->
			<div class="tab-content widget-tabbed__tab-content">
				<!-- Newest -->
				<div role="tabpanel" class="tab-pane fade show active" id="widget-tabbed-sm-newest-<?php echo esc_attr( $unique_id ); ?>">

					<?php
						$args_newest = array(
							'posts_per_page'      => $posts_newest_count,
							'orderby'             => 'date',
							'no_found_rows'       => true,
							'post_status'         => 'publish',
							'ignore_sticky_posts' => true
						);

						// Start the Loop
						$newest_posts_query = new WP_Query( $args_newest );
						if ( $newest_posts_query->have_posts() ) :
					?>
					<ul class="posts posts--simple-list">

						<?php while ( $newest_posts_query->have_posts()) : $newest_posts_query->the_post();

							include ALCADVPOSTS_PLUGIN_DIR . '/widgets/widget-top-posts/widget-top-posts-post.php';

						endwhile; ?>
						<?php wp_reset_postdata(); ?>

					</ul>
					<?php endif; ?>
				</div>
				<!-- Commented -->
				<div role="tabpanel" class="tab-pane fade" id="widget-tabbed-sm-commented-<?php echo esc_attr( $unique_id ); ?>">

					<?php
						$args_commented = array(
							'posts_per_page'      => $posts_commented_count,
							'orderby'             => 'comment_count',
							'no_found_rows'       => true,
							'post_status'         => 'publish',
							'ignore_sticky_posts' => true
						);

						// Start the Loop
						$commented_posts_query = new WP_Query( $args_commented );
						if ( $commented_posts_query->have_posts() ) :
					?>
					<ul class="posts posts--simple-list">

						<?php while ($commented_posts_query->have_posts()) : $commented_posts_query->the_post();

							include ALCADVPOSTS_PLUGIN_DIR . '/widgets/widget-top-posts/widget-top-posts-post.php';

						endwhile;
						wp_reset_postdata(); ?>

					</ul>
					<?php endif; ?>

				</div>
				<!-- Popular -->
				<div role="tabpanel" class="tab-pane fade" id="widget-tabbed-sm-popular-<?php echo esc_attr( $unique_id ); ?>">

					<?php
						$popularity_meta_key = '';
						if ( $popularity == 'likes' ) {
							$popularity_meta_key = '_post_like_count';
						} else {
							$popularity_meta_key = 'post_views_count';
						}
						$args_popular = array(
							'posts_per_page'      => $posts_popular_count,
							'orderby'             => $popularity_meta_key,
							'meta_key'            => $popularity_meta_key,
							'no_found_rows'       => true,
							'post_status'         => 'publish',
							'ignore_sticky_posts' => true
						);

						// Start the Loop
						$popular_posts_query = new WP_Query( $args_popular );
						if ( $popular_posts_query->have_posts() ) :
					?>
					<ul class="posts posts--simple-list">

						<?php while ($popular_posts_query->have_posts()) : $popular_posts_query->the_post();

							include ALCADVPOSTS_PLUGIN_DIR . '/widgets/widget-top-posts/widget-top-posts-post.php';

						endwhile;
						wp_reset_postdata(); ?>

					</ul>
					<?php endif;?>

				</div>
			</div>

		</div>


		<?php echo wp_kses_post( $after_widget );
	}

	/**
	 * Updates a particular instance of a widget.
	 */

	function update($new_instance, $old_instance) {

		$instance = $old_instance;

		$instance['title']                 = strip_tags( $new_instance['title'] );
		$instance['posts_newest_count']    = $new_instance['posts_newest_count'];
		$instance['posts_commented_count'] = $new_instance['posts_commented_count'];
		$instance['posts_popular_count']   = $new_instance['posts_popular_count'];
		$instance['popularity']            = $new_instance['popularity'];
		$instance['show_thumb']            = $new_instance['show_thumb'];
		$instance['thumb_size']            = $new_instance['thumb_size'];
		$instance['show_excerpt']          = $new_instance['show_excerpt'];
		$instance['excerpt_size']          = $new_instance['excerpt_size'];

		return $instance;
	}


	/**
	 * Outputs the settings update form.
	 */

	function form( $instance ) {

		$defaults = array(
			'title'                 => '',
			'posts_newest_count'    => 3,
			'posts_commented_count' => 3,
			'posts_popular_count'   => 3,
			'popularity'            => 'likes',
			'show_newest_posts'     => 'on',
			'show_commented_posts'  => 'on',
			'show_popular_posts'    => 'on',
			'show_thumb'            => 'off',
			'thumb_size'            => 'thumb-80x80',
			'show_excerpt'          => 'on',
			'excerpt_size'          => 20,
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'alc-advanced-posts' ); ?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'posts_newest_count' ) ); ?>"><?php esc_html_e( 'Number of newest posts:', 'alc-advanced-posts' ); ?></label>
			<input class="tiny-text" type="number" id="<?php echo esc_attr( $this->get_field_id( 'posts_newest_count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'posts_newest_count' ) ); ?>" step="1" min="1" size="3" value="<?php echo esc_attr( $instance['posts_newest_count'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'posts_commented_count' ) ); ?>"><?php esc_html_e( 'Number of most commented posts:', 'alc-advanced-posts' ); ?></label>
			<input class="tiny-text" type="number" id="<?php echo esc_attr( $this->get_field_id( 'posts_commented_count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'posts_commented_count' ) ); ?>" step="1" min="1" size="3" value="<?php echo esc_attr( $instance['posts_commented_count'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'posts_popular_count' ) ); ?>"><?php esc_html_e( 'Number of popular posts:', 'alc-advanced-posts' ); ?></label>
			<input class="tiny-text" type="number" id="<?php echo esc_attr( $this->get_field_id( 'posts_popular_count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'posts_popular_count' ) ); ?>" step="1" min="1" size="3" value="<?php echo esc_attr( $instance['posts_popular_count'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'popularity' ) ); ?>"><?php esc_html_e( 'Popularity:', 'alc-advanced-posts' ); ?></label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'popularity' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'popularity' ) ); ?>" class="widefat" style="width:100%;">
				<option value="likes" <?php echo ( 'likes' == $instance['popularity'] ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Likes', 'alc-advanced-posts' ); ?></option>
				<option value="views" <?php echo ( 'views' == $instance['popularity'] ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Views', 'alc-advanced-posts' ); ?></option>
			</select>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_thumb'], 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_thumb' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_thumb' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_thumb' ) ); ?>"><?php esc_attr_e( 'Show thumbnail', 'alc-advanced-posts' ); ?></label>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'thumb_size' ) ); ?>"><?php esc_html_e( 'Thumb size:', 'alc-advanced-posts' ); ?></label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'thumb_size' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'thumb_size' ) ); ?>" class="widefat" style="width:100%;">
				<option value="thumb-80x80" <?php echo ( 'thumb-80x80' == $instance['thumb_size'] ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Small - 80x80', 'alc-advanced-posts' ); ?></option>
				<option value="thumb-112x84" <?php echo ( 'thumb-112x84' == $instance['thumb_size'] ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Small - 112x84', 'alc-advanced-posts' ); ?></option>
			</select>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_excerpt'], 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_excerpt' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_excerpt' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_excerpt' ) ); ?>"><?php esc_attr_e( 'Show excerpt', 'alc-advanced-posts' ); ?></label>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'excerpt_size' ) ); ?>"><?php esc_html_e( 'Excerpt size (number of words):', 'alc-advanced-posts' ); ?></label>
			<input class="tiny-text" type="number" id="<?php echo esc_attr( $this->get_field_id( 'excerpt_size' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'excerpt_size' ) ); ?>" step="1" min="1" size="3" value="<?php echo esc_attr( $instance['excerpt_size'] ); ?>" />
		</p>

		<?php

	}
}
