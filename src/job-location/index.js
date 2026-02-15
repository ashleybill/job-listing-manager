import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { useEntityProp } from '@wordpress/core-data';
import { useSelect } from '@wordpress/data';

registerBlockType('job-listing-manager/job-location', {
	edit: ({ context }) => {
		const blockProps = useBlockProps();
		const { postId, postType } = context;

		const [meta] = useEntityProp('postType', postType, 'meta', postId);
		const location = meta?.job_location;

		return (
			<div {...blockProps}>
				{location || 'No location set'}
			</div>
		);
	}
});
