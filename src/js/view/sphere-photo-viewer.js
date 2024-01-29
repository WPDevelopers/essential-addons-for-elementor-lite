const SherePhotoViewer = function ($scope, $) {
    let sphereData = $scope.find('.eael-sphere-photo-wrapper').data('settings');

    if (sphereData?.plugins?.[0]?.[0]?.autorotatePitch !== undefined) {
        sphereData.plugins[0].unshift(PhotoSphereViewer.AutorotatePlugin);
    } else if (sphereData?.plugins?.[1]?.[0]?.autorotatePitch !== undefined) {
        sphereData.plugins[1].unshift(PhotoSphereViewer.AutorotatePlugin);
    }

    if (sphereData?.plugins?.[0]?.[0]?.markers !== undefined) {
        sphereData.plugins[0].unshift(PhotoSphereViewer.MarkersPlugin);
    } else if (sphereData?.plugins?.[1]?.[0]?.markers !== undefined) {
        sphereData.plugins[1].unshift(PhotoSphereViewer.MarkersPlugin);
    }

    const viewer = new PhotoSphereViewer.Viewer(sphereData);
};

jQuery(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction("frontend/element_ready/eael-sphere-photo-viewer.default", SherePhotoViewer);
});