const SherePhotoViewer = function ($scope, $) {
    console.log('started');
    const sphereData = $scope.find('.eael-sphere-photo-wrapper').data('settings');
    const viewer = new PhotoSphereViewer.Viewer(sphereData);
};

jQuery(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction("frontend/element_ready/eael-sphere-photo-viewer.default", SherePhotoViewer);
});