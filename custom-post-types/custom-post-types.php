<?php
/**
 * Register Custom Post Types
 *
 * @author    Dan Fisher
 * @package   Alchemists Advanced Posts
 * @since     1.0.0
 * @version   2.0.0
 */

/**
 * Album Custom Post Type
 */
add_action('init', 'alchemists_albums_custom_init');
function alchemists_albums_custom_init(){

	// Initialize Albums Custom Type Labels
	$labels = array(
		'name'               => _x('Albums', 'post type general name', 'alc-advanced-posts'),
		'singular_name'      => _x('Album', 'post type singular name', 'alc-advanced-posts'),
		'add_new'            => _x('Add New', 'Album', 'alc-advanced-posts'),
		'add_new_item'       => __('Add New Album', 'alc-advanced-posts'),
		'edit_item'          => __('Edit Album', 'alc-advanced-posts'),
		'new_item'           => __('New Album', 'alc-advanced-posts'),
		'view_item'          => __('View Album', 'alc-advanced-posts'),
		'search_items'       => __('Search Albums', 'alc-advanced-posts'),
		'not_found'          => __('No albums found', 'alc-advanced-posts'),
		'not_found_in_trash' => __('No albums found in Trash', 'alc-advanced-posts'),
		'parent_item_colon'  => '',
		'menu_name'          => __('Albums', 'alc-advanced-posts'),
	);

	$args = array(
		'labels'        => $labels,
		'public'        => true,
		'show_ui'       => true,
		'query_var'     => true,
		'rewrite'       => array(
			'slug' => get_option( 'alchemists_album_slug', 'album' ),
		),
		'menu_position' => 30,
		'menu_icon'     => 'dashicons-format-gallery',
		'supports' => array(
			'title',
			'thumbnail',
		)
	);
	register_post_type( 'albums', $args );

	// Initialize New Categories Labels
	$labels = array(
		'name'              => _x( 'Albums Categories', 'category general name', 'alc-advanced-posts' ),
		'singular_name'     => _x( 'Albums Category', 'taxonomy singular name', 'alc-advanced-posts' ),
		'search_items'      => __( 'Search Category', 'alc-advanced-posts' ),
		'all_items'         => __( 'All Categories', 'alc-advanced-posts' ),
		'parent_item'       => __( 'Parent Category', 'alc-advanced-posts' ),
		'parent_item_colon' => __( 'Parent Category:', 'alc-advanced-posts' ),
		'edit_item'         => __( 'Edit Category', 'alc-advanced-posts' ),
		'update_item'       => __( 'Update Category', 'alc-advanced-posts' ),
		'add_new_item'      => __( 'Add New Category', 'alc-advanced-posts' ),
		'new_item_name'     => __( 'New Category Name', 'alc-advanced-posts' ),
	);

	// Custom taxonomy for Album Categories
	register_taxonomy( 'catalbums', array('albums'), array(
		'hierarchical' => true,
		'public'       => true,
		'labels'       => $labels,
		'show_ui'      => true,
		'query_var'    => true,
		'rewrite'      => array(
			'slug' => 'cat-albums'
		),
	));
}


/**
 * Video Custom Post Type
 */
add_action('init', 'alchemists_video_custom_init');
function alchemists_video_custom_init(){

	// Initialize Videos Custom Type Labels
	$labels = array(
		'name'               => _x('Videos', 'post type general name', 'alc-advanced-posts'),
		'singular_name'      => _x('Video', 'post type singular name', 'alc-advanced-posts'),
		'add_new'            => _x('Add New', 'Video', 'alc-advanced-posts'),
		'add_new_item'       => __('Add New Video', 'alc-advanced-posts'),
		'edit_item'          => __('Edit Video', 'alc-advanced-posts'),
		'new_item'           => __('New Video', 'alc-advanced-posts'),
		'view_item'          => __('View Video', 'alc-advanced-posts'),
		'search_items'       => __('Search Videos', 'alc-advanced-posts'),
		'not_found'          => __('No videos found', 'alc-advanced-posts'),
		'not_found_in_trash' => __('No videos found in Trash', 'alc-advanced-posts'),
		'parent_item_colon'  => '',
		'menu_name'          => __('Videos', 'alc-advanced-posts'),
	);

	$args = array(
		'labels'        => $labels,
		'public'        => true,
		'show_ui'       => true,
		'query_var'     => true,
		'rewrite'       => array(
			'slug' => get_option( 'alchemists_video_slug', 'video' ),
		),
		'menu_position' => 30,
		'menu_icon'     => 'dashicons-video-alt3',
		'show_in_rest'  => true,
		'supports' => array(
			'title',
			'thumbnail',
			'editor',
			'comments',
			'excerpt',
		)
	);
	register_post_type( 'videos', $args );

	// Initialize New Categories Labels
	$labels = array(
		'name'              => _x( 'Videos Categories', 'category general name', 'alc-advanced-posts' ),
		'singular_name'     => _x( 'Videos Category', 'taxonomy singular name', 'alc-advanced-posts' ),
		'search_items'      => __( 'Search Category', 'alc-advanced-posts' ),
		'all_items'         => __( 'All Categories', 'alc-advanced-posts' ),
		'parent_item'       => __( 'Parent Category', 'alc-advanced-posts' ),
		'parent_item_colon' => __( 'Parent Category:', 'alc-advanced-posts' ),
		'edit_item'         => __( 'Edit Category', 'alc-advanced-posts' ),
		'update_item'       => __( 'Update Category', 'alc-advanced-posts' ),
		'add_new_item'      => __( 'Add New Category', 'alc-advanced-posts' ),
		'new_item_name'     => __( 'New Category Name', 'alc-advanced-posts' ),
	);

	// Custom taxonomy for Album Categories
	register_taxonomy( 'catvideos', array('videos'), array(
		'hierarchical' => true,
		'public'       => true,
		'labels'       => $labels,
		'show_ui'      => true,
		'query_var'    => true,
		'rewrite'      => array(
			'slug' => 'cat-videos'
		),
	));
}
