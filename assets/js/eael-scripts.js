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

    var FacebookFeedHandler = function ($scope, $) {

        var loadingFeed = $scope.find( '.eael-loading-feed' );
        var $fbCarousel            = $scope.find('.eael-facebook-feed-layout-wrapper').eq(0),
            $name         = ($fbCarousel.data("facebook-feed-ac-name") !== undefined) ? $fbCarousel.data("facebook-feed-ac-name") : '',
            $token         = ($fbCarousel.data("facebook-feed-ac-token") !== undefined) ? $fbCarousel.data("facebook-feed-ac-token") : '',
            $limit         = ($fbCarousel.data("facebook-feed-post-limit") !== undefined) ? $fbCarousel.data("facebook-feed-post-limit") : '',
            $length         = ($fbCarousel.data("facebook-feed-content-length") !== undefined) ? $fbCarousel.data("facebook-feed-content-length") : 400,
            $media         = ($fbCarousel.data("facebook-feed-media") !== undefined) ? $fbCarousel.data("facebook-feed-media") : false,
            $feed_type     = ($fbCarousel.data("facebook-feed-type") !== undefined) ? $fbCarousel.data("facebook-feed-type") : false,
            $carouselId         = ($fbCarousel.data("facebook-feed-id") !== undefined) ? $fbCarousel.data("facebook-feed-id") : ' ';
        // Facebook Feed Init
        function eael_facebook_feeds() {
            var $access_token = $token;
            var $id_name = $name.toString();
            $( '#eael-facebook-feed-'+ $carouselId +'.eael-facebook-feed-layout-container' ).socialfeed({

                facebook:{
                   accounts:[$id_name],
                   limit: $limit,
                   access_token: $access_token
                },

                // GENERAL SETTINGS
                length: $length,
                show_media: $media,
                template_html: '<div class="eael-social-feed-element {{? !it.moderation_passed}}hidden{{?}}" dt-create="{{=it.dt_create}}\" social-feed-id = "{{=it.id}}">\
                {{=it.attachment}}\
                <div class="eael-content">\
                    <a class="pull-left auth-img" href="{{=it.author_link}}" target="_blank">\
                        <img class="media-object" src="{{=it.author_picture}}">\
                    </a>\
                    <div class="media-body">\
                        <p>\
                            <i class="fa fa-{{=it.social_network}} social-feed-icon"></i>\
                            <span class="author-title">{{=it.author_name}}</span>\
                            <span class="muted pull-right social-feed-date"> {{=it.time_ago}}</span>\
                        </p>\
                        <div class="text-wrapper">\
                            <p class="social-feed-text">{{=it.text}} </p>\
                            <p><a href="{{=it.link}}" target="_blank" class="read-more-link">Read More</a></p>\
                        </div>\
                    </div>\
                </div>\
            </div>',
            });
        }
        
        // Facebook Feed Masonry View
        function eael_facebook_feed_masonry() {
            $('.eael-facebook-feed-layout-container.masonry-view').masonry({
                itemSelector: '.eael-social-feed-element',
                percentPosition: true,
                columnWidth: '.eael-social-feed-element'
            });
        }

        $.ajax({
            url: eael_facebook_feeds(),
            beforeSend: function() {
                loadingFeed.addClass( 'show-loading' );
            },
            success: function() {
                if($feed_type == 'masonry') {
                    setTimeout(function() {
                        eael_facebook_feed_masonry();
                    }, 2000);
                     
                }                
             loadingFeed.removeClass( 'show-loading' );
         },
         error: function() {
             console.log('error loading');
         }
     });
        

    };

    var TwitterFeedHandler = function ($scope, $) {
        var loadingFeed = $scope.find( '.eael-loading-feed' );
        var $twitterFeed            = $scope.find('.eael-twitter-feed-layout-wrapper').eq(0),
            $name               = ($twitterFeed.data("twitter-feed-ac-name") !== undefined) ? $twitterFeed.data("twitter-feed-ac-name") : '',
            $limit              = ($twitterFeed.data("twitter-feed-post-limit") !== undefined) ? $twitterFeed.data("twitter-feed-post-limit") : '',
            $hash_tag           = ($twitterFeed.data("twitter-feed-hashtag-name") !== undefined) ? $twitterFeed.data("twitter-feed-hashtag-name") : '',
            $key                = ($twitterFeed.data("twitter-feed-consumer-key") !== undefined) ? $twitterFeed.data("twitter-feed-consumer-key") : '',
            $app_secret         = ($twitterFeed.data("twitter-feed-consumer-secret") !== undefined) ? $twitterFeed.data("twitter-feed-consumer-secret") : '',
            $length             = ($twitterFeed.data("twitter-feed-content-length") !== undefined) ? $twitterFeed.data("twitter-feed-content-length") : 400,
            $media              = ($twitterFeed.data("twitter-feed-media") !== undefined) ? $twitterFeed.data("twitter-feed-media") : false,
            $feed_type          = ($twitterFeed.data("twitter-feed-type") !== undefined) ? $twitterFeed.data("twitter-feed-type") : false,  
            $carouselId         = ($twitterFeed.data("twitter-feed-id") !== undefined) ? $twitterFeed.data("twitter-feed-id") : ' '; 

        var $id_name = $name.toString(); 
        var $hash_tag_name = $hash_tag.toString();    
        var $key_name = $key.toString();
        var $app_secret = $app_secret.toString();
        
        function eael_twitter_feeds() {
            $( '#eael-twitter-feed-'+ $carouselId +'.eael-twitter-feed-layout-container' ).socialfeed({
                // TWITTER
                twitter:{
                   accounts: [ $id_name , $hash_tag_name ],
                   limit: $limit,
                   consumer_key: $key_name,
                   consumer_secret: $app_secret,
                },

                // GENERAL SETTINGS
                length: $length,
                show_media: $media,
                template_html: '<div class="eael-social-feed-element {{? !it.moderation_passed}}hidden{{?}}" dt-create="{{=it.dt_create}}" social-feed-id = "{{=it.id}}">\
                <div class="eael-content">\
                    <a class="pull-left auth-img" href="{{=it.author_link}}" target="_blank">\
                        <img class="media-object" src="{{=it.author_picture}}">\
                    </a>\
                    <div class="media-body">\
                        <p>\
                            <i class="fa fa-{{=it.social_network}} social-feed-icon"></i>\
                            <span class="author-title">{{=it.author_name}}</span>\
                            <span class="muted pull-right social-feed-date"> {{=it.time_ago}}</span>\
                        </p>\
                        <div class="text-wrapper">\
                            <p class="social-feed-text">{{=it.text}} </p>\
                            <p><a href="{{=it.link}}" target="_blank" class="read-more-link">Read More <i class="fa fa-angle-double-right"></i></a></p>\
                        </div>\
                    </div>\
                </div>\
                {{=it.attachment}}\
            </div>',
            });
        }

        
        //Twitter Feed masonry View
        
        function eael_twitter_feed_masonry() {
            $('.eael-twitter-feed-layout-container.masonry-view').masonry({
                itemSelector: '.eael-social-feed-element',
                percentPosition: true,
                columnWidth: '.eael-social-feed-element'
            });
        }

        $.ajax({
            url: eael_twitter_feeds(),
            beforeSend: function() {
                loadingFeed.addClass( 'show-loading' );
            },
            success: function() {
                if($feed_type == 'masonry') {
                    setTimeout(function() {
                        eael_twitter_feed_masonry();
                    }, 2000);
                     
                }
                loadingFeed.removeClass( 'show-loading' );
            },
            error: function() {
                console.log('error loading');
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
            $total_posts    = parseInt( $_this.data('total_posts'), 10 ),
            $timeline_id    = $_this.data('timeline_id'),

            $post_type          = $_this.data('post_type'),
            $posts_per_page     = parseInt( $_this.data('posts_per_page'), 10 ),
            $post_order         = $_this.data('post_order'),
            $post_orderby       = $_this.data('post_orderby'),
            $post_offset        = parseInt( $_this.data('post_offset'), 10 ),

            $show_images        = $_this.data('show_images'),
            $image_size         = $_this.data('image_size'),
            $show_title         = $_this.data('show_title'),
            
            $show_excerpt       = $_this.data('show_excerpt'),
            $excerpt_length     = parseInt( $_this.data('excerpt_length'), 10 ),

            $btn_text       = $_this.data('btn_text'),

            $categories     = $_this.data('categories'),
            $tags           = $_this.data('tags'),
            $exclude_posts  = $_this.data('exclude_posts');

        var options = {
            totalPosts: $total_posts,
            loadMoreBtn: $( '#eael-load-more-btn-' + $timeline_id ),
            postContainer: $( '.eael-post-appender-' + $timeline_id ),
            postStyle: 'timeline',
        }
    
        var settings = {
            postType: $post_type,
            perPage: $posts_per_page,
            postOrder: $post_order,
            orderBy: $post_orderby,
            offset: $post_offset > 0 ? $post_offset + $posts_per_page : 0,

            showImage: $show_images,
            imageSize: $image_size,
            showTitle: $show_title,
            showExcerpt: $show_excerpt,
            excerptLength: parseInt( $excerpt_length, 10 ),
            btnText: $btn_text,
            categories: $categories,
            eael_post_tags: $tags,
            exclude_posts: $exclude_posts,
        }

        loadMore( options, settings );
    }

    var ContentTicker = function ($scope, $) {
        var $contentTicker              = $scope.find('.eael-content-ticker').eq(0),
            $items                      = ($contentTicker.data("items") !== undefined) ? $contentTicker.data("items") : 3,
            $items_tablet               = ($contentTicker.data("items-tablet") !== undefined) ? $contentTicker.data("items-tablet") : 3,
            $items_mobile               = ($contentTicker.data("items-mobile") !== undefined) ? $contentTicker.data("items-mobile") : 3,
            $margin                     = ($contentTicker.data("margin") !== undefined) ? $contentTicker.data("margin") : 10,
            $margin_tablet              = ($contentTicker.data("margin-tablet") !== undefined) ? $contentTicker.data("margin-tablet") : 10,
            $margin_mobile              = ($contentTicker.data("margin-mobile") !== undefined) ? $contentTicker.data("margin-mobile") : 10,
            $effect                     = ($contentTicker.data("effect") !== undefined) ? $contentTicker.data("effect") : 'slide',
            $speed                      = ($contentTicker.data("speed") !== undefined) ? $contentTicker.data("speed") : 400,
            $autoplay                   = ($contentTicker.data("autoplay") !== undefined) ? $contentTicker.data("autoplay") : 999999,
            $loop                       = ($contentTicker.data("loop") !== undefined) ? $contentTicker.data("loop") : 0,
            $grab_cursor                = ($contentTicker.data("grab-cursor") !== undefined) ? $contentTicker.data("grab-cursor") : 0,
            $pagination                 = ($contentTicker.data("pagination") !== undefined) ? $contentTicker.data("pagination") : '.swiper-pagination',
            $arrow_next                 = ($contentTicker.data("arrow-next") !== undefined) ? $contentTicker.data("arrow-next") : '.swiper-button-next',
            $arrow_prev                 = ($contentTicker.data("arrow-prev") !== undefined) ? $contentTicker.data("arrow-prev") : '.swiper-button-prev',
            
            mySwiper = new Swiper($contentTicker, {
                direction:              'horizontal',
                speed:                  $speed,
                effect:                 $effect,
                slidesPerView:          $items,
                spaceBetween:           $margin,
                grabCursor:             $grab_cursor,
                paginationClickable:    true,
                autoHeight:             true,
                loop:                   $loop,
                autoplay: {
                    delay: $autoplay,
                },
                pagination: {
                    el: $pagination,
                    clickable: true,
                },
                navigation: {
                    nextEl: $arrow_next,
                    prevEl: $arrow_prev,
                },
                breakpoints: {
                    // when window width is <= 480px
                    480: {
                        slidesPerView:  $items_mobile,
                        spaceBetween:   $margin_mobile
                    },
                    // when window width is <= 640px
                    768: {
                        slidesPerView:  $items_tablet,
                        spaceBetween:   $margin_tablet
                    }
                }
            });
    };

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-filterable-gallery.default', FilterGallery);
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-adv-tabs.default', AdvanceTabHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-post-timeline.default', postTimelineHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-filterable-gallery.default', FilterGallery);
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-facebook-feed.default', FacebookFeedHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-twitter-feed.default', TwitterFeedHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-content-ticker.default', ContentTicker);
    });

}(jQuery));