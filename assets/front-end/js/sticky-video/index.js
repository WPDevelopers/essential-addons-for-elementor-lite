var eaelsvPosition = '';
var eaelsvWidth = 0;
var eaelsvHeight = 0;
var eaelsvDomHeight = 0;
var videoIsActive = 0;
var eaelMakeItSticky = 0;
var scrollHeight = 0;

jQuery(window).on('elementor/frontend/init', function () {
    
    if (isEditMode) {
        elementor.hooks.addAction('panel/open_editor/widget/eael-sticky-video', function(panel, model, view) {
            var interval;

            model.attributes.settings.on('change:eaelsv_sticky_width', function() {
                clearTimeout(interval);

                interval = setTimeout(function() {
                    var height = Math.ceil(model.getSetting('eaelsv_sticky_width') / 1.78);

                    model.attributes.settings.attributes.eaelsv_sticky_height = height;
                    panel.el.querySelector('[data-setting="eaelsv_sticky_height"]').value = height;
                }, 250);
            });
            
            model.attributes.settings.on('change:eaelsv_sticky_height', function() {
                clearTimeout(interval);

                interval = setTimeout(function() {
                    var width = Math.ceil(model.getSetting('eaelsv_sticky_height') * 1.78);

                    model.attributes.settings.attributes.eaelsv_sticky_width = width;
                    panel.el.querySelector('[data-setting="eaelsv_sticky_width"]').value = width;
                }, 250);
            });
        });
    }

    elementorFrontend.hooks.addAction('frontend/element_ready/eael-sticky-video.default', function ($scope, $) {
        $('.eaelsv-sticky-player-close', $scope).hide();

        var element = $scope.find('.eael-sticky-video-player2');
        var sticky = '';
        var autoplay = '';
        var overlay = '';

        sticky = element.data('sticky');
        autoplay = element.data('autoplay');
        eaelsvPosition = element.data('position');
        eaelsvHeight = element.data('sheight');
        eaelsvWidth = element.data('swidth');
        overlay = element.data('overlay');
        scrollHeight = element.data('scroll_height');

        PositionStickyPlayer(eaelsvPosition, eaelsvHeight, eaelsvWidth);

        var playerAbc = new Plyr('#eaelsv-player-' + $scope.data('id'));

        // If element is Sticky video
        if (sticky === 'yes') {
            // If autoplay is enable
            if ('yes' === autoplay && overlay === 'no') {
                eaelsvDomHeight = GetDomElementHeight(element);
                element.attr('id', 'videobox');

                if (videoIsActive == 0) {
                    videoIsActive = 1;
                }
            }

            // When play event is cliked
            // Do the sticky process
            PlayerPlay(playerAbc, element);
        }

        // Overlay Operation Started
        if (overlay === 'yes') {
            var ovrlyElmnt = element.prev();

            $(ovrlyElmnt).on('click', function () {
                $(this).css('display', 'none');

                if (
                    $(this)
                        .next()
                        .data('autoplay') === 'yes'
                ) {
                    playerAbc.restart();
                    eaelsvDomHeight = GetDomElementHeight(this);
                    $(this)
                        .next()
                        .attr('id', 'videobox');
                    videoIsActive = 1;
                }
            });
        }
        playerAbc.on('pause', function (event) {
            if (videoIsActive == 1) {
                videoIsActive = 0;
            }
        });

        $('.eaelsv-sticky-player-close').on('click', function () {
            element.removeClass('out').addClass('in');
            $('.eael-sticky-video-player2').removeAttr('style');
            videoIsActive = 0;
        });

        element.parent().css('height', element.height() + 'px');
        $(window).resize(function() {
            element.parent().css('height', element.height() + 'px');
        });
    });

});

jQuery(window).scroll(function () {
    var scrollTop = jQuery(window).scrollTop();
    var scrollBottom = jQuery(document).height() - scrollTop;

    if (scrollBottom > jQuery(window).height() + 400) {
        if (scrollTop >= eaelsvDomHeight) {
            if (videoIsActive == 1) {
                jQuery('#videobox')
                    .find('.eaelsv-sticky-player-close')
                    .css('display', 'block');
                jQuery('#videobox')
                    .removeClass('in')
                    .addClass('out');
                PositionStickyPlayer(eaelsvPosition, eaelsvHeight, eaelsvWidth);
            }
        } else {
            jQuery('.eaelsv-sticky-player-close').hide();
            jQuery('#videobox')
                .removeClass('out')
                .addClass('in');
            jQuery('.eael-sticky-video-player2').removeAttr('style');
        }
    }
});

function GetDomElementHeight(elem) {
    var contentHeight = jQuery(elem).parent().height();
    var expHeight = ((scrollHeight * contentHeight) / 100);
    var hght =
        jQuery(elem)
            .parent()
            .offset().top + expHeight;

    return hght;
}

function PositionStickyPlayer(p, h, w) {
    if (p == 'top-left') {
        jQuery('.eael-sticky-video-player2.out').css('top', '40px');
        jQuery('.eael-sticky-video-player2.out').css('left', '40px');
    }
    if (p == 'top-right') {
        jQuery('.eael-sticky-video-player2.out').css('top', '40px');
        jQuery('.eael-sticky-video-player2.out').css('right', '40px');
    }
    if (p == 'bottom-right') {
        jQuery('.eael-sticky-video-player2.out').css('bottom', '40px');
        jQuery('.eael-sticky-video-player2.out').css('right', '40px');
    }
    if (p == 'bottom-left') {
        jQuery('.eael-sticky-video-player2.out').css('bottom', '40px');
        jQuery('.eael-sticky-video-player2.out').css('left', '40px');
    }
    jQuery('.eael-sticky-video-player2.out').css('width', w + 'px');
    jQuery('.eael-sticky-video-player2.out').css('height', h + 'px');
}

function PlayerPlay(a, b) {
    a.on('play', function (event) {
        eaelsvDomHeight = GetDomElementHeight(b);
        jQuery('.eael-sticky-video-player2').removeAttr('id');
        jQuery('.eael-sticky-video-player2').removeClass('out');
        b.attr('id', 'videobox');

        videoIsActive = 1;
        eaelsvPosition = b.data('position');
        eaelsvHeight = b.data('sheight');
        eaelsvWidth = b.data('swidth');
    });
}

function RunStickyPlayer(elem) {
    var ovrplyer = new Plyr('#' + elem);
    ovrplyer.start();
}
