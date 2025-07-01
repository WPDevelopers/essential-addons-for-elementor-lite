var FlipBox = function ($scope, $) {
    var wrapper = $scope.find('.eael-elements-flip-box-container');

    function setFixedHeight(){
        var frontHeight = wrapper.find('.eael-elements-flip-box-front-container').height();
        var rearHeight = wrapper.find('.eael-elements-flip-box-rear-container').height();
        
        if( wrapper.hasClass('eael-flipbox-max') ){
            var maxHeight = Math.max(frontHeight, rearHeight);
            console.log('height', frontHeight, rearHeight, maxHeight);
            wrapper.find('.eael-elements-flip-box-flip-card').height(maxHeight);
        }
    }

    if (wrapper.hasClass('eael-flipbox-auto-height')) {
        let heightAdjustment = setInterval(setFixedHeight, 200);
        setTimeout(function(){
            clearInterval(heightAdjustment);
        }, 5000);

        // Get transition speed from data attribute
        var transitionSpeed = wrapper.data('height-transition-speed') || '300ms';        
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