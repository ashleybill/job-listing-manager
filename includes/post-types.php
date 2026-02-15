<?php
/**
 * Register Custom Post Types
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function jlm_register_post_types() {
	register_post_type( 'job_listing', array(
		'labels' => array(
			'name' => __( 'Job Listings', 'job-listing-manager' ),
			'singular_name' => __( 'Job Listing', 'job-listing-manager' ),
			'add_new' => __( 'Add New', 'job-listing-manager' ),
			'add_new_item' => __( 'Add New Job Listing', 'job-listing-manager' ),
			'edit_item' => __( 'Edit Job Listing', 'job-listing-manager' ),
			'new_item' => __( 'New Job Listing', 'job-listing-manager' ),
			'view_item' => __( 'View Job Listing', 'job-listing-manager' ),
			'search_items' => __( 'Search Job Listings', 'job-listing-manager' ),
			'not_found' => __( 'No job listings found', 'job-listing-manager' ),
			'not_found_in_trash' => __( 'No job listings found in trash', 'job-listing-manager' ),
		),
		'public' => true,
		'has_archive' => false,
		'show_in_rest' => true,
		'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'menu_icon' => 'dashicons-businessperson',
		'rewrite' => array( 'slug' => 'job' ),
	) );
}
add_action( 'init', 'jlm_register_post_types' );
