ea.hooks.addAction( "init", "ea", () => {
	const interactiveCircle = function ( $scope, $ ) {
		
		var $circleWrap = $scope.find( ".eael-circle-wrapper" );
		var $eventType  = "mouseenter";
		var $animation  = $circleWrap.data('animation');

		if ( $animation != 'eael-interactive-circle-animation-0' ) {
			var $circleContent = $scope.find( ".eael-circle-content" );

			$($circleContent).waypoint(
				function() {
					$circleWrap.addClass($animation);
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
		
		$tabLinks.each( function ( element ) {
			$( this ).on( $eventType, handleEvent( element ) );
		} );
		
		function handleEvent( element ) {
			return function () {
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
			};
		}
	};
	
	elementorFrontend.hooks.addAction(
		"frontend/element_ready/eael-interactive-circle.default",
		interactiveCircle
	);
} );
