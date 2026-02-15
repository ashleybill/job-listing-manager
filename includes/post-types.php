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
			if ( $key === '_jlm_is_template' ) {
				continue;
			}
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

function jlm_custom_job_slug( $slug, $post_id, $post_status, $post_type ) {
	if ( $post_type === 'job_listing' ) {
		$post = get_post( $post_id );
		$location = get_post_meta( $post_id, 'job_location', true );
		if ( $location ) {
			$slug = sanitize_title( $post->post_title . ' ' . $location );
		}
	}
	return $slug;
}
add_filter( 'wp_unique_post_slug', 'jlm_custom_job_slug', 10, 4 );

function jlm_update_slug_after_acf_save( $post_id ) {
	if ( get_post_type( $post_id ) !== 'job_listing' ) {
		return;
	}
	
	$location = get_post_meta( $post_id, 'job_location', true );
	if ( ! $location ) {
		return;
	}
	
	$post = get_post( $post_id );
	$new_slug = sanitize_title( $post->post_title . ' ' . $location );
	
	if ( $post->post_name !== $new_slug ) {
		global $wpdb;
		$new_slug_unique = wp_unique_post_slug( $new_slug, $post_id, $post->post_status, $post->post_type, $post->post_parent );
		$wpdb->update(
			$wpdb->posts,
			array( 'post_name' => $new_slug_unique ),
			array( 'ID' => $post_id )
		);
		clean_post_cache( $post_id );
	}
}
add_action( 'acf/save_post', 'jlm_update_slug_after_acf_save', 20 );



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

function jlm_add_location_column( $columns ) {
	$new_columns = array();
	foreach ( $columns as $key => $value ) {
		$new_columns[ $key ] = $value;
		if ( $key === 'title' ) {
			$new_columns['job_location'] = __( 'Location', 'job-listing-manager' );
		}
	}
	return $new_columns;
}
add_filter( 'manage_job_listing_posts_columns', 'jlm_add_location_column' );

function jlm_display_location_column( $column, $post_id ) {
	if ( $column === 'job_location' ) {
		$location = get_post_meta( $post_id, 'job_location', true );
		echo $location ? esc_html( $location ) : '—';
	}
}
add_action( 'manage_job_listing_posts_custom_column', 'jlm_display_location_column', 10, 2 );

function jlm_location_column_sortable( $columns ) {
	$columns['job_location'] = 'job_location';
	return $columns;
}
add_filter( 'manage_edit-job_listing_sortable_columns', 'jlm_location_column_sortable' );

function jlm_sort_templates_first( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}
	
	if ( $query->get( 'post_type' ) === 'job_listing' && ! isset( $_GET['orderby'] ) ) {
		add_filter( 'posts_orderby', 'jlm_custom_orderby', 10, 2 );
	}
}
add_action( 'pre_get_posts', 'jlm_sort_templates_first' );

function jlm_custom_orderby( $orderby, $query ) {
	if ( ! is_admin() || ! $query->is_main_query() || $query->get( 'post_type' ) !== 'job_listing' ) {
		return $orderby;
	}
	
	global $wpdb;
	$orderby = "(SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = {$wpdb->posts}.ID AND meta_key = '_jlm_is_template' LIMIT 1) DESC, {$wpdb->posts}.post_date DESC";
	
	remove_filter( 'posts_orderby', 'jlm_custom_orderby', 10 );
	return $orderby;
}
