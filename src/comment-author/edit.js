import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';

export default function Edit() {
	const blockProps = useBlockProps();
	return (
		<span { ...blockProps }>
			{ __( 'コメント投稿者名', 'next-comment-loop' ) }
		</span>
	);
}
