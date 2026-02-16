import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';

registerBlockType('job-listing-manager/job-closing-date', {
	edit: ({ attributes, setAttributes, context }) => {
		const blockProps = useBlockProps();
		const { postId, postType } = context;
		const { noDateMessage } = attributes;

		const closingDate = useSelect(
			(select) => {
				if (!postId || !postType) return null;
				const meta = select('core/editor').getEditedPostAttribute('meta');
				return meta?.job_closing_date;
			},
			[postId, postType]
		);

		const formattedDate = closingDate 
			? new Date(closingDate).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })
			: noDateMessage;

		return (
			<>
				<InspectorControls>
					<PanelBody title={__('Settings', 'job-listing-manager')}>
						<TextControl
							label={__('Message when no closing date', 'job-listing-manager')}
							value={noDateMessage}
							onChange={(value) => setAttributes({ noDateMessage: value })}
							help={__('Displayed when job has no closing date set', 'job-listing-manager')}
						/>
					</PanelBody>
				</InspectorControls>
				<div {...blockProps}>
					{formattedDate}
				</div>
			</>
		);
	}
});
