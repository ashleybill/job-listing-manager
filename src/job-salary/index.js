import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { useEntityProp } from '@wordpress/core-data';

registerBlockType('job-listing-manager/job-salary', {
	edit: ({ context }) => {
		const blockProps = useBlockProps();
		const { postId, postType } = context;

		const [meta] = useEntityProp('postType', postType, 'meta', postId);
		const salary = meta?.job_salary;

		return (
			<div {...blockProps}>
				{salary || 'No salary set'}
			</div>
		);
	}
});
