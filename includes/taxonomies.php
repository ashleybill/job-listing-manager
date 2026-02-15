<?php
/**
 * Register Custom Taxonomies
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function jlm_register_taxonomies() {
	register_taxonomy( 'job_category', 'job_listing', array(
		'labels' => array(
			'name' => __( 'Job Categories', 'job-listing-manager' ),
			'singular_name' => __( 'Job Category', 'job-listing-manager' ),
			'search_items' => __( 'Search Job Categories', 'job-listing-manager' ),
			'all_items' => __( 'All Job Categories', 'job-listing-manager' ),
			'edit_item' => __( 'Edit Job Category', 'job-listing-manager' ),
			'update_item' => __( 'Update Job Category', 'job-listing-manager' ),
			'add_new_item' => __( 'Add New Job Category', 'job-listing-manager' ),
			'new_item_name' => __( 'New Job Category Name', 'job-listing-manager' ),
		),
		'hierarchical' => true,
		'show_in_rest' => true,
		'rewrite' => array( 'slug' => 'job-category' ),
	) );
}
add_action( 'init', 'jlm_register_taxonomies' );
