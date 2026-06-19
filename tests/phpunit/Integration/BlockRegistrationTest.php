<?php
/**
 * Integration tests for block registration.
 *
 * @package next-comment-loop
 */

namespace NextCommentLoop\Tests\Integration;

use WP_UnitTestCase;
use WP_Block_Type_Registry;

/**
 * 各ブロックが正しく登録されることを検証する。
 */
class BlockRegistrationTest extends WP_UnitTestCase {

	/**
	 * プラグインが提供する全ブロックが登録されていること。
	 *
	 * @dataProvider provide_block_names
	 *
	 * @param string $block_name 登録されるブロック名。
	 */
	public function test_block_is_registered( string $block_name ): void {
		$registry = WP_Block_Type_Registry::get_instance();
		$this->assertTrue(
			$registry->is_registered( $block_name ),
			sprintf( 'Block "%s" should be registered.', $block_name )
		);
	}

	/**
	 * 登録されるべきブロック名の一覧。
	 *
	 * @return array<string, array<int, string>>
	 */
	public function provide_block_names(): array {
		return array(
			'comment-loop'       => array( 'next/comment-loop' ),
			'comment-date'       => array( 'next/comment-date' ),
			'comment-author'     => array( 'next/comment-author' ),
			'comment-content'    => array( 'next/comment-content' ),
			'comment-post-title' => array( 'next/comment-post-title' ),
		);
	}
}
