<?php  //phpcs:ignore

use WP_Mock\Tools\TestCase;

class GetVersionTest extends TestCase {

	public function setUp(): void {
		WP_Mock::setUp();
		parent::setUp();
	}

	public function tearDown(): void {
		parent::tearDown();
		WP_Mock::tearDown();
	}

	public function test_is_single() {
		WP_Mock::userFunction(
			'get_permalink',
			[
				'return_in_order' => [
					'https://google.com',
					'https://ya.ru',
				],
				//			'times' => 2
			]
		);

		$this->assertStringStartsWith( 'http', get_permalink() );
	}
}
