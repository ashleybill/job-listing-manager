<?php
/**
 * Test Settings
 */

class Test_Settings extends WP_UnitTestCase {

	public function test_settings_functions_exist() {
		$this->assertTrue( function_exists( 'jlm_register_settings' ) );
		$this->assertTrue( function_exists( 'jlm_add_settings_page' ) );
	}

	public function test_default_email_is_admin_email() {
		$email = get_option( 'jlm_application_email', get_option( 'admin_email' ) );
		$this->assertEquals( get_option( 'admin_email' ), $email );
	}

	public function test_custom_email_can_be_set() {
		update_option( 'jlm_application_email', 'jobs@example.com' );
		$email = get_option( 'jlm_application_email' );
		$this->assertEquals( 'jobs@example.com', $email );
	}

	public function test_email_body_has_default() {
		$default_body = "Name: \nEmail: \nPhone: \n\nCover Letter:\n\n\n\nPlease attach your resume to this email.";
		$body = get_option( 'jlm_email_body', $default_body );
		$this->assertNotEmpty( $body );
	}

	public function test_default_application_method_is_mailto() {
		$method = get_option( 'jlm_application_method', 'mailto' );
		$this->assertEquals( 'mailto', $method );
	}

	public function test_application_method_can_be_changed_to_form() {
		update_option( 'jlm_application_method', 'form' );
		$method = get_option( 'jlm_application_method' );
		$this->assertEquals( 'form', $method );
	}

	public function test_form_page_id_can_be_set() {
		update_option( 'jlm_form_page_id', 123 );
		$page_id = get_option( 'jlm_form_page_id' );
		$this->assertEquals( 123, $page_id );
	}
}
