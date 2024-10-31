import { basename } from 'path'

/**
 * Gets a shorter version of a URL for display purposes.
 * http://domain.com/ -> http://domain.com
 * http://domain.com/wp-content -> http://domain.com/wp-content
 * http://domain.com/wp-content/plugins -> http://domain.com/wp-content/plugins
 * http://domain.com/wp-content/plugins/name -> http://domain.com/wp-content/plugins/name
 * http://domain.com/wp-content/plugins/name/js/script.js -> http://domain.com/wp-content/plugins/.../script.js
 *
 * @param {url} String URL
 *
 * @return String Short URL
 */
export const getShortDisplayURL = url => {
	const parts = new URL( url )
	const numSlashes = [ ...parts.pathname ].filter( l => l === '/' ).length
	if ( numSlashes > 4 ) {
		const base = basename( parts.pathname )
		const startPath = parts.pathname.replace( /(^\/.*?\/.*?\/.*?\/)(.*)$/, '$1' )
		return `${parts.origin}${startPath}.../${base}`
	} else {
		return `${parts.origin}${parts.pathname}`
	}
	return parts.origin
}

/**
 * Returns the type of the URL for use in the `as` attribute of a <link>.
 * Similar to our PHP npc_get_type()
 *
 * @param {url} String The URL
 *
 * @return String The type
 *
 * @see https://www.w3.org/TR/preload/#x3-2-as-attribute
 */
export const getTypeURL = url => {
	const parts = new URL( url )
	const path = parts.pathname
	if ( path.match( /.js$/ ) ) {
		return 'script'
	} else if ( path.match( /.css$/ ) ) {
		return 'style'
	} else if ( path.match( /.(jpe?g|gif|png|svg)$/ ) ) {
		return 'image'
	} else if ( path.match( /.(eot|woff2?|ttf)$/ ) ) {
		return 'font'
	}
	return 'html'
}
