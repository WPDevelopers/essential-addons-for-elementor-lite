(function($) {
    "use strict";

    $(document).on('click', '.ea-clear-cache', function (e) {
        e.preventDefault();

        if(typeof localize != 'undefined' && localize) {
            var pageID = $(this).parent().find('.ea-clear-cache-id').data('pageid'),
            text = $(this).find('.ab-item');

            $.ajax({
                url: localize.ajaxurl,
                type: "post",
                data: {
                    action: "clear_cache_files_with_ajax",
                    security: localize.nonce,
                    pageID: pageID,
                    actionType: 'post'
                },
                beforeSend: function() {
                    text.text(
                        'Clearing...'
                    );
                },
                success: function(response) {
                    setTimeout(function() {
                        text.text('Clear Page Cache');
                        window.location.reload();
                    }, 1000);
                },
                error: function() {
                    console.log('Something went wrong!');
                }
            });
        } else {
            console.log('This page has no widget from EA');
        }
    });

    $(document).on('click', '.ea-all-cache-clear', function (e) {
        e.preventDefault();

        if(typeof localize != 'undefined' && localize) {
            var text = $(this).find('.ab-item');
            
            $.ajax({
                url: localize.ajaxurl,
                type: "post",
                data: {
                    action: "clear_cache_files_with_ajax",
                    security: localize.nonce
                },
                beforeSend: function() {
                    text.text(
                        'Clearing...'
                    );
                },
                success: function(response) {
                    setTimeout(function() {
                        text.text('Clear All Cache');
                        window.location.reload();
                    }, 1000);
                },
                error: function() {
                    console.log('Something went wrong!');
                }
            });
        }else {
            console.log('This page has no widget from EA, Clear cache from Dashboard');
        }
    });

})(jQuery);
