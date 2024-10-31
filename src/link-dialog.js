export const openLinkDialog = ( inputID, args, callback ) => {

	if ( 'undefined' === typeof args ) {
		args = {};
	}

	// Open the link dialog box.
	// @see http://stackoverflow.com/questions/11812929/use-wordpress-link-insert-dialog-in-metabox
	// wpLink.open( 'dummy-wplink-textarea' );
	wpLink.open( inputID );

	// Set the field values.
	// #link-options are backward compatible with 4.1.x.
	document.querySelector( '#wp-link-url, #link-options #url-field' ).value = args.url || '';
	document.querySelector( '#wp-link-text, #link-options #link-title-field' ).value = args.text || '';
	document.querySelector( '#wp-link-target, #link-options #link-target-checkbox' ).checked = !! args.target;

	// Show / hide the text field if needed.
	if ( 'undefined' === typeof args.hasText ) {
		document.querySelector( '#wp-link-wrap' ).classList.add( 'has-text-field' );
	} else if ( args.hasText ) {
		document.querySelector( '#wp-link-wrap' ).classList.add( 'has-text-field' );
	} else {
		document.querySelector( '#wp-link-wrap' ).classList.remove( 'has-text-field' );
	}

	// Show / hide the new window checkbox.
	if ( 'undefined' === typeof args.hasNewWindow ) {
		document.querySelector( '#wp-link .link-target' ).style.display = '';
	} else {
		document.querySelector( '#wp-link .link-target' ).style.display = args.hasNewWindow ? '' : 'none';
	}

	// Create our handler;
	const clickHandler = () => {

		// #link-options are backward compatible with 4.1.x.
		var url = document.querySelector( '#wp-link-url, #link-options #url-field' ).value,
			text = document.querySelector( '#wp-link-text, #link-options #link-title-field' ).value,
			target = document.querySelector( '#wp-link-target, #link-options #link-target-checkbox' ).checked;

		// Callback.
		callback( url, text, target );

		// Remove the click handler.
		document.querySelector( '#wp-link-submit' ).removeEventListener( 'click', clickHandler );

		// Close the dialog.
		wpLink.close();

	}

	// Set the click handler.
	document.querySelector( '#wp-link-submit' ).addEventListener( 'click', clickHandler );
}
