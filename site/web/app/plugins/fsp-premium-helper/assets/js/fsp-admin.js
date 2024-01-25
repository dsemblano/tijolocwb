jQuery(document).ready(function($){

	$( '.sap-new-admin-add-button' ).on( 'click', function() {

		setTimeout( fsp_api_field_added_handler, 300);
	});
});

function fsp_api_field_added_handler() {

	var highest = 0;
	jQuery( '.sap-infinite-table input[data-name="id"]' ).each( function() {
		highest = Math.max( highest, this.value );
	});

	jQuery( '.sap-infinite-table  tbody tr:last-of-type input[data-name="api_key"]' ).val( fsp_make_api_key() );
}

function fsp_make_api_key( ) {

	var length 				= 20;
    var result				= [];
    var characters 			= 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength 	= characters.length;

    for ( var i = 0; i < length; i++ ) {

		result.push( characters.charAt( Math.floor( Math.random() * charactersLength ) ) );
  	}

	return result.join('');
}