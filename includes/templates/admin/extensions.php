<?php

$extensions = [
	'eael-pro-extensions' => [
		'title'      => __( 'Premium Extensions', 'essential-addons-for-elementor-lite' ),
		'extensions' => [
			[
				'key'       => 'section-parallax',
				'title'     => __( 'Parallax', 'essential-addons-for-elementor-lite' ),
				'demo_link' => 'https://essential-addons.com/elementor/parallax-scrolling/',
				'doc_link'  => 'https://essential-addons.com/elementor/docs/ea-parallax/',
				'is_pro'    => true
			],
			[
				'key'       => 'section-particles',
				'title'     => __( 'Particles', 'essential-addons-for-elementor-lite' ),
				'demo_link' => 'https://essential-addons.com/elementor/particle-effect/',
				'doc_link'  => 'https://essential-addons.com/elementor/docs/particles/',
				'is_pro'    => true
			],
			[
				'key'       => 'tooltip-section',
				'title'     => __( 'Advanced Tooltip', 'essential-addons-for-elementor-lite' ),
				'demo_link' => 'https://essential-addons.com/elementor/advanced-tooltip/',
				'doc_link'  => 'https://essential-addons.com/elementor/docs/ea-advanced-tooltip/',
				'is_pro'    => true
			],
			[
				'key'       => 'content-protection',
				'title'     => __( 'Content Protection', 'essential-addons-for-elementor-lite' ),
				'demo_link' => 'https://essential-addons.com/elementor/content-protection/',
				'doc_link'  => 'https://essential-addons.com/elementor/docs/ea-content-protection/',
				'is_pro'    => true
			],
			[
				'key'       => 'reading-progress',
				'title'     => __( 'Reading Progress Bar', 'essential-addons-for-elementor-lite' ),
				'demo_link' => 'https://essential-addons.com/elementor/reading-progress/',
				'doc_link'  => 'https://essential-addons.com/elementor/docs/ea-reading-progress-bar/',
			],
			[
				'key'       => 'table-of-content',
				'title'     => __( 'Table of Contents', 'essential-addons-for-elementor-lite' ),
				'demo_link' => 'https://essential-addons.com/elementor/table-of-content/',
				'doc_link'  => 'https://essential-addons.com/elementor/docs/table-of-content',
			],
			[
				'key'       => 'post-duplicator',
				'title'     => __( 'Duplicator', 'essential-addons-for-elementor-lite' ),
				'demo_link' => 'https://essential-addons.com/elementor/duplicator/',
				'doc_link'  => 'https://essential-addons.com/elementor/docs/duplicator/',
			],
			[
				'key'       => 'custom-js',
				'title'     => __( 'Custom JS', 'essential-addons-for-elementor-lite' ),
				'demo_link' => 'https://essential-addons.com/elementor/custom-js/',
				'doc_link'  => 'https://essential-addons.com/elementor/docs/custom-js/',
			],
			[
				'key'       => 'xd-copy',
				'title'     => __( 'Cross-Domain Copy Paste', 'essential-addons-for-elementor-lite' ),
				'demo_link' => 'https://essential-addons.com/elementor/xd-copy/',
				'doc_link'  => 'https://essential-addons.com/elementor/docs/xd-copy/',
				'is_pro'    => true
			],
			[
				'key'       => 'scroll-to-top',
				'title'     => __( 'Scroll to Top', 'essential-addons-for-elementor-lite' ),
				'demo_link' => 'https://essential-addons.com/elementor/scroll-to-top/',
				'doc_link'  => 'https://essential-addons.com/elementor/docs/ea-scroll-to-top/',
			],
		]
	]
];

?>


