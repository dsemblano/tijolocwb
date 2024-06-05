//FixIn: 8.7.11.10
(function ( $ ){

	$.fn.extend( {
		wpbc_timeselector: function (){
			var times_options = [];
			this.each( function (){
				var el = $( this );
				if ( el.parent().find( '.wpbc_times_selector' ).length ) {
					el.parent().find( '.wpbc_times_selector' ).remove();
				}
				var currentTime = new Date();
				var currentHour = currentTime.getHours();
				var currentMinute = currentTime.getMinutes();
				// var currentDate = new Date();
				// currentDate.setHours(0, 0, 0, 0);
				var today = new Date();
				var dd = String(today.getDate()).padStart(2, '0');
				var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
				var yyyy = today.getFullYear();
				var currentDateAsNumber = Number(yyyy + mm + dd);

				console.log("Current time: " + currentHour + ":" + currentMinute); // Log the current time
				console.log("Current date: " + currentDateAsNumber); // Current date
				el.find( 'option' ).each( function ( ind ){
					var time = jQuery( this ).val();
					var hour = parseInt(time.split(':')[0]);
					var minute = parseInt(time.split(':')[1]);
					var isDisabled = jQuery( this ).is( ':disabled' );
					// The magic
					if(hour < currentHour + 2 || (hour == currentHour + 2 && minute < currentMinute)){
						isDisabled = true;
					}
					console.log("Option time: " + hour + ":" + minute + ", Disabled: " + isDisabled); // Log each option's time and whether it's disabled
					times_options.push( {
						title   : jQuery( this ).text(),
						value   : time,
						disabled: isDisabled,
						selected: jQuery( this ).is( ':selected' )
					});
				});
				var times_options_html = $.fn.wpbc_timeselector.format( times_options );
				el.after( times_options_html );
				el.next('.wpbc_times_selector').find('div').on( "click", function() {
					var selected_value = jQuery( this ).attr( 'data-value' );
					jQuery( this ).parent( '.wpbc_times_selector' ).find( '.wpbc_time_selected' ).removeClass( 'wpbc_time_selected' );
					jQuery( this ).addClass('wpbc_time_selected');
					el.find( 'option' ).prop( 'selected', false );
					el.find( 'option[value="' + selected_value + '"]' ).prop( 'selected', true );
					el.trigger( 'change' );
				});
				el.hide();
				times_options = [];
			});
			return this;
		}
	});
	
	
	


	// Get HTML structure of times selection
	$.fn.wpbc_timeselector.format = function ( el_arr ) {

		var select_div = '';
		var css_class='';

		$.each( el_arr, function (index, el_item){

			if ( !el_item.disabled ){

				if (el_item.selected){
					css_class = 'wpbc_time_selected';
				} else {
					css_class = '';
				}

				select_div += '<div '
									+ ' data-value="' + el_item.value + '" '
									+ ' class="' + css_class + '" '
					         + '>'
									+ el_item.title
							 + '</div>'
			}

		} );

		if ( '' == select_div ){
			select_div = '<span class="wpbc_no_time_pickers">'
							+ 'No available times'
					   + '</span>'
		}
		return '<div class="wpbc_times_selector">' + select_div + '</div>';
	}


})( jQuery );



jQuery(document).ready(function(){

//	 setTimeout( function ( ) {					// Need to  have some delay  for loading of all  times in Garbage

			// Load after page loaded
			jQuery( 'select[name^="rangetime"]' ).wpbc_timeselector();
			jQuery( 'select[name^="starttime"]' ).wpbc_timeselector();
			jQuery( 'select[name^="endtime"]' ).wpbc_timeselector();
			jQuery( 'select[name^="durationtime"]' ).wpbc_timeselector();

			// This hook loading after each day selection																//FixIn: 8.7.11.9
			jQuery( ".booking_form_div" ).on( 'wpbc_hook_timeslots_disabled', function ( event, bk_type, all_dates ){
				jQuery( '#booking_form_div' + bk_type + ' select[name^="rangetime"]' ).wpbc_timeselector();
				jQuery( '#booking_form_div' + bk_type + ' select[name^="starttime"]' ).wpbc_timeselector();
				jQuery( '#booking_form_div' + bk_type + ' select[name^="endtime"]' ).wpbc_timeselector();
				jQuery( '#booking_form_div' + bk_type + ' select[name^="durationtime"]' ).wpbc_timeselector();
			} );

//	}, 1000 );

});