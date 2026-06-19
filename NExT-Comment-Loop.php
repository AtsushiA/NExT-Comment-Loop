<?php
/**
 * Plugin Name: NExT Comment Loop
 * Description: コメント一覧を表示するGutenbergブロックプラグイン。コメントループ・日時・投稿者名・内容の各ブロックを提供します。
 * Version:     1.1.4
 * Requires at least: 6.4
 * Requires PHP: 8.0
 * Author:      NExT-Season
 * Author URI:  https://next-season.net/
 * License:     GPL-2.0-or-later
 * Text Domain: next-comment-loop
 * Domain Path: /languages
 *
 * @package next-comment-loop
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * ブロックを登録する。
 */
function next_comment_loop_register_blocks(): void {
	$asset_file = __DIR__ . '/build/index.asset.php';

	if ( ! file_exists( $asset_file ) ) {
		return;
	}

	$asset = include $asset_file;

	wp_register_script(
		'next-comment-loop-editor',
		plugin_dir_url( __FILE__ ) . 'build/index.js',
		$asset['dependencies'],
		$asset['version'],
		true
	);

	register_block_type_from_metadata( __DIR__ . '/src/comment-loop' );
	register_block_type_from_metadata( __DIR__ . '/src/comment-date' );
	register_block_type_from_metadata( __DIR__ . '/src/comment-author' );
	register_block_type_from_metadata( __DIR__ . '/src/comment-content' );
	register_block_type_from_metadata( __DIR__ . '/src/comment-post-title' );
}
add_action( 'init', 'next_comment_loop_register_blocks' );
