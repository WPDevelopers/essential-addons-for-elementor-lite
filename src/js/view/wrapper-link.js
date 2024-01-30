let EaelWrapperLink = function ($scope, $) {
    function sanitizeURL(url) {
        try {
            const urlObject = new URL(url);

            // Check if the protocol is valid (allowing only 'http' and 'https')
            if (!['http:', 'https:', 'ftp:', 'ftps:', 'mailto:', 'news:', 'irc:', 'irc6:', 'ircs:', 'gopher:', 'nntp:', 'feed:', 'telnet:', 'mms:', 'rtsp:', 'sms:', 'svn:', 'tel:', 'fax:', 'xmpp:', 'webcal:', 'urn:'].includes(urlObject.protocol)) {
                throw new Error('Invalid protocol');
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