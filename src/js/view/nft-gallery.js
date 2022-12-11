var NFTGalleryHandler = function ($scope, $) {
    let $eael_nft_gallery = $(".eael-nft-gallery-wrapper", $scope);
    let $posts_per_page = $eael_nft_gallery.data("posts-per-page");
    let $total_posts = $eael_nft_gallery.data("total-posts");
    let $nomore_item_text = $eael_nft_gallery.data("nomore-item-text");
    let $next_page = $eael_nft_gallery.data("next-page");

    $scope.on("click", ".eael-nft-gallery-load-more", function (e) {
        e.preventDefault();
        $('.eael-nft-item.page-' + $next_page, $scope).removeClass('eael-d-none').addClass('eael-d-block');
        $eael_nft_gallery.attr("data-next-page", $next_page + 1);

        if ($('.eael-nft-item.page-' + $next_page, $scope).hasClass('eael-last-nft-gallery-item')) {
            $(".eael-nft-gallery-load-more", $scope).html($nomore_item_text).fadeOut('1500');
        }

        $next_page++;
    });

};

jQuery(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-nft-gallery.default",
        NFTGalleryHandler
    );
});
