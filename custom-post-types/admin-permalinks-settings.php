<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Permalinks settings
 *
 * Adds settings to the permalinks admin settings page.
 *
 * @author    Dan Fisher
 * @package   Alchemists Advanced Posts
 * @version   2.0.0
 * @since     2.0.0
 */

if ( ! class_exists( 'ALC_Admin_Permalink_Settings' ) ) :

	/**
	 * ALC_Admin_Permalink_Settings Class
	 */
	class ALC_Admin_Permalink_Settings {

		/**
		 * Hook in tabs.
		 */
		public function __construct() {
			$this->slugs = apply_filters( 'alchemists_permalink_slugs', array(
        array( 'album', __( 'Albums', 'alc-advanced-posts' ) ),
        array( 'video', __( 'Videos', 'alc-advanced-posts' ) ),
			) );

			add_action( 'admin_init', array( $this, 'settings_init' ) );
			add_action( 'admin_init', array( $this, 'settings_save' ) );
		}

		/**
		 * Init our settings
		 */
		public function settings_init() {
			// Add a section to the permalinks page
			add_settings_section( 'alchemists-permalink', __( 'Alchemists', 'alchemists-custom-post-types' ), array( $this, 'settings' ), 'permalink' );

			// Add our settings
			foreach ( $this->slugs as $slug ):
				add_settings_field(	
					$slug[0],                     // id
					$slug[1],                     // setting title
					array( $this, 'slug_input' ), // display callback
					'permalink',                  // settings page
					'alchemists-permalink'        // settings section
				);
			endforeach;
		}

		/**
		 * Show a slug input box.
		 */
		public function slug_input() {
			$slug = array_shift( $this->slugs );
			$key = $slug[0];
			$text = get_option( 'alchemists_' . $key . '_slug', null );
			?><fieldset><input id="alchemists_<?php echo $key; ?>_slug" name="alchemists_<?php echo $key; ?>_slug" type="text" class="regular-text code" value="<?php echo $text; ?>" placeholder="<?php echo $key; ?>"></fieldset><?php
		}

		/**
		 * Show the settings
		 */
		public function settings() {
			echo wpautop( __( 'These settings control the permalinks used for Alchemists custom post types. These settings only apply when <strong>not using "plain" permalinks above</strong>.', 'alc-advanced-posts' ) );
		}

		/**
		 * Save the settings
		 */
		public function settings_save() {
			if ( ! is_admin() )
				return;

			if ( isset( $_POST['permalink_structure'] ) ):
				foreach ( $this->slugs as $slug ):
					$key = 'alchemists_' . $slug[0] . '_slug';
					$value = null;
					if ( isset( $_POST[ $key ] ) )
						$value = sanitize_text_field( $_POST[ $key ] );
					if ( empty( $value ) )
						delete_option( $key );
					else
						update_option( $key, $value );
				endforeach;
				flush_rewrite_rules();
			endif;
		}
	}

endif;

return new ALC_Admin_Permalink_Settings();
