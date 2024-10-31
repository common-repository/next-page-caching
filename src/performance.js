/**
 * Sorts a URL array by time loaded if available, if cached, sorts by file size.
 *
 * @param {urlArray} Array Array of PerformanceEntry
 *
 * @return Array Sorted PerformanceEntry array
 */
export const sortURLs = ( urlArray ) => {
	let hasZeroTime = false
	urlArray.sort( ( a, b ) => {
		if ( ! a.time || ! b.time ) {
			hasZeroTime = true
		}
		return b.time - a.time
	} )
	if ( hasZeroTime ) {
		urlArray.sort( ( a, b ) => ( b.size - a.size ) )
	}
	return urlArray
}

/**
 * Gets the size by bytes of a PerformanceEntry.
 *
 * @param {perfEntry} PerformanceEntry
 *
 * @param Number Size in bytes
 */
export const getPerfSize = ( perfEntry ) => {
	return perfEntry.decodedBodySize || perfEntry.encodedBodySize || perfEntry.transferSize || 0
}

/**
 * Gets the time duration of a PerformanceEntry. Units aren't reliable, can be
 * given by browsers as ms or seconds.
 *
 * @param {perfEntry} PerformanceEntry
 *
 * @param Number Time in ms or seconds
 */
export const getPerfTime = ( perfEntry ) => {
	return perfEntry.duration || 0
}

/**
 * Onload callback on an iframe, called internally by getResourcesLoaded.
 *
 * @param {iframe} DomElement The iframe to wait for
 * @param {callback} Function Called after onload
 */
const onIframeLoad = ( iframe, callback = () => {} ) => {
	setTimeout( () => {
		let entriesArray = []
		try {
			const perfEntries = iframe.contentWindow.performance.getEntriesByType( 'resource' )
			entriesArray = perfEntries.map( perfEntry => {
				return {
					size: getPerfSize( perfEntry ),
					time: getPerfTime( perfEntry ),
					name: perfEntry.name,
				}
			} )
		} catch ( err ) {
			console.error( `[Next Page Caching] cannot get performance metrics: ${err}` )
		}
		iframe.remove()
		callback( sortURLs( entriesArray ) )
	}, 5000 );
}

/**
 * Loads a URL, then gets the resource URLs loaded sorted by largest filesize
 * or longest loading time (depending whether the resources were cached).
 *
 * @param {url} String The URL to load
 * @param {callback} Fucntion The function to call after loading, the resource
 *                            URLs loaded are passed to the function.
 *
 * @return DomElement The iframe temporarily created to do the loading.
 */
export const getResourcesLoaded = ( url, callback ) => {
	const iframe = document.createElement( 'iframe' )
	iframe.onload = onIframeLoad( iframe, callback )
	iframe.setAttribute( 'src', url )
	iframe.style.width = '1px'
	iframe.style.height = '1px'
	// iframe.style.visibility = 'hidden'
	// iframe.style.position = 'absolute'
	iframe.style.top = 0
	iframe.style.left = 0
	document.body.appendChild( iframe )
	iframe.contentWindow.performance.clearResourceTimings()
	return iframe
}
