var ProgressBar = function ($scope, $) {
    $('.eael-progressbar', $scope).eaelProgressBar()
};
elementorFrontend.hooks.addAction('frontend/element_ready/eael-progress-bar.default', ProgressBar);