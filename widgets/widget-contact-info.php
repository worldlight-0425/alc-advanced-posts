<?php
/**
 * Contact Info
 *
 * @author    Dan Fisher
 * @package   Alchemists Advanced Posts
 * @since     1.1.0
 * @version   1.1.5
 */


// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}


/**
 * Widget class.
 */

class Alchemists_Widget_Contact_Info extends WP_Widget {


	/**
	 * Constructor.
	 *
	 * @access public
	 */
	function __construct() {

		$widget_ops = array(
			'classname' => 'widget-contact-info',
			'description' => esc_html__( 'Display Contact Info as a widget.', 'alc-advanced-posts' ),
		);
		$control_ops = array(
			'id_base' => 'contact-info-widget'
		);

		parent::__construct( 'contact-info-widget', 'ALC - Contact Info', $widget_ops, $control_ops );

	}


	/**
	 * Outputs the widget content.
	 */

	function widget( $args, $instance ) {

		extract( $args );

		$title        = apply_filters( 'widget_title', isset( $instance['title'] ) ? $instance['title'] : '' );
		$desc         = isset( $instance['desc'] ) ? $instance['desc'] : '';
		$label_1      = isset( $instance['label_1'] ) ? $instance['label_1'] : '';
		$email_1      = isset( $instance['email_1'] ) ? $instance['email_1'] : '';
		$icon_1       = isset( $instance['icon_1'] ) ? $instance['icon_1'] : '';
		$label_2      = isset( $instance['label_2'] ) ? $instance['label_2'] : '';
		$email_2      = isset( $instance['email_2'] ) ? $instance['email_2'] : '';
		$icon_2       = isset( $instance['icon_2'] ) ? $instance['icon_2'] : '';
		$soc_tw       = isset( $instance['soc_tw'] ) ? $instance['soc_tw'] : '';
		$soc_fb       = isset( $instance['soc_fb'] ) ? $instance['soc_fb'] : '';
		$soc_ggl      = isset( $instance['soc_ggl'] ) ? $instance['soc_ggl'] : '';
		$soc_inst     = isset( $instance['soc_inst'] ) ? $instance['soc_inst'] : '';
		$soc_tele     = isset( $instance['soc_tele'] ) ? $instance['soc_tele'] : '';
		$soc_snap     = isset( $instance['soc_snap'] ) ? $instance['soc_snap'] : '';
		$soc_twitch   = isset( $instance['soc_twitch'] ) ? $instance['soc_twitch'] : '';

		echo wp_kses_post( $before_widget );

		if ( $title ) {
			echo wp_kses_post( $before_title ) . esc_html( $title ) . wp_kses_post( $after_title );
		}

		// check if Primary Email Address is an email address or link
		if ( filter_var( $email_1, FILTER_VALIDATE_EMAIL ) ) {
			$email_1_attr = 'mailto:' . $email_1;
		} elseif ( filter_var( $email_1, FILTER_VALIDATE_URL ) ) {
			$email_1_attr = esc_url( $email_1 );
		} else {
			$email_1_attr = 'tel:' . $email_1;
		}

		// check if Secondary Email Address is an email address or link
		if ( filter_var( $email_2, FILTER_VALIDATE_EMAIL ) ) {
			$email_2_attr = 'mailto:' . $email_2;
		} elseif ( filter_var( $email_2, FILTER_VALIDATE_URL ) ) {
			$email_2_attr = esc_url( $email_2 );
		} else {
			$email_2_attr = 'tel:' . $email_2;
		}
		?>

		<?php if ( !empty( $desc ) ) : ?>
		<div class="widget-contact-info__desc">
			<p><?php echo wp_kses_post( $desc ); ?></p>
		</div>
		<?php endif; ?>
		<div class="widget-contact-info__body info-block">

			<?php if ( !empty( $email_1 ) ) : ?>
				<div class="info-block__item">
					<?php if ( ! empty( $icon_1 ) ) : ?>
						<span class="df-icon-custom"><?php echo $icon_1; ?></span>
					<?php else : ?>
						<?php if ( alchemists_sp_preset( 'soccer' ) ) : ?>
							<svg role="img" class="df-icon df-icon--soccer-ball">
								<use xlink:href="<?php echo get_template_directory_uri(); ?>/assets/images/icons-soccer.svg#soccer-ball"/>
							</svg>
						<?php elseif ( alchemists_sp_preset( 'football' ) ) : ?>
							<svg role="img" class="df-icon df-icon--football-helmet">
								<use xlink:href="<?php echo get_template_directory_uri(); ?>/assets/images/football/icons-football.svg#football-helmet"/>
							</svg>
						<?php else : ?>
							<svg role="img" class="df-icon df-icon--basketball">
								<use xlink:href="<?php echo get_template_directory_uri(); ?>/assets/images/icons-basket.svg#basketball"/>
							</svg>
						<?php endif; ?>
					<?php endif; ?>

					<?php if ( !empty( $label_1 ) ) : ?>
						<h6 class="info-block__heading"><?php echo esc_html( $label_1 ); ?></h6>
					<?php endif; ?>
					<a class="info-block__link" href="<?php echo $email_1_attr; ?>"><?php echo esc_html( alchemists_remove_protocol( $email_1 ) ); ?></a>
				</div>
			<?php endif; ?>

			<?php if ( !empty( $email_2 ) ) : ?>
				<div class="info-block__item">

					<?php if ( ! empty( $icon_2 ) ) : ?>
						<span class="df-icon-custom"><?php echo $icon_2; ?></span>
					<?php else : ?>
						<?php if ( alchemists_sp_preset('soccer') ) : ?>
							<svg role="img" class="df-icon df-icon--whistle">
								<use xlink:href="<?php echo get_template_directory_uri(); ?>/assets/images/icons-soccer.svg#whistle"/>
							</svg>
						<?php elseif ( alchemists_sp_preset( 'football' ) ) : ?>
							<svg role="img" class="df-icon df-icon--football-ball">
								<use xlink:href="<?php echo get_template_directory_uri(); ?>/assets/images/football/icons-football.svg#football-ball"/>
							</svg>
						<?php else : ?>
							<svg role="img" class="df-icon df-icon--jersey">
								<use xlink:href="<?php echo get_template_directory_uri(); ?>/assets/images/icons-basket.svg#jersey"/>
							</svg>
						<?php endif; ?>
					<?php endif; ?>

					<?php if ( !empty( $label_2) ) : ?>
						<h6 class="info-block__heading"><?php echo esc_html( $label_2); ?></h6>
					<?php endif; ?>
					<a class="info-block__link" href="<?php echo $email_2_attr; ?>"><?php echo esc_html( alchemists_remove_protocol( $email_2 ) ); ?></a>
				</div>
			<?php endif; ?>

			<?php if ( !empty( $soc_tw ) || !empty( $soc_fb) || !empty( $soc_ggl) || !empty( $soc_inst) ) : ?>
			<div class="info-block__item info-block__item--nopadding">
				<ul class="social-links">

					<?php if ( !empty( $soc_fb) ): ?>
					<li class="social-links__item">
						<a href="<?php echo esc_attr( $soc_fb ); ?>" class="social-links__link" target="_blank"><i class="fa fa-facebook"></i> <?php esc_html_e( 'Facebook', 'alc-advanced-posts' ); ?></a>
					</li>
					<?php endif; ?>

					<?php if ( !empty( $soc_tw) ): ?>
					<li class="social-links__item">
						<a href="<?php echo esc_attr( $soc_tw ); ?>" class="social-links__link" target="_blank"><i class="fa fa-twitter"></i> <?php esc_html_e( 'Twitter', 'alc-advanced-posts' ); ?></a>
					</li>
					<?php endif; ?>

					<?php if ( !empty( $soc_ggl) ): ?>
					<li class="social-links__item">
						<a href="<?php echo esc_attr( $soc_ggl ); ?>" class="social-links__link" target="_blank"><i class="fa fa-google-plus"></i> <?php esc_html_e( 'Google+', 'alc-advanced-posts' ); ?></a>
					</li>
					<?php endif; ?>

					<?php if ( !empty( $soc_inst) ): ?>
					<li class="social-links__item">
						<a href="<?php echo esc_attr( $soc_inst ); ?>" class="social-links__link" target="_blank"><i class="fa fa-instagram"></i> <?php esc_html_e( 'Instagram', 'alc-advanced-posts' ); ?></a>
					</li>
					<?php endif; ?>

					<?php if ( !empty( $soc_tele) ): ?>
					<li class="social-links__item">
						<a href="<?php echo esc_attr( $soc_tele ); ?>" class="social-links__link" target="_blank"><i class="fa fa-paper-plane"></i> <?php esc_html_e( 'Telegram', 'alc-advanced-posts' ); ?></a>
					</li>
					<?php endif; ?>
					
					<?php if ( !empty( $soc_snap) ): ?>
					<li class="social-links__item">
						<a href="<?php echo esc_attr( $soc_snap ); ?>" class="social-links__link" target="_blank"><i class="fa fa-snapchat-ghost"></i> <?php esc_html_e( 'Snapchat', 'alc-advanced-posts' ); ?></a>
					</li>
					<?php endif; ?>

					<?php if ( !empty( $soc_twitch) ): ?>
					<li class="social-links__item">
						<a href="<?php echo esc_attr( $soc_twitch ); ?>" class="social-links__link" target="_blank"><i class="fa fa-twitch"></i> <?php esc_html_e( 'Twitch', 'alc-advanced-posts' ); ?></a>
					</li>
					<?php endif; ?>
				</ul>
			</div>
			<?php endif; ?>
		</div>


		<?php echo wp_kses_post( $after_widget );
	}

