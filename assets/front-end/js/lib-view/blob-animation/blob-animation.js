
/**
 * Morphing Blob Animation Library
 * Applies CSS clip-path blob shape effects to images with GSAP animations
 *
 * @author Essential Addons
 * @version 1.1.0
 */

class MorphingBlobAnimation {
    constructor(selector, options = {}) {
        // Default configuration
        this.defaults = {
            duration: 4,
            easing: 'power2.inOut',
            loop: true,
            autoStart: false,
            scale: { min: 1, max: 1 },
            rotation: false,
            rotationSpeed: 360,
            shapeType: 'polygon', // 'polygon' or 'path'
            // CSS clip-path polygon shapes for reliable cross-browser support
            blobShapes: [
                'polygon(30% 40%, 70% 30%, 80% 70%, 20% 80%)',
                'polygon(40% 20%, 80% 40%, 60% 80%, 10% 60%)',
                'polygon(20% 30%, 60% 10%, 90% 50%, 50% 90%)',
                'polygon(35% 25%, 75% 35%, 65% 75%, 25% 65%)'
            ]
        };

        // Merge options with defaults
        this.options = { ...this.defaults, ...options };

        // Initialize properties
        this.selector = selector;
        this.element = null;
        this.svgElement = null;
        this.pathElement = null;
        this.timeline = null;
        this.isActive = false;
        this.uniqueId = this.generateUniqueId();

        // Initialize the animation
        this.init();
    }

    /**
     * Initialize the blob animation
     */
    init() {
        try {
            // Find target element
            this.element = document.querySelector(this.selector);
            if (!this.element) {
                console.error(`MorphingBlobAnimation: Element not found for selector "${this.selector}"`);
                return;
            }

            // Check if GSAP is available
            if (typeof gsap === 'undefined') {
                console.error('MorphingBlobAnimation: GSAP library is required');
                return;
            }

            // Detect shape type from first shape if not explicitly set
            if (this.options.shapeType === 'auto') {
                this.options.shapeType = this.detectShapeType(this.options.blobShapes[0]);
            }

            // Initialize based on shape type
            if (this.options.shapeType === 'path') {
                this.createSVGClipPath();
            } else {
                this.applyClipPath(this.options.blobShapes[0]);
            }

            // Auto start if enabled
            if (this.options.autoStart) {
                this.start();
            }

        } catch (error) {
            console.error('MorphingBlobAnimation initialization error:', error);
        }
    }

    /**
     * Detect shape type from shape string
     */
    detectShapeType(shape) {
        if (shape.startsWith('polygon(')) {
            return 'polygon';
        } else if (shape.startsWith('M') || shape.includes('C') || shape.includes('Z')) {
            return 'path';
        }
        return 'polygon'; // default fallback
    }

    /**
     * Create SVG clip-path for path-based shapes
     */
    createSVGClipPath() {
        // Create SVG element
        this.svgElement = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
        this.svgElement.setAttribute('width', '0');
        this.svgElement.setAttribute('height', '0');
        this.svgElement.style.position = 'absolute';
        this.svgElement.style.pointerEvents = 'none';

        // Create defs element
        const defs = document.createElementNS('http://www.w3.org/2000/svg', 'defs');

        // Create clipPath element
        const clipPath = document.createElementNS('http://www.w3.org/2000/svg', 'clipPath');
        clipPath.setAttribute('id', `blob-clip-${this.uniqueId}`);
        clipPath.setAttribute('clipPathUnits', 'objectBoundingBox');

        // Create path element
        this.pathElement = document.createElementNS('http://www.w3.org/2000/svg', 'path');
        this.pathElement.setAttribute('d', this.normalizePathForClipPath(this.options.blobShapes[0]));

        // Append elements
        clipPath.appendChild(this.pathElement);
        defs.appendChild(clipPath);
        this.svgElement.appendChild(defs);

        // Add SVG to document
        document.body.appendChild(this.svgElement);

        // Apply SVG clip-path to element
        if (this.element) {
            this.element.style.clipPath = `url(#blob-clip-${this.uniqueId})`;
            this.element.style.webkitClipPath = `url(#blob-clip-${this.uniqueId})`;
        }
    }

    /**
     * Normalize SVG path for clip-path usage (scale to 0-1 range)
     */
    normalizePathForClipPath(pathData) {
        // Remove transform attribute if present and extract path
        let cleanPath = pathData;

        // Handle transform="translate(100 100)" by removing it
        cleanPath = cleanPath.replace(/transform="[^"]*"/g, '');

        // Simple normalization - scale the path to fit 0-1 coordinate system
        // This assumes the original path is roughly in 0-200 range (based on your example)
        return cleanPath.replace(/(-?\d+(?:\.\d+)?)/g, (match) => {
            const num = parseFloat(match);
            // Scale from -100 to 100 range to 0-1 range
            const normalized = (num + 100) / 200;
            return Math.max(0, Math.min(1, normalized)).toFixed(4);
        });
    }

