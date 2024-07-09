const elForm       = document.querySelector( '.js-flade-find-posts-form' );
const elThead      = document.querySelector( '.js-flade-find-posts-thead' );
const elResultsBox = document.querySelector( '.js-flade-find-posts-results' );
const elPagination = document.querySelector( '.js-flade-find-posts-pagination' );
const elSearch     = elForm?.querySelector( '[name="flade_search_value"]' );
if ( elForm && elThead && elResultsBox && elPagination && elSearch ) {
	let autoSubmitTimeout;
	let waitFiltering = 0;

	// Reload items on search typing
	elSearch.addEventListener( 'input', () => {
		submitForm();
	} );

	// Prevent direct form submit
	elForm.addEventListener( 'submit', e => {
		e.preventDefault();

		submitForm( 50 );
	} );

	// Reload on other inputs change
	elForm.querySelectorAll( '.js-flade-find-posts-input' ).forEach( elInput => {
		elInput.addEventListener( 'change', () => {
			// Don't submit if "Search By" select was changed without the searched value
			if ( elInput.name === 'flade_search_by' && ! elSearch.value ) return;

			submitForm( 0 );
		} )
	} );

	// Reload items on pagination number change
	elForm.addEventListener( 'change', e => {
		const elPageInput = e?.target?.closest( '[name="flade_paged"]' )
		if ( ! elPageInput ) return;

		// Save page param in the URL
		updateUrlWithParams( {
			'paged': elPageInput.value || 1,
		} );

		submitForm( 0, false );
	} );

	// Reload items on pagination click
	elForm.addEventListener( 'click', e => {
		const elPagingBtn = e?.target?.closest( '.js-flade-find-posts-paging' );
		if ( ! elPagingBtn ) return;

		// Change the input value and run the listener
		const elPageInput = elForm.querySelector( '[name="flade_paged"]' );
		if ( elPageInput ) {
			// Prevent page reload
			e.preventDefault();

			elPageInput.value = elPagingBtn.dataset.page || 1;
			elPageInput.dispatchEvent( new Event( 'change', { bubbles: true } ) );
		}
	} );

	// Reload items on sort change
	const elAllTh = elThead.querySelectorAll( 'th' );
	elThead.querySelectorAll( '.js-flade-find-posts-sort' ).forEach( elLink => {
		elLink.addEventListener( 'click', e => {
			e.preventDefault();

			const elThisTh = elLink.closest( 'th' );
			if ( ! elThisTh ) return;

			// Change classes like WP is doing
			if ( ! elThisTh.classList.contains( 'sorted' ) ) {
				elAllTh.forEach( elLoopTh => {
					elLoopTh.classList.remove( 'sorted' );
				} );

				elThisTh.classList.add( 'sorted' );
			}

			elThisTh.classList.toggle( 'desc' );
			elThisTh.classList.toggle( 'asc' );

			submitForm( 0 );
		} )
	} );

	// Check timeout to prevent multiple requests
	function submitForm( delay = 300, resetCurrentPage = true ) {
		if ( resetCurrentPage ) {
			// Save page param in the URL
			updateUrlWithParams( {
				'paged': 1,
			} );
		}

		clearTimeout( autoSubmitTimeout );

		autoSubmitTimeout = setTimeout( () => {
			reloadItems( elForm );
		}, delay );
	}

	function reloadItems( elForm ) {
		waitFiltering++;

		// Set loading state
		elResultsBox.classList.add( 'loading' );

		// Gather selected post statuses
		let postStatus = [];
		elForm.querySelectorAll( '[name="flade_post_status"]:checked' ).forEach( elCheckbox => {
			postStatus.push( elCheckbox.value );
		} );

		const order       = elThead.querySelector( '.sorted' )?.classList.contains( 'asc' ) ? 'asc' : 'desc';
		const orderBy     = elThead.querySelector( '.sorted' )?.querySelector( '[data-order-by]' )?.dataset.orderBy || 'post_date';
		const perPage     = elForm.querySelector( '[name="flade_per_page"]' )?.value || '';
		const searchBy    = elForm.querySelector( '[name="flade_search_by"]' )?.value || 'post_title';
		const searchValue = elSearch?.value || '';

		let currentUrl  = window.location.href;
		const url       = new URL( currentUrl );
		const urlSearch = new URLSearchParams( url.search );
		let page        = urlSearch.get( 'paged' ) || 1;

		// Save all searched params in the URL, so it can be used on page reload
		updateUrlWithParams( {
			'order': order,
			'order_by': orderBy,
			'flade_search_by': searchBy,
			'flade_search_value': searchValue,
			'flade_post_status': postStatus,
			'flade_per_page': perPage,
			'paged': page, // keep "paged" parameter in the end, as it often rewrites
		} );


		if ( waitFiltering > 1 ) return;

		// Get updated url
		currentUrl = window.location.href;

		const body = {
			action: 'flade_find_posts',
			nonce: flade_ajax.nonce,
			current_url: currentUrl,
			order: order,
			order_by: orderBy,
			paged: page,
			per_page: perPage,
			post_status: postStatus,
			search_by: searchBy,
			search_value: searchValue,
		};

		fetch(
			flade_ajax.url,
			{
				method: 'POST',
				credentials: 'same-origin',
				headers: new Headers( { 'Content-Type': 'application/x-www-form-urlencoded' } ),
				body: new URLSearchParams( body ).toString()
			}
		)
			.then( response => response.json() )
			.then(
				response => {
					const tableMarkup      = response.data?.[ 'table_markup' ] || '';
					const paginationMarkup = response.data?.[ 'pagination_markup' ] || '';

					if ( response.success && tableMarkup ) {
						if ( waitFiltering > 1 ) {
							waitFiltering = 0;
							reloadItems( elForm );
						} else {
							waitFiltering = 0;

							// Insert loaded content
							elResultsBox.innerHTML = tableMarkup;
							elPagination.innerHTML = paginationMarkup;
						}
					}
				}
			)
			.catch(
				error => {
					console.error( 'Error:', error );
				}
			)
			.then(
				() => {
					// Remove loading state
					if ( waitFiltering < 1 ) {
						elResultsBox.classList.remove( 'loading' );
					}
				}
			);
	}

	function updateUrlWithParams( paramsPairs = {} ) {
		// Modify URL with search parameters
		let newUrl = window.location.href;

		for ( const key in paramsPairs ) {
			newUrl = updateURLParameter( newUrl, key, paramsPairs[ key ] );
		}

		// Update URL without page reload
		window.history.pushState( null, null, newUrl );
	}

	// Function to update query parameters in the URL
	function updateURLParameter( url, param, paramVal ) {
		let theAnchor;
		let tmpAnchor;
		let theParams;
		let newAdditionalURL = '';
		let tempArray        = url.split( '?' );
		let baseURL          = tempArray[ 0 ];
		let additionalURL    = tempArray[ 1 ];
		let temp             = '';

		paramVal = encodeURIComponent( paramVal );

		if ( additionalURL ) {
			tmpAnchor = additionalURL.split( '#' );
			theParams = tmpAnchor[ 0 ];
			theAnchor = tmpAnchor[ 1 ];
			if ( theAnchor )
				additionalURL = theParams;

			tempArray = additionalURL.split( '&' );

			for ( let i = 0; i < tempArray.length; i++ ) {
				if ( tempArray[ i ].split( '=' )[ 0 ] !== param ) {
					newAdditionalURL += temp + tempArray[ i ];
					temp = '&';
				}
			}
		} else {
			tmpAnchor = baseURL.split( '#' );
			theParams = tmpAnchor[ 0 ];
			theAnchor = tmpAnchor[ 1 ];

			if ( theParams ) {
				baseURL = theParams;
			}
		}

		if ( theAnchor )
			paramVal += '#' + theAnchor;

		const rowsTxt = temp + '' + param + '=' + paramVal;

		return baseURL + '?' + newAdditionalURL + rowsTxt;
	}

	// Submit the form on a page load in case of saved params in the URL
	submitForm( 0, false );
}
