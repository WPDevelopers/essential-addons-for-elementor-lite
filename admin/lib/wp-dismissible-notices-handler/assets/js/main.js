jQuery(document).ready(function($) {
    $( '.notice.is-dismissible' ).on('click', '.notice-dismiss', function ( event ) {
        event.preventDefault();
        var $this = $(this);
        if( 'undefined' == $this.parent().attr('id') ){
            return;
        }
        $.post( ajaxurl, {
            action: 'dnh_dismiss_notice',
            url: ajaxurl,
            id: $this.parent().attr('id')
        });

    });
});