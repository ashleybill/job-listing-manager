<?php
/**
 * Test Template Functionality
 */

class Test_Template_Functionality extends WP_UnitTestCase {

	public function test_template_status_registered() {
		$status = get_post_status_object( 'template' );
		$this->assertNotNull( $status );
		$this->assertEquals( 'Template', $status->label );
	}

	public function test_duplicate_template_creates_draft() {
		$template_id = wp_insert_post( array(
			'post_type' => 'job_listing',
			'post_status' => 'template',
			'post_title' => 'Test Template',
			'post_content' => 'Template content',
		) );

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
			foreach ( $values as $value ) {
				add_post_meta( $new_post_id, $key, maybe_unserialize( $value ) );
			}
		}

		$new_post = get_post( $new_post_id );
		$this->assertEquals( 'draft', $new_post->post_status );
		$this->assertEquals( 'Test Template', $new_post->post_title );
		$this->assertEquals( 'Test Location', get_post_meta( $new_post_id, 'job_location', true ) );
		$this->assertEquals( '50000', get_post_meta( $new_post_id, 'job_salary', true ) );
	}

	public function test_templates_excluded_from_frontend() {
		$template_id = wp_insert_post( array(
			'post_type' => 'job_listing',
			'post_status' => 'template',
			'post_title' => 'Template Post',
		) );

		$published_id = wp_insert_post( array(
			'post_type' => 'job_listing',
			'post_status' => 'publish',
			'post_title' => 'Published Post',
		) );

		$this->assertIsInt( $template_id );
		$this->assertIsInt( $published_id );

		$template = get_post( $template_id );
		$published = get_post( $published_id );

		$this->assertNotNull( $template );
		$this->assertNotNull( $published );
		$this->assertEquals( 'template', $template->post_status );
		$this->assertEquals( 'publish', $published->post_status );
		$this->assertFalse( get_post_status_object( 'template' )->public );
	}
}
