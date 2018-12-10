(function($) {
	// inView
	var inviewObjects = [],
		viewportSize, viewportOffset,
		d = document,
		w = window,
		documentElement = d.documentElement,
		timer

	$.event.special.inview = {
		add: function(data) {
			inviewObjects.push({
				data: data,
				$element: $(this),
				element: this
			})

			if (!timer && inviewObjects.length) {
				timer = setInterval(checkInView, 250)
			}
		},

		remove: function(data) {
			for (var i = 0; i < inviewObjects.length; i++) {
				var inviewObject = inviewObjects[i]
				if (inviewObject.element === this && inviewObject.data.guid === data.guid) {
					inviewObjects.splice(i, 1)
					break
				}
			}

			if (!inviewObjects.length) {
				clearInterval(timer)
				timer = null
			}
		}
	}

	function getViewportSize() {
		var mode, domObject, size = {
			height: w.innerHeight,
			width: w.innerWidth
		}

		if (!size.height) {
			mode = d.compatMode
			if (mode || !$.support.boxModel) { // IE, Gecko
				domObject = mode === 'CSS1Compat' ?
					documentElement : // Standards
					d.body // Quirks
				size = {
					height: domObject.clientHeight,
					width: domObject.clientWidth
				}
			}
		}

		return size
	}

	function getViewportOffset() {
		return {
			top: w.pageYOffset || documentElement.scrollTop || d.body.scrollTop,
			left: w.pageXOffset || documentElement.scrollLeft || d.body.scrollLeft
		}
	}

	function checkInView() {
		if (!inviewObjects.length) {
			return
		}

		var i = 0,
			$elements = $.map(inviewObjects, function(inviewObject) {
				var selector = inviewObject.data.selector,
					$element = inviewObject.$element
				return selector ? $element.find(selector) : $element
			})

		viewportSize = viewportSize || getViewportSize()
		viewportOffset = viewportOffset || getViewportOffset()

		for (; i < inviewObjects.length; i++) {
			if (!$.contains(documentElement, $elements[i][0])) {
				continue
			}

			var $element = $($elements[i]),
				elementSize = {
					height: $element[0].offsetHeight,
					width: $element[0].offsetWidth
				},
				elementOffset = $element.offset(),
				inView = $element.data('inview')

			if (!viewportOffset || !viewportSize) {
				return
			}

			if (elementOffset.top + elementSize.height > viewportOffset.top &&
				elementOffset.top < viewportOffset.top + viewportSize.height &&
				elementOffset.left + elementSize.width > viewportOffset.left &&
				elementOffset.left < viewportOffset.left + viewportSize.width) {
				if (!inView) {
					$element.data('inview', true).trigger('inview', [true])
				}
			} else if (inView) {
				$element.data('inview', false).trigger('inview', [false])
			}
		}
	}

	$(w).on("scroll resize scrollstop", function() {
		viewportSize = viewportOffset = null
	})

	if (!documentElement.addEventListener && documentElement.attachEvent) {
		documentElement.attachEvent("onfocusin", function() {
			viewportOffset = null
		})
	}

	// eaelProgressBar
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
}(jQuery))