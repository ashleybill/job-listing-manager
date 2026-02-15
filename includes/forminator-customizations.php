<?php
/**
 * Forminator Form Customizations
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function jlm_forminator_styles() {
	?>
	<style>
	.forminator-input,
	.forminator-textarea {
		border-radius: 12px !important;
	}
	</style>
	<?php
}
add_action( 'wp_footer', 'jlm_forminator_styles' );

function jlm_forminator_readonly_fields() {
	?>
	<style>
	input[name="text-1"][readonly],
	input[name="text-2"][readonly] {
		background-color: #f5f5f5;
		cursor: not-allowed;
		opacity: 0.7;
	}
	</style>
	<script>
	document.addEventListener('DOMContentLoaded', function() {
		const positionField = document.querySelector('input[name="text-1"]');
		const locationField = document.querySelector('input[name="text-2"]');
		
		if (positionField) {
			positionField.setAttribute('readonly', 'readonly');
		}
		if (locationField) {
			locationField.setAttribute('readonly', 'readonly');
		}
	});
	</script>
	<?php
}
add_action( 'wp_footer', 'jlm_forminator_readonly_fields' );
