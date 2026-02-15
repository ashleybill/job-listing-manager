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

function jlm_register_template_status() {
	register_post_status( 'template', array(
		'label' => __( 'Template', 'job-listing-manager' ),
		'public' => false,
		'exclude_from_search' => true,
		'show_in_admin_all_list' => true,
		'show_in_admin_status_list' => true,
		'label_count' => _n_noop( 'Template <span class="count">(%s)</span>', 'Templates <span class="count">(%s)</span>', 'job-listing-manager' ),
	) );
}
add_action( 'init', 'jlm_register_template_status' );

function jlm_add_template_status_dropdown() {
	global $post;
	if ( get_post_type( $post ) === 'job_listing' ) {
		?>
		<script>
		jQuery(document).ready(function($) {
			if ($('#post_status').length) {
				$('#post_status').append('<option value="template" ' + ($('#post_status').val() === 'template' ? 'selected' : '') + '>Template</option>');
			}
			if ($('.misc-pub-section label').length && $('#post-status-display').text() === 'Template') {
				$('#post-status-display').text('Template');
			}
		});
		</script>
		<?php
	}
}
add_action( 'admin_footer-post.php', 'jlm_add_template_status_dropdown' );
add_action( 'admin_footer-post-new.php', 'jlm_add_template_status_dropdown' );

function jlm_duplicate_action( $actions, $post ) {
	if ( $post->post_type === 'job_listing' && $post->post_status === 'template' && current_user_can( 'edit_posts' ) ) {
		$actions['duplicate'] = '<a href="' . wp_nonce_url( admin_url( 'admin.php?action=jlm_duplicate_template&post=' . $post->ID ), 'jlm_duplicate_' . $post->ID ) . '">' . __( 'Duplicate', 'job-listing-manager' ) . '</a>';
	}
	return $actions;
}
add_filter( 'post_row_actions', 'jlm_duplicate_action', 10, 2 );

function jlm_duplicate_template() {
	if ( ! isset( $_GET['post'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'jlm_duplicate_' . $_GET['post'] ) ) {
		wp_die( __( 'Invalid request', 'job-listing-manager' ) );
	}
	
	$post_id = absint( $_GET['post'] );
	$post = get_post( $post_id );
	
	if ( ! $post || $post->post_type !== 'job_listing' ) {
		wp_die( __( 'Invalid post', 'job-listing-manager' ) );
	}
	
	$new_post = array(
		'post_title' => $post->post_title,
		'post_content' => $post->post_content,
		'post_excerpt' => $post->post_excerpt,
		'post_type' => $post->post_type,
		'post_status' => 'draft',
	);
	
	$new_post_id = wp_insert_post( $new_post );
	
	if ( $new_post_id ) {
		$meta = get_post_meta( $post_id );
		foreach ( $meta as $key => $values ) {
			foreach ( $values as $value ) {
				add_post_meta( $new_post_id, $key, maybe_unserialize( $value ) );
			}
		}
		
		if ( has_post_thumbnail( $post_id ) ) {
			set_post_thumbnail( $new_post_id, get_post_thumbnail_id( $post_id ) );
		}
		
		$terms = wp_get_post_terms( $post_id, 'job_category', array( 'fields' => 'ids' ) );
		if ( ! is_wp_error( $terms ) ) {
			wp_set_post_terms( $new_post_id, $terms, 'job_category' );
		}
		
		wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
		exit;
	}
}
add_action( 'admin_action_jlm_duplicate_template', 'jlm_duplicate_template' );

function jlm_exclude_templates_from_frontend( $query ) {
	if ( ! is_admin() && $query->is_main_query() && $query->get( 'post_type' ) === 'job_listing' ) {
		$query->set( 'post_status', array( 'publish' ) );
	}
}
add_action( 'pre_get_posts', 'jlm_exclude_templates_from_frontend' );
