(function($) {
    $(document).ready(function() {
        $('#eael_plugins_versions').on('change', function() {
            var $this  = $(this),
                button = $this.siblings('.eael-version-rollback'),
                url    = button.attr('href'),
                val    = $this.val(),
                url    = url+'&upgrade_version='+val;
                button.attr('href', url);
        });

        $('.eael-version-rollback').on( 'click', function(e) {
            e.preventDefault();
            var $this          = $( this ),
                dialogsManager = new DialogsManager.Instance();
        
            dialogsManager.createWidget( 'confirm', {
                headerMessage: EAELRollBackConfirm.i18n.rollback_to_previous_version,
                message      : EAELRollBackConfirm.i18n.rollback_confirm,
                strings      : {
                    cancel : EAELRollBackConfirm.i18n.cancel,
                    confirm: EAELRollBackConfirm.i18n.yes,
                },
                onConfirm: function() {
                    $this.addClass( 'loading' );
                    location.href = $this.attr( 'href' );
                }
            } ).show();
        } );
    });

})(jQuery);