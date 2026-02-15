<?php
$post_id = $block->context['postId'] ?? get_the_ID();
$title = get_the_title( $post_id );
$location = get_field( 'job_location', $post_id );
$button_text = $attributes['buttonText'] ?? 'Apply Now';
$method = get_option( 'jlm_application_method', 'mailto' );

if ( $method === 'form' ) {
	// Form page method
	$page_id = get_option( 'jlm_form_page_id', 0 );
	if ( $page_id ) {
		$apply_url = get_permalink( $page_id );
	} else {
		$apply_url = home_url( '/employment/apply/' );
	}
	
	$query_params = array(
		'position' => $title,
	);
	
	if ( $location ) {
		$query_params['location'] = $location;
	}
	
	$apply_url = add_query_arg( $query_params, $apply_url );
	$href = esc_url( $apply_url );
} else {
	// Mailto method
	$email = get_option( 'jlm_application_email', get_option( 'admin_email' ) );
	
	$subject = 'Application for ' . $title;
	if ( $location ) {
		$subject .= ' in ' . $location;
	}
	
	$default_body = "Name: \n";
	$default_body .= "Email: \n";
	$default_body .= "Phone: \n\n";
	$default_body .= "Cover Letter:\n\n\n\n";
	$default_body .= "Please attach your resume to this email.";
	
	$body = get_option( 'jlm_email_body', $default_body );
	
	$mailto = 'mailto:' . $email;
	$mailto .= '?subject=' . rawurlencode( $subject );
	$mailto .= '&body=' . rawurlencode( $body );
	$href = esc_attr( $mailto );
}
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'wp-block-button' ) ); ?>>
	<a href="<?php echo $href; ?>" class="wp-block-button__link wp-element-button">
		<?php echo esc_html( $button_text ); ?>
	</a>
</div>
