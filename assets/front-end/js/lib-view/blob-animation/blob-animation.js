/**
 * Blob Image Mask Library
 * Creates morphing blob shapes for image masks
 * Requires GSAP library
 *
 * @version 2.0.0
 */

class BlobImageMask {
  constructor(selector, options = {}) {
    // Handle both parameter formats for backward compatibility
    if (typeof selector === 'object' && selector !== null) {
      options = selector;
      selector = options.selector;
    }

    // Selector is required
    if (!selector) {
      throw new Error('BlobImageMask: Selector is required. Please provide a valid CSS selector, jQuery object, or DOM element.');
      return;
    }

    this.selector = selector;
    this.options = {
      duration: 3,
      ease: 'power2.inOut',
      repeat: -1,
      stagger: 0.5,
      autoStart: true,
      paths: [
        "M380.045 96.0397C432.405 102.966 501.969 126.823 517.304 167.611C532.638 208.783 493.741 266.502 460.455 329.607C427.169 392.713 399.493 460.821 346.759 497.761C293.651 535.085 215.859 541.242 159.011 510.074C101.789 479.291 65.5105 411.568 40.0785 346.538C14.2724 281.893 -0.313612 219.942 0.808391 151.834C1.93039 83.7264 18.7604 9.46198 64.3885 1.38139C110.017 -6.6992 184.069 51.4041 238.673 75.6459C292.903 99.5028 328.059 89.4983 380.045 96.0397Z",
        "M420.123 85.234C465.789 95.123 510.456 135.678 515.234 185.456C520.012 235.234 475.123 294.567 435.678 365.234C396.234 435.901 361.456 518.234 305.789 545.123C250.123 572.012 173.456 553.456 115.234 515.678C57.012 477.901 17.234 421.234 5.678 358.456C-5.878 295.678 10.789 226.789 45.234 168.123C79.678 109.456 131.901 61.012 185.234 45.678C238.567 30.345 293.012 48.123 345.678 58.234C398.345 68.345 374.456 75.345 420.123 85.234Z",
        "M350.234 120.456C415.678 115.234 485.123 140.789 505.456 195.234C525.789 249.678 496.234 323.456 455.789 385.123C415.345 446.789 364.012 496.234 295.456 520.789C226.901 545.345 141.123 545.012 85.789 515.234C30.456 485.456 5.123 426.234 15.234 365.789C25.345 305.345 70.901 243.678 125.234 195.456C179.567 147.234 242.678 112.456 305.123 105.789C367.567 99.123 284.789 125.678 350.234 120.456Z",
        "M395.678 110.789C445.234 125.456 485.789 165.234 510.123 215.678C534.456 266.123 542.567 326.234 515.234 375.456C487.901 424.678 425.234 462.012 365.789 485.234C306.345 508.456 250.123 517.567 195.456 505.789C140.789 494.012 87.678 461.345 55.234 415.678C22.789 370.012 11.123 311.345 25.456 255.234C39.789 199.123 80.012 145.567 135.234 115.456C190.456 85.345 260.678 78.678 325.234 95.234C389.789 111.789 346.123 96.123 395.678 110.789Z",
        "M365.234 135.678C405.789 145.234 440.123 175.456 460.234 215.789C480.345 256.123 486.234 306.567 470.789 350.234C455.345 393.901 418.567 430.789 375.234 455.678C331.901 480.567 282.456 493.456 235.789 485.234C189.123 477.012 145.234 447.678 115.456 405.234C85.678 362.789 70.012 307.234 75.234 254.567C80.456 201.901 106.567 152.123 145.234 125.456C183.901 98.789 235.123 95.234 285.678 105.789C336.234 116.345 324.678 126.123 365.234 135.678Z"
      ],
      ...options
    };

    this.timelines = [];
    this.isPlaying = true;
    this.currentSpeed = 1;

    if (this.options.autoStart) {
      this.init();
    }
  }

