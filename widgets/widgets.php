<?php
/**
 * Alchemists widgets
 *
 * @author    Dan Fisher
 * @package   Alchemists Advanced Posts
 * @since     1.1.0
 * @version   1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Load the widget on widgets_init
function alc_init_widgets() {
	register_widget( 'Alchemists_Widget_Instagram_Feed' );
	register_widget( 'Alchemists_Widget_Top_Posts' );
	register_widget( 'Alchemists_Widget_Recent_Comments' );
	register_widget( 'Alchemists_Widget_Contact_Info' );
	register_widget( 'Alchemists_Widget_Recent_Posts' );
}
add_action( 'widgets_init', 'alc_init_widgets' );

include_once ALCADVPOSTS_PLUGIN_DIR . '/widgets/widget-instagram-feed.php';
include_once ALCADVPOSTS_PLUGIN_DIR . '/widgets/widget-top-posts.php';
include_once ALCADVPOSTS_PLUGIN_DIR . '/widgets/widget-recent-comments.php';
include_once ALCADVPOSTS_PLUGIN_DIR . '/widgets/widget-contact-info.php';
include_once ALCADVPOSTS_PLUGIN_DIR . '/widgets/widget-recent-posts.php';
