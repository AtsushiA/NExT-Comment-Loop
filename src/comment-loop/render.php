<?php
/**
 * Comment Loop ブロックのサーバーサイドレンダリング。
 *
 * @package next-comment-loop
 *
 * @param array    $attributes ブロック属性。
 * @param string   $content    保存済みインナーブロックのHTML（未使用）。
 * @param WP_Block $block      ブロックインスタンス。
 */

// ソート順の検証（allowlist）.
$allowed_orders = array( 'date_asc', 'date_desc' );
$sort_order     = isset( $attributes['sortOrder'] ) && in_array( $attributes['sortOrder'], $allowed_orders, true )
	? $attributes['sortOrder']
	: 'date_desc';

$comment_order = ( 'date_asc' === $sort_order ) ? 'ASC' : 'DESC';
$number        = max( 1, min( 100, isset( $attributes['number'] ) ? absint( $attributes['number'] ) : 10 ) );

// 投稿タイプの検証（公開済み投稿タイプのみ許可）.
$raw_post_types   = isset( $attributes['postTypes'] ) && is_array( $attributes['postTypes'] )
	? $attributes['postTypes']
	: array( 'post' );
$valid_post_types = get_post_types( array( 'public' => true ), 'names' );
$post_types       = array_values(
	array_filter(
		$raw_post_types,
		static function ( $pt ) use ( $valid_post_types ) {
			return is_string( $pt ) && isset( $valid_post_types[ $pt ] );
		}
	)
);

if ( empty( $post_types ) ) {
	$post_types = array( 'post' );
}

$comment_items = get_comments(
	array(
		'status'    => 'approve',
		'number'    => $number,
		'orderby'   => 'comment_date',
		'order'     => $comment_order,
		'post_type' => $post_types,
	)
);

$wrapper_attributes = get_block_wrapper_attributes();

if ( empty( $comment_items ) ) {
	printf(
		'<div %s><p>%s</p></div>', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		$wrapper_attributes, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		esc_html__( 'コメントはありません。', 'next-comment-loop' )
	);
	return;
}

$items = '';
foreach ( $comment_items as $comment_item ) {
	$item_content = '';
	foreach ( $block->inner_blocks as $inner_block ) {
		$item_content .= ( new WP_Block(
			$inner_block->parsed_block,
			array( 'next/commentId' => $comment_item->comment_ID )
		) )->render();
	}
	$items .= '<div class="next-comment-item">' . $item_content . '</div>';
}

printf(
	'<div %s>%s</div>', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	$wrapper_attributes, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	$items // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
);