    /**
     * Apply clip-path to target element
     */
    applyClipPath(clipPath) {
        if (this.element) {
            this.element.style.clipPath = clipPath;
            this.element.style.webkitClipPath = clipPath;
        }
    }

    /**
     * Start the blob animation
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
        const shapes = this.options.blobShapes;
        const duration = this.options.duration / shapes.length;

        if (this.options.shapeType === 'path' && this.pathElement) {
            // Animate SVG path data
            shapes.forEach((shape, index) => {
                this.timeline.to(this.pathElement, {
                    duration: duration,
                    attr: { d: this.normalizePathForClipPath(shape) },
                    ease: this.options.easing
                }, index * duration);
            });
        } else {
            // Animate CSS clip-path polygon
            shapes.forEach((shape, index) => {
                this.timeline.to(this.element, {
                    duration: duration,
                    clipPath: shape,
                    ease: this.options.easing
                }, index * duration);
            });
        }
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
     * Stop the blob animation
     */
    stop() {
        if (this.timeline) {
            this.timeline.kill();
            this.timeline = null;
        }
        this.isActive = false;
    }

    /**
     * Destroy the blob animation and clean up
     */
    destroy() {
        this.stop();

        // Remove clip-path from element
        if (this.element) {
            this.element.style.clipPath = '';
            this.element.style.webkitClipPath = '';
            this.element.style.transform = '';
        }

        // Remove SVG element if it exists
        if (this.svgElement && this.svgElement.parentNode) {
            this.svgElement.parentNode.removeChild(this.svgElement);
        }

        // Reset properties
        this.element = null;
        this.svgElement = null;
        this.pathElement = null;
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
     * Generate unique ID for SVG elements
     */
    generateUniqueId() {
        return Math.random().toString(36).substring(2, 11);
    }

    /**
     * Get current animation state
     */
    getState() {
        return {
            isActive: this.isActive,
            options: this.options,
            element: this.element
        };
    }

    /**
     * Create custom blob shapes from coordinates
     */
    static createBlobFromPoints(points) {
        const polygonPoints = points.map(point => `${point.x}% ${point.y}%`).join(', ');
        return `polygon(${polygonPoints})`;
    }

    /**
     * Predefined blob shape sets
     */
    static getPresetShapes(preset = 'organic', type = 'polygon') {
        const polygonPresets = {
            organic: [
                'polygon(30% 40%, 70% 30%, 80% 70%, 20% 80%)',
                'polygon(40% 20%, 80% 40%, 60% 80%, 10% 60%)',
                'polygon(20% 30%, 60% 10%, 90% 50%, 50% 90%)',
                'polygon(35% 25%, 75% 35%, 65% 75%, 25% 65%)'
            ],
            smooth: [
                'polygon(25% 35%, 75% 25%, 85% 75%, 15% 85%)',
                'polygon(35% 15%, 85% 35%, 75% 85%, 25% 75%)',
                'polygon(15% 25%, 65% 15%, 95% 65%, 45% 95%)',
                'polygon(45% 35%, 95% 45%, 85% 95%, 35% 85%)'
            ],
            geometric: [
                'polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%)',
                'polygon(30% 0%, 70% 0%, 100% 30%, 100% 70%, 70% 100%, 30% 100%, 0% 70%, 0% 30%)',
                'polygon(25% 0%, 75% 0%, 100% 25%, 100% 75%, 75% 100%, 25% 100%, 0% 75%, 0% 25%)',
                'polygon(20% 0%, 80% 0%, 100% 20%, 100% 80%, 80% 100%, 20% 100%, 0% 80%, 0% 20%)'
            ]
        };

        const pathPresets = {
            organic: [
                'M53.6,-12.8C61.8,7.5,55.2,37.2,36.3,51.3C17.4,65.3,-13.8,63.7,-36.4,47.8C-59,31.9,-73,1.8,-65.5,-17.7C-58,-37.3,-29,-46.3,-3.1,-45.2C22.8,-44.2,45.5,-33.2,53.6,-12.8Z',
                'M47.2,-23.1C55.8,-4.2,54.1,19.8,42.1,35.4C30.1,51,7.8,58.2,-15.7,55.8C-39.2,53.4,-63.9,41.4,-71.3,22.1C-78.7,2.8,-68.8,-23.8,-52.1,-41.2C-35.4,-58.6,-11.9,-66.8,7.2,-69.4C26.3,-72,38.6,-42,47.2,-23.1Z',
                'M39.8,-25.7C48.6,-11.2,50.7,8.1,44.3,24.8C37.9,41.5,23,55.6,4.8,53.2C-13.4,50.8,-34.9,31.9,-45.2,8.7C-55.5,-14.5,-54.6,-42,-44.1,-56.4C-33.6,-70.8,-13.4,-72.1,3.2,-72.8C19.8,-73.5,31,-25.7,39.8,-25.7Z',
                'M35.2,-18.9C42.8,-7.1,44.2,8.9,38.1,22.4C32,35.9,18.4,46.9,2.1,46.1C-14.2,45.3,-33.1,32.7,-42.3,15.8C-51.5,-1.1,-51,-22.3,-42.8,-36.2C-34.6,-50.1,-18.7,-56.7,-2.9,-55.8C12.9,-54.9,27.6,-30.7,35.2,-18.9Z'
            ],
            smooth: [
                'M42.1,-23.4C51.2,-8.7,53.4,10.2,47.3,26.3C41.2,42.4,26.8,55.7,8.9,58.2C-9,60.7,-30.4,52.4,-43.2,37.8C-56,23.2,-60.2,2.3,-56.8,-16.1C-53.4,-34.5,-42.4,-50.4,-27.8,-54.8C-13.2,-59.2,4,-51.1,18.7,-38.2C33.4,-25.3,45.6,-12.7,42.1,-23.4Z',
                'M38.7,-19.5C46.8,-6.2,48.2,11.3,42.1,26.1C36,40.9,22.4,53,5.9,50.8C-10.6,48.6,-29.9,32.1,-40.8,11.8C-51.7,-8.5,-54.2,-32.6,-46.2,-47.2C-38.2,-61.8,-19.1,-66.9,-1.8,-66.1C15.5,-65.3,30.6,-32.8,38.7,-19.5Z',
                'M44.3,-26.1C54.2,-12.8,57.2,5.1,52.1,21.4C47,37.7,33.8,52.4,16.9,58.3C0,64.2,-20.6,61.3,-35.8,51.2C-51,41.1,-60.8,23.8,-62.1,5.8C-63.4,-12.2,-56.2,-30.9,-43.7,-44.2C-31.2,-57.5,-13.4,-65.4,2.1,-66.3C17.6,-67.2,34.4,-39.4,44.3,-26.1Z',
                'M40.2,-22.3C48.9,-9.1,51.2,8.2,46.1,23.8C41,39.4,28.5,53.3,12.4,58.4C-3.7,63.5,-23.4,59.8,-37.8,49.3C-52.2,38.8,-61.3,21.5,-62.8,3.1C-64.3,-15.3,-58.2,-34.8,-46.4,-48.1C-34.6,-61.4,-17.1,-68.5,-0.8,-68.1C15.5,-67.7,31.5,-35.5,40.2,-22.3Z'
            ],
            geometric: [
                'M50,-50C66.7,-33.3,83.3,-16.7,83.3,0C83.3,16.7,66.7,33.3,50,50C33.3,66.7,16.7,83.3,0,83.3C-16.7,83.3,-33.3,66.7,-50,50C-66.7,33.3,-83.3,16.7,-83.3,0C-83.3,-16.7,-66.7,-33.3,-50,-50C-33.3,-66.7,-16.7,-83.3,0,-83.3C16.7,-83.3,33.3,-66.7,50,-50Z',
                'M60,-60C70.7,-50,81.4,-25,81.4,0C81.4,25,70.7,50,60,60C50,70.7,25,81.4,0,81.4C-25,81.4,-50,70.7,-60,60C-70.7,50,-81.4,25,-81.4,0C-81.4,-25,-70.7,-50,-60,-60C-50,-70.7,-25,-81.4,0,-81.4C25,-81.4,50,-70.7,60,-60Z',
                'M45,-45C60,-30,75,-15,75,0C75,15,60,30,45,45C30,60,15,75,0,75C-15,75,-30,60,-45,45C-60,30,-75,15,-75,0C-75,-15,-60,-30,-45,-45C-30,-60,-15,-75,0,-75C15,-75,30,-60,45,-45Z',
                'M55,-55C73.3,-36.7,91.7,-18.3,91.7,0C91.7,18.3,73.3,36.7,55,55C36.7,73.3,18.3,91.7,0,91.7C-18.3,91.7,-36.7,73.3,-55,55C-73.3,36.7,-91.7,18.3,-91.7,0C-91.7,-18.3,-73.3,-36.7,-55,-55C-36.7,-73.3,-18.3,-91.7,0,-91.7C18.3,-91.7,36.7,-73.3,55,-55Z'
            ]
        };

        if (type === 'path') {
            return pathPresets[preset] || pathPresets.organic;
        }

        return polygonPresets[preset] || polygonPresets.organic;
    }
}

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = MorphingBlobAnimation;
}

// Global assignment for browser usage
if (typeof window !== 'undefined') {
    window.MorphingBlobAnimation = MorphingBlobAnimation;
}
