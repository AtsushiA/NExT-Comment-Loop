<?php
/**
 * Comment Author ブロックのサーバーサイドレンダリング。
 *
 * @package next-comment-loop
 *
 * @param array    $attributes ブロック属性。
 * @param string   $content    保存済みコンテンツ（未使用）。
 * @param WP_Block $block      ブロックインスタンス。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$comment_id = $block->context['next/commentId'] ?? 0;
if ( ! $comment_id ) {
	return;
}

$comment_obj = get_comment( absint( $comment_id ) );
if ( ! $comment_obj ) {
	return;
}

$author = '' !== $comment_obj->comment_author
	? $comment_obj->comment_author
	: __( '名無し', 'next-comment-loop' );

$wrapper_attributes = get_block_wrapper_attributes();

printf(
	'<span %s>%s</span>', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	$wrapper_attributes, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	esc_html( $author )
);
