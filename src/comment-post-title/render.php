<?php
/**
 * Comment Post Title ブロックのサーバーサイドレンダリング。
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

$post_id = absint( $comment_obj->comment_post_ID );
if ( ! $post_id ) {
	return;
}

$post_obj = get_post( $post_id );
if ( ! $post_obj || 'publish' !== $post_obj->post_status ) {
	return;
}

$title   = get_the_title( $post_obj );
$is_link = isset( $attributes['isLink'] ) ? (bool) $attributes['isLink'] : true;

if ( $is_link ) {
	$inner = sprintf(
		'<a href="%s">%s</a>',
		esc_url( get_permalink( $post_obj ) ),
		esc_html( $title )
	);
} else {
	$inner = esc_html( $title );
}

$wrapper_attributes = get_block_wrapper_attributes();

printf(
	'<div %s>%s</div>', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	$wrapper_attributes, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	$inner // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
);
