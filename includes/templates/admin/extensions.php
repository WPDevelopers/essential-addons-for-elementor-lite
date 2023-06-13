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
				'is_pro'    => true,
				'promotion' => 'popular'
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
				'promotion' => 'popular'
			],
			[
				'key'       => 'post-duplicator',
				'title'     => __( 'Duplicator', 'essential-addons-for-elementor-lite' ),
				'demo_link' => 'https://essential-addons.com/elementor/duplicator/',
				'doc_link'  => 'https://essential-addons.com/elementor/docs/duplicator/',
				'setting'   => [ 'id' => 'eael-post-duplicator-setting' ]
			],
			[
				'key'       => 'custom-js',
				'title'     => __( 'Custom JS', 'essential-addons-for-elementor-lite' ),
				'demo_link' => 'https://essential-addons.com/elementor/custom-js/',
				'doc_link'  => 'https://essential-addons.com/elementor/docs/custom-js/',
				'promotion' => 'popular'
			],
			[
				'key'       => 'xd-copy',
				'title'     => __( 'Cross-Domain Copy Paste', 'essential-addons-for-elementor-lite' ),
				'demo_link' => 'https://essential-addons.com/elementor/cross-domain-copy-paste/',
				'doc_link'  => 'https://essential-addons.com/elementor/docs/cross-domain-copy-paste/',
				'is_pro'    => true,
				'promotion' => 'new'
			],
			[
				'key'       => 'scroll-to-top',
				'title'     => __( 'Scroll to Top', 'essential-addons-for-elementor-lite' ),
				'demo_link' => 'https://essential-addons.com/elementor/scroll-to-top/',
				'doc_link'  => 'https://essential-addons.com/elementor/docs/scroll-to-top/',
				'promotion' => 'new'
			],
			[
				'key'       => 'conditional-display',
				'title'     => __( 'Conditional Display', 'essential-addons-for-elementor-lite' ),
				'demo_link' => 'https://essential-addons.com/elementor/conditional-display/',
				'doc_link'  => 'https://essential-addons.com/elementor/docs/conditional-display/',
				'is_pro'    => true,
				'promotion' => 'new'
			],
			[
				'key'       => 'wrapper-link',
				'title'     => __( 'Wrapper Link', 'essential-addons-for-elementor-lite' ),
				'demo_link' => 'https://essential-addons.com/elementor/wrapper-link/',
				'doc_link'  => 'https://essential-addons.com/elementor/docs/ea-wrapper-link/',
				'promotion' => 'new'
			],
		]
	]
];

?>


<div id="extensions" class="eael-admin-setting-tab">
    <div class="eael-global__control mb45">
        <div class="global__control__content">
            <h4><?php _e( 'Global Control', 'essential-addons-for-elementor-lite' ); ?></h4>
            <p><?php _e( 'Use the Toggle Button to Activate or Deactivate all the Extensions of Essential Addons at once.', 'essential-addons-for-elementor-lite' ); ?></p>
        </div>
        <div class="global__control__switch">
            <label class="eael-switch eael-switch--xl">
                <input data-id="eael-extensions-list" class="eael-element-global-switch" type="checkbox">
                <span class="switch__box"></span>
            </label>
            <span class="switch__status enable"><?php _e( 'Enable All', 'essential-addons-for-elementor-lite' ); ?></span>
            <span class="switch__status disable"><?php _e( 'Disable All', 'essential-addons-for-elementor-lite' ); ?></span>
        </div>
        <div class="global__control__button">
            <button type="button" class="eael-button js-eael-settings-save"><?php _e( 'Save Settings', 'essential-addons-for-elementor-lite' ); ?></button>
        </div>
    </div>

	<?php foreach ( $extensions as $key => $element ): ?>
        <div class="eael-section mb50">
            <h3 class="eael-section__header"><?php echo esc_html( $element[ 'title' ] ) ?></h3>
            <div class="eael-element__wrap">
	            <?php foreach ( $element[ 'extensions' ] as $item ): ?>
		            <?php
		            $promotion_class = ! empty( $item['promotion'] ) ? sprintf( "eael-promotion-is%s", $item['promotion'] ) : "";
		            ?>
                    <div class="eael-element__item <?php esc_attr_e($promotion_class); ?>">
                        <div class="element__content">
                            <h4><?php echo esc_html( $item[ 'title' ] ); ?></h4>
                            <div class="element__options">

	                            <?php
	                            if ( !empty( $item[ 'setting' ] ) ):
		                            $link = !empty( $item[ 'setting' ][ 'link' ] ) ? $item[ 'setting' ][ 'link' ] : '#';
		                            $id = !empty( $item[ 'setting' ][ 'id' ] ) ? $item[ 'setting' ][ 'id' ] : '';
		                            ?>
                                    <a href="<?php echo esc_url( $link ); ?>" id="<?php echo esc_attr( $id ); ?>" class="element__icon">
                                        <i class="ea-admin-icon icon-gear"></i>
                                        <span class="tooltip-text"><?php esc_html_e( 'Setting', 'essential-addons-for-elementor-lite' ); ?></span>
                                    </a>
	                            <?php endif; ?>
                                <a target="_blank" href="<?php echo esc_url( $item[ 'doc_link' ] ); ?>" class="element__icon">
                                    <i class="ea-admin-icon icon-doc"></i>
                                    <span class="tooltip-text"><?php esc_html_e( 'Documentation', 'essential-addons-for-elementor-lite' ); ?></span>
                                </a>
                                <a target="_blank" href="<?php echo esc_url( $item[ 'demo_link' ] ); ?>" class="element__icon">
                                    <i class="ea-admin-icon icon-monitor"></i>
                                    <span class="tooltip-text"><?php esc_html_e( 'Demo', 'essential-addons-for-elementor-lite' ); ?></span>
                                </a>
                                <label class="eael-switch">
									<?php
									$disabled = !empty( $item[ 'is_pro' ] ) && !$this->pro_enabled ? 'disabled' : '';
									$status = isset($item['is_pro']) && !$this->pro_enabled ? 'disabled' : checked( 1, $this->get_settings($item['key']), false );
									printf( '<input class="eael-widget-item eael-extensions-list" id="%1$s" name="%1$s"
                                           type="checkbox" %2$s>', $item[ 'key' ], $status );
									?>

                                    <span class="switch__box <?php echo esc_attr( $disabled ); ?>"></span>
                                </label>
	                            <?php printf( "%s", ( $disabled == 'disabled') ? '<i class="ea-admin-icon icon-lock-alt eael-lock-style"></i>' : '' ); ?>
                            </div>
                        </div>
                    </div>
				<?php endforeach; ?>
            </div>
        </div>
	<?php endforeach; ?>
    <div class="border__line mt30"><span></span></div>
    <div class="eael__flex justify__end mt30">
        <button type="button" class="eael-button button__themeColor js-eael-settings-save"><?php _e('Save Settings','essential-addons-for-elementor-lite'); ?></button>
    </div>
</div>

