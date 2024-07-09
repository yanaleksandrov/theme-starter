<?php

namespace fladeTheme\Ajax;

use fladeTheme\Patterns\Singleton;

class Ajax_Core extends Singleton {

	use Ajax;

	/**
	 * @return void
	 */
	public function init() {
		$this->add_action( 'test_ajax_anywhere', $this->handler( [ $this, 'test_ajax_anywhere' ] ) );
		$this->add_action( 'test_ajax_admin', $this->handler( [ $this, 'test_ajax_admin' ] ), true );
	}

	/**
	 * Boilerplate for ajax query
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function test_ajax_anywhere() {
		$data = $this->check_nonce();

		// now sanitize data & add logic

		if ( $data ) {
			wp_send_json_success( $data );
		}

		wp_send_json_error( $data );
	}

	/**
	 * Boilerplate for no private ajax query in dashboard
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function test_ajax_admin() {
		$data = $this->check_nonce();

		// now sanitize data & add logic

		if ( $data ) {
			wp_send_json_success( $data );
		}

		wp_send_json_error( $data );
	}
}
