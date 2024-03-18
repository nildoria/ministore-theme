(function() {
	var morphSearch = document.getElementById( 'morphsearch' ),
		input = morphSearch.querySelector( 'input.morphsearch-input' ),
		ctrlClose = morphSearch.querySelector( 'span.morphsearch-close' ),
		isOpen = isAnimating = false,
		// show/hide search area
		toggleSearch = function(evt) {
			// return if open and the input gets focused
			if( evt.type.toLowerCase() === 'focus' && isOpen ) return false;

			if( isOpen ) {
				classie.remove( morphSearch, 'open' );

				// trick to hide input text once the search overlay closes 
				// todo: hardcoded times, should be done after transition ends
				if( input.value !== '' ) {
					setTimeout(function() {
						classie.add( morphSearch, 'hideInput' );
						setTimeout(function() {
							classie.remove( morphSearch, 'hideInput' );
							input.value = '';
						}, 300 );
					}, 500);
				}
				
				input.blur();
			}
			else {
				classie.add( morphSearch, 'open' );
			}
			isOpen = !isOpen;
		};

	// events
	input.addEventListener( 'focus', toggleSearch );
	ctrlClose.addEventListener( 'click', toggleSearch );
	// esc key closes search overlay
	// keyboard navigation events
	document.addEventListener( 'keydown', function( ev ) {
		var keyCode = ev.keyCode || ev.which;
		if( keyCode === 27 && isOpen ) {
			toggleSearch(ev);
		}
	} );
	
    
    jQuery(document).ready(function() {
        // Initialize the MorphSearch instance here
        
        // Add click event handler for the 1st field
        jQuery('.elem_input_search').click(function() {
            // After a 1-second delay, open the MorphSearch popup
            setTimeout(function() {
                jQuery('.input_search').focus();
            }, 1000); // 1000 milliseconds = 1 second
        });

        // Add focus event handler for the input_search field
        jQuery('.elem_input_search').focus(function() {
            // Remove focus from elem_input_search
            jQuery('.elem_input_search').blur();
            jQuery('#morphsearch').addClass('open');
        });

        // Add focus event handler for the input_search field
        jQuery('.input_search').focus(function() {
            // Remove focus from elem_input_search
            jQuery('.elem_input_search').blur();
        });
    });
	
})();
