<?php  //phpcs:ignore

use WP_Mock\Tools\TestCase;
use function fladeTheme\Helpers\trim_string;

class TrimStringTest extends TestCase {

	public function setUp(): void {
		WP_Mock::setUp();
		parent::setUp();
	}

	public function tearDown(): void {
		parent::tearDown();
		WP_Mock::tearDown();
	}

	public function test_trim_string_trims_correctly() {
		$this->assertEquals( 'hello', trim_string( '  hello  ' ) );
		$this->assertEquals( 'world', trim_string( 'world   ' ) );
		$this->assertEquals( 'test', trim_string( '   test' ) );
	}

	public function test_trim_string_returns_second_argument_for_non_string() {
		$this->assertEquals( 'default', trim_string( 123, 'default' ) );
		$this->assertEquals( 'fallback', trim_string( [ 'test' ], 'fallback' ) );
		$this->assertEquals( '', trim_string( null ) ); // No second argument is provided, so it should return an empty string.
	}
}
