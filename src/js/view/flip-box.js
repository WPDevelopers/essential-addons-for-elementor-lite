var FlipBox = function ($scope, $) {
    var wrapper = $scope.find('.eael-elements-flip-box-container');

    function setFixedHeight(){
        var frontHeight = wrapper.find('.eael-elements-flip-box-front-container').height();
        var rearHeight = wrapper.find('.eael-elements-flip-box-rear-container').height();
        
        var maxHeight = Math.max(frontHeight, rearHeight);
        wrapper.find('.eael-elements-flip-box-flip-card').height(maxHeight);
    
    }

    function setDynamicHeight(){
        var frontHeight = wrapper.find('.eael-elements-flip-box-front-container').height();
        var rearHeight = wrapper.find('.eael-elements-flip-box-rear-container').height();
        
        if( wrapper.hasClass('--active') ){
            wrapper.find('.eael-elements-flip-box-flip-card').height(rearHeight);
        } else {
            wrapper.find('.eael-elements-flip-box-flip-card').height(frontHeight);
        }
    }

    $('.eael-flip-box-click', $scope).off('click').on( 'click', function() {
        $(this).toggleClass( '--active' );
    });
    
    $('.eael-flip-box-hover', $scope).on('mouseenter mouseleave', function(){
        $(this).toggleClass( '--active' );
    })

    if (wrapper.hasClass('eael-flipbox-auto-height')) {
        if( wrapper.hasClass('eael-flipbox-max') ){
            let heightAdjustment = setInterval(setFixedHeight, 200);
            setTimeout(function(){
                clearInterval(heightAdjustment);
            }, 5000);
        }else if( wrapper.hasClass('eael-flipbox-dynamic') ){
            $('.eael-flip-box-click', $scope).on('click', debounce(setDynamicHeight, 100));
            $('.eael-flip-box-hover', $scope).on('mouseenter mouseleave', debounce(setDynamicHeight, 100));
        }
    }

    // Debounce function to limit resize event frequency
    function debounce(func, wait) {
        var timeout;
        return function executedFunction() {
            var later = function() {
                clearTimeout(timeout);
                func();
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
};

jQuery(window).on("elementor/frontend/init", function () {
    if (eael.elementStatusCheck('eaelFlipBox')) {
        return false;
    }
    elementorFrontend.hooks.addAction("frontend/element_ready/eael-flip-box.default", FlipBox);
});