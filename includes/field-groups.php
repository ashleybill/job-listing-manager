<?php
/**
 * Register SCF Field Groups
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function jlm_register_field_groups() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group( array(
		'key' => 'group_job_listing_details',
		'title' => 'Job Listing Details',
		'menu_order' => 0,
		'fields' => array(
			array(
				'key' => 'field_job_location',
				'label' => 'Location',
				'name' => 'job_location',
				'type' => 'text',
			),
			array(
				'key' => 'field_job_salary',
				'label' => 'Salary Range',
				'name' => 'job_salary',
				'type' => 'text',
			),
			array(
				'key' => 'field_job_closing_date',
				'label' => 'Closing Date',
				'name' => 'job_closing_date',
				'type' => 'date_picker',
				'display_format' => 'F j, Y',
				'return_format' => 'Y-m-d',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'job_listing',
				),
			),
		),
	) );
}
add_action( 'acf/init', 'jlm_register_field_groups' );
