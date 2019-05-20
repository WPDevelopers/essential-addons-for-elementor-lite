(function($) {
	$.fn.eaelProgressBar = function() {
		var $this = $(this)
		var $layout = $this.data('layout')
		var $num = $this.data('count')
		var $duration = $this.data('duration')

		$this.one('inview', function() {
			if ($layout == 'line') {
				$('.eael-progressbar-line-fill', $this).css({
					'width': $num + '%',
				})
			} else if ($layout == 'half_circle') {
				$('.eael-progressbar-circle-half', $this).css({
					'transform': 'rotate(' + ($num * 1.8) + 'deg)',
				})
			}

			$('.eael-progressbar-count', $this).prop({
				'counter': 0
			}).animate({
				counter: $num
			}, {
				duration: $duration,
				easing: 'linear',
				step: function(counter) {
					if ($layout == 'circle') {
						var rotate = (counter * 3.6)
						$('.eael-progressbar-circle-half-left', $this).css({
							'transform': "rotate(" + rotate + "deg)",
						})
						if (rotate > 180) {
							$('.eael-progressbar-circle-pie', $this).css({
								'clip-path': 'inset(0)'
							})
							$('.eael-progressbar-circle-half-right', $this).css({
								'visibility': 'visible'
							})
						}
					}

					$(this).text(Math.ceil(counter))
				}
			})
		})
	}
}(jQuery));