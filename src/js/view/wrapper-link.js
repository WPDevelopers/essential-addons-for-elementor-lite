let EaelWrapperLink = function ($scope, $) {
    function sanitizeURL(url) {
        try {
            const urlObject = new URL(url);

            // Check if the protocol is valid (allowing only 'http' and 'https')
            if (urlObject.protocol !== 'http:' && urlObject.protocol !== 'https:') {
                throw new Error('Invalid protocol');
            }

            // Ensure that the host is not empty
            if (!urlObject.host) {
                throw new Error('Invalid host');
            }

            // If all checks pass, return the sanitized URL
            return urlObject.toString();
        } catch (error) {
            console.error('Error sanitizing URL:', error.message);
            return '#';
        }
    }

    if ($scope.data("eael-wrapper-link") !== undefined) {
        let wrapperData = $scope.data("eael-wrapper-link"),
            target = wrapperData.is_external === 'on' ? '_blank' : '_self';

        $scope.on('click', function () {
            let anchor = document.createElement('a');

            anchor.href = sanitizeURL(wrapperData.url);
            anchor.target = target;

            if (wrapperData.nofollow === 'on') {
                anchor.rel = 'nofollow';
            }

            anchor.click();
        });
    }
};

jQuery(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/global', EaelWrapperLink);
});