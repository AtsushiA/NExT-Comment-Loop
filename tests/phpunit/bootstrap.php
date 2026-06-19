<?php
/**
 * PHPUnit bootstrap file for Integration tests.
 *
 * WordPress テスト環境をロードする bootstrap。
 *
 * @package next-comment-loop
 */

// Composer autoloader.
require_once dirname( __DIR__, 2 ) . '/vendor/autoload.php';

$_tests_dir = getenv( 'WP_TESTS_DIR' );
if ( ! $_tests_dir ) {
	$_tests_dir = '/tmp/wordpress-tests-lib';
}

require_once $_tests_dir . '/includes/functions.php';

/**
 * テスト対象プラグインを手動で読み込む。
 */
function _manually_load_plugin() {
	require dirname( __DIR__, 2 ) . '/NExT-Comment-Loop.php';
}
tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

// WordPress テスト環境を起動。
require $_tests_dir . '/includes/bootstrap.php';
