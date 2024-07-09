// Prevent links redirection while editing the content
document.addEventListener(
	'click',
	( e ) => {
		if ( e?.target?.closest( '.editor-styles-wrapper' ) ) {
			if ( e?.target?.nodeName?.toLowerCase() === 'a' || e?.target?.closest( 'a' ) ) {
				e.preventDefault();
			}
		}
	}
)
