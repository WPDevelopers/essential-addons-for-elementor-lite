(function ($) {
  /**
   * ---------------------------------------------------------------
   * Image Zoom Lens Effect
   * Inspired by the work of HckKiu on CodePen:
   * https://codepen.io/hckkiu/pen/poyoRKQ
   *
   * Original concept by HckKiu — enhanced and adapted to support:
   * - On-demand DOM injection for lens and result containers
   * - Scroll-based lens resizing with cursor-centered zoom
   * - Multi-instance support with cleanup on mouse leave
   *
   * Respectfully credited and extended for broader use cases.
   * ---------------------------------------------------------------
   */
  $.fn.eaelZoomLense = function (userOptions) {
    const settings = $.extend({
      lensWidth: 100,
      lensHeight: 100,
      borderRadius: '50%',
      lensBorder: '1px solid #aaa',
      resultBorder: '1px solid #aaa',
      gap: 10,
      autoResize: false,
      minLensSize: 40,
      maxLensSize: 300,
      step: 10,
      zoomId: userOptions.zoomId ? 'zoom_' + userOptions.zoomId : 'zoom'
    }, userOptions);

    $(this).on('mouseenter touchstart mousemove touchmove', function (e) {
      const $img = $(this);
      const zoomId = settings.zoomId;

        if ($('.eael-lens-' + zoomId).length || $('.eael-result-' + zoomId).length) {
            return;
        }
      let lensWidth = settings.lensWidth;
      let lensHeight = settings.lensHeight;

      const $lens = $('<div>', {
        class: 'eael-lens-' + zoomId,
        css: {
          position: 'absolute',
          pointerEvents: 'none',
          borderRadius: settings.borderRadius,
          border: settings.lensBorder,
          backgroundColor: 'rgba(255,255,255,0.3)',
          zIndex: 999
        }
      }).appendTo('body');

      const $result = $('<div>', {
        class: 'eael-result-' + zoomId,
        css: {
          position: 'absolute',
          border: settings.resultBorder,
          backgroundRepeat: 'no-repeat',
          zIndex: 998
        }
      }).appendTo('body');

      const imgOffset = $img.offset();

      // Calculate optimal position and adaptive size for zoom result window
      function calculateOptimalPositionAndSize() {
        const imgWidth = $img.outerWidth();
        const imgHeight = $img.outerHeight();
        const originalResultWidth = imgWidth;
        const originalResultHeight = imgHeight;
        const gap = settings.gap;
        const minSize = 150; // Minimum size constraint for usability

        // Get viewport dimensions
        const viewportWidth = $(window).width();
        const viewportHeight = $(window).height();
        const scrollLeft = $(window).scrollLeft();
        const scrollTop = $(window).scrollTop();

        // Calculate available space in each direction
        const spaceRight = viewportWidth - (imgOffset.left - scrollLeft + imgWidth);
        const spaceLeft = imgOffset.left - scrollLeft;
        const spaceBottom = viewportHeight - (imgOffset.top - scrollTop + imgHeight);
        const spaceTop = imgOffset.top - scrollTop;

        let position = { top: imgOffset.top, left: imgOffset.left };
        let resultWidth = originalResultWidth;
        let resultHeight = originalResultHeight;

        // Helper function to calculate adaptive size while maintaining aspect ratio
        function calculateAdaptiveSize(availableWidth, availableHeight) {
          const aspectRatio = originalResultWidth / originalResultHeight;
          let adaptedWidth = Math.min(originalResultWidth, availableWidth - gap - 20); // 20px margin
          let adaptedHeight = Math.min(originalResultHeight, availableHeight - gap - 20);

          // Maintain aspect ratio
          if (adaptedWidth / aspectRatio > adaptedHeight) {
            adaptedWidth = adaptedHeight * aspectRatio;
          } else {
            adaptedHeight = adaptedWidth / aspectRatio;
          }

          // Apply minimum size constraints
          if (adaptedWidth < minSize || adaptedHeight < minSize) {
            if (aspectRatio >= 1) {
              adaptedWidth = minSize;
              adaptedHeight = minSize / aspectRatio;
            } else {
              adaptedHeight = minSize;
              adaptedWidth = minSize * aspectRatio;
            }
          }

          return { width: adaptedWidth, height: adaptedHeight };
        }

        // Priority sequence: right → left → bottom → top with adaptive sizing
        if (spaceRight >= originalResultWidth + gap) {
          // Position to the right (default behavior) - full size
          position.left = imgOffset.left + imgWidth + gap;
          position.top = imgOffset.top;
        } else if (spaceLeft >= originalResultWidth + gap) {
          // Position to the left - full size
          position.left = imgOffset.left - originalResultWidth - gap;
          position.top = imgOffset.top;
        } else if (spaceBottom >= originalResultHeight + gap) {
          // Position below - full size
          position.left = imgOffset.left;
          position.top = imgOffset.top + imgHeight + gap;
        } else if (spaceTop >= originalResultHeight + gap) {
          // Position above - full size
          position.left = imgOffset.left;
          position.top = imgOffset.top - originalResultHeight - gap;
        } else {
          // Adaptive sizing needed - find best direction and resize accordingly
          const directions = [
            { space: spaceRight, direction: 'right', availableWidth: spaceRight, availableHeight: viewportHeight },
            { space: spaceLeft, direction: 'left', availableWidth: spaceLeft, availableHeight: viewportHeight },
            { space: spaceBottom, direction: 'bottom', availableWidth: viewportWidth, availableHeight: spaceBottom },
            { space: spaceTop, direction: 'top', availableWidth: viewportWidth, availableHeight: spaceTop }
          ];

          // Sort by available space (largest first)
          directions.sort((a, b) => b.space - a.space);
          const bestDirection = directions[0];

          // Calculate adaptive size for the best direction
          const adaptiveSize = calculateAdaptiveSize(bestDirection.availableWidth, bestDirection.availableHeight);
          resultWidth = adaptiveSize.width;
          resultHeight = adaptiveSize.height;

          // Position based on best direction
          switch (bestDirection.direction) {
            case 'right':
              position.left = imgOffset.left + imgWidth + gap;
              position.top = imgOffset.top;
              break;
            case 'left':
              position.left = imgOffset.left - resultWidth - gap;
              position.top = imgOffset.top;
              break;
            case 'bottom':
              position.left = imgOffset.left;
              position.top = imgOffset.top + imgHeight + gap;
              break;
            case 'top':
              position.left = imgOffset.left;
              position.top = imgOffset.top - resultHeight - gap;
              break;
          }

          // Ensure result window stays within viewport boundaries
          position.left = Math.max(scrollLeft + 10, Math.min(position.left, scrollLeft + viewportWidth - resultWidth - 10));
          position.top = Math.max(scrollTop + 10, Math.min(position.top, scrollTop + viewportHeight - resultHeight - 10));
        }

        return {
          position: position,
          width: resultWidth,
          height: resultHeight,
          scaleX: resultWidth / originalResultWidth,
          scaleY: resultHeight / originalResultHeight
        };
      }

      const optimalConfig = calculateOptimalPositionAndSize();

      $result.css({
        width: optimalConfig.width,
        height: optimalConfig.height,
        top: optimalConfig.position.top,
        left: optimalConfig.position.left,
        backgroundImage: `url(${$img.attr('src')})`,
        backgroundSize: `${$img.outerWidth() * (optimalConfig.width / lensWidth)}px ${$img.outerHeight() * (optimalConfig.height / lensHeight)}px`
      });

      // Store initial configuration for adaptive sizing
      $result.data('currentConfig', optimalConfig);

      // Function to update result window position and size dynamically
      function updateResultPosition() {
        const optimalConfig = calculateOptimalPositionAndSize();
        $result.css({
          width: optimalConfig.width,
          height: optimalConfig.height,
          top: optimalConfig.position.top,
          left: optimalConfig.position.left,
          backgroundSize: `${$img.outerWidth() * (optimalConfig.width / lensWidth)}px ${$img.outerHeight() * (optimalConfig.height / lensHeight)}px`
        });

        // Store current config for moveLens function
        $result.data('currentConfig', optimalConfig);
      }

      function moveLens(ev) {
        const evt = ev.originalEvent.touches ? ev.originalEvent.touches[0] : ev;
        const pageX = evt.pageX;
        const pageY = evt.pageY;

        const imgOffset = $img.offset();
        let lensX = pageX - lensWidth / 2;
        let lensY = pageY - lensHeight / 2;

        const maxX = imgOffset.left + $img.outerWidth() - lensWidth;
        const maxY = imgOffset.top + $img.outerHeight() - lensHeight;

        lensX = Math.max(imgOffset.left, Math.min(lensX, maxX));
        lensY = Math.max(imgOffset.top, Math.min(lensY, maxY));

        $lens.css({
          width: lensWidth,
          height: lensHeight,
          top: lensY,
          left: lensX,
          display: 'block'
        });

        // Get current result window configuration for adaptive sizing
        const currentConfig = $result.data('currentConfig') || { width: $result.width(), height: $result.height() };

        const cx = currentConfig.width / lensWidth;
        const cy = currentConfig.height / lensHeight;

        $result.css({
          display: 'block',
          backgroundPosition: `-${(lensX - imgOffset.left) * cx}px -${(lensY - imgOffset.top) * cy}px`
        });
      }

      if (settings.autoResize) {
        $(window).on('wheel.' + zoomId, function (e) {
          if (!$lens.is(':visible')) return;

          e.preventDefault();
          const delta = e.originalEvent.deltaY;
          const direction = delta > 0 ? 1 : -1;

          const newWidth = Math.max(settings.minLensSize, Math.min(settings.maxLensSize, lensWidth - direction * settings.step));
          const newHeight = Math.max(settings.minLensSize, Math.min(settings.maxLensSize, lensHeight - direction * settings.step));

          const dx = (lensWidth - newWidth) / 2;
          const dy = (lensHeight - newHeight) / 2;

          lensWidth = newWidth;
          lensHeight = newHeight;

          const currentOffset = $lens.offset();
          $lens.css({
            width: lensWidth,
            height: lensHeight,
            top: currentOffset.top + dy,
            left: currentOffset.left + dx
          });

          // Get current result window configuration for adaptive sizing
          const currentConfig = $result.data('currentConfig') || { width: $result.width(), height: $result.height() };

          const cx = currentConfig.width / lensWidth;
          const cy = currentConfig.height / lensHeight;

          const imgOffset = $img.offset();
          $result.css({
            backgroundSize: `${$img.outerWidth() * cx}px ${$img.outerHeight() * cy}px`,
            backgroundPosition: `-${($lens.offset().left - imgOffset.left) * cx}px -${($lens.offset().top - imgOffset.top) * cy}px`
          });
        });
      }

      $(document).on('mousemove.' + zoomId + ' touchmove.' + zoomId, moveLens);

      // Add event listeners for dynamic repositioning
      $(window).on('scroll.' + zoomId + ' resize.' + zoomId, updateResultPosition);

      $img.on('mouseleave.' + zoomId + ' touchend.' + zoomId, function () {
        $lens.remove();
        $result.remove();
        $(document).off('mousemove.' + zoomId + ' touchmove.' + zoomId);
        $(window).off('wheel.' + zoomId + ' scroll.' + zoomId + ' resize.' + zoomId);
        $img.off('mouseleave.' + zoomId + ' touchend.' + zoomId);
      });

      // Init movement immediately
      moveLens(e);
    });

    return this;
  };


  /**
   * eaelMagnify - jQuery Image Magnifier Plugin
   * 
   * Inspired by and adapted from:
   * "A Simple Image Magnifier" by Avid Code
   * CodePen: https://codepen.io/avidcode/pen/YZqEaZ
   * 
   * License: Please refer to the original CodePen for license details.
   */
  $.fn.eaelMagnify = function (options) {
    const settings = $.extend({
      lensSize: 200,
      zoom: 2,
      lensBorder: '2px solid #fff',
    }, options);

    return this.each(function () {
      const $img = $(this);
      let $lens = null;
      let zoom = settings.zoom;
      let initialized = false;

      function init() {
        if (initialized) return;
        initialized = true;

        const natW = $img[0].naturalWidth;
        const natH = $img[0].naturalHeight;

        function updateLens(e) {
          if (!$lens) return;

          // Handle both mouse and touch events
          const evt = e.originalEvent.touches ? e.originalEvent.touches[0] : e;
          const pageX = evt.pageX;
          const pageY = evt.pageY;

          const imgOffset = $img.offset();
          const imgW = $img.width();
          const imgH = $img.height();
          const x = pageX - imgOffset.left;
          const y = pageY - imgOffset.top;

          if (x < 0 || y < 0 || x > imgW || y > imgH) {
            $lens.css('opacity', 0);
            return;
          }

          const rx = (x / imgW) * natW;
          const ry = (y / imgH) * natH;

          const bgSizeW = natW * zoom;
          const bgSizeH = natH * zoom;

          const bgPosX = (rx * zoom - $lens.width() / 2);
          const bgPosY = (ry * zoom - $lens.height() / 2);

          $lens.css({
            left: `${pageX - $lens.width() / 2}px`,
            top: `${pageY - $lens.height() / 2}px`,
            backgroundPosition: `-${bgPosX}px -${bgPosY}px`,
            backgroundSize: `${bgSizeW}px ${bgSizeH}px`,
            opacity: 1
          });
        }

        $img.on('mouseenter touchstart', function (e) {
          if ($lens) return; // avoid duplicates

          // Prevent default touch behavior to avoid scrolling issues
          if (e.type === 'touchstart') {
            e.preventDefault();
          }

          $lens = $('<div class="eael-magnify-lens"></div>').css({
            width: settings.lensSize,
            height: settings.lensSize,
            border: settings.lensBorder,
            backgroundImage: `url(${$img.attr('src')})`,
            backgroundRepeat: 'no-repeat',
            pointerEvents: 'none',
            opacity: 0,
            boxShadow: '0 5px 10px rgba(0, 0, 0, 0.2)',
            borderRadius: '50%',
            transition: 'opacity 0.2s',
            position: 'absolute',
            zIndex: 9999
          });
          $('body').append($lens);

          // Initialize lens position immediately for touch events
          if (e.type === 'touchstart') {
            updateLens(e);
          }
        });

        $img.on('mousemove touchmove', function(e) {
          // Prevent default touch behavior to avoid scrolling issues
          if (e.type === 'touchmove') {
            e.preventDefault();
          }
          updateLens(e);
        });

        function cleanupLens() {
          if ($lens) {
            $lens.css('opacity', 0);
            setTimeout(() => {
              $lens?.remove();
              $lens = null;
              // Clean up document event listeners
              $(document).off('touchend.eaelMagnify touchcancel.eaelMagnify');
            }, 200); // match fade out duration
          }
        }

        $img.on('mouseleave touchend touchcancel', cleanupLens);

        // Add document-level touch end handler for better mobile support
        $(document).on('touchend.eaelMagnify touchcancel.eaelMagnify', function(e) {
          if ($lens && !$(e.target).closest($img).length) {
            cleanupLens();
          }
        });

        $img.on('wheel', function (e) {
          if (!$lens) return;
          e.preventDefault();

          const delta = e.originalEvent.deltaY || e.originalEvent.wheelDelta;

          zoom = delta > 0
            ? Math.max(1, zoom - 0.1)
            : Math.min(5, zoom + 0.1);

          updateLens(e);
        });
      }

      // Init when image is loaded or immediately if already complete
      if ($img[0].complete && $img[0].naturalWidth) {
        init();
      } else {
        $img.one('load', init);
      }
    });
  };

})(jQuery);