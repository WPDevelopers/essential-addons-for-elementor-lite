var ProgressBar = function($scope, $) {
    $(".eael-progressbar", $scope).eaelProgressBar();
};
jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-progress-bar.default",
        ProgressBar
    );
});
