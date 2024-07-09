<?php

$test_name = $argv[1] ?? null;

if ( ! $test_name ) {
	echo "Please provide a test name.\n";
	exit( 1 );
}

$file_name = __DIR__ . "/{$test_name}Test.php";

$template = <<<EOT
<?php
use WP_Mock\Tools\TestCase;

class {$test_name}Test extends TestCase {

	public function setUp(): void {
		WP_Mock::setUp();
		parent::setUp();
	}

	public function tearDown(): void {
		parent::tearDown();
		WP_Mock::tearDown();
	}

	public function test_example_function() {
		// Your test code here
	}
}
EOT;

file_put_contents( $file_name, $template ); //phpcs:ignore

echo "Test template created as {$file_name}\n"; //phpcs:ignore
