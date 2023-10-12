const SherePhotoViewer = function ($scope, $) {
    console.log('started');
    let sphereData = $scope.find('.eael-sphere-photo-wrapper').data('settings');
    console.log(sphereData);
    sphereData.plugins = [
        [PhotoSphereViewer.AutorotatePlugin, {
            autostartDelay: 1000,
            autorotatePitch: '5deg',
        }],
    ];
    console.log(sphereData);
    const viewer = new PhotoSphereViewer.Viewer(sphereData);
};

jQuery(window).on("elementor/frontend/init", function () {
    // elementorFrontend.hooks.addAction("frontend/element_ready/eael-sphere-photo-viewer.default", SherePhotoViewer);
});
console.log('360');