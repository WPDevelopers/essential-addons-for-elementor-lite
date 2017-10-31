(function($) {
	'use strict';
	if( typeof settings == 'undefined' ) {
		return;
	}

	console.log( settings.btnId );
	var perPage = parseInt( settings.perPage, 10 );
	var offset = parseInt( settings.perPage, 10 );
	var totalPosts = settings.totalPosts;
	var loadMoreBtn = $( '#eael-load-more-btn' );
	var postBlockContainer = $( '.eael-post-block-grid' );
	var postGridContainer = $( '.eael-post-grid' );
	var postTimelineContainer = $( '.eael-post-timeline-grid' );

	// Elementor Settings
	var showImage 		= settings.showImage;
	var showTitle 		= settings.showTitle;
	var showExcerpt 	= settings.showExcerpt;
	var showMeta 		= settings.showMeta;
	var metaPosition 	= settings.metaPosition;
	var excerptLength 	= parseInt( settings.excerptLength, 10 );
	var btnText			= settings.btnText;

	loadMoreBtn.on( 'click', function( e ) {
		e.preventDefault();
		$(this).addClass( 'button--loading' );
		$(this).find( 'span' ).html('Loading...');
		$.ajax({
			url: settings.siteurl+'wp-json/wp/v2/posts?per_page='+perPage+'&offset='+offset+'&_embed',
			type: 'GET',
			success: function( res ) {
				if( 'post-block' === settings.gridType ) {
					createPostBlockHtml( res );
				}else if( 'post-grid' === settings.gridType ) {
					createPostGridHtml( res );
				}else if( 'post-timeline' === settings.gridType ) {
					createPostTimelineHtml( res );
				}
				$( '#eael-post-load-more' ).removeClass( 'button--loading' );
				$( '#eael-post-load-more' ).find( 'span' ).html( btnText );
				offset = offset + perPage;
				if( offset >= totalPosts ) {
					loadMoreBtn.remove();
				}
			},
			error: function( err ) {
				console.log( 'Something went wrong!' );
			}
		});
	} );

	/**
	 * Create HTML Post Block
	 */
	function createPostBlockHtml( data ) {
		var html = '';
		for( var i = 0; i < data.length; i++ ) {
			// Get Image
			if( data[i]._links['wp:featuredmedia'] ) {
				var feature_image = '<img src="'+data[i]._embedded['wp:featuredmedia'][0].source_url+'" />';
			}else {
				var feature_image = '';
			}
			// Get Date
			var getPostDate = new Date( data[i].date );

			html += '<article class="eael-post-block-item eael-post-block-column">';
			html += '<div class="eael-post-block-item-holder">';
			html += '<div class="eael-post-block-item-holder-inner">';
			if( showImage == 1 ) {
				html += '<div class="eael-entry-media">';
				html += '<div class="eael-entry-overlay">';
				html += '<i class="fa fa-long-arrow-right" aria-hidden="true"></i>';
				html += '<a href="'+data[i].link+'"></a>';
				html += '</div>';
				html += '<div class="eael-entry-thumbnail">'+feature_image;
				html += '</div>';
				html += '</div>';
			}
			html += '<div class="eael-entry-wrapper">';

			html += '<header class="eael-entry-header">';
			if( showTitle == 1 ) {
				html += '<h2 class="eael-entry-title"><a class="eael-grid-post-link" href="'+data[i].link+'" title="'+data[i].title.rendered+'">'+data[i].title.rendered+'</a></h2>';
			}
			if( showMeta == 1 && metaPosition == 'meta-entry-header') {
				html += '<div class="eael-entry-meta">';
				html += '<span class="eael-posted-by"><a href="'+data[i]._embedded.author[0].link+'">'+data[i]._embedded.author[0].name+'</a></span>';
				html += '<span class="eael-posted-on"><time datetime="'+get_post_date( getPostDate )+'">'+get_post_date( getPostDate )+'</time></span>';
				html += '</div>';
			}
			html += '</header>';

			html += '<div class="eael-entry-content">';
			if( showExcerpt == 1 ) {
				html += '<div class="eael-grid-post-excerpt">';
				html += '<p>'+data[i].excerpt.rendered.split( /\s+/ ).slice( 0, excerptLength ).join( " " )+'...</p>';
				html += '</div>';
			}
			html += '</div>';
			html += '</div>';
			if( showMeta == 1 && metaPosition == 'meta-entry-footer' ) {
				html += '<div class="eael-entry-footer">';
				html += '<div class="eael-author-avatar">';
				html += '<a href="'+data[i]._embedded.author[0].link+'">';
				html += '<img src="'+data[i]._embedded.author[0].avatar_urls[96]+'" class="avatar avatar-96 photo" />';
				html += '</a>';
				html += '</div>';
				html += '<div class="eael-entry-meta">';
				html += '<div class="eael-posted-by">';
				html += '<a href="'+data[i]._embedded.author[0].link+'">'+data[i]._embedded.author[0].name+'</a>';
				html += '</div>';
				html += '<div class="eael-posted-on">';
				html += '<time datetime="'+get_post_date( getPostDate )+'">'+get_post_date( getPostDate );
				html += '</time>';
				html += '</div>';
				html += '</div>';
				html += '</div>';
			}
			html += '</div>';
			html += '</article>';
		}
		postBlockContainer.append( html );
	}

	/**
	 * Create Html Post Grid
	 */
	 function createPostGridHtml( data ) {
		var html = '';
		for( var i = 0; i < data.length; i++ ) {
			// Get Image
			if( data[i]._links['wp:featuredmedia'] ) {
				var feature_image = '<img src="'+data[i]._embedded['wp:featuredmedia'][0].source_url+'" />';
			}else {
				var feature_image = '';
			}
			// Get Date
			var getPostDate = new Date( data[i].date );

			html += '<article class="eael-grid-post eael-post-grid-column">';
			html += '<div class="eael-grid-post-holder">';
			html += '<div class="eael-grid-post-holder-inner">';
			if( data[i]._links['wp:featuredmedia'] ) {
				html += '<div class="eael-entry-media">';
				html += '<div class="eael-entry-overlay">';
				html += '<i class="fa fa-long-arrow-right" aria-hidden="true"></i>';
				html += '<a href="'+data[i].link+'"></a>';
				html += '</div>';
				if( showImage == 1 ) {
					html += '<div class="eael-entry-thumbnail">'+feature_image;
					html += '</div>';
				}
				html += '</div>';
			}
			html += '<div class="eael-entry-wrapper">';

			html += '<header class="eael-entry-header">';
			if( showTitle == 1 ) {
				html += '<h2 class="eael-entry-title"><a class="eael-grid-post-link" href="'+data[i].link+'" title="'+data[i].title.rendered+'">'+data[i].title.rendered+'</a></h2>';
			}
			if( showMeta == 1 && metaPosition == 'meta-entry-header') {
				html += '<div class="eael-entry-meta">';
				html += '<span class="eael-posted-by"><a href="'+data[i]._embedded.author[0].link+'">'+data[i]._embedded.author[0].name+'</a></span>';
				html += '<span class="eael-posted-on"><time datetime="'+get_post_date( getPostDate )+'">'+get_post_date( getPostDate )+'</time></span>';
				html += '</div>';
			}
			html += '</header>';

			html += '<div class="eael-entry-content">';
			if( showExcerpt == 1 ) {
				html += '<div class="eael-grid-post-excerpt">';
				html += '<p>'+data[i].excerpt.rendered.split( /\s+/ ).slice( 0, excerptLength ).join( " " )+'...</p>';
				html += '</div>';
			}
			html += '</div>';
			html += '</div>';
			if( showMeta == 1 && metaPosition == 'meta-entry-footer' ) {
				html += '<div class="eael-entry-footer">';
				html += '<div class="eael-author-avatar">';
				html += '<a href="'+data[i]._embedded.author[0].link+'">';
				html += '<img src="'+data[i]._embedded.author[0].avatar_urls[96]+'" class="avatar avatar-96 photo" />';
				html += '</a>';
				html += '</div>';
				html += '<div class="eael-entry-meta">';
				html += '<div class="eael-posted-by">';
				html += '<a href="'+data[i]._embedded.author[0].link+'">'+data[i]._embedded.author[0].name+'</a>';
				html += '</div>';
				html += '<div class="eael-posted-on">';
				html += '<time datetime="'+get_post_date( getPostDate )+'">'+get_post_date( getPostDate );
				html += '</time>';
				html += '</div>';
				html += '</div>';
				html += '</div>';
			}
			html += '</div>';
			html += '</article>';
		}
		postGridContainer.append( html );
	}

	/**
	 * Create Html Post Timeline
	 */
	function createPostTimelineHtml( data ) {
		var html = '';
		for( var i = 0; i < data.length; i++ ) {
			// Get Image
			if( data[i]._links['wp:featuredmedia'] ) {
				var feature_image = 'style="background-image: url('+data[i]._embedded['wp:featuredmedia'][0].source_url+');"';
			}else {
				var feature_image = '';
			}
			// Get Date
			var getPostDate = new Date( data[i].date );

			html += '<article class="eael-timeline-post eael-timeline-column">';
				html += '<div class="eael-timeline-bullet"></div>';
					html += '<div class="eael-timeline-post-inner">';
						html += '<a class="eael-timeline-post-link" href="'+data[i].link+'" title="'+data[i].title.rendered+'">';
							html += '<time datetime="'+get_post_date( getPostDate )+'">'+get_post_date( getPostDate )+'</time>';
							html += '<div class="eael-timeline-post-image" '+feature_image+' ></div>';
							if( showExcerpt == 1 ) {
								html += '<div class="eael-timeline-post-excerpt">';
									html += ''+data[i].excerpt.rendered.split( /\s+/ ).slice( 0, excerptLength ).join( " " )+'...';
								html += '</div>';
							}
							if( showTitle == 1 ) {
								html += '<div class="eael-timeline-post-title">';
									html += '<h2>'+data[i].title.rendered+'</h2>';
								html += '</div>';
							}
						html += '</a>';
					html += '</div>';
				html += '</div>';
			html += '</article>';
		}
		postTimelineContainer.append( html );
	}
	/**
	 * Get Date
	 */
	function get_post_date( date ) {
		var getDate = new Date( date );
		var month = new Array();
		month[0] = "January";
		month[1] = "February";
		month[2] = "March";
		month[3] = "April";
		month[4] = "May";
		month[5] = "June";
		month[6] = "July";
		month[7] = "August";
		month[8] = "September";
		month[9] = "October";
		month[10] = "November";
		month[11] = "December";
		var dayNum = getDate.getDate();
		var monthName = month[ getDate.getMonth() ];
		var getYear = getDate.getFullYear();

		var returnYear = monthName + ' ' + dayNum + ', ' + getYear;
		return returnYear;
	}

})(jQuery);