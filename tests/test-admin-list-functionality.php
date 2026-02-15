<?php
/**
 * Test Admin List Functionality
 */

class Test_Admin_List_Functionality extends WP_UnitTestCase {

	public function test_location_column_added() {
		$columns = apply_filters( 'manage_job_listing_posts_columns', array( 'title' => 'Title' ) );
		$this->assertArrayHasKey( 'job_location', $columns );
	}

	public function test_location_column_displays_value() {
		$post_id = wp_insert_post( array(
			'post_type' => 'job_listing',
			'post_status' => 'publish',
			'post_title' => 'Test Job',
		) );

		update_post_meta( $post_id, 'job_location', 'New York' );

		ob_start();
		do_action( 'manage_job_listing_posts_custom_column', 'job_location', $post_id );
		$output = ob_get_clean();

		$this->assertEquals( 'New York', $output );
	}

	public function test_location_column_sortable() {
		$columns = apply_filters( 'manage_edit-job_listing_sortable_columns', array() );
		$this->assertArrayHasKey( 'job_location', $columns );
		$this->assertEquals( 'job_location', $columns['job_location'] );
	}

	public function test_templates_sorted_first() {
		$regular_id = wp_insert_post( array(
			'post_type' => 'job_listing',
			'post_status' => 'publish',
			'post_title' => 'Regular Job',
			'post_date' => '2024-01-01 00:00:00',
		) );

		$template_id = wp_insert_post( array(
			'post_type' => 'job_listing',
			'post_status' => 'publish',
			'post_title' => 'Template Job',
			'post_date' => '2024-01-02 00:00:00',
		) );

		update_post_meta( $template_id, '_jlm_is_template', '1' );

		$query = new WP_Query( array(
			'post_type' => 'job_listing',
			'posts_per_page' => -1,
		) );

		jlm_sort_templates_first( $query );
		$query->get_posts();

		$post_ids = wp_list_pluck( $query->posts, 'ID' );
		$template_position = array_search( $template_id, $post_ids );
		$regular_position = array_search( $regular_id, $post_ids );

		$this->assertLessThan( $regular_position, $template_position );
	}
}
