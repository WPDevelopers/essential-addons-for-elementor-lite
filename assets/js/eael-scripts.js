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
                $limit         = ($fbCarousel.data("facebook-feed-post-limit") !== undefined) ? $fbCarousel.data("facebook-feed-post-limit") : '',
                $app_id         = ($fbCarousel.data("facebook-feed-app-id") !== undefined) ? $fbCarousel.data("facebook-feed-app-id") : '',
                $app_secret         = ($fbCarousel.data("facebook-feed-app-secret") !== undefined) ? $fbCarousel.data("facebook-feed-app-secret") : '',
                $length         = ($fbCarousel.data("facebook-feed-content-length") !== undefined) ? $fbCarousel.data("facebook-feed-content-length") : 400,
                $media         = ($fbCarousel.data("facebook-feed-media") !== undefined) ? $fbCarousel.data("facebook-feed-media") : false,
                $feed_type     = ($fbCarousel.data("facebook-feed-type") !== undefined) ? $fbCarousel.data("facebook-feed-type") : false,
                $carouselId         = ($fbCarousel.data("facebook-feed-id") !== undefined) ? $fbCarousel.data("facebook-feed-id") : ' ';
        // Facebook Feed Init
        function eael_facebook_feeds() {
            var $access_token = ($app_id+'|'+$app_secret).toString();
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
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-filterable-gallery.default', FilterGallery);
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-facebook-feed.default', FacebookFeedHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-twitter-feed.default', TwitterFeedHandler);
    });
}(jQuery));