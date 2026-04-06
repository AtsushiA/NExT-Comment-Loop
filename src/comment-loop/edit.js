import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	InnerBlocks,
	InspectorControls,
} from '@wordpress/block-editor';
import {
	PanelBody,
	SelectControl,
	RangeControl,
	CheckboxControl,
	Spinner,
} from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';

const ALLOWED_BLOCKS = [
	'next/comment-date',
	'next/comment-author',
	'next/comment-content',
	'next/comment-post-title',
];

const TEMPLATE = [
	[ 'next/comment-date' ],
	[ 'next/comment-author' ],
	[ 'next/comment-content' ],
];

export default function Edit( { attributes, setAttributes } ) {
	const { sortOrder, number, postTypes } = attributes;
	const blockProps = useBlockProps( { className: 'next-comment-loop' } );

	const availablePostTypes = useSelect( ( select ) => {
		const types = select( coreStore ).getPostTypes( { per_page: -1 } );
		if ( ! types ) return null;
		return types.filter(
			( type ) =>
				type.viewable &&
				type.slug !== 'attachment' &&
				type.slug !== 'wp_block' &&
				type.slug !== 'wp_template' &&
				type.slug !== 'wp_template_part' &&
				type.slug !== 'wp_navigation'
		);
	}, [] );

	const togglePostType = ( slug, checked ) => {
		const updated = checked
			? [ ...postTypes, slug ]
			: postTypes.filter( ( t ) => t !== slug );
		setAttributes( { postTypes: updated.length ? updated : [ slug ] } );
	};

	return (
		<>
			<InspectorControls>
				<PanelBody
					title={ __( 'コメントループ設定', 'next-comment-loop' ) }
				>
					<p>
						<strong>
							{ __(
								'対象の投稿タイプ',
								'next-comment-loop'
							) }
						</strong>
					</p>
					{ availablePostTypes === null ? (
						<Spinner />
					) : (
						availablePostTypes.map( ( type ) => (
							<CheckboxControl
								key={ type.slug }
								label={ type.name }
								checked={ postTypes.includes( type.slug ) }
								onChange={ ( checked ) =>
									togglePostType( type.slug, checked )
								}
							/>
						) )
					) }
					<SelectControl
						label={ __( 'ソート順', 'next-comment-loop' ) }
						value={ sortOrder }
						options={ [
							{
								label: __(
									'新しい順（降順）',
									'next-comment-loop'
								),
								value: 'date_desc',
							},
							{
								label: __(
									'古い順（昇順）',
									'next-comment-loop'
								),
								value: 'date_asc',
							},
						] }
						onChange={ ( value ) =>
							setAttributes( { sortOrder: value } )
						}
					/>
					<RangeControl
						label={ __( '取得件数', 'next-comment-loop' ) }
						value={ number }
						onChange={ ( value ) =>
							setAttributes( { number: value } )
						}
						min={ 1 }
						max={ 100 }
					/>
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				<InnerBlocks
					allowedBlocks={ ALLOWED_BLOCKS }
					template={ TEMPLATE }
				/>
			</div>
		</>
	);
}
