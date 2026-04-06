import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl } from '@wordpress/components';

export default function Edit( { attributes, setAttributes } ) {
	const { isLink } = attributes;
	const blockProps = useBlockProps();

	const label = __( '投稿タイトル', 'next-comment-loop' );

	return (
		<>
			<InspectorControls>
				<PanelBody
					title={ __( '投稿タイトル設定', 'next-comment-loop' ) }
				>
					<ToggleControl
						label={ __(
							'投稿へのリンクを付ける',
							'next-comment-loop'
						) }
						checked={ isLink }
						onChange={ ( value ) =>
							setAttributes( { isLink: value } )
						}
					/>
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				{ isLink ? (
					<a href="#">{ label }</a>
				) : (
					label
				) }
			</div>
		</>
	);
}
