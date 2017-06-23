<?php

/**
 * Register Album Custom Post Type
 */
add_action('init', 'alchemists_albums_custom_init');

function alchemists_albums_custom_init(){

	global $alchemists_data;

	if(isset($alchemists_data['alchemists__opt-albums-slug'])){
		$albums_slug = $alchemists_data['alchemists__opt-albums-slug'];
	} else {
		$albums_slug = 'album';
	}

	// Initialize Albums Custom Type Labels
	$labels = array(
		'name'               => _x('Albums', 'post type general name', 'alchemists'),
		'singular_name'      => _x('Album', 'post type singular name', 'alchemists'),
		'add_new'            => _x('Add New', 'Album', 'alchemists'),
		'add_new_item'       => __('Add New Album', 'alchemists'),
		'edit_item'          => __('Edit Album', 'alchemists'),
		'new_item'           => __('New Album', 'alchemists'),
		'view_item'          => __('View Album', 'alchemists'),
		'search_items'       => __('Search Albums', 'alchemists'),
		'not_found'          => __('No albums found', 'alchemists'),
		'not_found_in_trash' => __('No albums found in Trash', 'alchemists'),
		'parent_item_colon'  => '',
		'menu_name'          => __('Albums', 'alchemists'),
	);

	$args = array(
		'labels'        => $labels,
		'public'        => true,
		'show_ui'       => true,
		'query_var'     => true,
		'rewrite'       => array( "slug" => $albums_slug ),
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
		'name'              => _x( 'Albums Categories', 'category general name', 'alchemists' ),
		'singular_name'     => _x( 'Albums Category', 'taxonomy singular name', 'alchemists' ),
		'search_items'      => __( 'Search Category', 'alchemists' ),
		'all_items'         => __( 'All Categories', 'alchemists' ),
		'parent_item'       => __( 'Parent Category', 'alchemists' ),
		'parent_item_colon' => __( 'Parent Category:', 'alchemists' ),
		'edit_item'         => __( 'Edit Category', 'alchemists' ),
		'update_item'       => __( 'Update Category', 'alchemists' ),
		'add_new_item'      => __( 'Add New Category', 'alchemists' ),
		'new_item_name'     => __( 'New Category Name', 'alchemists' ),
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
