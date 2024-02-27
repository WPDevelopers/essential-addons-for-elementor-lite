ea.hooks.addAction( "init", "ea", () => {
	const interactiveCircle = function ( $scope, $ ) {

		let $circleWrap = $scope.find(".eael-circle-wrapper"),
			$eventType = "mouseenter",
			$animation = $circleWrap.data('animation'),
			$autoplay = $circleWrap.data('autoplay'),
			$autoplayInterval = parseInt($circleWrap.data('autoplay-interval')),
			$autoplayPause = 0,
			$activeItem = $scope.find('.eael-circle-btn.active');

		if ($activeItem.length > 1 ){
			$activeItem.not(':last').removeClass('active');
			$activeItem.siblings('.eael-circle-btn-content').removeClass('active');
		}
	
		if ( $animation !== 'eael-interactive-circle-animation-0' ) {
			let $circleContent = $scope.find(".eael-circle-content"),
				$activeItem = $scope.find('.eael-circle-btn.active');
			$activeItem.siblings('.eael-circle-btn-content').removeClass('active');

			$($circleContent).waypoint(
				function() {
					$circleWrap.addClass($animation);
					setTimeout(function (){
						$activeItem.siblings('.eael-circle-btn-content').addClass('active');
					},1700);
				},
				{
					offset: "80%",
					triggerOnce: true
				}
			);	
		}
		
		if ( $circleWrap.hasClass( 'eael-interactive-circle-event-click' ) ) {
			$eventType = "click";
		}
		
		var $tabLinks    = $circleWrap.find( ".eael-circle-btn" );
		var $tabContents = $circleWrap.find( ".eael-circle-btn-content" );

		//Support for Keyboard accessibility
		$scope.on( 'keyup', '.eael-circle-btn', function ( e ) {
			if ( e.which === 9 || e.which === 32 ) {
				$( this ).trigger( $eventType );
			}
		});
		
		$tabLinks.each( function ( element ) {
			$( this ).on( $eventType, handleEvent( element ) );

		} );
		
		if( $autoplay ){
			setInterval(function () {
				if( $autoplayPause ){
					setTimeout(function(){
						autoplayInteractiveCircle();
					}, 5000);
				} else {
					autoplayInteractiveCircle();
				}
			}, $autoplayInterval);
		}

		function autoplayInteractiveCircle(){
			let activeIndex = 0;
			$tabLinks.each( function ( index ) {
				if( $(this).hasClass('active') ) {
					activeIndex = index + 1;
					activeIndex = activeIndex >= $tabLinks.length ? 0 : activeIndex;
				}
			} );
			setTimeout(function(){
				$( $tabLinks[ activeIndex ] ).trigger( $eventType );
			}, 300);
		}

		function handleEvent( element ) {
			return function (event) {
				var $element   = $( this );
				var $activeTab = $( this ).hasClass( "active" );
				if ( $activeTab == false ) {
					$tabLinks.each( function ( tabLink ) {
						$( this ).removeClass( "active" );
					} );
					
					$element.addClass( "active" );
					
					$tabContents.each( function ( tabContent ) {
						$( this ).removeClass( "active" );
						if ( $( this ).hasClass( $element.attr( "id" ) ) ) {
							$( this ).addClass( "active" );
						}
					} );
				}
				$autoplayPause = event.originalEvent ? 1 : 0;
			};
		}
	};
	
	elementorFrontend.hooks.addAction(
		"frontend/element_ready/eael-interactive-circle.default",
		interactiveCircle
	);
} );
