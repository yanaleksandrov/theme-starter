/**
 * Get a meta value from Gutenberg.
 *
 * @param {string} field Meta field slug.
 *
 * @return {mixed} Parsed value of the meta field.
 */
export function getMeta( field ) {
	const currentMeta = wp.data.select( 'core/editor' ).getEditedPostAttribute( 'meta' );

	if ( 'undefined' === typeof currentMeta || ! currentMeta[ field ] ) {
		return '';
	}

	return currentMeta[ field ];
}

/**
 * Save a meta value from Gutenberg.
 *
 * @param {string} field Meta field for which to set a new value.
 * @param {string} value New value for meta field.
 */
export function setMeta( field, value ) {
	const newMeta = { [ field ]: value };

	wp.data.dispatch( 'core/editor' ).editPost( { meta: newMeta, } );
}
