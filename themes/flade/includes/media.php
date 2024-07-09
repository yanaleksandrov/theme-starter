<?php

namespace fladeTheme\Media;

function start() {
	$callback = function ( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	// Setting unique slugs for attachments on upload to not intersect with other posts
	add_filter( 'pre_wp_unique_post_slug', $callback( 'modify_attachment_slug' ), 10, 5 );

	// Remove attachments rewrite rules
	add_filter( 'rewrite_rules_array', $callback( 'rewrite_rules_array' ) );

	// Remove attachment query variables
	add_filter( 'request', $callback( 'attachment_request' ) );

	// Remove attachment link
	add_filter( 'attachment_link', '__return_empty_string' );
}

/**
 * Filters the post slug before it is generated to be unique.
 *
 * @param string|null $override_slug Short-circuit return value.
 * @param string $slug The desired slug (post_name).
 * @param int $post_ID Post ID.
 * @param string $post_status The post status.
 * @param string $post_type Post type.
 *
 * @return string|null
 */
function modify_attachment_slug( ?string $override_slug, string $slug, int $post_ID, string $post_status, string $post_type ): ?string {
	// Apply only for attachments
	if ( 'attachment' !== $post_type ) {
		return $override_slug;
	}

	// Slug prefix that should be applied
	$prefix = 'attachment-';

	// Check if modified slug is already in use, add a numerical suffix if needed
	global $wpdb;
	$check_sql  = "SELECT post_name FROM $wpdb->posts WHERE (post_name = %s || post_name = %s) AND ID != %d LIMIT 1";
	$name_check = $wpdb->get_var( $wpdb->prepare( $check_sql, $slug, $prefix . $slug, $post_ID ) ); //phpcs:ignore
	if ( $name_check ) {
		$suffix = 2;
		do {
			$alt_post_name = _truncate_post_slug( $slug, 200 - ( strlen( $suffix ) + 1 ) ) . "-$suffix";
			$name_check    = $wpdb->get_var( $wpdb->prepare( $check_sql, $alt_post_name, $prefix . $alt_post_name, $post_ID ) ); //phpcs:ignore
			$suffix ++;
		} while ( $name_check );
		$slug = $alt_post_name;
	}

	// Return modified slug
	return $prefix . $slug;
}

/**
 * Filters the full set of generated rewrite rules.
 *
 * @param string[] $rules
 *
 * @return string[]
 */
function rewrite_rules_array( array $rules ): array {
	// Remove any rule, written for attachments
	foreach ( $rules as $regex => $query ) {
		if ( strpos( $regex, 'attachment' ) || strpos( $query, 'attachment' ) ) {
			unset( $rules[ $regex ] );
		}
	}

	return $rules;
}

/**
 * Filters the array of parsed query variables.
 *
 * @param array $query_vars The array of requested query variables.
 */
function attachment_request( array $query_vars ): array {
	if ( ! empty( $query_vars['attachment'] ) ) {
		$query_vars['page'] = '';
		$query_vars['name'] = $query_vars['attachment'];
		unset( $query_vars['attachment'] );
	}

	return $query_vars;
}
