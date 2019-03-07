(function ($) {
    "use strict";

    var isEditMode = false;

    function mybe_note_undefined($selector, $data_atts) {
		return ($selector.data($data_atts) !== undefined) ? $selector.data($data_atts) : '';
	}

	/*=================================*/
    /* 01. Filterable Gallery
    /*=================================*/
    var filterableGalleryHandler = function($scope, $) {
        if (!isEditMode) {
            var $gallery = $('.eael-filter-gallery-container', $scope),
                $settings = $gallery.data('settings'),
                $gallery_items = $gallery.data('gallery-items'),
                $layout_mode = ($settings.grid_style == 'masonry' ? 'masonry' : 'fitRows'),
                $gallery_enabled = ($settings.gallery_enabled == 'yes' ? true : false);
            
            // init isotope
            var $isotope_gallery = $gallery.isotope({
                itemSelector: '.eael-filterable-gallery-item-wrap',
                layoutMode: $layout_mode,
                percentPosition: true,
                stagger: 30,
                transitionDuration: $settings.duration + 'ms',
                filter: $('.eael-filter-gallery-control .control.active', $scope).data('filter')
            });

            // layout gal - not necessary, just in case
            $isotope_gallery.imagesLoaded().progress(function() {
                $isotope_gallery.isotope('layout');
            });

            // filter
            $scope.on('click', '.control', function() {
                var $this = $(this),
                    $filterValue = $this.data('filter');

                $this.siblings().removeClass('active');
                $this.addClass('active');
                $isotope_gallery.isotope({
                    filter: $filterValue
                });
            });

            // popup
            $('.eael-magnific-link', $scope).magnificPopup({
                type: 'image',
                gallery: {
                    enabled: $gallery_enabled,
                },
                callbacks: {
                    close: function() {
                        $('#elementor-lightbox').hide();
                    }
                }
            });

            $($scope).magnificPopup({
                delegate: '.eael-magnific-video-link',
                type: 'iframe',
                callbacks: {
                    close: function() {
                        $('#elementor-lightbox').hide();
                    }
                }
            });

            // Load more button
            $scope.on('click', '.eael-gallery-load-more', function(e) {
                e.preventDefault();

                var $this = $(this),
                    $init_show = $('.eael-filter-gallery-container', $scope).children('.eael-filterable-gallery-item-wrap').length,
                    $total_items = $gallery.data('total-gallery-items'),
                    $images_per_page = $gallery.data('images-per-page'),
                    $nomore_text = $gallery.data('nomore-item-text'),
                    $items = [];

                if ($init_show == $total_items) {
                    $this.html('<div class="no-more-items-text">' + $nomore_text + '</div>');
                    setTimeout(function() {
                        $this.fadeOut('slow');
                    }, 600);
                }

                // new items html
                for (var i = $init_show; i < ($init_show + $images_per_page); i++) {
                    $items.push($($gallery_items[i])[0]);
                }

                // append items
                $gallery.append($items)
                $isotope_gallery.isotope('appended', $items)
                $isotope_gallery.imagesLoaded().progress(function() {
                    $isotope_gallery.isotope('layout')
                })

                // reinit magnificPopup
                $('.eael-magnific-link', $scope).magnificPopup({
                    type: 'image',
                    gallery: {
                        enabled: $gallery_enabled
                    },
                    callbacks: {
                        close: function() {
                            $('#elementor-lightbox').hide();
                        }
                    }
                })
            });
        }

    }



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
                $('.eael-twitter-feed-layout-container').bind("DOMSubtreeModified", function() {
                    if ($feed_type == 'masonry') {
                        setTimeout(function() {
                            eael_twitter_feed_masonry();
                        }, 150);
                    }
                })
                loadingFeed.removeClass( 'show-loading' );
            },
            error: function() {
                console.log('error loading');
            }
        });
                
     }

    var AdvanceTabHandler = function ($scope, $) {
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
    /* Advance accordion
    /* ------------------------------ */
    var AdvAccordionHandler = function($scope, $) {
    var $advanceAccordion = $scope.find(".eael-adv-accordion"),
      $accordionHeader = $scope.find(".eael-accordion-header"),
      $accordionType = $advanceAccordion.data("accordion-type"),
            $accordionSpeed = $advanceAccordion.data("toogle-speed");
            
            // Open default actived tab
            $accordionHeader.each(function(){
                if($(this).hasClass('active-default')){
                    $(this).addClass('show active');
                    $(this).next().slideDown($accordionSpeed)
                }
            })

        // Remove multiple click event for nested accordion
    $accordionHeader.unbind("click");

    $accordionHeader.click(function(e) {
            e.preventDefault();
            
            var $this = $(this);
            
            if($accordionType === 'accordion') {
                if ($this.hasClass("show")) {
                    $this.removeClass("show active");
                    $this.next().slideUp($accordionSpeed);
                } else {
                    $this.parent().parent().find(".eael-accordion-header").removeClass("show active");
                    $this.parent().parent().find(".eael-accordion-content").slideUp($accordionSpeed);
                    $this.toggleClass("show active");
                    $this.next().slideToggle($accordionSpeed);
                }
            } else {
                // For acccordion type 'toggle'
                if ($this.hasClass("show")) {
                    $this.removeClass("show active");
                    $this.next().slideUp($accordionSpeed);
                } else {
                    $this.addClass("show active");
                    $this.next().slideDown($accordionSpeed);
                }
            }
    });
    }; // End of advance accordion

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

            $tax_query     = $_this.data('tax_query'),
            $post__in     = $_this.data('post__in'),
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
            offset: $post_offset,

            showImage: $show_images,
            imageSize: $image_size,
            showTitle: $show_title,
            showExcerpt: $show_excerpt,
            excerptLength: parseInt( $excerpt_length, 10 ),
            btnText: $btn_text,
            tax_query: $tax_query,
            post__in: $post__in,
            exclude_posts: $exclude_posts,
        }

        eaelLoadMore( options, settings );
    }

    var ContentTicker = function ($scope, $) {
		var $contentTicker  = $scope.find('.eael-content-ticker').eq(0),
		    $items          = ($contentTicker.data("items") !== undefined) ? $contentTicker.data("items") : 1,
		    $items_tablet   = ($contentTicker.data("items-tablet") !== undefined) ? $contentTicker.data("items-tablet") : 1,
		    $items_mobile   = ($contentTicker.data("items-mobile") !== undefined) ? $contentTicker.data("items-mobile") : 1,
		    $margin         = ($contentTicker.data("margin") !== undefined) ? $contentTicker.data("margin") : 10,
		    $margin_tablet  = ($contentTicker.data("margin-tablet") !== undefined) ? $contentTicker.data("margin-tablet") : 10,
		    $margin_mobile  = ($contentTicker.data("margin-mobile") !== undefined) ? $contentTicker.data("margin-mobile") : 10,
		    $effect         = ($contentTicker.data("effect") !== undefined) ? $contentTicker.data("effect") : 'slide',
		    $speed          = ($contentTicker.data("speed") !== undefined) ? $contentTicker.data("speed") : 400,
		    $autoplay       = ($contentTicker.data("autoplay") !== undefined) ? $contentTicker.data("autoplay") : 5000,
		    $loop           = ($contentTicker.data("loop") !== undefined) ? $contentTicker.data("loop") : false,
		    $grab_cursor    = ($contentTicker.data("grab-cursor") !== undefined) ? $contentTicker.data("grab-cursor") : false,
		    $pagination     = ($contentTicker.data("pagination") !== undefined) ? $contentTicker.data("pagination") : '.swiper-pagination',
		    $arrow_next     = ($contentTicker.data("arrow-next") !== undefined) ? $contentTicker.data("arrow-next") : '.swiper-button-next',
		    $arrow_prev     = ($contentTicker.data("arrow-prev") !== undefined) ? $contentTicker.data("arrow-prev") : '.swiper-button-prev',
		    $pause_on_hover = ($contentTicker.data('pause-on-hover') !== undefined ? $contentTicker.data('pause-on-hover') : ''),
			$contentTickerOptions = {
				direction          : 'horizontal',
				loop               : $loop,
				speed              : $speed,
				effect             : $effect,
				slidesPerView      : $items,
				spaceBetween       : $margin,
				grabCursor         : $grab_cursor,
				paginationClickable: true,
				autoHeight         : true,
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
						slidesPerView: $items_mobile,
						spaceBetween : $margin_mobile
					},
					// when window width is <= 640px
					768: {
						slidesPerView: $items_tablet,
						spaceBetween : $margin_tablet
					}
				}
			};

		var $contentTickerSlider = new Swiper($contentTicker, $contentTickerOptions);
		if($autoplay === 0) {
			$contentTickerSlider.autoplay.stop();
		}
		if($pause_on_hover && $autoplay !== 0) {
			$contentTicker.on('mouseenter', function() {
				$contentTickerSlider.autoplay.stop();
			});
			$contentTicker.on('mouseleave', function() {
				$contentTickerSlider.autoplay.start();
			});
		}
	};

    /* ------------------------------ */
    /* Data Table
    /* ------------------------------ */
    var dataTable = function($scope, $) {
        var $_this = $scope.find('.eael-data-table-wrap'),
            $id = $_this.data('table_id');

        var responsive = $_this.data('custom_responsive');
        if( true == responsive ) {
            var $th = $scope.find('.eael-data-table').find('th');
            var $tbody = $scope.find('.eael-data-table').find('tbody');

            $tbody.find('tr').each(function(i, item) {
                $(item).find('td .td-content-wrapper').each(function(index, item){
                $(this)
                    .prepend('<div class="th-mobile-screen">' + $th.eq(index).html() + '</div>');
                });
            });
        }


    } // end of Data Table

    var FancyText = function ($scope, $) { 
        var $fancyText              = $scope.find('.eael-fancy-text-container').eq(0),
            $id                     = ($fancyText.data("fancy-text-id") !== undefined) ? $fancyText.data("fancy-text-id") : '',
            $fancy_text             = ($fancyText.data("fancy-text")  !== undefined) ? $fancyText.data("fancy-text") : '',
            $transition_type        = ($fancyText.data("fancy-text-transition-type")  !== undefined) ? $fancyText.data("fancy-text-transition-type") : '',
            $fancy_text_speed       = ($fancyText.data("fancy-text-speed") !== undefined) ? $fancyText.data("fancy-text-speed") : '',
            $fancy_text_delay       = ($fancyText.data("fancy-text-delay")     !== undefined) ? $fancyText.data("fancy-text-delay") : '',  
            $fancy_text_cursor      = ($fancyText.data("fancy-text-cursor")     !== undefined) ? true : false,    
            $fancy_text_loop   = ($fancyText.data("fancy-text-loop")     !== undefined) ? ($fancyText.data("fancy-text-loop") == 'yes' ? true : false) : false;
            $fancy_text = $fancy_text.split("|");
            
        if ( $transition_type  == 'typing' ) {
            $("#eael-fancy-text-" + $id).typed({
                strings: $fancy_text,
                typeSpeed: $fancy_text_speed,
                backSpeed: 0,
                startDelay: 300,
                backDelay: $fancy_text_delay,
                showCursor: $fancy_text_cursor,
                loop: $fancy_text_loop,
            });
        }

        if ( $transition_type  != 'typing' ) {
            $("#eael-fancy-text-" + $id).Morphext({
             animation: $transition_type,
				separator: ", ",
				speed: $fancy_text_delay,
				complete: function () {
				        // Overrides default empty function
				    }
			});
        }
    }

    var ImageAccordion = function ($scope, $) {
        var $imageAccordion         = $scope.find('.eael-img-accordion').eq(0),
            $id                     = ($imageAccordion.data("img-accordion-id") !== undefined) ? $imageAccordion.data("img-accordion-id") : '',
            $type             = ($imageAccordion.data("img-accordion-type")  !== undefined) ? $imageAccordion.data("img-accordion-type") : '';
           

        if( 'on-click' === $type ) {
            $('#eael-img-accordion-'+ $id +' a').on('click', function(e) {
                e.preventDefault();
                $('#eael-img-accordion-'+ $id +' a').css('flex', '1');
                $(this).find('.overlay').parent('a').addClass('overlay-active');
                $('#eael-img-accordion-'+ $id +' a').find('.overlay-inner').removeClass('overlay-inner-show');
                $(this).find('.overlay-inner').addClass('overlay-inner-show');
                $(this).css('flex', '3');
            });
            $('#eael-img-accordion-'+ $id +' a').on('blur', function(e) {
                $('#eael-img-accordion-'+ $id +' a').css('flex', '1');
                $('#eael-img-accordion-'+ $id +' a').find('.overlay-inner').removeClass('overlay-inner-show');
                $(this).find('.overlay').parent('a').removeClass('overlay-active');
            });
        }
    }

	var CountDown = function ($scope, $) {
		var $coundDown           = $scope.find('.eael-countdown-wrapper').eq(0),
		$countdown_id            = ($coundDown.data("countdown-id") !== undefined) ? $coundDown.data("countdown-id") : '',
		$expire_type             = ($coundDown.data("expire-type")  !== undefined) ? $coundDown.data("expire-type") : '',
		$expiry_text             = ($coundDown.data("expiry-text")  !== undefined) ? $coundDown.data("expiry-text") : '',
		$expiry_title			 = ($coundDown.data("expiry-title") !== undefined) ? $coundDown.data('expiry-title') : '',
		$redirect_url            = ($coundDown.data("redirect-url") !== undefined) ? $coundDown.data("redirect-url") : '',
		$template                = ($coundDown.data("template")     !== undefined) ? $coundDown.data("template") : '';
		
		jQuery(document).ready(function($) {
			'use strict';
			var countDown = $("#eael-countdown-" + $countdown_id);
	
			countDown.countdown({
				end: function() {
					if( $expire_type == 'text'){
						countDown.html( '<div class="eael-countdown-finish-message"><h4 class="expiry-title">' + $expiry_title + '</h4>' + '<div class="eael-countdown-finish-text">' + $expiry_text + '</div></div>');
					}
					else if ( $expire_type === 'url'){
						var editMode = $('body').find('#elementor').length;
						if( editMode > 0 ) {
							countDown.html("Your Page will be redirected to given URL (only on Frontend).");
						} else {
							window.location.href = $redirect_url;
						}	
					}
					else if ( $expire_type === 'template'){
						countDown.html( $template );
					} else {
						//do nothing!
					}
				}
			});
		});
	}

    var PricingTooltip = function($scope, $) {
		if( $.fn.tooltipster ) {
			var $tooltip = $scope.find('.tooltip'), i;

			for( i = 0; i < $tooltip.length; i++) {
				var $currentTooltip = $( '#' + $($tooltip[i]).attr('id') ),
					$tooltipSide	= ( $currentTooltip.data('side') !== undefined ) ? $currentTooltip.data('side') : false,
					$tooltipTrigger	= ( $currentTooltip.data('trigger') !== undefined ) ? $currentTooltip.data('trigger') : 'hover',
					$animation		= ( $currentTooltip.data('animation') !== undefined ) ? $currentTooltip.data('animation') : 'fade',
					$anim_duration	= ( $currentTooltip.data('animation_duration') !== undefined ) ? $currentTooltip.data('animation_duration') : 300,
					$theme 			= ( $currentTooltip.data('theme') !== undefined ) ? $currentTooltip.data('theme') : 'default',
					$arrow			= ( 'yes' == $currentTooltip.data('arrow') ) ? true : false;

				$currentTooltip.tooltipster({
					animation: $animation,
					trigger: $tooltipTrigger,
					side: $tooltipSide,
					delay: $anim_duration,
					arrow: $arrow,
					theme: 'tooltipster-' + $theme
				});

			}
		}
    }
    
    var ProgressBar = function ($scope, $) {
        $('.eael-progressbar', $scope).eaelProgressBar()
    };

    /*=================================*/
	/* 36. Section Particles
	/*=================================*/
    var EaelParticlesHandler = function ($scope, $) {
        var sectionId = $scope.data('id'),
            editMode = elementorFrontend.isEditMode(),
            theme = $scope.data('theme'),
            settings;

        $scope.addClass('eael-particles-section');

        if (editMode) {
            var editorElements = null,
                particleArgs = {},
                settings = {};

            if (!window.elementor.hasOwnProperty('elements')) {
                return false;
            }

            editorElements = window.elementor.elements;

            if (!editorElements.models) {
                return false;
            }

            $.each(editorElements.models, function (i, el) {
                if (sectionId == el.id) {
                    particleArgs = el.attributes.settings.attributes;

                } else if (el.id == $scope.closest('.elementor-top-section').data('id')) {
                    $.each(el.attributes.elements.models, function (i, col) {
                        $.each(col.attributes.elements.models, function (i, subSec) {
                            particleArgs = subSec.attributes.settings.attributes;
                        });
                    });
                }
            });

            settings.switch = particleArgs['eael_particle_switch'];
            settings.themeSource = particleArgs['eael_particle_theme_from'];

            if (settings.themeSource == 'presets') {
                settings.selected_theme = (ParticleThemesData[particleArgs['eael_particle_preset_themes']]);
            }

            if ((settings.themeSource == 'custom') && ('' !== particleArgs['eael_particles_custom_style'])) {
                settings.selected_theme = particleArgs['eael_particles_custom_style'];
            }

            if (0 !== settings.length) {
                settings = settings;
            }
        } else {
            if (typeof theme != 'undefined' && theme !== '')  {
                particlesJS('eael-section-particles-' + sectionId, theme);
            }
        }

        if (!editMode || !settings) {
            return false;
        }

        if (settings.switch == 'yes') {

            if (settings.themeSource === 'presets' || settings.themeSource === 'custom' && '' !== settings.selected_theme) {
                $scope.attr('id', 'eael-section-particles-' + sectionId);
                if (typeof particlesJS !== 'undefined' && $.isFunction(particlesJS)) {
                    particlesJS("eael-section-particles-" + sectionId, JSON.parse(settings.selected_theme));
                    $scope.children('canvas.particles-js-canvas-el').css({
                        position: 'absolute',
                        top: 0
                    });
                }
            }
        } else {
            $scope.removeClass('eael-particles-section');
        }

    };
    
    $(window).on('elementor/frontend/init', function () {
        if(elementorFrontend.isEditMode()) {
            isEditMode = true;
        }
        
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-filterable-gallery.default', filterableGalleryHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-adv-tabs.default', AdvanceTabHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-adv-accordion.default', AdvAccordionHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-pricing-table.default', PricingTooltip);
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-post-timeline.default', postTimelineHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-twitter-feed.default', TwitterFeedHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-content-ticker.default', ContentTicker);
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-data-table.default', dataTable);
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-fancy-text.default', FancyText);
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-image-accordion.default', ImageAccordion);
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-countdown.default', CountDown);
        elementorFrontend.hooks.addAction('frontend/element_ready/eael-progress-bar.default', ProgressBar);
        elementorFrontend.hooks.addAction( 'frontend/element_ready/section', EaelParticlesHandler );
    });

}(jQuery));