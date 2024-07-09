<?php
namespace fladeTheme\Ajax;

trait Ajax {

	/**
	 * Add ajax action
	 *
	 * @param $action
	 * @param $callback
	 * @param bool $for_logged_user
	 * @return void
	 */
	public function add_action( $action, $callback, $for_logged_user = false ) {
		if ( ! wp_doing_ajax() ) {
			return;
		}

		$action = sanitize_text_field( $action );
		if ( ! $action || ! $callback instanceof \Closure ) {
			return;
		}

		switch ( $for_logged_user ) {
			case true:
				add_action( 'wp_ajax_' . $action, $callback );
				break;
			case false:
				add_action( 'wp_ajax_' . $action, $callback );
				add_action( 'wp_ajax_nopriv_' . $action, $callback );
				break;
		}
	}

	/**
	 * Middleware for callback
	 *
	 * @param callable|array $callback
	 * @param array $args
	 * @return \Closure
	 */
	public function handler( $callback, $args = [] ) {
		return function () use ( $callback, $args ) {
			call_user_func_array( $callback, $args );
		};
	}

	/**
	 * Validate ajax nonce
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function check_nonce() {
		$nonce_ = $_REQUEST['nonce'] ?? '';
		if ( ! wp_verify_nonce( $nonce_, 'flade-nonce' ) ) {
			wp_send_json_error(
				[ 'msg' => esc_html__( 'Invalid nonce', 'flade' ) ],
				401
			);
		}
		return $_REQUEST;
	}

	/**
	 * Upload files to admin
	 *
	 * @return array
	 */
	public function upload_files( $field = '' ) {
		$attachments = [];

		$field = sanitize_text_field( $field );
		$files = ! empty( $field ) ? ( $_FILES[ $field ] ?? [] ) : $_FILES;
		if ( $files ) {
			require_once ABSPATH . 'wp-admin/includes/image.php';
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/media.php';

			foreach ( $files as $file_id => $file_data ) {
				$attach_id = media_handle_upload( $file_id, $file_data );
				if ( $attach_id ) {
					$attachments[] = array_merge( $file_data, [ 'id' => $attach_id ] );
				}
			}
		}

		return $attachments;
	}
}
