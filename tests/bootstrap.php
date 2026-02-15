<?php
/**
 * PHPUnit bootstrap file
 */

define( 'WP_TESTS_PHPUNIT_POLYFILLS_PATH', dirname( __DIR__ ) . '/vendor/yoast/phpunit-polyfills/' );

require_once dirname( __DIR__ ) . '/vendor/yoast/phpunit-polyfills/phpunitpolyfills-autoload.php';

// Load WordPress
require_once '/var/www/html/wp-load.php';

if ( ! class_exists( 'WP_UnitTestCase' ) ) {
	class WP_UnitTestCase extends \PHPUnit\Framework\TestCase {
		protected function setUp(): void {
			parent::setUp();
			global $wpdb;
			$wpdb->query( 'START TRANSACTION' );
		}

		protected function tearDown(): void {
			global $wpdb;
			$wpdb->query( 'ROLLBACK' );
			parent::tearDown();
		}
	}
}
