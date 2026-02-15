<?php

class Test_Expiration extends WP_UnitTestCase {

	public function test_cron_event_scheduled() {
		jlm_schedule_expiration_check();
		$this->assertNotFalse( wp_next_scheduled( 'jlm_check_expired_jobs' ) );
	}

	public function test_expired_jobs_moved_to_draft() {
		$job_id = wp_insert_post( array(
			'post_type' => 'job_listing',
			'post_title' => 'Test Job',
			'post_status' => 'publish',
		) );

		update_field( 'job_closing_date', date( 'Y-m-d', strtotime( '-1 day' ) ), $job_id );

		jlm_check_expired_jobs();

		$job = get_post( $job_id );
		$this->assertEquals( 'draft', $job->post_status );
	}

	public function test_active_jobs_remain_published() {
		$job_id = wp_insert_post( array(
			'post_type' => 'job_listing',
			'post_title' => 'Test Job',
			'post_status' => 'publish',
		) );

		update_field( 'job_closing_date', date( 'Y-m-d', strtotime( '+1 day' ) ), $job_id );

		jlm_check_expired_jobs();

		$job = get_post( $job_id );
		$this->assertEquals( 'publish', $job->post_status );
	}
}
