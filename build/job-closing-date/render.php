<?php
$post_id = $block->context['postId'] ?? get_the_ID();
$closing_date = get_field( 'job_closing_date', $post_id );

if ( ! $closing_date ) {
	return;
}

$formatted_date = date( 'F j, Y', strtotime( $closing_date ) );
?>
<div <?php echo get_block_wrapper_attributes(); ?>>
	<?php echo esc_html( $formatted_date ); ?>
</div>
