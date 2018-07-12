(function($) {
	'use strict';

	window.loadMore = function( options, settings ) {

		// Default Values for Load More Js
		var optionsValue = {
			totalPosts: options.totalPosts,
			loadMoreBtn: options.loadMoreBtn,
			postContainer: options.postContainer,
			postStyle: options.postStyle, // block, grid, timeline, 
		}
		// Settings Values
		var settingsValue = {
			postType: settings.postType,
			perPage: settings.perPage,
			postOrder: settings.postOrder,
			showImage: settings.showImage,
			showTitle: settings.showTitle,
			showExcerpt: settings.showExcerpt,
			showMeta: settings.showMeta,
			metaPosition: settings.metaPosition,
			excerptLength: settings.excerptLength,
			btnText: settings.btnText,
			categories: settings.categories,
		}

		var offset = settingsValue.perPage;

		optionsValue.loadMoreBtn.on( 'click', function( e ) {
			e.preventDefault();
			$(this).addClass( 'button--loading' );
			$(this).find( 'span' ).html( 'Loading...' );

			$.ajax( {
				url: eaelPostGrid.ajaxurl,
				type: 'post',
				data: {
					action: 'load_more',
					showImage : settingsValue.showImage,
					showExcerpt : settingsValue.showExcerpt,
					showTitle : settingsValue.showTitle,
					showMeta : settingsValue.showMeta,
					metaPosition : settingsValue.metaPosition,
					excerptLength : settingsValue.excerptLength,

					eael_post_type: settingsValue.postType,
					// eael_post_authors: settingsValue.postType,
					eael_posts_count : settingsValue.perPage,
					eael_post_offset : offset,
					category_id_string: settingsValue.categories,
					// category: settingsValue.postType,
					// eael_post_orderby: settingsValue.orderBy,
					eael_post_order: settingsValue.postOrder,
					// eael_post_tags: settingsValue.postTags,
					// eael_post_exclude_posts: settingsValue.exPosts,
				},
				beforeSend: function() {
					// _this.html('<i class="fa fa-spinner fa-spin"></i>&nbsp;Saving Data..');
				},
				success: function( response ) {
					console.log( response );
					if( optionsValue.postStyle === 'grid' ) {
						setTimeout(function() {
							var $content = $( response );
							optionsValue.postContainer.masonry();
							optionsValue.postContainer.append( $content ).masonry( 'appended', $content );
							optionsValue.postContainer.masonry({
						    	itemSelector: '.eael-grid-post',
						    	percentPosition: true,
						    	columnWidth: '.eael-post-grid-column'
						    });
						}, 100);
					}
					optionsValue.loadMoreBtn.removeClass( 'button--loading' );
					optionsValue.loadMoreBtn.find( 'span' ).html( settingsValue.btnText );

					offset = offset + settingsValue.perPage;
					if( offset >= optionsValue.totalPosts ) {
						optionsValue.loadMoreBtn.remove();
					}

				},
				error: function() {
					
				}
			} );

			// $.ajax({
			// 	url: restUrl,
			// 	type: 'GET',
			// 	success: function( res ) {
			// 		var html = createPostHtml( res );
					// if( optionsValue.postStyle === 'grid' ) {
					// 	setTimeout(function() {
					// 		var $content = $( html );
					// 		optionsValue.postContainer.masonry();
					// 		optionsValue.postContainer.append( $content ).masonry( 'appended', $content );
					// 		optionsValue.postContainer.masonry({
					// 	    	itemSelector: '.eael-grid-post',
					// 	    	percentPosition: true,
					// 	    	columnWidth: '.eael-post-grid-column'
					// 	    });
					// 	}, 100);
					// }
					// optionsValue.loadMoreBtn.removeClass( 'button--loading' );
					// optionsValue.loadMoreBtn.find( 'span' ).html( settingsValue.btnText );

					// offset = offset + settingsValue.perPage;
					// if( offset >= optionsValue.totalPosts ) {
					// 	optionsValue.loadMoreBtn.remove();
					// }
			// 	},
			// 	error: function( err ) {
			// 		console.log( 'Something went wrong!' );
			// 	}
			// });
		} );

		/**
		 * Create Html Post Block
		 */
		function createPostHtml( data ) {

			if( optionsValue.postStyle === 'timeline' ) {
				var html = '';
				for (var i = 0; i < data.length; i++) {
				    // Get Image
				    if (data[i]._links['wp:featuredmedia']) {
				        var feature_image = 'style="background-image: url(' + data[i]._embedded['wp:featuredmedia'][0].source_url + ');"';
				    } else {
				        var feature_image = '';
				    }
				    // Get Date
				    var getPostDate = new Date(data[i].date);

				    html += '<article class="eael-timeline-post eael-timeline-column">';
				    html += '<div class="eael-timeline-bullet"></div>';
				    html += '<div class="eael-timeline-post-inner">';
				    html += '<a class="eael-timeline-post-link" href="' + data[i].link + '" title="' + data[i].title.rendered + '">';
				    html += '<time datetime="' + get_post_date(getPostDate) + '">' + get_post_date(getPostDate) + '</time>';
				    html += '<div class="eael-timeline-post-image" ' + feature_image + ' ></div>';
				    if ( settingsValue.showExcerpt == 1 ) {
				        html += '<div class="eael-timeline-post-excerpt">';
				        html += '' + data[i].excerpt.rendered.split(/\s+/).slice(0, settingsValue.excerptLength).join(" ");
				        html += '</div>';
				    }
				    if ( settingsValue.showTitle == 1 ) {
				        html += '<div class="eael-timeline-post-title">';
				        html += '<h2>' + data[i].title.rendered + '</h2>';
				        html += '</div>';
				    }
				    html += '</a>';
				    html += '</div>';
				    html += '</div>';
				    html += '</article>';
				}
				optionsValue.postContainer.append(html);
			}else if( optionsValue.postStyle === 'grid' ) {
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
						if( settingsValue.showImage == 1 ) {
							html += '<div class="eael-entry-thumbnail">'+feature_image;
							html += '</div>';
						}
						html += '</div>';
					}
					html += '<div class="eael-entry-wrapper">';

					html += '<header class="eael-entry-header">';
					if( settingsValue.showTitle == 1 ) {
						html += '<h2 class="eael-entry-title"><a class="eael-grid-post-link" href="'+data[i].link+'" title="'+data[i].title.rendered+'">'+data[i].title.rendered+'</a></h2>';
					}
					if( settingsValue.showMeta == 1 && settingsValue.metaPosition == 'meta-entry-header') {
						html += '<div class="eael-entry-meta">';
						html += '<span class="eael-posted-by"><a href="'+data[i]._embedded.author[0].link+'">'+data[i]._embedded.author[0].name+'</a></span>';
						html += '<span class="eael-posted-on"><time datetime="'+get_post_date( getPostDate )+'">'+get_post_date( getPostDate )+'</time></span>';
						html += '</div>';
					}
					html += '</header>';

					html += '<div class="eael-entry-content">';
					if( settingsValue.showExcerpt == 1 ) {
						html += '<div class="eael-grid-post-excerpt">';
						html += '<p>'+data[i].excerpt.rendered.split( /\s+/ ).slice( 0, settingsValue.excerptLength ).join( " " )+'...</p>';
						html += '</div>';
					}
					html += '</div>';
					html += '</div>';
					if( settingsValue.showMeta == 1 && settingsValue.metaPosition == 'meta-entry-footer' ) {
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
				
				// optionsValue.postContainer.append( html )
				return html;
			}

		}

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