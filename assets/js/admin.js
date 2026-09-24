jQuery( function ( $ ) {
	'use strict';

	$( document ).on( 'click', '.pb-delete-link', function ( event ) {
		if ( ! window.confirm( pbAdmin.confirmDelete ) ) {
			event.preventDefault();
		}
	} );
} );
