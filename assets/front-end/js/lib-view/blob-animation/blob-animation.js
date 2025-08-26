/**
 * Polygon Morphing Animation Library
 * Applies CSS clip-path polygon shape effects to images with GSAP animations
 * jQuery-based implementation with automatic initialization
 *
 * @author Essential Addons
 * @version 2.0.0
 */

class PolygonMorphingAnimation {
    constructor(selector, options = {}) {
        // Default configuration
        this.defaults = {
            duration: 4,
            easing: 'power2.inOut',
            loop: true,
            autoStart: true,
            scale: { min: 1, max: 1 },
            rotation: false,
            rotationSpeed: 360,
            // Smooth polygon shapes for morphing animation
            polygonShapes: [
                'polygon(25% 35%, 75% 25%, 85% 75%, 15% 85%)',
                'polygon(35% 15%, 85% 35%, 75% 85%, 25% 75%)',
                'polygon(15% 25%, 65% 15%, 95% 65%, 45% 95%)',
                'polygon(45% 35%, 95% 45%, 85% 95%, 35% 85%)'
            ]
        };

        // Merge options with defaults
        this.options = { ...this.defaults, ...options };

        // Initialize properties
        this.selector = selector;
        this.$element = null;
        this.element = null;
        this.timeline = null;
        this.isActive = false;

        // Initialize the animation
        this.init();
    }

    /**
     * Initialize the polygon animation with jQuery support
     */
    init() {
        try {
            // Check if jQuery is available
            if (typeof jQuery === 'undefined') {
                return;
            }

            // Handle different selector types
            if (typeof this.selector === 'string') {
                this.$element = jQuery(this.selector);
                if (this.$element.length === 0) {
                    return;
                }
                this.element = this.$element[0];
            } else if (this.selector instanceof jQuery) {
                this.$element = this.selector;
                if (this.$element.length === 0) {
                    return;
                }
                this.element = this.$element[0];
            } else if (this.selector instanceof HTMLElement) {
                this.element = this.selector;
                this.$element = jQuery(this.element);
            } else {
                return;
            }

            // Check if GSAP is available
            if (typeof gsap === 'undefined') {
                return;
            }

            // Apply initial polygon shape
            this.applyClipPath(this.options.polygonShapes[0]);

            // Auto start if enabled
            if (this.options.autoStart) {
                this.start();
            }

        } catch (error) {
            // Silent error handling
        }
    }

    /**
     * Apply clip-path to target element using jQuery
     */
    applyClipPath(clipPath) {
        if (this.$element) {
            this.$element.css({
                'clip-path': clipPath,
                '-webkit-clip-path': clipPath
            });
        }
    }

    /**
     * Start the polygon animation
     */
    start() {
        if (this.isActive) return;

        this.isActive = true;

        // Create GSAP timeline
        this.timeline = gsap.timeline({
            repeat: this.options.loop ? -1 : 0,
            yoyo: this.options.loop,
            ease: this.options.easing
        });

        // Add morphing animation
        this.addMorphingAnimation();

        // Add scaling animation if enabled
        if (this.options.scale.min !== 1 || this.options.scale.max !== 1) {
            this.addScalingAnimation();
        }

        // Add rotation animation if enabled
        if (this.options.rotation) {
            this.addRotationAnimation();
        }
    }

    /**
     * Add morphing animation to timeline
     */
    addMorphingAnimation() {
        const shapes = this.options.polygonShapes;
        const duration = this.options.duration / shapes.length;

        // Animate CSS clip-path polygon
        shapes.forEach((shape) => {
            this.timeline.to(this.element, {
                duration: duration,
                clipPath: shape,
                ease: this.options.easing
            });
        });
    }

    /**
     * Add scaling animation to timeline
     */
    addScalingAnimation() {
        const scaleTimeline = gsap.timeline({ repeat: -1, yoyo: true });
        scaleTimeline.to(this.element, {
            duration: this.options.duration * 0.5,
            scale: this.options.scale.max,
            ease: 'power2.inOut',
            transformOrigin: 'center center'
        }).to(this.element, {
            duration: this.options.duration * 0.5,
            scale: this.options.scale.min,
            ease: 'power2.inOut',
            transformOrigin: 'center center'
        });
    }

    /**
     * Add rotation animation to timeline
     */
    addRotationAnimation() {
        gsap.to(this.element, {
            duration: this.options.duration * 2,
            rotation: this.options.rotationSpeed,
            repeat: -1,
            ease: 'none',
            transformOrigin: 'center center'
        });
    }

    /**
     * Stop the animation
     */
    stop() {
        if (this.timeline) {
            this.timeline.kill();
            this.timeline = null;
        }
        this.isActive = false;
    }

    /**
     * Destroy the animation and clean up
     */
    destroy() {
        this.stop();
        if (this.$element) {
            this.$element.css({
                'clip-path': '',
                '-webkit-clip-path': '',
                'transform': ''
            });
        }
        this.$element = null;
        this.element = null;
        this.timeline = null;
        this.isActive = false;
    }

    /**
     * Update animation options
     */
    updateOptions(newOptions) {
        this.options = { ...this.options, ...newOptions };
        if (this.isActive) {
            this.stop();
            this.start();
        }
    }

    /**
     * Get current animation state
     */
    getState() {
        return {
            isActive: this.isActive,
            options: this.options,
            element: this.element,
            $element: this.$element
        };
    }

    /**
     * Get preset polygon shapes
     */
    static getPresetShapes() {
        return [
            'polygon(25% 35%, 75% 25%, 85% 75%, 15% 85%)',
            'polygon(35% 15%, 85% 35%, 75% 85%, 25% 75%)',
            'polygon(15% 25%, 65% 15%, 95% 65%, 45% 95%)',
            'polygon(45% 35%, 95% 45%, 85% 95%, 35% 85%)'
        ];
    }
}

// Export for different environments
if (typeof module !== 'undefined' && module.exports) {
    module.exports = PolygonMorphingAnimation;
}

if (typeof window !== 'undefined') {
    window.PolygonMorphingAnimation = PolygonMorphingAnimation;
}