  /**
   * Initialize the blob morphing effect
   */
  init() {
    if (typeof gsap === 'undefined') {
      console.error('BlobImageMask: GSAP library is required');
      return;
    }

    let elements;

    // Support both jQuery and vanilla JavaScript selectors
    if (typeof $ !== 'undefined' && this.selector instanceof $) {
      // jQuery object passed directly
      elements = this.selector.get();
    } else if (typeof $ !== 'undefined' && typeof this.selector === 'string') {
      // jQuery selector string
      const $elements = $(this.selector);
      if ($elements.length > 0) {
        elements = $elements.get();
      } else {
        // Fallback to vanilla JS if jQuery doesn't find elements
        elements = document.querySelectorAll(this.selector);
      }
    } else {
      // Vanilla JavaScript selector
      if (this.selector instanceof NodeList || this.selector instanceof HTMLCollection) {
        elements = Array.from(this.selector);
      } else if (this.selector instanceof Element) {
        elements = [this.selector];
      } else if (typeof this.selector === 'string') {
        elements = document.querySelectorAll(this.selector);
      } else {
        console.error('BlobImageMask: Invalid selector type:', typeof this.selector);
        return;
      }
    }

    if (!elements || elements.length === 0) {
      throw new Error(`BlobImageMask: No elements found with selector: "${this.selector}". Please check your selector or ensure elements exist in the DOM.`);
    }

    elements.forEach((element, index) => {
      this.initElement(element, index);
    });

    console.log(`BlobImageMask: Initialized ${elements.length} elements with selector:`, this.selector);
    return this;
  }

  /**
   * Initialize morphing for a single element
   */
  initElement(element, index) {
    let clipPath;

    // Use custom clip path selector if provided
    if (this.options.clipPathSelector) {
      if (typeof $ !== 'undefined') {
        clipPath = $(element).find(this.options.clipPathSelector).get(0);
      } else {
        clipPath = element.querySelector(this.options.clipPathSelector);
      }
    } else {
      // Default behavior: look for morphing ID and clip path
      const morphingId = element.dataset.morphingId;
      if (morphingId) {
        clipPath = element.querySelector(`#shape-morphing-${morphingId} path`);
      } else {
        // Fallback: find any clip path in the element
        clipPath = element.querySelector('clipPath path');
      }
    }

    if (!clipPath) {
      console.warn('BlobImageMask: No clip path found for element', element);
      return;
    }

    // Create timeline for this element
    const timeline = gsap.timeline({
      repeat: this.options.repeat,
      delay: index * this.options.stagger,
      ease: this.options.ease
    });

    // Add morphing animations
    this.options.paths.forEach(path => {
      timeline.to(clipPath, {
        attr: { d: path },
        duration: this.options.duration,
        ease: this.options.ease
      });
    });

    this.timelines.push(timeline);
  }



  /**
   * Play the animation
   */
  play() {
    gsap.globalTimeline.resume();
    this.isPlaying = true;
    return this;
  }

  /**
   * Pause the animation
   */
  pause() {
    gsap.globalTimeline.pause();
    this.isPlaying = false;
    return this;
  }

  /**
   * Toggle play/pause
   */
  toggle() {
    if (this.isPlaying) {
      this.pause();
    } else {
      this.play();
    }
    return this;
  }

  /**
   * Set animation speed
   */
  setSpeed(speed) {
    this.currentSpeed = speed;
    gsap.globalTimeline.timeScale(speed);
    return this;
  }

  /**
   * Update paths and restart animation
   */
  updatePaths(newPaths) {
    this.options.paths = newPaths;
    this.destroy();
    this.init();
    return this;
  }

  /**
   * Destroy all timelines
   */
  destroy() {
    this.timelines.forEach(timeline => {
      timeline.kill();
    });
    this.timelines = [];
    return this;
  }
}

// No auto-initialization - selector is required

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
  module.exports = BlobImageMask;
}

// Global export
window.BlobImageMask = BlobImageMask;