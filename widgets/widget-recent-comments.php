<?php
/**
 * Recent Comments
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

class Alchemists_Widget_Recent_Comments extends WP_Widget {


	/**
	 * Constructor.
	 *
	 * @access public
	 */
	function __construct() {

		$widget_ops = array(
			'classname' => 'widget-comments',
			'description' => esc_html__( 'A widget to show the most recent comments.', 'alc-advanced-posts' ),
		);
		$control_ops = array(
			'id_base' => 'comments-widget'
		);

		parent::__construct( 'comments-widget', 'ALC - Recent Comments', $widget_ops, $control_ops );

	}


	/**
	 * Outputs the widget content.
	 */

	function widget( $args, $instance ) {

		extract( $args );

		$title        = apply_filters( 'widget_title', isset( $instance['title'] ) ? $instance['title'] : '' );
		$comments_num = isset( $instance['comments_num'] ) ? $instance['comments_num'] : 3;
		$excerpt      = isset( $instance['excerpt'] ) ? $instance['excerpt'] : 20;
		$layout_style = isset( $instance['layout_style'] ) ? $instance['layout_style'] : 'default';

		echo wp_kses_post( $before_widget );

		if( $title ) {
			echo wp_kses_post( $before_title ) . esc_html( $title ) . wp_kses_post( $after_title );
		}
		?>

		<?php
		$comments_query = new WP_Comment_Query();
		$comments = $comments_query->query( array(
			'number' => $comments_num,
			'status' => 'approve',
			'type' => 'comment',
			'post_type' => 'post',
		));

		// echo '<pre>' . var_export($comments, true). '</pre>';

		$comments_list_classes = array('comments-list');
		$comments_item_template = '';

		if ( $layout_style == 'alt' ) {
			$comments_list_classes[] = 'comments-list--alt';
		}

		?>
		<ul class="<?php echo esc_attr( implode( ' ', $comments_list_classes ) ); ?>">

			<?php if ( $comments ) : foreach ( $comments as $comment ) : ?>
			<li class="comments-list__item">
				<header class="comments-list__header">
					<figure class="comments-list__avatar">
						<?php echo get_avatar( $comment->comment_author_email, 40 ) ?>
					</figure>
					<div class="comments-list__info">
						<h5 class="comments-list__author-name"><?php echo get_comment_author( $comment->comment_ID ); ?></h5>

						<?php if ( $layout_style == 'alt') : ?>
						<h6 class="comments-list__post">
							<a href="#"><?php echo get_the_title( $comment->comment_post_ID ); ?></a>
						</h6>
						<?php else : ?>
						<time class="comments-list__date" datetime="<?php echo esc_attr( $comment->comment_date ); ?>">
							<?php printf( _x( '%s ago', '%s = human-readable time difference', 'alc-advanced-posts' ),
								human_time_diff( get_comment_date( 'U', $comment->comment_ID ),
								current_time( 'timestamp' )
							) ); ?>
						</time>
						<?php endif; ?>

					</div>
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>" class="comments-list__link"><span class="icon-options"></span></a>
				</header>
				<div class="comments-list__body">
					<?php echo strip_tags( alchemists_string_limit_words( apply_filters( 'get_comment_text', $comment->comment_content ), $excerpt) ); ?>
				</div>

				<?php if ( $layout_style == 'alt') : ?>
				<footer class="comments-list__footer">
					<time class="comments-list__date" datetime="<?php echo esc_attr( $comment->comment_date ); ?>">
						<?php printf( _x( '%s ago', '%s = human-readable time difference', 'alc-advanced-posts' ),
							human_time_diff( get_comment_date( 'U', $comment->comment_ID ),
							current_time( 'timestamp' )
						) ); ?>
					</time>
				</footer>
				<?php endif; ?>

			</li>

			<?php endforeach; else : ?>

			<li class="comments-list__item">
				<div class="comments-list__body">
					<?php esc_html_e( 'No comments.', 'alc-advanced-posts' ); ?>
				</div>
			</li>

			<?php endif; ?>

		</ul>


		<?php echo wp_kses_post( $after_widget );
	}

	/**
	 * Updates a particular instance of a widget.
	 */

	function update($new_instance, $old_instance) {

		$instance = $old_instance;

		$instance['title']        = strip_tags( $new_instance['title'] );
		$instance['comments_num'] = $new_instance['comments_num'];
		$instance['excerpt']      = $new_instance['excerpt'];
		$instance['layout_style'] = $new_instance['layout_style'];

		return $instance;
	}


	/**
	 * Outputs the settings update form.
	 */

	function form( $instance ) {

		$defaults = array(
			'title'        => '',
			'comments_num' => 3,
			'excerpt'      => 20,
			'layout_style' => 'default',
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'alc-advanced-posts' ); ?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'comments_num' ) ); ?>"><?php esc_html_e( 'Number of comments:', 'alc-advanced-posts' ); ?></label>
			<input class="tiny-text" type="number" id="<?php echo esc_attr( $this->get_field_id( 'comments_num' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'comments_num' ) ); ?>" step="1" min="1" size="3" value="<?php echo esc_attr( $instance['comments_num'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'excerpt' ) ); ?>"><?php esc_html_e( 'Comment size (number of words):', 'alc-advanced-posts' ); ?></label>
			<input class="tiny-text" type="number" id="<?php echo esc_attr( $this->get_field_id( 'excerpt' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'excerpt' ) ); ?>" step="1" min="1" size="3" value="<?php echo esc_attr( $instance['excerpt'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'layout_style' ) ); ?>"><?php esc_html_e( 'Layout:', 'alc-advanced-posts' ); ?></label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'layout_style' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'layout_style' ) ); ?>" class="widefat">
				<option value="default" <?php echo ( 'default' == $instance['layout_style'] ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Layout 1', 'alc-advanced-posts' ); ?></option>
				<option value="alt" <?php echo ( 'alt' == $instance['layout_style'] ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Layout 2', 'alc-advanced-posts' ); ?></option>
			</select>
		</p>


		<?php

	}
}
