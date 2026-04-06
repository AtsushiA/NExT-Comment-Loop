import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';

export default function Edit( { attributes, setAttributes } ) {
	const { format } = attributes;
	const blockProps = useBlockProps();

	return (
		<>
			<InspectorControls>
				<PanelBody
					title={ __( '日時フォーマット設定', 'next-comment-loop' ) }
				>
					<TextControl
						label={ __( '日時フォーマット', 'next-comment-loop' ) }
						help={ __(
							'PHPのdate()書式を使用。例: Y/m/d H:i、Y年m月d日、Y-m-d',
							'next-comment-loop'
						) }
						value={ format }
						onChange={ ( value ) =>
							setAttributes( { format: value } )
						}
					/>
				</PanelBody>
			</InspectorControls>
			<time { ...blockProps }>
				{ format || __( 'コメント日時', 'next-comment-loop' ) }
			</time>
		</>
	);
}
