<?php
$post_id = $block->context['postId'] ?? get_the_ID();
$location = get_field( 'job_location', $post_id );

if ( ! $location ) {
	return;
}
?>
<div <?php echo get_block_wrapper_attributes(); ?>>
	<?php echo esc_html( $location ); ?>
</div>
