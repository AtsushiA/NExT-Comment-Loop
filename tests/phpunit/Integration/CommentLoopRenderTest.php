<?php
/**
 * Integration tests for the comment-loop block rendering.
 *
 * @package next-comment-loop
 */

namespace NextCommentLoop\Tests\Integration;

use WP_UnitTestCase;

/**
 * comment-loop ブロックのサーバーサイドレンダリングを検証する。
 */
class CommentLoopRenderTest extends WP_UnitTestCase {

	/**
	 * コメントが存在しない場合は「コメントはありません。」を表示すること。
	 */
	public function test_renders_empty_message_when_no_comments(): void {
		$block_markup = '<!-- wp:next/comment-loop --><!-- wp:next/comment-content /--><!-- /wp:next/comment-loop -->';

		$output = do_blocks( $block_markup );

		$this->assertStringContainsString( 'コメントはありません。', $output );
	}

	/**
	 * 承認済みコメントが存在する場合はコメント項目を出力すること。
	 */
	public function test_renders_comment_items_when_comments_exist(): void {
		$post_id = self::factory()->post->create(
			array( 'post_status' => 'publish' )
		);

		self::factory()->comment->create(
			array(
				'comment_post_ID'  => $post_id,
				'comment_content'  => 'これはテストコメントです。',
				'comment_approved' => '1',
			)
		);

		$block_markup = '<!-- wp:next/comment-loop --><!-- wp:next/comment-content /--><!-- /wp:next/comment-loop -->';

		$output = do_blocks( $block_markup );

		$this->assertStringContainsString( 'next-comment-item', $output );
		$this->assertStringContainsString( 'これはテストコメントです。', $output );
		$this->assertStringNotContainsString( 'コメントはありません。', $output );
	}

	/**
	 * number 属性が 1〜100 の範囲にクランプされ、出力件数を制御すること。
	 */
	public function test_number_attribute_limits_rendered_items(): void {
		$post_id = self::factory()->post->create(
			array( 'post_status' => 'publish' )
		);

		for ( $i = 0; $i < 3; $i++ ) {
			self::factory()->comment->create(
				array(
					'comment_post_ID'  => $post_id,
					'comment_content'  => 'コメント' . $i,
					'comment_approved' => '1',
				)
			);
		}

		$block_markup = '<!-- wp:next/comment-loop {"number":2} --><!-- wp:next/comment-content /--><!-- /wp:next/comment-loop -->';

		$output = do_blocks( $block_markup );

		$this->assertSame( 2, substr_count( $output, 'next-comment-item' ) );
	}
}
