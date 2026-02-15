<?php
/**
 * Test Template Functionality
 */

class Test_Template_Functionality extends WP_UnitTestCase {

	public function test_template_meta_can_be_set() {
		$post_id = wp_insert_post( array(
			'post_type' => 'job_listing',
			'post_status' => 'draft',
			'post_title' => 'Test Template',
		) );

		update_post_meta( $post_id, '_jlm_is_template', '1' );
		$is_template = get_post_meta( $post_id, '_jlm_is_template', true );

		$this->assertEquals( '1', $is_template );
	}

	public function test_duplicate_template_creates_draft() {
		$template_id = wp_insert_post( array(
			'post_type' => 'job_listing',
			'post_status' => 'draft',
			'post_title' => 'Test Template',
			'post_content' => 'Template content',
		) );

		update_post_meta( $template_id, '_jlm_is_template', '1' );
		add_post_meta( $template_id, 'job_location', 'Test Location' );
		add_post_meta( $template_id, 'job_salary', '50000' );

		$template = get_post( $template_id );
		$new_post_id = wp_insert_post( array(
			'post_title' => $template->post_title,
			'post_content' => $template->post_content,
			'post_type' => $template->post_type,
			'post_status' => 'draft',
		) );

		$meta = get_post_meta( $template_id );
		foreach ( $meta as $key => $values ) {
			if ( $key === '_jlm_is_template' ) {
				continue;
			}
			foreach ( $values as $value ) {
				add_post_meta( $new_post_id, $key, maybe_unserialize( $value ) );
			}
		}

		$new_post = get_post( $new_post_id );
		$this->assertEquals( 'draft', $new_post->post_status );
		$this->assertEquals( 'Test Template', $new_post->post_title );
		$this->assertEquals( 'Test Location', get_post_meta( $new_post_id, 'job_location', true ) );
		$this->assertEquals( '50000', get_post_meta( $new_post_id, 'job_salary', true ) );
		$this->assertEmpty( get_post_meta( $new_post_id, '_jlm_is_template', true ) );
	}

	public function test_custom_slug_includes_location() {
		$post_id = wp_insert_post( array(
			'post_type' => 'job_listing',
			'post_status' => 'publish',
			'post_title' => 'Software Engineer',
		) );

		update_post_meta( $post_id, 'job_location', 'San Francisco' );
		
		$post = get_post( $post_id );
		$slug = jlm_custom_job_slug( '', $post_id, 'publish', 'job_listing' );
		$expected = sanitize_title( 'Software Engineer San Francisco' );
		
		$this->assertEquals( $expected, $slug );
	}

	public function test_templates_excluded_from_frontend() {
		$template_id = wp_insert_post( array(
			'post_type' => 'job_listing',
			'post_status' => 'publish',
			'post_title' => 'Template Post',
		) );
		update_post_meta( $template_id, '_jlm_is_template', '1' );

		$published_id = wp_insert_post( array(
			'post_type' => 'job_listing',
			'post_status' => 'publish',
			'post_title' => 'Published Post',
		) );

		$query = new WP_Query( array(
			'post_type' => 'job_listing',
			'post_status' => 'publish',
			'meta_query' => array(
				array(
					'key' => '_jlm_is_template',
					'compare' => 'NOT EXISTS',
				),
			),
		) );

		$post_ids = wp_list_pluck( $query->posts, 'ID' );
		$this->assertTrue( in_array( $published_id, $post_ids ) );
		$this->assertFalse( in_array( $template_id, $post_ids ) );
	}
}
