<?php

class Test_Blocks extends WP_UnitTestCase {

	public function test_job_location_block_registered() {
		$this->assertTrue( WP_Block_Type_Registry::get_instance()->is_registered( 'job-listing-manager/job-location' ) );
	}

	public function test_job_salary_block_registered() {
		$this->assertTrue( WP_Block_Type_Registry::get_instance()->is_registered( 'job-listing-manager/job-salary' ) );
	}

	public function test_job_closing_date_block_registered() {
		$this->assertTrue( WP_Block_Type_Registry::get_instance()->is_registered( 'job-listing-manager/job-closing-date' ) );
	}

	public function test_job_apply_button_block_registered() {
		$this->assertTrue( WP_Block_Type_Registry::get_instance()->is_registered( 'job-listing-manager/job-apply-button' ) );
	}
}
