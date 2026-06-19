<?php
/**
 * Comment Content ブロックのサーバーサイドレンダリング。
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

$wrapper_attributes = get_block_wrapper_attributes();

printf(
	'<div %s>%s</div>', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	$wrapper_attributes, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	wp_kses_post( $comment_obj->comment_content )
);
