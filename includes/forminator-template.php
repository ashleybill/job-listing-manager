<?php
/**
 * Forminator Form Template
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function jlm_create_forminator_template() {
	if ( ! class_exists( 'Forminator_API' ) ) {
		return;
	}

	if ( get_option( 'jlm_forminator_template_created' ) ) {
		return;
	}

	$wrappers = array(
		array(
			'wrapper_id' => 'wrapper-1',
			'fields' => array(
				array(
					'element_id' => 'name-1',
					'type' => 'name',
					'required' => 'true',
					'field_label' => 'Full Name',
					'prefix' => 'false',
					'fname' => 'true',
					'lname' => 'true',
					'cols' => '12'
				)
			)
		),
		array(
			'wrapper_id' => 'wrapper-2',
			'fields' => array(
				array(
					'element_id' => 'email-1',
					'type' => 'email',
					'required' => 'true',
					'field_label' => 'Email Address',
					'cols' => '12'
				)
			)
		),
		array(
			'wrapper_id' => 'wrapper-3',
			'fields' => array(
				array(
					'element_id' => 'phone-1',
					'type' => 'phone',
					'required' => 'true',
					'field_label' => 'Phone Number',
					'cols' => '12'
				)
			)
		),
		array(
			'wrapper_id' => 'wrapper-3a',
			'fields' => array(
				array(
					'element_id' => 'text-1',
					'type' => 'text',
					'required' => 'false',
					'field_label' => 'Position',
					'placeholder' => 'Position',
					'prefill' => 'position',
					'cols' => '6'
				),
				array(
					'element_id' => 'text-2',
					'type' => 'text',
					'required' => 'false',
					'field_label' => 'Location',
					'placeholder' => 'Location',
					'prefill' => 'location',
					'cols' => '6'
				)
			)
		),
		array(
			'wrapper_id' => 'wrapper-4',
			'fields' => array(
				array(
					'element_id' => 'upload-1',
					'type' => 'upload',
					'required' => 'true',
					'field_label' => 'Resume',
					'file-type' => 'single',
					'file-types' => 'pdf,doc,docx',
					'cols' => '12'
				)
			)
		),
		array(
			'wrapper_id' => 'wrapper-5',
			'fields' => array(
				array(
					'element_id' => 'textarea-1',
					'type' => 'textarea',
					'required' => 'false',
					'field_label' => 'Cover Letter',
					'limit' => '500',
					'limit_type' => 'words',
					'cols' => '12'
				)
			)
		)
	);

	$settings = array(
		'formName' => 'Job Application Form',
		'submission-indicator' => 'show',
		'submission-message' => 'Application Submitted!',
		'submitData' => array(
			'custom-submit-text' => 'Apply'
		),
		'notification' => array(
			array(
				'slug' => 'notification-1',
				'label' => 'Admin Email',
				'email-recipients' => 'admin',
				'recipients' => get_option( 'admin_email' ),
				'email-subject' => 'New Job Application',
				'email-editor' => 'You have received a new job application.'
			)
		)
	);

	$form_id = Forminator_API::add_form( 'Job Application Form', $wrappers, $settings );

	if ( is_wp_error( $form_id ) ) {
		return;
	}
	
	update_option( 'jlm_forminator_template_created', true );
}
add_action( 'admin_init', 'jlm_create_forminator_template' );
