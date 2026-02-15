<?php

class Test_Post_Types extends WP_UnitTestCase {

	public function test_job_listing_post_type_registered() {
		$this->assertTrue( post_type_exists( 'job_listing' ) );
	}

	public function test_job_listing_post_type_is_public() {
		$post_type = get_post_type_object( 'job_listing' );
		$this->assertTrue( $post_type->public );
	}

	public function test_job_listing_post_type_has_no_archive() {
		$post_type = get_post_type_object( 'job_listing' );
		$this->assertFalse( $post_type->has_archive );
	}

	public function test_job_listing_post_type_shows_in_rest() {
		$post_type = get_post_type_object( 'job_listing' );
		$this->assertTrue( $post_type->show_in_rest );
	}

	public function test_job_listing_post_type_supports_features() {
		$this->assertTrue( post_type_supports( 'job_listing', 'title' ) );
		$this->assertTrue( post_type_supports( 'job_listing', 'editor' ) );
		$this->assertTrue( post_type_supports( 'job_listing', 'thumbnail' ) );
		$this->assertTrue( post_type_supports( 'job_listing', 'excerpt' ) );
	}
}
