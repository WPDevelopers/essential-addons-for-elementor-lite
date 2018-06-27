(function ($) {
	"use strict";

    var FilterGallery = function( $scope, $ ) {
        var filtergallery_elem = $scope.find('.eael-filter-gallery-wrapper').eq(0);

        $(filtergallery_elem).each(function() {
            var gridStyle = $(this).data('grid-style'),
                ref = $(this).find('.item').data('ref'),
                duration = $(this).data('duration'),
                effects = $(this).data('effects'),
                popup = $(this).data('popup'),
                galleryEnabled = $(this).data('gallery-enabled');
            var mixer = mixitup( $(this), {
                controls: {
                    scope: 'local'
                },
                selectors: {
                    target: '[data-ref~="'+ref+'"]'
                },
                animation: {
                    enable: true,
                    duration: ''+duration+'',
                    effects: ''+effects+'',
                    easing: 'cubic-bezier(0.245, 0.045, 0.955, 1)',
                }
            } );

            // Set Background Image
            if( gridStyle == 'eael-hoverer' || gridStyle == 'eael-tiles' ) {
                var postColumn = $(this).find( '.eael-filter-gallery-container .item' );
                postColumn.each( function() {
                    let dataBg = $(this).attr( 'data-item-bg' );
                    $(this).css( 'background-image', 'url( '+ dataBg +' )' );
                } );
            }
            // Magnific Popup
            if( true == popup ) {
                $(this).find('.eael-magnific-link').magnificPopup({
                    type: 'image',
                    gallery:{
                        enabled: galleryEnabled
                    },
                    callbacks: {
                        close: function() {
                            $( '#elementor-lightbox' ).hide();
                        }
                    }
                });
            }
        });
    }

    var AdvanceTabHandler = function ($scope, $) {
        console.log('running');
        var $currentTab = $scope.find('.eael-advance-tabs'),
            $currentTabId = '#' + $currentTab.attr('id').toString();

            $($currentTabId + ' .eael-tabs-nav ul li').each( function(index) {
                if( $(this).hasClass('active-default') ) {
                    $($currentTabId + ' .eael-tabs-nav > ul li').removeClass('active').addClass('inactive');
                    $(this).removeClass('inactive');
                }else {
                    if( index == 0 ) {
                        $(this).removeClass('inactive').addClass('active');
            
                    }
                }
            } );

            $($currentTabId + ' .eael-tabs-content div').each( function(index) {
                if( $(this).hasClass('active-default') ) {
                    $($currentTabId + ' .eael-tabs-content > div').removeClass('active');
                }else {
                    if( index == 0 ) {
                        $(this).removeClass('inactive').addClass('active');
                    }
                }
            } );

            $($currentTabId + ' .eael-tabs-nav ul li').click(function(){
                var currentTabIndex = $(this).index();
                var tabsContainer = $(this).closest('.eael-advance-tabs');
                var tabsNav = $(tabsContainer).children('.eael-tabs-nav').children('ul').children('li');
                var tabsContent = $(tabsContainer).children('.eael-tabs-content').children('div');
            
                $(this).parent('li').addClass('active');
            
                $(tabsNav).removeClass('active active-default').addClass('inactive');
                $(this).addClass('active').removeClass('inactive');
            
                $(tabsContent).removeClass('active').addClass('inactive');
                $(tabsContent).eq(currentTabIndex).addClass('active').removeClass('inactive');
            
                $(tabsContent).each( function(index) {
                    $(this).removeClass('active-default');
            });
        });
    }

    /* ------------------------------ */
    /* Post Timeline
    /* ------------------------------ */
    var postTimelineHandler = function ($scope, $) {
        var $_this = $scope.find('.eael-post-timeline'),
            $currentTimelineId = '#' + $_this.attr('id'),
            $site_url       = $_this.data('url'),
            $total_posts    = $_this.data('total_posts'),
            $timeline_id    = $_this.data('timeline_id'),
            $post_type      = $_this.data('post_type'),
            $posts_per_page     = $_this.data('posts_per_page'),
            $post_order         = $_this.data('post_order'),
            $show_images        = $_this.data('show_images'),
            $show_title         = $_this.data('show_title'),
            $show_excerpt       = $_this.data('show_excerpt'),
            $excerpt_length     = $_this.data('excerpt_length'),
            $btn_text       = $_this.data('btn_text'),
            $categories     = $_this.data('categories');

        var options = {
            siteUrl: $site_url,
            totalPosts: $total_posts,
            loadMoreBtn: $( '#eael-load-more-btn-'+$timeline_id ),
            postContainer: $( '.eael-post-appender-'+$timeline_id ),
            postStyle: 'timeline',
        }
    
        var settings = {
            postType: $post_type,
            perPage: parseInt( $posts_per_page, 10 ),
            postOrder: $post_order,
            showImage: $show_images,
            showTitle: $show_title,
            showExcerpt: $show_excerpt,
            excerptLength: parseInt( $excerpt_length, 10 ),
            btnText: $btn_text,
            categories: $categories
        }
        loadMore( options, settings );
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-filterable-gallery.default', FilterGallery);
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-adv-tabs.default', AdvanceTabHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-post-timeline.default', postTimelineHandler);
    });
}(jQuery));