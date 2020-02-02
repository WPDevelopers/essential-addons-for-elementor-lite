var AdvanceTabHandler = function($scope, $) {
    var $currentTab = $scope.find(".eael-advance-tabs"),
        $currentTabId = "#" + $currentTab.attr("id").toString();

    $($currentTabId + " .eael-tabs-nav ul li").each(function(index) {
        if ($(this).hasClass("active-default")) {
            $($currentTabId + " .eael-tabs-nav > ul li")
                .removeClass("active")
                .addClass("inactive");
            $(this).removeClass("inactive");
        } else {
            if (index == 0) {
                $(this)
                    .removeClass("inactive")
                    .addClass("active");
            }
        }
    });

    $($currentTabId + " .eael-tabs-content div").each(function(index) {
        if ($(this).hasClass("active-default")) {
            $($currentTabId + " .eael-tabs-content > div").removeClass(
                "active"
            );
        } else {
            if (index == 0) {
                $(this)
                    .removeClass("inactive")
                    .addClass("active");
            }
        }
    });

    $($currentTabId + " .eael-tabs-nav ul li").click(function() {
        var currentTabIndex = $(this).index();
        var tabsContainer = $(this).closest(".eael-advance-tabs");

        var tabsNav = $(tabsContainer)
            .children(".eael-tabs-nav")
            .children("ul")
            .children("li");
        var tabsContent = $(tabsContainer)
            .children(".eael-tabs-content")
            .children("div");

        $(this)
            .parent("li")
            .addClass("active");

        $(tabsNav)
            .removeClass("active active-default")
            .addClass("inactive");
        $(this)
            .addClass("active")
            .removeClass("inactive");

        $(tabsContent)
            .removeClass("active")
            .addClass("inactive");
        $(tabsContent)
            .eq(currentTabIndex)
            .addClass("active")
            .removeClass("inactive");

        var $filterGallery = tabsContent.eq(currentTabIndex).find('.eael-filter-gallery-container'),
            $postGridGallery = tabsContent.eq(currentTabIndex).find('.eael-post-grid.eael-post-appender'),
            $twitterfeedGallery = tabsContent.eq(currentTabIndex).find('.eael-twitter-feed-masonry'),
            $instaGallery = tabsContent.eq(currentTabIndex).find('.eael-instafeed');
        var $imgCompContainer = tabsContent.eq(currentTabIndex).find('.eael-img-comp-container');
            

        if($postGridGallery.length) {
            $postGridGallery.isotope();
        }

        if($twitterfeedGallery.length) {
            $twitterfeedGallery.isotope("layout");
        }
        
        if($filterGallery.length) {
            $filterGallery.isotope("layout");
        }
        
        if($instaGallery.length) {
            $instaGallery.isotope("layout");
        }

        if($imgCompContainer.length) {
            $imgCompContainer.isotope("layout");
        }

        $(tabsContent).each(function(index) {
            $(this).removeClass("active-default");
        });
    });
};

jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-adv-tabs.default",
        AdvanceTabHandler
    );
});
