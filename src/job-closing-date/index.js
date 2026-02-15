import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { useEntityProp } from '@wordpress/core-data';

registerBlockType('job-listing-manager/job-closing-date', {
	edit: ({ context }) => {
		const blockProps = useBlockProps();
		const { postId, postType } = context;

		const [meta] = useEntityProp('postType', postType, 'meta', postId);
		const closingDate = meta?.job_closing_date;

		return (
			<div {...blockProps}>
				{closingDate || 'No closing date set'}
			</div>
		);
	}
});
