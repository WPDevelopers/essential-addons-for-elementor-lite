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
			orderBy: settings.orderBy,
			showImage: settings.showImage,
			showTitle: settings.showTitle,
			showExcerpt: settings.showExcerpt,
			showMeta: settings.showMeta,
			metaPosition: settings.metaPosition,
			excerptLength: settings.excerptLength,
			btnText: settings.btnText,
			categories: settings.categories,
			tags: settings.eael_post_tags,
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
					category: settingsValue.categories,
					eael_post_tags: settingsValue.tags ? settingsValue.tags : [],

					eael_post_orderby: settingsValue.orderBy,
					eael_post_order: settingsValue.postOrder,

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
		} );
	}
})(jQuery);