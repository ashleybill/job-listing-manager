<?php
/**
 * Plugin Settings
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function jlm_register_settings() {
	register_setting( 'jlm_settings', 'jlm_application_method' );
	register_setting( 'jlm_settings', 'jlm_application_email' );
	register_setting( 'jlm_settings', 'jlm_email_body' );
	register_setting( 'jlm_settings', 'jlm_form_page_id' );
	
	add_settings_section(
		'jlm_settings_section',
		'Job Application Settings',
		null,
		'jlm_settings'
	);
	
	add_settings_field(
		'jlm_application_method',
		'Application Method',
		'jlm_application_method_field',
		'jlm_settings',
		'jlm_settings_section'
	);
	
	add_settings_field(
		'jlm_application_email',
		'Application Email',
		'jlm_application_email_field',
		'jlm_settings',
		'jlm_settings_section'
	);
	
	add_settings_field(
		'jlm_email_body',
		'Email Body Template',
		'jlm_email_body_field',
		'jlm_settings',
		'jlm_settings_section'
	);
	
	add_settings_field(
		'jlm_form_page_id',
		'Application Form Page',
		'jlm_form_page_field',
		'jlm_settings',
		'jlm_settings_section'
	);
}
add_action( 'admin_init', 'jlm_register_settings' );

function jlm_application_method_field() {
	$method = get_option( 'jlm_application_method', 'mailto' );
	?>
	<select name="jlm_application_method" id="jlm_application_method">
		<option value="mailto" <?php selected( $method, 'mailto' ); ?>>Email Link (mailto)</option>
		<option value="form" <?php selected( $method, 'form' ); ?>>Form Page</option>
	</select>
	<p class="description">Choose how users apply for jobs.</p>
	<?php
}

function jlm_application_email_field() {
	$method = get_option( 'jlm_application_method', 'mailto' );
	$email = get_option( 'jlm_application_email', get_option( 'admin_email' ) );
	$display = $method === 'mailto' ? '' : 'style="display:none;"';
	?>
	<div class="jlm-mailto-field" <?php echo $display; ?>>
		<input type="email" name="jlm_application_email" value="<?php echo esc_attr( $email ); ?>" class="regular-text" />
		<p class="description">Email address where job applications will be sent. Defaults to site admin email.</p>
	</div>
	<?php
}

function jlm_email_body_field() {
	$method = get_option( 'jlm_application_method', 'mailto' );
	$default_body = "Name: \nEmail: \nPhone: \n\nCover Letter:\n\n\n\nPlease attach your resume to this email.";
	$body = get_option( 'jlm_email_body', $default_body );
	$display = $method === 'mailto' ? '' : 'style="display:none;"';
	?>
	<div class="jlm-mailto-field" <?php echo $display; ?>>
		<textarea name="jlm_email_body" rows="10" class="large-text code"><?php echo esc_textarea( $body ); ?></textarea>
		<p class="description">Template for the email body. Use \n for line breaks.</p>
	</div>
	<?php
}

function jlm_form_page_field() {
	$method = get_option( 'jlm_application_method', 'mailto' );
	$page_id = get_option( 'jlm_form_page_id', 0 );
	$display = $method === 'form' ? '' : 'style="display:none;"';
	?>
	<div class="jlm-form-field" <?php echo $display; ?>>
		<?php
		wp_dropdown_pages( array(
			'name' => 'jlm_form_page_id',
			'selected' => $page_id,
			'show_option_none' => 'Select a page',
			'option_none_value' => '0'
		) );
		?>
		<p class="description">Page containing the application form.</p>
	</div>
	<script>
	document.addEventListener('DOMContentLoaded', function() {
		const methodSelect = document.getElementById('jlm_application_method');
		const mailtoFields = document.querySelectorAll('.jlm-mailto-field');
		const formFields = document.querySelectorAll('.jlm-form-field');
		
		methodSelect.addEventListener('change', function() {
			if (this.value === 'mailto') {
				mailtoFields.forEach(f => f.style.display = '');
				formFields.forEach(f => f.style.display = 'none');
			} else {
				mailtoFields.forEach(f => f.style.display = 'none');
				formFields.forEach(f => f.style.display = '');
			}
		});
	});
	</script>
	<?php
}

function jlm_add_settings_page() {
	add_options_page(
		'Job Listing Manager Settings',
		'Job Listings',
		'manage_options',
		'jlm_settings',
		'jlm_settings_page'
	);
}
add_action( 'admin_menu', 'jlm_add_settings_page' );

function jlm_settings_page() {
	?>
	<div class="wrap">
		<h1>Job Listing Manager Settings</h1>
		<form method="post" action="options.php">
			<?php
			settings_fields( 'jlm_settings' );
			do_settings_sections( 'jlm_settings' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}