<div id="extensions" class="eael-admin-setting-tab">
    <div class="eael-global__control mb45">
        <div class="global__control__content">
            <h4>Global Control</h4>
            <p>Use the Buttons to Activate or Deactivate all the Elements of Essential Addons at once.</p>
        </div>
        <div class="global__control__switch">
            <label class="eael-switch eael-switch--xl">
                <input type="checkbox">
                <span class="switch__box"></span>
            </label>
            <span class="switch__status enable">Enable All</span>
            <span class="switch__status disable">Disable All</span>
        </div>
        <div class="global__control__button">
            <a href="#" class="button">Save Setting</a>
        </div>
    </div>

    <div class="eael-section mb50">
        <h3 class="eael-section__header">Content Elements</h3>
        <div class="eael-element__wrap">
            <div class="eael-element__item">
                <div class="isPro">
                    <span>Pro</span>
                </div>
                <div class="element__content">
                    <h4>Creative Button</h4>
                    <div class="element__options">
                        <a href="#" class="element__icon">
                            <i class="ea-icon icon-doc"></i>
                            <span class="tooltip-text">Documentation Documentation</span>
                        </a>
                        <a href="#" class="element__icon">
                            <i class="ea-icon icon-monitor"></i>
                            <span class="tooltip-text">Preview</span>
                        </a>
                        <label class="eael-switch">
                            <input type="checkbox">
                            <span class="switch__box"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="eael-element__item">
                <div class="isPro"></div>
                <div class="element__content">
                    <h4>Creative Button</h4>
                    <div class="element__options">
                        <a href="#" class="element__icon">
                            <i class="ea-icon icon-doc"></i>
                            <span class="tooltip-text">Documentation</span>
                        </a>
                        <a href="#" class="element__icon">
                            <i class="ea-icon icon-monitor"></i>
                            <span class="tooltip-text">Preview</span>
                        </a>
                        <label class="eael-switch">
                            <input type="checkbox" checked>
                            <span class="switch__box"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="eael-element__item">
                <div class="isPro"></div>
                <div class="element__content">
                    <h4>Creative Button</h4>
                    <div class="element__options">
                        <a href="#" class="element__icon">
                            <i class="ea-icon icon-doc"></i>
                            <span class="tooltip-text">Documentation</span>
                        </a>
                        <a href="#" class="element__icon">
                            <i class="ea-icon icon-monitor"></i>
                            <span class="tooltip-text">Preview</span>
                        </a>
                        <label class="eael-switch">
                            <input type="checkbox">
                            <span class="switch__box"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="eael-element__item">
                <div class="isPro"></div>
                <div class="element__content">
                    <h4>Creative Button</h4>
                    <div class="element__options">
                        <a href="#" class="element__icon">
                            <i class="ea-icon icon-doc"></i>
                            <span class="tooltip-text">Documentation</span>
                        </a>
                        <a href="#" class="element__icon">
                            <i class="ea-icon icon-monitor"></i>
                            <span class="tooltip-text">Preview</span>
                        </a>
                        <label class="eael-switch">
                            <input type="checkbox">
                            <span class="switch__box"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="eael-element__item">
                <div class="isPro"><span>Pro</span></div>
                <div class="element__content">
                    <h4>Creative Button</h4>
                    <div class="element__options">
                        <a href="#" class="element__icon">
                            <i class="ea-icon icon-doc"></i>
                            <span class="tooltip-text">Documentation</span>
                        </a>
                        <a href="#" class="element__icon">
                            <i class="ea-icon icon-monitor"></i>
                            <span class="tooltip-text">Preview</span>
                        </a>
                        <label class="eael-switch">
                            <input type="checkbox" checked>
                            <span class="switch__box"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="eael-element__item">
                <div class="element__content">
                    <h4>Creative Button</h4>
                    <div class="element__options">
                        <a href="#" class="element__icon">
                            <i class="ea-icon icon-doc"></i>
                            <span class="tooltip-text">Documentation</span>
                        </a>
                        <a href="#" class="element__icon">
                            <i class="ea-icon icon-monitor"></i>
                            <span class="tooltip-text">Preview</span>
                        </a>
                        <label class="eael-switch">
                            <input type="checkbox">
                            <span class="switch__box"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
