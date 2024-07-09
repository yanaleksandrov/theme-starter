/**
 * Fade In animation.
 *
 * @param elem
 * @param ms
 * @param display
 */
export const fadeIn = ( elem, ms, display ) => {
	if ( ! elem ) {
		return;
	}

	elem.style.opacity = '0';
	elem.style.filter  = 'alpha(opacity=0)';
	elem.style.display = display || 'block';

	if ( ms ) {
		let opacity = 0;
		const timer = setInterval(
			function() {
				opacity += 10 / ms;
				if ( opacity >= 1 ) {
					clearInterval( timer );
					opacity = 1;
				}
				elem.style.opacity = opacity;
				elem.style.filter  = 'alpha(opacity=' + opacity * 100 + ')';
			},
			10
		);
	} else {
		elem.style.opacity = '1';
		elem.style.filter  = 'alpha(opacity=1)';
	}
}

/**
 * Fade Out animation.
 *
 * @param elem
 * @param ms
 */
export const fadeOut = ( elem, ms ) => {
	if ( ! elem ) {
		return;
	}

	if ( ms ) {
		let opacity = 1;
		const timer = setInterval(
			function() {
				opacity -= 10 / ms;
				if ( opacity <= 0 ) {
					clearInterval( timer );
					opacity            = 0;
					elem.style.display = 'none';
				}
				elem.style.opacity = opacity;
				elem.style.filter  = 'alpha(opacity=' + opacity * 100 + ')';
			},
			10
		);
	} else {
		elem.style.opacity = '0';
		elem.style.filter  = 'alpha(opacity=0)';
		elem.style.display = 'none';
	}
}

// Scroll window to the element position
export const scrollToElem = ( el ) => {

	const header = document.querySelector( '.header' );
	const offset = header ? header.offsetHeight : 0; // keep in mind the sticky header height
	const topPos = el.getBoundingClientRect().top + window.scrollY - offset;

	window.scrollTo( { top: topPos, behavior: 'smooth' } );
}

/**
 * Serialize form data.
 *
 * @param data Form data.
 * @returns {{}}
 */
export const serializeFormData = ( data ) => {

	let obj = {};

	for ( let [ key, value ] of data ) {
		if ( obj[ key ] !== undefined ) {
			if ( ! Array.isArray( obj[ key ] ) ) {
				obj[ key ] = [ obj[ key ] ];
			}
			obj[ key ].push( value );
		} else {
			obj[ key ] = value;
		}
	}

	return obj;
}

/**
 * Get Element's offset from the top/left.
 *
 * @param el
 * @param direction top|left
 * @returns {number} Distance in px
 */
export const getOffset = ( el, direction ) => {

	let _x = 0;
	let _y = 0;

	while ( el && ! isNaN( el.offsetLeft ) && ! isNaN( el.offsetTop ) ) {
		_x += el.offsetLeft - el.scrollLeft;
		_y += el.offsetTop - el.scrollTop;

		el = el.offsetParent;
	}

	switch ( direction ) {
		case 'top':
			return _y;

		case 'left':
			return _x;

		default:
			return 0;
	}
}

/**
 * Check for a mobile device.
 *
 * @param mobWidth Screen width that will be compared as a fallback.
 * @returns {boolean}
 */
export const isMobile = ( mobWidth = 992 ) => {
	try {
		return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(
			navigator.userAgent
		)
	} catch ( e ) {
		return window.innerWidth < mobWidth;
	}
}
