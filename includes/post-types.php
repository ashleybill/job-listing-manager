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
		'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
		'menu_icon' => 'dashicons-businessperson',
		'rewrite' => array( 'slug' => 'job' ),
	) );
}
add_action( 'init', 'jlm_register_post_types' );

function jlm_add_template_meta_box() {
	add_meta_box(
		'jlm_template_meta',
		__( 'Template', 'job-listing-manager' ),
		'jlm_template_meta_box_callback',
		'job_listing',
		'side',
		'high'
	);
}
add_action( 'add_meta_boxes', 'jlm_add_template_meta_box' );

function jlm_template_meta_box_callback( $post ) {
	wp_nonce_field( 'jlm_template_meta', 'jlm_template_nonce' );
	$is_template = get_post_meta( $post->ID, '_jlm_is_template', true );
	?>
	<label>
		<input type="checkbox" name="jlm_is_template" value="1" <?php checked( $is_template, '1' ); ?> />
		<?php _e( 'This is a template', 'job-listing-manager' ); ?>
	</label>
	<p class="description"><?php _e( 'Templates can be duplicated and are hidden from the frontend.', 'job-listing-manager' ); ?></p>
	<?php
}

function jlm_save_template_meta( $post_id ) {
	if ( ! isset( $_POST['jlm_template_nonce'] ) || ! wp_verify_nonce( $_POST['jlm_template_nonce'], 'jlm_template_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	
	if ( isset( $_POST['jlm_is_template'] ) ) {
		update_post_meta( $post_id, '_jlm_is_template', '1' );
	} else {
		delete_post_meta( $post_id, '_jlm_is_template' );
	}
}
add_action( 'save_post_job_listing', 'jlm_save_template_meta' );

function jlm_display_template_label( $post_states, $post ) {
	if ( $post->post_type === 'job_listing' && get_post_meta( $post->ID, '_jlm_is_template', true ) ) {
		$post_states['template'] = __( 'Template', 'job-listing-manager' );
	}
	return $post_states;
}
add_filter( 'display_post_states', 'jlm_display_template_label', 10, 2 );

function jlm_duplicate_action( $actions, $post ) {
	if ( $post->post_type === 'job_listing' && get_post_meta( $post->ID, '_jlm_is_template', true ) && current_user_can( 'edit_posts' ) ) {
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
		$meta_query = $query->get( 'meta_query' ) ?: array();
		$meta_query[] = array(
			'key' => '_jlm_is_template',
			'compare' => 'NOT EXISTS',
		);
		$query->set( 'meta_query', $meta_query );
	}
}
add_action( 'pre_get_posts', 'jlm_exclude_templates_from_frontend' );
