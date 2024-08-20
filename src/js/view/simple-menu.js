var SimpleMenu = function ($scope, $) {
    let $hamburger_icon = $('.eael-simple-menu-container', $scope).data('hamburger-icon'),
        $indicator_icon = $('.eael-simple-menu-container', $scope).data('indicator-icon'),
        $dropdown_indicator_icon = $('.eael-simple-menu-container', $scope).data('dropdown-indicator-icon'),
        $horizontal = $('.eael-simple-menu', $scope).hasClass('eael-simple-menu-horizontal'),
        $hamburger_breakpoints = $('.eael-simple-menu-container', $scope).data('hamburger-breakpoints'),
        $hamburger_device = $('.eael-simple-menu-container', $scope).data('hamburger-device');

    if( typeof $hamburger_device === 'undefined' || $hamburger_device === '' || $hamburger_device === null ) {
        $hamburger_device = 'tablet';
    }

    let selectorByType = $horizontal ? '.eael-simple-menu-horizontal' : '.eael-simple-menu-vertical';
    let $hamburger_max_width = getHamburgerMaxWidth($hamburger_breakpoints, $hamburger_device)
    var $fullWidth = $('.eael-simple-menu--stretch'), all_ids = [];

    // add menu active class
    $('.eael-simple-menu li a', $scope).each(function () {
        let $this = $(this),
            hashURL = $this.attr('href'),
            thisURL = hashURL,
            isStartWithHash,
            splitURL = thisURL !== undefined ? thisURL.split('#') : [];

        hashURL = hashURL === undefined ? '' : hashURL;
        isStartWithHash = hashURL.startsWith('#');
        
        if ( hashURL !== '#' && splitURL.length > 1 && localize.page_permalink === splitURL[0] && splitURL[1] ){
            all_ids.push(splitURL[1]);
        }else if( isStartWithHash && splitURL[1] && $(hashURL).length > 0 ){
            all_ids.push(splitURL[1]);
        }
        if ( !isStartWithHash && localize.page_permalink === thisURL ) {
            $this.addClass('eael-item-active');
        }
        if( $this.parent().hasClass('current-menu-item') || $this.parent().hasClass('current-menu-parent') ){
            $this.addClass('eael-item-active');
        }
    });
    
    $(window).on('load resize scroll', function() {
        if ( all_ids.length > 0 ){
            $.each(all_ids,function (index, item){
                if ($('#'+item).isInViewport()) {
                    $('a[href="'+localize.page_permalink+'#'+item+'"]', $scope).addClass('eael-menu-'+item+' eael-item-active');
                    $('a[href="#'+item+'"]', $scope).addClass('eael-menu-'+item+' eael-item-active');
                } else {
                    $('.eael-menu-'+item).removeClass('eael-menu-'+item+' eael-item-active');
                }
            });
        }

    });

    if($horizontal){
        // insert indicator
        $('.eael-simple-menu > li.menu-item-has-children', $scope).each(
            function () {
                $('> a', $(this)).append(
                    `<span> ${$indicator_icon} </span>`
                )
            }
        )

        $('.eael-simple-menu > li ul li.menu-item-has-children', $scope).each(
            function () {
                $('> a', $(this)).append(
                    `<span class="eael-simple-menu-dropdown-indicator"> ${$dropdown_indicator_icon} </span>`
                )
            }
        )
    }

    // insert responsive menu toggle, text
    $(selectorByType, $scope).before('<span class="eael-simple-menu-toggle-text"></span>')
    eael_menu_resize( $hamburger_max_width );
    
    // responsive menu slide
    $('.eael-simple-menu-container', $scope).on(
        'click',
        '.eael-simple-menu-toggle',
        function (e) {
            e.preventDefault();
            const $siblings = $(this).siblings('nav').children( selectorByType );
            $siblings.css('display') == 'none'
                ? $siblings.slideDown(300)
                : $siblings.slideUp(300)
        }
    )

    // clear responsive props
    $(window).on('resize load', function () {
        eael_menu_resize( $hamburger_max_width );
    });
    
    function eael_menu_resize( max_width_value = 0 ) {
        if (window.matchMedia('(max-width: '+ max_width_value +'px)').matches) {
            $('.eael-simple-menu-container', $scope).addClass(
                'eael-simple-menu-hamburger'
            )
            $(selectorByType, $scope).addClass(
                'eael-simple-menu-responsive'
            )
            $('.eael-simple-menu-toggle-text', $scope).text(
                $(
                    '.eael-simple-menu-horizontal .current-menu-item a',
                    $scope
                )
                .eq(0)
                .text()
            )

            // Mobile Dropdown Breakpoints
            $('.eael-simple-menu-container', $scope).closest('.elementor-widget-eael-simple-menu')
                .removeClass('eael-hamburger--not-responsive')
                .addClass('eael-hamburger--responsive');

            if ($('.eael-simple-menu-container', $scope).hasClass('eael-simple-menu--stretch')){
                const css = {}
                if(!$(selectorByType, $scope).parent().hasClass('eael-nav-menu-wrapper')){
                    $(selectorByType, $scope).wrap('<nav class="eael-nav-menu-wrapper"></nav>');
                }
                const $navMenu = $(".eael-simple-menu-container nav",$scope);
                menu_size_reset($navMenu);

                css.width = parseFloat($('.elementor').width()) + 'px';
                css.left = -parseFloat($navMenu.offset().left) + 'px';
                css.position = 'absolute';

                $navMenu.css(css);
            } else {
                const css = {}
                if(!$(selectorByType, $scope).parent().hasClass('eael-nav-menu-wrapper')){
                    $(selectorByType, $scope).wrap('<nav class="eael-nav-menu-wrapper"></nav>');
                }
                const $navMenu = $(".eael-simple-menu-container nav",$scope);
                menu_size_reset($navMenu);

                css.width = '';
                css.left = '';
                css.position = 'inherit';

                $navMenu.css(css);
            }
        } else {
            $('.eael-simple-menu-container', $scope).removeClass(
                'eael-simple-menu-hamburger'
            )
            $(selectorByType, $scope).removeClass(
                'eael-simple-menu-responsive'
            )
            $(
                selectorByType + ', ' + selectorByType + ' ul',
                $scope
            ).css('display', '')
            $(".eael-simple-menu-container nav",$scope).removeAttr( 'style' );

            // Mobile Dropdown Breakpoints
            $('.eael-simple-menu-container', $scope).closest('.elementor-widget-eael-simple-menu')
                .removeClass('eael-hamburger--responsive')
                .addClass('eael-hamburger--not-responsive');

        }
    }

    function menu_size_reset(selector){
        const css = {};
        css.width = '';
        css.left = '';
        css.position = 'inherit';
        selector.css(css);
    }

    function getHamburgerMaxWidth($breakpoints, $device) {
        let $max_width = 0;
        if( $device === 'none' || typeof $device === 'undefined' || $device === '' || $device === null ){
            return $max_width;
        }

        for (let $key in $breakpoints) {
            if ($key == $device) {
                $max_width = $breakpoints[$key];
            }
        }
        // fetch max width value from string like 'Mobile (> 767px)' to 767
        $max_width = $max_width.replace(/[^0-9]/g, '');
        return $max_width;
    }

    $('.eael-simple-menu > li.menu-item-has-children', $scope).each(
        function () {
            $(this).append(
                '<span class="eael-simple-menu-indicator"> ' +
                $indicator_icon +
                '</span>'
            )
        }
    )

    $('.eael-simple-menu > li ul li.menu-item-has-children', $scope).each(
        function (e) {
            $(this).append(
                '<span class="eael-simple-menu-indicator"> ' +
                $dropdown_indicator_icon +
                '</span>'
            )
        }
    )

    // menu indent
    $('.eael-simple-menu-dropdown-align-left .eael-simple-menu-vertical li.menu-item-has-children').each(function () {
        var $padding_left = parseInt($('a', $(this)).css('padding-left'))

        $('ul li a', this).css({
            'padding-left': $padding_left + 20 + 'px',
        })
    })

    $('.eael-simple-menu-dropdown-align-right .eael-simple-menu-vertical li.menu-item-has-children').each(function () {
        var $padding_right = parseInt($('a', $(this)).css('padding-right'))

        $('ul li a', this).css({
            'padding-right': $padding_right + 20 + 'px',
        })
    })

    // main menu toggle
    $('.eael-simple-menu-container', $scope).on(
        'click',
        '.eael-simple-menu-responsive li a:not([href="#"])',
        function (e) {
            $(this).parents(selectorByType).slideUp(300)
        }
    )

    $('.eael-simple-menu', $scope).on('click', 'a[href="#"]', function (e) {
        e.preventDefault();
        $(this).siblings('.eael-simple-menu-indicator').click();
    });


    // menu dropdown toggle
    $('.eael-simple-menu', $scope).on('click', '.eael-simple-menu-indicator', function (e) {
            e.preventDefault();
            $(this).toggleClass('eael-simple-menu-indicator-open')
            $(this).hasClass('eael-simple-menu-indicator-open')
                ? $(this).siblings('ul').slideDown(300)
                : $(this).siblings('ul').slideUp(300)
            $('.eael-simple-menu-indicator-open').not($(this).parents('.menu-item-has-children').children('span')).removeClass('eael-simple-menu-indicator-open').siblings('ul').slideUp(300);
        }
    );

    if ( elementorFrontend.isEditMode() ) {
		elementor.channels.editor.on( 'change', function( view ) {
			let changed = view.elementSettingsModel.changed;
			if ( changed.eael_simple_menu_dropdown ) {
                elementor.saver.update.apply().then(function () {
                    elementor.reloadPreview();
                });
			}
		});
	}
}

jQuery(window).on('elementor/frontend/init', function () {

    if (eael.elementStatusCheck('eaelSimpleMenu')) {
        return false;
    }

    elementorFrontend.hooks.addAction(
        'frontend/element_ready/eael-simple-menu.default',
        SimpleMenu
    )
})