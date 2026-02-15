import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, RichText, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, ComboboxControl } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';

registerBlockType('job-listing-manager/job-apply-button', {
	edit: ({ attributes, setAttributes, context }) => {
		const blockProps = useBlockProps({
			className: 'wp-block-button'
		});
		const { buttonText, applyPageId } = attributes;

		const pages = useSelect((select) => {
			const { getEntityRecords } = select('core');
			const pagesData = getEntityRecords('postType', 'page', { per_page: -1 });
			return pagesData ? pagesData.map(page => ({
				value: page.id,
				label: page.title.rendered
			})) : [];
		}, []);

		return (
			<>
				<InspectorControls>
					<PanelBody title={__('Button Settings', 'job-listing-manager')}>
						<TextControl
							label={__('Button Text', 'job-listing-manager')}
							value={buttonText}
							onChange={(value) => setAttributes({ buttonText: value })}
						/>
						<ComboboxControl
							label={__('Apply Page', 'job-listing-manager')}
							value={applyPageId}
							onChange={(value) => setAttributes({ applyPageId: parseInt(value) })}
							options={pages}
							help={__('Select the page where the application form is located', 'job-listing-manager')}
						/>
					</PanelBody>
				</InspectorControls>
				<div {...blockProps}>
					<RichText
						tagName="a"
						className="wp-block-button__link wp-element-button"
						value={buttonText}
						onChange={(value) => setAttributes({ buttonText: value })}
						placeholder={__('Apply Now', 'job-listing-manager')}
					/>
				</div>
			</>
		);
	}
});
