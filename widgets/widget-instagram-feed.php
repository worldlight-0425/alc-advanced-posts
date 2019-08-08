<?php
/**
 * Instagram
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

class Alchemists_Widget_Instagram_Feed extends WP_Widget {


	/**
	 * Constructor.
	 *
	 * @access public
	 */
	function __construct() {

		$widget_ops = array(
			'classname' => 'widget-instagram',
			'description' => esc_html__( 'Display Instagram feed as a widget.', 'alc-advanced-posts' ),
		);
		$control_ops = array(
			'id_base' => 'instagram-widget'
		);

		parent::__construct( 'instagram-widget', 'ALC - Instagram Feed', $widget_ops, $control_ops );

		//enqueue JS on frontend only if widget is active.
		if(is_active_widget(false, false, $this->id_base)) {
			add_action('wp_enqueue_scripts', 'alchemists_instafeed_widget_load');
		}

	}


	/**
	 * Outputs the widget content.
	 */

	function widget( $args, $instance ) {

		extract( $args );

		$title        = apply_filters( 'widget_title', isset( $instance['title'] ) ? $instance['title'] : '' );
		$user_id      = isset( $instance['user_id'] ) ? $instance['user_id'] : '';
		$user_name    = isset( $instance['user_name'] ) ? $instance['user_name'] : '';
		$access_token = isset( $instance['access_token'] ) ? $instance['access_token'] : '';
		$img_count    = isset( $instance['img_count'] ) ? $instance['img_count'] : 6;
		$layout_style = isset( $instance['layout_style'] ) ? $instance['layout_style'] : '3cols';
		$show_button  = isset( $instance['show_button'] ) ? true : false;
		$btn_label    = isset( $instance['btn_label'] ) ? $instance['btn_label'] : esc_html__( 'Follow Our Instagram', 'alc-advanced-posts' );

		echo wp_kses_post( $before_widget );

		if( $title ) {
			echo wp_kses_post( $before_title ) . esc_html( $title ) . wp_kses_post( $after_title );
		}
		?>


		<?php
		$uid = uniqid();
		$alchemists_data        = get_option('alchemists_data');
		$alchemists_insta_user  = isset( $alchemists_data['alchemists__opt-social-insta-user'] ) ? esc_html( $alchemists_data['alchemists__opt-social-insta-user'] ) : '';
		$alchemists_insta_token = isset( $alchemists_data['alchemists__opt-social-insta-token'] ) ? esc_html( $alchemists_data['alchemists__opt-social-insta-token'] ) : '';

		$instagram_layout = '';
		if ( $layout_style == '4cols' ) {
			$instagram_layout = 'widget-instagram__list--4cols';
		}
		?>

		<ul id="instagram-<?php echo esc_attr( $uid ); ?>" class="widget-instagram__list <?php echo esc_attr( $instagram_layout ); ?>"></ul>

		<?php if ( $show_button ) : ?>
		<a href="https://www.instagram.com/<?php echo esc_attr( $user_name ); ?>" class="btn btn-sm btn-instagram btn-icon-right" target="_blank"><?php echo esc_html( $btn_label ); ?> <i class="icon-arrow-right"></i></a>
		<?php endif; ?>

		<?php if ( ! empty( $user_id ) && ! empty( $access_token ) ) : ?>
		<script type="text/javascript">
			jQuery(document).on('ready', function() {
				var feed_<?php echo esc_js( $uid ); ?> = new Instafeed({
					get: 'user',
					target: 'instagram-<?php echo esc_js( $uid ); ?>',
					userId: '<?php echo esc_js( $user_id ); ?>',
					accessToken: '<?php echo esc_js( $access_token ); ?>',
					limit: <?php echo esc_js( $img_count ); ?>,
					template: '<li class="widget-instagram__item"><a href="{{link}}" id="{{id}}" class="widget-instagram__link-wrapper" target="_blank"><span class="widget-instagram__plus-sign"><img src="{{image}}" alt="" class="widget-instagram__img" /></span></a></li>'
				});
				feed_<?php echo esc_js( $uid ); ?>.run();
			});
		</script>
		<?php endif; ?>

		<?php echo wp_kses_post( $after_widget );
	}

	/**
	 * Updates a particular instance of a widget.
	 */

	function update($new_instance, $old_instance) {

		$instance = $old_instance;

		$instance['title']        = strip_tags( $new_instance['title'] );
		$instance['user_id']      = $new_instance['user_id'];
		$instance['user_name']    = $new_instance['user_name'];
		$instance['access_token'] = $new_instance['access_token'];
		$instance['img_count']    = $new_instance['img_count'];
		$instance['layout_style'] = $new_instance['layout_style'];
		$instance['show_button']  = $new_instance['show_button'];
		$instance['btn_label']    = $new_instance['btn_label'];

		return $instance;
	}


	/**
	 * Outputs the settings update form.
	 */

	function form( $instance ) {

		$defaults = array(
			'title'        => '',
			'user_id'      => '',
			'user_name'    => '',
			'access_token' => '',
			'img_count'    => 6,
			'layout_style' => '3cols',
			'show_button'  => 'on',
			'btn_label'    => esc_html__( 'Follow Our Instagram', 'alc-advanced-posts' ),
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'alc-advanced-posts' ); ?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'user_name' ) ); ?>"><?php esc_html_e( 'Username:', 'alc-advanced-posts' ); ?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'user_name' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'user_name' ) ); ?>" value="<?php echo esc_attr( $instance['user_name'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'user_id' ) ); ?>"><?php esc_html_e( 'User ID:', 'alc-advanced-posts' ); ?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'user_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'user_id' ) ); ?>" value="<?php echo esc_attr( $instance['user_id'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'access_token' ) ); ?>"><?php esc_html_e( 'Access Token:', 'alc-advanced-posts' ); ?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'access_token' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'access_token' ) ); ?>" value="<?php echo esc_attr( $instance['access_token'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'img_count' ) ); ?>"><?php esc_html_e( 'Number of images:', 'alc-advanced-posts' ); ?></label>
			<input class="tiny-text" type="number" id="<?php echo esc_attr( $this->get_field_id( 'img_count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'img_count' ) ); ?>" step="1" min="1" size="3" value="<?php echo esc_attr( $instance['img_count'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'layout_style' ) ); ?>"><?php esc_html_e( 'Layout:', 'alc-advanced-posts' ); ?></label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'layout_style' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'layout_style' ) ); ?>" class="widefat">
				<option value="3cols" <?php echo ( '3cols' == $instance['layout_style'] ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( '3 Columns', 'alc-advanced-posts' ); ?></option>
				<option value="4cols" <?php echo ( '4cols' == $instance['layout_style'] ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( '4 Columns', 'alc-advanced-posts' ); ?></option>
			</select>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_button'], 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_button' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_button' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_button' ) ); ?>"><?php esc_attr_e( 'Show Follow Button', 'alc-advanced-posts' ); ?></label>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'btn_label' ) ); ?>"><?php esc_html_e( 'Follow Button Label:', 'alc-advanced-posts' ); ?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'btn_label' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'btn_label' ) ); ?>" value="<?php echo esc_attr( $instance['btn_label'] ); ?>" />
		</p>

		<?php

	}
}
