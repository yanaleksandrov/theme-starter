import { useEffect } from '@wordpress/element';

/**
 * Detects if a click is outside an element
 *
 * @param {element} ref Element reference using the React way with `useRef`.
 * @param {callback} handler Handler function to call when click is outside ref element.
 *
 * @return {void}
 */
export const useOnClickOutside = ( ref, handler ) => {
	useEffect( () => {
		const listener = ( event ) => {
			// Do nothing if clicking the ref's element or descendent elements
			if ( ! ref.current || ref.current.contains( event.target ) ) {
				return;
			}
			handler( event );
		};
		document.addEventListener( 'mousedown', listener );
		document.addEventListener( 'touchstart', listener );

		return () => {
			document.removeEventListener( 'mousedown', listener );
			document.removeEventListener( 'touchstart', listener );
		};
	}, [ ref, handler ] );
};
