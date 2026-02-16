<?php
/**
 * Auto-expire job listings
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function jlm_schedule_expiration_check() {
	if ( ! wp_next_scheduled( 'jlm_check_expired_jobs' ) ) {
		wp_schedule_event( time(), 'daily', 'jlm_check_expired_jobs' );
	}
}
add_action( 'wp', 'jlm_schedule_expiration_check' );

function jlm_check_expired_jobs() {
	$args = array(
		'post_type' => 'job_listing',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'meta_query' => array(
			'relation' => 'AND',
			array(
				'key' => 'job_closing_date',
				'compare' => 'EXISTS',
			),
			array(
				'key' => 'job_closing_date',
				'value' => '',
				'compare' => '!=',
			),
			array(
				'key' => 'job_closing_date',
				'value' => date( 'Y-m-d' ),
				'compare' => '<',
				'type' => 'DATE',
			),
		),
	);

	$expired_jobs = get_posts( $args );

	foreach ( $expired_jobs as $job ) {
		wp_update_post( array(
			'ID' => $job->ID,
			'post_status' => 'draft',
		) );
	}
}
add_action( 'jlm_check_expired_jobs', 'jlm_check_expired_jobs' );

function jlm_clear_scheduled_hook() {
	$timestamp = wp_next_scheduled( 'jlm_check_expired_jobs' );
	if ( $timestamp ) {
		wp_unschedule_event( $timestamp, 'jlm_check_expired_jobs' );
	}
}
register_deactivation_hook( __FILE__, 'jlm_clear_scheduled_hook' );
