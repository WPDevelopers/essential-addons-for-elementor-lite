const SherePhotoViewer = function ($scope, $) {
    const viewer = new PhotoSphereViewer.Viewer({
        container: document.querySelector('#sphere-photo'),
        panorama: 'https://photo-sphere-viewer-data.netlify.app/assets/sphere.jpg',
    });
};

jQuery(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction("frontend/element_ready/eael-sphere-photo-viewer.default", SherePhotoViewer);
});