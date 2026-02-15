<?php

class Test_Taxonomies extends WP_UnitTestCase {

	public function test_job_category_taxonomy_registered() {
		$this->assertTrue( taxonomy_exists( 'job_category' ) );
	}

	public function test_job_category_is_hierarchical() {
		$taxonomy = get_taxonomy( 'job_category' );
		$this->assertTrue( $taxonomy->hierarchical );
	}

	public function test_job_category_shows_in_rest() {
		$taxonomy = get_taxonomy( 'job_category' );
		$this->assertTrue( $taxonomy->show_in_rest );
	}

	public function test_job_category_attached_to_job_listing() {
		$taxonomy = get_taxonomy( 'job_category' );
		$this->assertContains( 'job_listing', $taxonomy->object_type );
	}
}
