<?php
$post_id = $block->context['postId'] ?? get_the_ID();
$salary = get_field( 'job_salary', $post_id );

if ( ! $salary ) {
	return;
}
?>
<div <?php echo get_block_wrapper_attributes(); ?>>
	<?php echo esc_html( $salary ); ?>
</div>