	/**
	 * Updates a particular instance of a widget.
	 */

	function update($new_instance, $old_instance) {

		$instance = $old_instance;

		$instance['title']      = strip_tags( $new_instance['title'] );
		$instance['desc']       = $new_instance['desc'];
		$instance['label_1']    = $new_instance['label_1'];
		$instance['email_1']    = $new_instance['email_1'];
		$instance['icon_1']     = $new_instance['icon_1'];
		$instance['label_2']    = $new_instance['label_2'];
		$instance['email_2']    = $new_instance['email_2'];
		$instance['icon_2']     = $new_instance['icon_2'];
		$instance['soc_tw']     = $new_instance['soc_tw'];
		$instance['soc_fb']     = $new_instance['soc_fb'];
		$instance['soc_ggl']    = $new_instance['soc_ggl'];
		$instance['soc_inst']   = $new_instance['soc_inst'];
		$instance['soc_tele']   = $new_instance['soc_tele'];
		$instance['soc_snap']   = $new_instance['soc_snap'];
		$instance['soc_twitch'] = $new_instance['soc_twitch'];

		return $instance;
	}


	/**
	 * Outputs the settings update form.
	 */

	function form( $instance ) {

		$defaults = array(
			'title'      => '',
			'desc'       => '',
			'label_1'    => '',
			'email_1'    => '',
			'icon_1'     => '',
			'label_2'    => '',
			'email_2'    => '',
			'icon_2'     => '',
			'soc_tw'     => '',
			'soc_fb'     => '',
			'soc_ggl'    => '',
			'soc_inst'   => '',
			'soc_tele'   => '',
			'soc_snap'   => '',
			'soc_twitch' => '',
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'alc-advanced-posts' ); ?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'desc' ) ); ?>"><?php esc_html_e( 'Short Info:', 'alc-advanced-posts' ); ?></label>
			<textarea class="widefat" row="4" cols="20" id="<?php echo esc_attr( $this->get_field_id( 'desc' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'desc' ) ); ?>"><?php echo esc_attr( $instance['desc'] ); ?></textarea>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'label_1' ) ); ?>"><?php esc_html_e( '1st Label:', 'alc-advanced-posts' ); ?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'label_1' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'label_1' ) ); ?>" value="<?php echo esc_attr( $instance['label_1'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'email_1' ) ); ?>"><?php esc_html_e( '1st Email, Link or Phone:', 'alc-advanced-posts' ); ?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'email_1' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'email_1' ) ); ?>" value="<?php echo esc_attr( $instance['email_1'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'icon_1' ) ); ?>"><?php esc_html_e( '1st Custom Icon:', 'alc-advanced-posts' ); ?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'icon_1' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'icon_1' ) ); ?>" value="<?php echo esc_attr( $instance['icon_1'] ); ?>" />
			<p class="help"><?php _e( 'Add your custom icon, e.g. <code>&lt;i class="fa fa-user"&gt;&lt;/i&gt;</code> or <code>&lt;img src="PATH_TO_IMAGE" /&gt;</code>', 'alc-advanced-posts'); ?></p>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'label_2' ) ); ?>"><?php esc_html_e( '2nd Label:', 'alc-advanced-posts' ); ?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'label_2' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'label_2' ) ); ?>" value="<?php echo esc_attr( $instance['label_2'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'email_2' ) ); ?>"><?php esc_html_e( '2nd Email, Link or Phone:', 'alc-advanced-posts' ); ?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'email_2' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'email_2' ) ); ?>" value="<?php echo esc_attr( $instance['email_2'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'icon_2' ) ); ?>"><?php esc_html_e( '2nd Custom Icon:', 'alc-advanced-posts' ); ?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'icon_2' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'icon_2' ) ); ?>" value="<?php echo esc_attr( $instance['icon_2'] ); ?>" />
			<p class="help"><?php _e( 'Add your custom icon, e.g. <code>&lt;i class="fa fa-user"&gt;&lt;/i&gt;</code> or <code>&lt;img src="PATH_TO_IMAGE" /&gt;</code>', 'alc-advanced-posts'); ?></p>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'soc_fb' ) ); ?>"><?php esc_html_e( 'Social - Facebook:', 'alc-advanced-posts' ); ?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'soc_fb' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'soc_fb' ) ); ?>" value="<?php echo esc_attr( $instance['soc_fb'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'soc_tw' ) ); ?>"><?php esc_html_e( 'Social - Twitter:', 'alc-advanced-posts' ); ?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'soc_tw' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'soc_tw' ) ); ?>" value="<?php echo esc_attr( $instance['soc_tw'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'soc_ggl' ) ); ?>"><?php esc_html_e( 'Social - Google+:', 'alc-advanced-posts' ); ?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'soc_ggl' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'soc_ggl' ) ); ?>" value="<?php echo esc_attr( $instance['soc_ggl'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'soc_inst' ) ); ?>"><?php esc_html_e( 'Social - Instagram:', 'alc-advanced-posts' ); ?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'soc_inst' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'soc_inst' ) ); ?>" value="<?php echo esc_attr( $instance['soc_inst'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'soc_tele' ) ); ?>"><?php esc_html_e( 'Social - Telegram:', 'alc-advanced-posts' ); ?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'soc_tele' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'soc_tele' ) ); ?>" value="<?php echo esc_attr( $instance['soc_tele'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'soc_snap' ) ); ?>"><?php esc_html_e( 'Social - Snapchat:', 'alc-advanced-posts' ); ?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'soc_snap' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'soc_snap' ) ); ?>" value="<?php echo esc_attr( $instance['soc_snap'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'soc_twitch' ) ); ?>"><?php esc_html_e( 'Social - Twitch:', 'alc-advanced-posts' ); ?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'soc_twitch' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'soc_twitch' ) ); ?>" value="<?php echo esc_attr( $instance['soc_twitch'] ); ?>" />
		</p>


		<?php

	}
}
