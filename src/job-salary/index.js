import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';

registerBlockType('job-listing-manager/job-salary', {
	edit: ({ context }) => {
		const blockProps = useBlockProps();
		const { postId, postType } = context;

		const salary = useSelect(
			(select) => {
				if (!postId || !postType) return null;
				const meta = select('core/editor').getEditedPostAttribute('meta');
				return meta?.job_salary;
			},
			[postId, postType]
		);

		return (
			<div {...blockProps}>
				{salary || 'No salary set'}
			</div>
		);
	}
});
