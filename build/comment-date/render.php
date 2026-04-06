<?php
/**
 * Comment Date ブロックのサーバーサイドレンダリング。
 *
 * @param array    $attributes ブロック属性。
 * @param string   $content    保存済みコンテンツ（未使用）。
 * @param WP_Block $block      ブロックインスタンス。
 */

$comment_id = $block->context['next/commentId'] ?? 0;
if ( ! $comment_id ) {
	return;
}

$comment_obj = get_comment( absint( $comment_id ) );
if ( ! $comment_obj ) {
	return;
}

$format = isset( $attributes['format'] ) && '' !== $attributes['format']
	? $attributes['format']
	: 'Y/m/d H:i';

$date               = get_comment_date( $format, $comment_obj );
$wrapper_attributes = get_block_wrapper_attributes();

printf(
	'<time %s>%s</time>', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	$wrapper_attributes, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	esc_html( $date )
);
