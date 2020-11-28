<?php

/**
 * Register the scripts for the public-facing side of the site.
 */
add_action( 'wp_enqueue_scripts', 'alchemists_post_views_enqueue_scripts', 999 );
function alchemists_post_views_enqueue_scripts() {
	if ( ! is_singular( array( 'post', 'videos' ) ) ) return;
	wp_enqueue_script( 'alchemists-post-views', plugin_dir_url( __FILE__ ) . 'js/alchemists-post-views-min.js', array( 'jquery' ), ALCADVPOSTS_VERSION_NUM, false );
	wp_localize_script( 'alchemists-post-views', 'alchemistsPostViews', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' )
	) );
}

/**
 * Get Post Views
 */
if ( ! function_exists( 'alchemists_getPostViews' ) ) {
	function alchemists_getPostViews( $postID ) {
		$count_key = 'post_views_count';
		$count = get_post_meta( $postID, $count_key, true );
		if ( ! is_singular( array( 'post', 'videos' ) ) ) {
			return '<div class="meta__item meta__item--views">' . $count . '</div>';
		}
	
		$nonce = wp_create_nonce('alchemists_count_post');
		if ( $count == "0" ) {
			delete_post_meta( $postID, $count_key );
			add_post_meta( $postID, $count_key, '0' );
			return '<div class="meta__item meta__item--views js-meta__item--views-count" data-id="' . $postID . '" data-nonce="' . $nonce . '">0</div>';
		}
		return '<div class="meta__item meta__item--views js-meta__item--views-count" data-id="' . $postID . '" data-nonce="' . $nonce . '">' . $count . '</div>';
	}
}

/**
 * Set Post Views
 */
if ( ! function_exists( 'alchemists_setPostViews' ) ) {
	function alchemists_setPostViews( $postID ) {
		$count_key = 'post_views_count';
		$count = get_post_meta( $postID, $count_key, true );
	
		if ( $count == "0" || empty( $count ) || !isset( $count ) ) {
			add_post_meta( $postID, $count_key, 1 );
			update_post_meta( $postID, $count_key, 1 );
		} else {
			$count++;
			update_post_meta( $postID, $count_key, $count );
		}
	}
}

/**
 * Counter callback
 */
add_action( 'wp_ajax_alchemists-ajax-counter', 'alchemists_post_views_ajax_callback' );
add_action( 'wp_ajax_nopriv_alchemists-ajax-counter', 'alchemists_post_views_ajax_callback' );
if ( ! function_exists( 'alchemists_post_views_ajax_callback' ) ) {
	function alchemists_post_views_ajax_callback() {
		if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'alchemists_count_post' ) ) {
			exit();
		}
		$count = 0;
		if ( isset( $_GET['p'] ) ) {
			global $post;
			$postID = intval( $_GET['p'] );
			$post   = get_post( $postID );
			if ( $post && !empty( $post ) && !is_wp_error( $post ) ){
				alchemists_setPostViews( $postID );
				$count_key = 'post_views_count';
				$count = get_post_meta( $postID, $count_key, true );
			}
		}
		die( $count );
	}
}
