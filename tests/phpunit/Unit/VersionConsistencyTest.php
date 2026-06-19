<?php
/**
 * Unit tests for version consistency.
 *
 * @package next-comment-loop
 */

namespace NextCommentLoop\Tests\Unit;

use Yoast\WPTestUtils\BrainMonkey\TestCase;

/**
 * プラグインヘッダーと package.json のバージョン整合性を検証する。
 *
 * WordPress に依存しない純粋な Unit テスト。
 */
class VersionConsistencyTest extends TestCase {

	/**
	 * プラグインルートディレクトリのパス。
	 *
	 * @var string
	 */
	private string $plugin_dir;

	/**
	 * セットアップ。
	 */
	protected function set_up(): void {
		parent::set_up();
		$this->plugin_dir = dirname( __DIR__, 3 );
	}

	/**
	 * プラグインヘッダーから Version を取得する。
	 */
	private function get_header_version(): ?string {
		$contents = (string) file_get_contents( $this->plugin_dir . '/NExT-Comment-Loop.php' );
		if ( preg_match( '/Version:\s*([0-9]+\.[0-9]+\.[0-9]+)/', $contents, $matches ) ) {
			return $matches[1];
		}
		return null;
	}

	/**
	 * package.json から version を取得する。
	 */
	private function get_package_version(): ?string {
		$json = json_decode( (string) file_get_contents( $this->plugin_dir . '/package.json' ), true );
		return $json['version'] ?? null;
	}

	/**
	 * プラグインヘッダーにバージョンが定義されていること。
	 */
	public function test_plugin_header_has_version(): void {
		$this->assertMatchesRegularExpression(
			'/^[0-9]+\.[0-9]+\.[0-9]+$/',
			(string) $this->get_header_version()
		);
	}

	/**
	 * プラグインヘッダーと package.json のバージョンが一致すること。
	 */
	public function test_header_and_package_versions_match(): void {
		$this->assertSame(
			$this->get_header_version(),
			$this->get_package_version(),
			'Plugin header version and package.json version must match.'
		);
	}
}
