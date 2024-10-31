import DocReady from 'es6-docready'
import { getResourcesLoaded } from './performance'
import { getShortDisplayURL, getTypeURL } from './url'
import { openLinkDialog } from './link-dialog'

const nextPageCallback = ( url, text, target ) => {
	document.querySelector( '#npc_next_url' ).value = url
}

const refreshCriticalURLs = () => {
	const criticalButton = document.querySelector( '#npc_critical_urls_button' )
	const criticalSelect = document.querySelector( '#npc_critical_urls' )
	console.log(criticalSelect.getAttribute( 'data-permalink' ));
	criticalButton.setAttribute( 'disabled', 'disabled' )
	criticalSelect.setAttribute( 'disabled', 'disabled' )
	populateSelect( criticalSelect.getAttribute( 'data-permalink' ), criticalSelect, () => {
		criticalButton.removeAttribute( 'disabled' )
		criticalSelect.removeAttribute( 'disabled' )
	} )
}

DocReady( () => {

	// Picker for the next URL.
	const nextPageInput = document.querySelector( '#npc_next_url' )
	const nextPageButton = document.querySelector( '#npc_next_url_button' )
	if ( nextPageButton ) {
		nextPageButton.addEventListener( 'click', () => {
			openLinkDialog( 'npc_next_url', {
				url: nextPageInput.value,
				hasText: false,
				hasNewWindow: false,
			}, nextPageCallback )
		} )
	}

	const criticalButton = document.querySelector( '#npc_critical_urls_button' )
	if ( criticalButton ) {
		criticalButton.addEventListener( 'click', refreshCriticalURLs )
		refreshCriticalURLs()
	}
	// npc_critical_urls
	// const buttons = document.querySelectorAll( '.npc-refresh-page-urls' )
	// Array.from( buttons ).forEach( button => {
	// 	button.addEventListener( 'click', () => {
	// 		populateSelect( url, button )
	// 	} )
	// } )
} )

export const populateSelect = ( url, select, callback = () => {} ) => {
	const urlCallback = ( urls ) => {
		const options = urls.map( url => {
			const type = getTypeURL( url.name )
			const label = getShortDisplayURL( url.name )
			let size = url.size / 1000
			if ( size <= 0 ) {
				size = ''
			} else {
				size = size.toLocaleString( undefined, { maximumFractionDigits: 2, minimumFractionDigits: 2 } )
			}
			return {
				label: size ? `[${type}] ${label} (${size}kb)` : `[${type}] ${label}`,
				value: url.name,
			}
		} )
		replaceSelectOptions( select, options )
		callback()
	}
	getResourcesLoaded( url, urlCallback )
}

/**
 * Replaces the options of the given element, retains selected options.
 *
 * @param {element} DomElement The <select> element
 * @param {options} Array The elements to replace the current options with
 */
export const replaceSelectOptions = ( element, options ) => {

	// Get the currently selected value.
	const selectedOptions = element.querySelectorAll( 'option:checked' )
	const selectedValues = Array.from( selectedOptions ).map( opt => opt.value )

	// Clear all values.
	Array.from( element.children ).forEach( opt => opt.remove() )

	// Replace the options with the new ones.
	options.forEach( data => {
		const newOpt = document.createElement( 'option' )
		newOpt.setAttribute( 'value', data.value )

		// Set as selected if the previous value was selected.
		if ( selectedValues.includes( data.value ) ) {
			newOpt.setAttribute( 'selected', '' )
		}

		newOpt.innerHTML = data.label
		element.appendChild( newOpt )
	} )
}
