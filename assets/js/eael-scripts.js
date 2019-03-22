(function ($) {
    "use strict";

    var isEditMode = false;

    function mybe_note_undefined($selector, $data_atts) {
		return ($selector.data($data_atts) !== undefined) ? $selector.data($data_atts) : '';
	}

	/*=================================*/
    /* 01. Filterable Gallery
    /*=================================*/
    



    

    

    /* ------------------------------ */
    /* Advance accordion
    /* ------------------------------ */
     // End of advance accordion

    /* ------------------------------ */
    /* Post Timeline
    /* ------------------------------ */
    

    

    /* ------------------------------ */
    /* Data Table
    /* ------------------------------ */
     // end of Data Table

    

    

	

    
    
    

    /*=================================*/
	/* 36. Section Particles
	/*=================================*/
    
    
    $(window).on('elementor/frontend/init', function () {
        if(elementorFrontend.isEditMode()) {
            isEditMode = true;
        }
    });

}(jQuery));