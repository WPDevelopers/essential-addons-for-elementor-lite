<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Image_Size;
use \Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use \Elementor\Plugin;
use \Elementor\Utils;
use \Elementor\Widget_Base;
use \Essential_Addons_Elementor\Classes\Helper;
use ParagonIE\Sodium\Core\Curve25519\Ge\P2;

class NFT_Gallery extends Widget_Base
{
    public function get_name()
    {
        return 'eael-nft-gallery';
    }

    public function get_title()
    {
        return esc_html__('NFT Gallery', 'essential-addons-for-elementor-lite');
    }

    public function get_icon()
    {
        return 'eaicon-info-box';
    }

    public function get_categories()
    {
        return ['essential-addons-elementor'];
    }

    public function get_keywords()
    {
        return [
            'gallery',
            'nft gallery',
            'ea nft gallery',
            'image gallery',
            'photo gallery',
            'portfolio',
            'ea portfolio',
            'image grid',
            'photo grid',
            'responsive gallery',
            'ea',
            'essential addons'
        ];
    }

    public function get_custom_help_url()
    {
        return 'https://essential-addons.com/elementor/docs/nft-gallery/';
    }

    protected function register_controls()
    {

        /**
         * NFT Settings
         */
        $this->start_controls_section(
            'eael_section_nft_gallery_general_settings',
            [
                'label' => esc_html__('General', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_nft_gallery_sources',
            [
                'label' => __('Source', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'opensea',
                'options' => [
                    'opensea' => __('OpenSea', 'essential-addons-for-elementor-lite'),
                ],
            ]
        );

        $this->add_control(
            'eael_nft_gallery_source_key',
            [
                'label' => __('APi Key', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'description' => sprintf(__('<a href="https://docs.opensea.io/reference/request-an-api-key" class="eael-btn" target="_blank">%s</a>',
                    'essential-addons-for-elementor-lite'), 'Get API Key'),
            ]
        );

        $this->add_control(
            'eael_nft_gallery_opensea_type',
            [
                'label'   => esc_html__('Type', 'essential-addons-for-elementor-lite'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'items',
                'options' => [
                    'items'    => esc_html__('Items', 'essential-addons-for-elementor-lite'),
                    'collection' => esc_html__('Collections', 'essential-addons-for-elementor-lite'),
                ],
                'condition' => [
                    'eael_nft_gallery_sources' => 'opensea'
                ],
            ]
        );

        $this->add_control('eael_nft_gallery_opensea_filterby', [
            'label' => esc_html__('Filter By', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::SELECT,
            'default' => '',
            'options' => [
                '' => __( 'None', 'essential-addons-for-elementor-lite' ),
                'slug' => __( 'Collection Slug', 'essential-addons-for-elementor-lite' ),
                'wallet' => __( 'Wallet Address', 'essential-addons-for-elementor-lite' ),
            ],
            'condition' => [
                'eael_nft_gallery_opensea_type!' => 'collection'
            ],
        ]);

        $this->add_control(
            'eael_nft_gallery_opensea_filterby_slug',
            [
                'label' => __('Collection Slug', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
			    'placeholder'   => 'cryptopunks',
                'condition' => [
                    'eael_nft_gallery_opensea_filterby' => 'slug'
                ],
            ]
        );

        $this->add_control(
            'eael_nft_gallery_opensea_filterby_wallet',
            [
                'label' => __('Wallet Address', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
			    'placeholder'   => '0x1......',
                'condition' => [
                    'eael_nft_gallery_opensea_filterby' => 'wallet'
                ],
            ]
        );

        $this->add_control(
            'eael_nft_gallery_opensea_filterby_collections_wallet',
            [
                'label' => __('Wallet Address', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'eael_nft_gallery_opensea_type' => 'collection'
                ],
            ]
        );

        $this->add_control('eael_nft_gallery_opensea_order', [
            'label' => __('Order', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'asc' => 'Ascending',
                'desc' => 'Descending',
            ],
            'default' => 'desc',
            'condition' => [
                'eael_nft_gallery_opensea_type!' => 'collection'
            ],
        ]);

        $this->add_control(
            'eael_nft_gallery_opensea_posts_per_page',
            [
                'label' => __('Posts Per Page', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::NUMBER,
                'default' => '6',
                'min' => '1',
            ]
        );

        $this->end_controls_section();

        /**
         * NFT Settings
         */
        $this->start_controls_section(
            'eael_section_nft_gallery_settings',
            [
                'label' => esc_html__('Layout', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_nft_gallery_items_layout',
            [
                'label'   => esc_html__('Layout Type', 'essential-addons-for-elementor-lite'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'grid',
                'options' => [
                    'grid'    => esc_html__('Grid', 'essential-addons-for-elementor-lite'),
                    'list' => esc_html__('List', 'essential-addons-for-elementor-lite'),
                ]
            ]
        );

        $this->add_control(
            'eael_nft_gallery_style_preset',
            [
                'label'   => esc_html__('Style Preset', 'essential-addons-for-elementor-lite'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'preset-1',
                'options' => [
                    'preset-1' => esc_html__('Preset 1', 'essential-addons-for-elementor-lite'),
                    'preset-2' => esc_html__('Preset 2', 'essential-addons-for-elementor-lite'),
                    'preset-3' => esc_html__('Preset 3', 'essential-addons-for-elementor-lite'),
                ],
                'condition' => [
                    'eael_nft_gallery_items_layout' => 'grid'
                ],
            ]
        );

        $this->add_control(
            'eael_nft_gallery_list_style_preset',
            [
                'label'   => esc_html__('Style Preset', 'essential-addons-for-elementor-lite'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'preset-2',
                'options' => [
                    'preset-2' => esc_html__('Preset 1', 'essential-addons-for-elementor-lite'),
                ],
                'condition' => [
                    'eael_nft_gallery_items_layout' => 'list'
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_nft_gallery_column',
            [
                'label'        => esc_html__('Columns', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SELECT,
                'default'      => '3',
                'options'      => [
                    '1' => esc_html__('1', 'essential-addons-for-elementor-lite'),
                    '2' => esc_html__('2', 'essential-addons-for-elementor-lite'),
                    '3' => esc_html__('3', 'essential-addons-for-elementor-lite'),
                    '4' => esc_html__('4', 'essential-addons-for-elementor-lite'),
                    '5' => esc_html__('5', 'essential-addons-for-elementor-lite'),
                    '6' => esc_html__('6', 'essential-addons-for-elementor-lite'),
                ],
                'condition' => [
                    'eael_nft_gallery_items_layout' => 'grid'
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-grid' => 'grid-template-columns: repeat( {{VALUE}}, 1fr);',
                ]
            ]
        );

        $this->add_responsive_control(
            'eael_nft_list_gallery_column',
            [
                'label'        => esc_html__('Columns', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SELECT,
                'default'      => '1',
                'options'      => [
                    '1' => esc_html__('1', 'essential-addons-for-elementor-lite'),
                    '2' => esc_html__('2', 'essential-addons-for-elementor-lite'),
                    '3' => esc_html__('3', 'essential-addons-for-elementor-lite'),
                    '4' => esc_html__('4', 'essential-addons-for-elementor-lite'),
                    '5' => esc_html__('5', 'essential-addons-for-elementor-lite'),
                    '6' => esc_html__('6', 'essential-addons-for-elementor-lite'),
                ],
                'condition' => [
                    'eael_nft_gallery_items_layout' => 'list'
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-list' => 'grid-template-columns: repeat( {{VALUE}}, 1fr);'
                ]
            ]
        );

        $this->add_control(
			'eael_nft_gallery_show_image',
			[
				'label' => __( 'Show NFT Image', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off' => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
			]
		);

        $this->add_control(
			'eael_nft_gallery_show_title',
			[
				'label' => __( 'Show Title', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off' => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
			]
		);

        $this->add_control(
			'eael_nft_gallery_show_owner',
			[
				'label' => __( 'Show Current Owner', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off' => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
			]
		);

        $this->add_control(
			'eael_nft_gallery_show_creator',
			[
				'label' => __( 'Show Creator', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off' => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
			]
		);

        $this->add_control(
			'eael_nft_gallery_show_button',
			[
				'label' => __( 'Show Button', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off' => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
			]
		);

        $this->end_controls_section();

        /**
         * NFT Content
         */
        $this->start_controls_section(
            'eael_section_nft_gallery_content',
            [
                'label' => esc_html__('Content', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_section_nft_gallery_content_label',
            [
                'label' => esc_html__('Label Text'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control('eael_nft_gallery_content_owned_by_label', [
            'label' => esc_html__('Owned By Text', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::TEXT,
            'label_block' => false,
            'default' => esc_html__('Owned By', 'essential-addons-for-elementor-lite'),
        ]);

        $this->add_control('eael_nft_gallery_content_created_by_label', [
            'label' => esc_html__('Created By Text', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::TEXT,
            'label_block' => false,
            'default' => esc_html__('Created By', 'essential-addons-for-elementor-lite'),
        ]);
        
        $this->add_control('eael_nft_gallery_content_view_details_label', [
            'label' => esc_html__('View Details Text', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::TEXT,
            'label_block' => false,
            'default' => esc_html__('View Details', 'essential-addons-for-elementor-lite'),
        ]);

        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Tab Style (NFT Gallery Style)
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_nft_gallery_style_settings',
            [
                'label' => esc_html__('NFT Gallery', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // #ToDo Section Layout Styles: Column Gap, Row Gap
        // #ToDo Section Item Box Styles: Background (Normal, Hover: Classic, Gradient), Overlay Color, Border and Shadow, Padding
        // #ToDo Section Image Styles: Height, Width, Margin, Border Radius
        // #ToDo Section Title: Typography, Color, Margin
        // #ToDo Section Creator/Owner: Show Name, Show Image, Typography, Label Color, Link Color, Image Height, Image Width, Image Border Radius, Margin
        // #ToDo Section Price Style: Typography, Color, Margin
        // #ToDo Section Button Style: Typography, Text Color, Background Color, Hover Text Color, Hover Background Color, Margin, Padding
        // #ToDo Section Border & Shadow Style: Border (including box shadow) (Normal and Hover), Border Radius Transition on hover


        $this->end_controls_section();
    }

    public function print_nft_gallery( $opensea_items )
    {
        $settings = $this->get_settings();
        ob_start();

        $nft_gallery = [];
        $opensea_item_formatted = [];

        $nft_gallery['source'] = ! empty( $settings['eael_nft_gallery_sources'] ) ? esc_html( $settings['eael_nft_gallery_sources'] ) : 'opensea';
        $nft_gallery['layout'] = !empty($settings['eael_nft_gallery_items_layout']) ? $settings['eael_nft_gallery_items_layout'] : 'grid';
        $nft_gallery['preset'] = !empty($settings['eael_nft_gallery_style_preset']) ? $settings['eael_nft_gallery_style_preset'] : 'preset-2';
        $nft_gallery['preset'] = 'list' === $nft_gallery['layout'] && !empty($settings['eael_nft_gallery_list_style_preset']) ? $settings['eael_nft_gallery_list_style_preset'] : $nft_gallery['preset'];
        $nft_gallery['owned_by_label'] = ! empty( $settings['eael_nft_gallery_content_owned_by_label'] ) ? $settings['eael_nft_gallery_content_owned_by_label'] : __('Owned By', 'essential-addons-for-elementor-lite');
        $nft_gallery['created_by_label'] = ! empty( $settings['eael_nft_gallery_content_created_by_label'] ) ? $settings['eael_nft_gallery_content_created_by_label'] : __('Owned By', 'essential-addons-for-elementor-lite');
        $nft_gallery['view_details_text'] =  ! empty( $settings['eael_nft_gallery_content_view_details_label'] ) ? $settings['eael_nft_gallery_content_view_details_label'] : __('View Details', 'essential-addons-for-elementor-lite');       
        
        $nft_gallery['api_url'] = '';
        $nft_gallery['api_url'] = 'opensea' === $nft_gallery['source'] ? 'https://opensea.io/' : ''; 

        $this->add_render_attribute(
            'eael-nft-gallery-items',
            [
                'id' => 'eael-nft-gallery-' . esc_attr($this->get_id()),
                'class' => [
                    'eael-nft-gallery-items',
                    'eael-nft-' . esc_attr($nft_gallery['layout']),
                    esc_attr($nft_gallery['preset']),
                ],
            ]
        );
?>
        <div class="eael-nft-gallery-wrapper">
            <div <?php echo $this->get_render_attribute_string('eael-nft-gallery-items'); ?> >
                <?php if ( is_array( $opensea_items ) && count( $opensea_items ) ) : ?>
                    <?php foreach ($opensea_items as $item) : ?>
                        <?php
                        $item_formatted['thumbnail'] = ! empty( $item->image_url ) ? $item->image_url : '';
                        $item_formatted['title'] = ! empty( $item->name ) ? $item->name : '';
                        
                        if( ! empty( $item->creator ) ){
                            $item_formatted['creator_thumbnail'] = ! empty( $item->creator->profile_img_url ) ? $item->creator->profile_img_url : '';
                            $item_formatted['created_by_link'] = ! empty( $item->creator->address ) ? esc_url( $nft_gallery['api_url'] . '/' . $item->creator->address ) : '#';
                            $item_formatted['created_by_link_text'] = ! empty( $item->creator->user ) && ! empty( $item->creator->user->username ) ? esc_html( $item->creator->user->username ) : '';
                        }

                        if( ! empty( $item->owner ) ){
                            $item_formatted['owner_thumbnail'] = ! empty( $item->owner->profile_img_url ) ? $item->owner->profile_img_url : '';
                            $item_formatted['owned_by_link'] = ! empty( $item->owner->address ) ? esc_url( $nft_gallery['api_url'] . '/' . $item->owner->address ) : '#';
                            $item_formatted['owned_by_link_text'] = ! empty( $item->owner->user ) && ! empty( $item->owner->user->username ) ? esc_html( $item->owner->user->username ) : '';
                        }

                        $item_formatted['view_details_link'] = ! empty( $item->permalink ) ? $item->permalink : '#';
                        
                        ?>
                        <div class="eael-nft-item">
                            <!-- Thumbnail -->
                            <div class="eael-nft-thumbnail">
                                <?php
                                if (!empty($item_formatted['thumbnail'])) {
                                    printf('<img src="%s" alt="%s">', esc_attr($item_formatted['thumbnail']), esc_attr('NFT Gallery'));
                                } else {
                                    ?>
                                    <svg viewBox="0 0 320 330" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 0H320V330H0V0Z" fill="url(#paint0_linear_16_5)"></path><g opacity="0.15" clip-path="url(#clip0_16_5)"><path d="M136.04 172.213C129.37 172.213 123.436 167.942 121.282 161.584L121.136 161.105C120.628 159.421 120.415 158.005 120.415 156.588V128.178L110.306 161.921C109.006 166.884 111.969 172.029 116.94 173.4L181.371 190.655C182.175 190.864 182.979 190.964 183.771 190.964C187.921 190.964 191.713 188.209 192.775 184.15L196.529 172.213H136.04Z" fill="white"></path><path d="M147.499 128.462C152.095 128.462 155.832 124.724 155.832 120.128C155.832 115.532 152.095 111.795 147.499 111.795C142.903 111.795 139.165 115.532 139.165 120.128C139.165 124.724 142.903 128.462 147.499 128.462Z" fill="white"></path><path opacity="0.5" d="M199.583 99.2943H137.081C131.34 99.2943 126.665 103.97 126.665 109.712V155.546C126.665 161.288 131.34 165.963 137.081 165.963H199.583C205.325 165.963 210 161.288 210 155.546V109.712C210 103.97 205.325 99.2943 199.583 99.2943ZM137.081 107.628H199.583C200.733 107.628 201.666 108.561 201.666 109.712V139.292L188.504 123.932C187.108 122.295 185.087 121.42 182.916 121.37C180.758 121.383 178.733 122.341 177.349 124L161.874 142.575L156.832 137.545C153.982 134.696 149.344 134.696 146.498 137.545L134.998 149.041V109.712C134.998 108.561 135.931 107.628 137.081 107.628Z" fill="white"></path></g><path d="M60.746 217.336H62.798V229H60.746V217.336ZM64.9331 220.036H66.9311V229H64.9331V220.036ZM70.7111 219.838C71.2271 219.838 71.7011 219.91 72.1331 220.054C72.5771 220.198 72.9551 220.42 73.2671 220.72C73.5911 221.008 73.8371 221.368 74.0051 221.8C74.1851 222.232 74.2751 222.742 74.2751 223.33V229H72.2771V223.726C72.2771 222.994 72.0971 222.454 71.7371 222.106C71.3891 221.746 70.8371 221.566 70.0811 221.566C69.5051 221.566 68.9771 221.692 68.4971 221.944C68.0291 222.184 67.6451 222.502 67.3451 222.898C67.0451 223.282 66.8711 223.708 66.8231 224.176L66.8051 223.204C66.8651 222.76 66.9971 222.34 67.2011 221.944C67.4051 221.536 67.6751 221.176 68.0111 220.864C68.3471 220.54 68.7431 220.288 69.1991 220.108C69.6551 219.928 70.1591 219.838 70.7111 219.838ZM77.8571 219.838C78.3851 219.838 78.8651 219.91 79.2971 220.054C79.7291 220.198 80.1011 220.42 80.4131 220.72C80.7371 221.008 80.9891 221.368 81.1691 221.8C81.3491 222.232 81.4391 222.742 81.4391 223.33V229H79.4231V223.726C79.4231 222.994 79.2431 222.454 78.8831 222.106C78.5351 221.746 77.9831 221.566 77.2271 221.566C76.6511 221.566 76.1231 221.692 75.6431 221.944C75.1751 222.184 74.7911 222.502 74.4911 222.898C74.2031 223.282 74.0351 223.708 73.9871 224.176L73.9511 223.168C74.0111 222.736 74.1491 222.322 74.3651 221.926C74.5811 221.518 74.8571 221.158 75.1931 220.846C75.5291 220.534 75.9191 220.288 76.3631 220.108C76.8191 219.928 77.3171 219.838 77.8571 219.838ZM86.9122 229.198C86.0962 229.198 85.3462 229 84.6622 228.604C83.9902 228.208 83.4502 227.662 83.0422 226.966C82.6462 226.258 82.4482 225.448 82.4482 224.536C82.4482 223.6 82.6522 222.784 83.0602 222.088C83.4682 221.38 84.0202 220.828 84.7162 220.432C85.4122 220.036 86.1922 219.838 87.0562 219.838C88.0162 219.838 88.7782 220.048 89.3422 220.468C89.9062 220.888 90.3082 221.452 90.5482 222.16C90.7882 222.868 90.9082 223.66 90.9082 224.536C90.9082 225.028 90.8362 225.55 90.6922 226.102C90.5482 226.642 90.3202 227.146 90.0082 227.614C89.7082 228.082 89.3002 228.466 88.7842 228.766C88.2802 229.054 87.6562 229.198 86.9122 229.198ZM87.5242 227.542C88.1962 227.542 88.7662 227.416 89.2342 227.164C89.7142 226.9 90.0742 226.54 90.3142 226.084C90.5662 225.628 90.6922 225.112 90.6922 224.536C90.6922 223.9 90.5662 223.36 90.3142 222.916C90.0622 222.46 89.7022 222.112 89.2342 221.872C88.7662 221.62 88.1962 221.494 87.5242 221.494C86.5642 221.494 85.8202 221.776 85.2922 222.34C84.7642 222.904 84.5002 223.636 84.5002 224.536C84.5002 225.124 84.6262 225.646 84.8782 226.102C85.1422 226.558 85.5022 226.912 85.9582 227.164C86.4142 227.416 86.9362 227.542 87.5242 227.542ZM90.6922 220.036H92.7082V229H90.8362C90.8362 229 90.8242 228.886 90.8002 228.658C90.7762 228.43 90.7522 228.154 90.7282 227.83C90.7042 227.494 90.6922 227.176 90.6922 226.876V220.036ZM98.1805 227.614C97.3405 227.614 96.5785 227.464 95.8945 227.164C95.2225 226.864 94.6885 226.426 94.2925 225.85C93.9085 225.274 93.7165 224.584 93.7165 223.78C93.7165 222.976 93.9025 222.28 94.2745 221.692C94.6585 221.104 95.1865 220.648 95.8585 220.324C96.5425 220 97.3165 219.838 98.1805 219.838C98.4325 219.838 98.6725 219.856 98.9005 219.892C99.1405 219.928 99.3685 219.976 99.5845 220.036L103.779 220.054V221.728C103.203 221.74 102.621 221.662 102.033 221.494C101.457 221.314 100.947 221.128 100.503 220.936L100.449 220.828C100.857 221.032 101.223 221.284 101.547 221.584C101.883 221.872 102.147 222.202 102.339 222.574C102.531 222.946 102.627 223.366 102.627 223.834C102.627 224.626 102.435 225.304 102.051 225.868C101.679 226.432 101.157 226.864 100.485 227.164C99.8245 227.464 99.0565 227.614 98.1805 227.614ZM101.061 232.654V232.24C101.061 231.652 100.887 231.238 100.539 230.998C100.191 230.758 99.7045 230.638 99.0805 230.638H96.6505C96.1705 230.638 95.7565 230.596 95.4085 230.512C95.0725 230.44 94.8025 230.326 94.5985 230.17C94.3945 230.026 94.2445 229.852 94.1485 229.648C94.0525 229.456 94.0045 229.24 94.0045 229C94.0045 228.52 94.1485 228.16 94.4365 227.92C94.7365 227.68 95.1265 227.524 95.6065 227.452C96.0985 227.38 96.6145 227.368 97.1545 227.416L98.1805 227.614C97.4725 227.638 96.9325 227.704 96.5605 227.812C96.2005 227.908 96.0205 228.106 96.0205 228.406C96.0205 228.586 96.0925 228.73 96.2365 228.838C96.3805 228.946 96.5845 229 96.8485 229H99.4045C100.137 229 100.779 229.084 101.331 229.252C101.883 229.432 102.309 229.726 102.609 230.134C102.921 230.554 103.077 231.124 103.077 231.844V232.654H101.061ZM98.1805 226.048C98.6605 226.048 99.0865 225.958 99.4585 225.778C99.8305 225.598 100.125 225.34 100.341 225.004C100.557 224.668 100.665 224.26 100.665 223.78C100.665 223.3 100.557 222.886 100.341 222.538C100.125 222.19 99.8305 221.926 99.4585 221.746C99.0865 221.566 98.6605 221.476 98.1805 221.476C97.7125 221.476 97.2865 221.566 96.9025 221.746C96.5305 221.926 96.2365 222.19 96.0205 222.538C95.8045 222.874 95.6965 223.288 95.6965 223.78C95.6965 224.26 95.8045 224.668 96.0205 225.004C96.2365 225.34 96.5305 225.598 96.9025 225.778C97.2745 225.958 97.7005 226.048 98.1805 226.048ZM112.044 226.048H113.97C113.874 226.66 113.634 227.206 113.25 227.686C112.878 228.154 112.368 228.526 111.72 228.802C111.072 229.066 110.298 229.198 109.398 229.198C108.378 229.198 107.466 229.012 106.662 228.64C105.858 228.256 105.228 227.716 104.772 227.02C104.316 226.324 104.088 225.496 104.088 224.536C104.088 223.588 104.31 222.76 104.754 222.052C105.198 221.344 105.81 220.798 106.59 220.414C107.382 220.03 108.294 219.838 109.326 219.838C110.394 219.838 111.282 220.03 111.99 220.414C112.71 220.786 113.244 221.35 113.592 222.106C113.94 222.85 114.084 223.792 114.024 224.932H106.122C106.182 225.436 106.344 225.892 106.608 226.3C106.884 226.696 107.256 227.008 107.724 227.236C108.192 227.452 108.738 227.56 109.362 227.56C110.058 227.56 110.64 227.422 111.108 227.146C111.588 226.87 111.9 226.504 112.044 226.048ZM109.272 221.458C108.456 221.458 107.784 221.662 107.256 222.07C106.728 222.466 106.386 222.976 106.23 223.6H112.008C111.96 222.928 111.69 222.406 111.198 222.034C110.706 221.65 110.064 221.458 109.272 221.458ZM129.585 226.876L128.973 227.11V217.336H131.007V229H128.973L120.225 219.514L120.855 219.28V229H118.803V217.336H120.855L129.585 226.876ZM137.576 229.198C136.58 229.198 135.686 229.018 134.894 228.658C134.102 228.298 133.478 227.77 133.022 227.074C132.566 226.378 132.338 225.532 132.338 224.536C132.338 223.552 132.566 222.712 133.022 222.016C133.478 221.308 134.102 220.768 134.894 220.396C135.686 220.024 136.58 219.838 137.576 219.838C138.572 219.838 139.46 220.024 140.24 220.396C141.02 220.768 141.632 221.308 142.076 222.016C142.532 222.712 142.76 223.552 142.76 224.536C142.76 225.532 142.532 226.378 142.076 227.074C141.632 227.77 141.02 228.298 140.24 228.658C139.46 229.018 138.572 229.198 137.576 229.198ZM137.576 227.56C138.152 227.56 138.68 227.446 139.16 227.218C139.64 226.99 140.018 226.648 140.294 226.192C140.582 225.736 140.726 225.184 140.726 224.536C140.726 223.888 140.582 223.336 140.294 222.88C140.018 222.424 139.64 222.076 139.16 221.836C138.692 221.584 138.164 221.458 137.576 221.458C136.988 221.458 136.454 221.578 135.974 221.818C135.494 222.058 135.104 222.406 134.804 222.862C134.516 223.318 134.372 223.876 134.372 224.536C134.372 225.184 134.516 225.736 134.804 226.192C135.092 226.648 135.476 226.99 135.956 227.218C136.448 227.446 136.988 227.56 137.576 227.56ZM143.058 220.036H149.394V221.674H143.058V220.036ZM145.218 217.624H147.234V229H145.218V217.624ZM155.582 226.264V224.518H162.53V226.264H155.582ZM152.882 229L157.976 217.336H160.172L165.302 229H163.088L158.624 218.47H159.524L155.078 229H152.882ZM169.668 227.578H168.966L172.062 220.036H174.24L170.28 229H168.372L164.466 220.036H166.662L169.668 227.578ZM178.607 229.198C177.791 229.198 177.041 229 176.357 228.604C175.685 228.208 175.145 227.662 174.737 226.966C174.341 226.258 174.143 225.448 174.143 224.536C174.143 223.6 174.347 222.784 174.755 222.088C175.163 221.38 175.715 220.828 176.411 220.432C177.107 220.036 177.887 219.838 178.751 219.838C179.711 219.838 180.473 220.048 181.037 220.468C181.601 220.888 182.003 221.452 182.243 222.16C182.483 222.868 182.603 223.66 182.603 224.536C182.603 225.028 182.531 225.55 182.387 226.102C182.243 226.642 182.015 227.146 181.703 227.614C181.403 228.082 180.995 228.466 180.479 228.766C179.975 229.054 179.351 229.198 178.607 229.198ZM179.219 227.542C179.891 227.542 180.461 227.416 180.929 227.164C181.409 226.9 181.769 226.54 182.009 226.084C182.261 225.628 182.387 225.112 182.387 224.536C182.387 223.9 182.261 223.36 182.009 222.916C181.757 222.46 181.397 222.112 180.929 221.872C180.461 221.62 179.891 221.494 179.219 221.494C178.259 221.494 177.515 221.776 176.987 222.34C176.459 222.904 176.195 223.636 176.195 224.536C176.195 225.124 176.321 225.646 176.573 226.102C176.837 226.558 177.197 226.912 177.653 227.164C178.109 227.416 178.631 227.542 179.219 227.542ZM182.387 220.036H184.403V229H182.531C182.531 229 182.519 228.886 182.495 228.658C182.471 228.43 182.447 228.154 182.423 227.83C182.399 227.494 182.387 227.176 182.387 226.876V220.036ZM188.219 216.238V218.164H185.825V216.238H188.219ZM186.005 220.036H188.021V229H186.005V220.036ZM189.874 216.436H191.872V229H189.874V216.436ZM197.576 229.198C196.76 229.198 196.01 229 195.326 228.604C194.654 228.208 194.114 227.662 193.706 226.966C193.31 226.258 193.112 225.448 193.112 224.536C193.112 223.6 193.316 222.784 193.724 222.088C194.132 221.38 194.684 220.828 195.38 220.432C196.076 220.036 196.856 219.838 197.72 219.838C198.68 219.838 199.442 220.048 200.006 220.468C200.57 220.888 200.972 221.452 201.212 222.16C201.452 222.868 201.572 223.66 201.572 224.536C201.572 225.028 201.5 225.55 201.356 226.102C201.212 226.642 200.984 227.146 200.672 227.614C200.372 228.082 199.964 228.466 199.448 228.766C198.944 229.054 198.32 229.198 197.576 229.198ZM198.188 227.542C198.86 227.542 199.43 227.416 199.898 227.164C200.378 226.9 200.738 226.54 200.978 226.084C201.23 225.628 201.356 225.112 201.356 224.536C201.356 223.9 201.23 223.36 200.978 222.916C200.726 222.46 200.366 222.112 199.898 221.872C199.43 221.62 198.86 221.494 198.188 221.494C197.228 221.494 196.484 221.776 195.956 222.34C195.428 222.904 195.164 223.636 195.164 224.536C195.164 225.124 195.29 225.646 195.542 226.102C195.806 226.558 196.166 226.912 196.622 227.164C197.078 227.416 197.6 227.542 198.188 227.542ZM201.356 220.036H203.372V229H201.5C201.5 229 201.488 228.886 201.464 228.658C201.44 228.43 201.416 228.154 201.392 227.83C201.368 227.494 201.356 227.176 201.356 226.876V220.036ZM210.774 229.198C210.03 229.198 209.4 229.054 208.884 228.766C208.38 228.466 207.972 228.082 207.66 227.614C207.36 227.146 207.138 226.642 206.994 226.102C206.862 225.55 206.796 225.028 206.796 224.536C206.796 223.876 206.862 223.264 206.994 222.7C207.126 222.136 207.342 221.644 207.642 221.224C207.954 220.792 208.362 220.456 208.866 220.216C209.382 219.964 210.018 219.838 210.774 219.838C211.638 219.838 212.4 220.036 213.06 220.432C213.732 220.828 214.26 221.38 214.644 222.088C215.04 222.784 215.238 223.6 215.238 224.536C215.238 225.448 215.04 226.258 214.644 226.966C214.248 227.662 213.714 228.208 213.042 228.604C212.37 229 211.614 229.198 210.774 229.198ZM210.162 227.542C210.774 227.542 211.302 227.416 211.746 227.164C212.202 226.912 212.556 226.558 212.808 226.102C213.06 225.646 213.186 225.124 213.186 224.536C213.186 223.636 212.922 222.904 212.394 222.34C211.866 221.764 211.122 221.476 210.162 221.476C209.502 221.476 208.932 221.602 208.452 221.854C207.984 222.106 207.624 222.46 207.372 222.916C207.132 223.36 207.012 223.9 207.012 224.536C207.012 225.112 207.132 225.628 207.372 226.084C207.624 226.54 207.984 226.9 208.452 227.164C208.92 227.416 209.49 227.542 210.162 227.542ZM207.012 216.436V226.876C207.012 227.2 206.994 227.566 206.958 227.974C206.922 228.382 206.886 228.724 206.85 229H204.996V216.436H207.012ZM216.454 216.436H218.452V229H216.454V216.436ZM227.648 226.048H229.574C229.478 226.66 229.238 227.206 228.854 227.686C228.482 228.154 227.972 228.526 227.324 228.802C226.676 229.066 225.902 229.198 225.002 229.198C223.982 229.198 223.07 229.012 222.266 228.64C221.462 228.256 220.832 227.716 220.376 227.02C219.92 226.324 219.692 225.496 219.692 224.536C219.692 223.588 219.914 222.76 220.358 222.052C220.802 221.344 221.414 220.798 222.194 220.414C222.986 220.03 223.898 219.838 224.93 219.838C225.998 219.838 226.886 220.03 227.594 220.414C228.314 220.786 228.848 221.35 229.196 222.106C229.544 222.85 229.688 223.792 229.628 224.932H221.726C221.786 225.436 221.948 225.892 222.212 226.3C222.488 226.696 222.86 227.008 223.328 227.236C223.796 227.452 224.342 227.56 224.966 227.56C225.662 227.56 226.244 227.422 226.712 227.146C227.192 226.87 227.504 226.504 227.648 226.048ZM224.876 221.458C224.06 221.458 223.388 221.662 222.86 222.07C222.332 222.466 221.99 222.976 221.834 223.6H227.612C227.564 222.928 227.294 222.406 226.802 222.034C226.31 221.65 225.668 221.458 224.876 221.458ZM244.936 217.336L240.184 225.202V229H238.132V225.202L233.38 217.336H235.738L239.752 224.212H238.636L242.596 217.336H244.936ZM251.486 226.048H253.412C253.316 226.66 253.076 227.206 252.692 227.686C252.32 228.154 251.81 228.526 251.162 228.802C250.514 229.066 249.74 229.198 248.84 229.198C247.82 229.198 246.908 229.012 246.104 228.64C245.3 228.256 244.67 227.716 244.214 227.02C243.758 226.324 243.53 225.496 243.53 224.536C243.53 223.588 243.752 222.76 244.196 222.052C244.64 221.344 245.252 220.798 246.032 220.414C246.824 220.03 247.736 219.838 248.768 219.838C249.836 219.838 250.724 220.03 251.432 220.414C252.152 220.786 252.686 221.35 253.034 222.106C253.382 222.85 253.526 223.792 253.466 224.932H245.564C245.624 225.436 245.786 225.892 246.05 226.3C246.326 226.696 246.698 227.008 247.166 227.236C247.634 227.452 248.18 227.56 248.804 227.56C249.5 227.56 250.082 227.422 250.55 227.146C251.03 226.87 251.342 226.504 251.486 226.048ZM248.714 221.458C247.898 221.458 247.226 221.662 246.698 222.07C246.17 222.466 245.828 222.976 245.672 223.6H251.45C251.402 222.928 251.132 222.406 250.64 222.034C250.148 221.65 249.506 221.458 248.714 221.458ZM253.757 220.036H260.093V221.674H253.757V220.036ZM255.917 217.624H257.933V229H255.917V217.624Z" fill="white"></path><defs><linearGradient id="paint0_linear_16_5" x1="326.4" y1="1.29268e-05" x2="-63.6095" y2="214.16" gradientUnits="userSpaceOnUse"><stop stop-color="#275EFF"></stop><stop offset="1" stop-color="#A913FF"></stop></linearGradient><clipPath id="clip0_16_5"><rect width="100" height="100" fill="white" transform="translate(110 95)"></rect></clipPath></defs></svg>
                                    <?php  
                                }
                                ?>
                            </div>

                            <div class="eael-nft-main-content">
                                <!-- Content  -->
                                <div class="eael-nft-content">
                                    <!-- Title  -->
                                    <h3 class="eael-nft-title"><?php print_r('%s', esc_html( $item_formatted['title'] ) ); ?></h3>

                                    <!-- Creator -->
                                    <div class="eael-nft-creator-wrapper">
                                        <div class="eael-nft-creator-img">
                                            <?php
                                            if (!empty($item_formatted['creator_thumbnail'])) {
                                                printf('<img src="%s" alt="%s">', esc_attr($item_formatted['creator_thumbnail']), esc_attr__('EA NFT Creator Thumbnail', 'essential-addons-for-elementor-lite'));
                                            } else {
                                                // default creator svg
                                            }
                                            ?>
                                        </div>
                                        <div class="eael-nft-created-by">
                                            <span><?php printf('%s', esc_html( $nft_gallery['created_by_label'] ) ); ?> </span>
                                            <?php printf('<a target="_blank" href="%s">%s</a>', esc_attr( $item_formatted['created_by_link'] ), esc_html( $item_formatted['created_by_link_text'] ) ); ?>
                                        </div>
                                    </div>

                                    <!-- Owner -->
                                    <div class="eael-nft-owner-wrapper">
                                        <div class="eael-nft-owner-img">
                                            <?php
                                            if (!empty($item_formatted['owner_thumbnail'])) {
                                                printf('<img src="%s" alt="%s">', esc_attr($item_formatted['owner_thumbnail']), esc_attr__('EA NFT Owner Thumbnail', 'essential-addons-for-elementor-lite'));
                                            } else {
                                                // default owner svg
                                            }
                                            ?>
                                        </div>
                                        <div class="eael-nft-owned-by">
                                            <span><?php printf('%s', esc_html( $nft_gallery['owned_by_label'] ) ); ?> </span>
                                            <?php printf('<a target="_blank" href="%s">%s</a>', esc_attr( $item_formatted['owned_by_link'] ), esc_html( $item_formatted['owned_by_link_text'] ) ); ?>
                                        </div>
                                    </div>

                                    <!-- Price -->
                                    <div class="eael-nft-price-wrapper">
                                        
                                    </div>

                                    <!-- Last Sale -->
                                    <div class="eael-nft-last-sale-wrapper eael-d-none">
                                        
                                    </div>
                                </div>

                                <!-- Button -->
                                <div class="eael-nft-button">
                                    <button>
                                        <?php printf('<a target="_blank" href="%s">%s</a>', esc_attr( $item_formatted['view_details_link'] ), esc_html__( $nft_gallery['view_details_text'] ) ) ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <!-- /.column  -->
            </div>
        </div>
<?php
        echo ob_get_clean();
    }

    /**
     * API Call to Get NFT Data
     */
    public function fetch_nft_gallery_from_api()
    {
        $settings = $this->get_settings();
        
        $response = [];
        $nft_gallery = [];
        $nft_gallery['source'] = ! empty( $settings['eael_nft_gallery_sources'] ) ? esc_html( $settings['eael_nft_gallery_sources'] ) : 'opensea';
        $nft_gallery['api_key'] = ! empty( $settings['eael_nft_gallery_source_key'] ) ? esc_html( $settings['eael_nft_gallery_source_key'] ) : '';
        $nft_gallery['opensea_type'] = ! empty( $settings['eael_nft_gallery_opensea_type'] ) ? esc_html( $settings['eael_nft_gallery_opensea_type'] ) : 'items';
        $nft_gallery['filterby'] = ! empty( $settings['eael_nft_gallery_opensea_filterby'] ) ? esc_html( $settings['eael_nft_gallery_opensea_filterby'] ) : '';
        $nft_gallery['order'] = ! empty( $settings['eael_nft_gallery_opensea_order'] ) ? esc_html( $settings['eael_nft_gallery_opensea_order'] ) : 'desc';
        $nft_gallery['posts_per_page'] = ! empty( $settings['eael_nft_gallery_opensea_posts_per_page'] ) ? esc_html( $settings['eael_nft_gallery_opensea_posts_per_page'] ) : 6;

        if ( 'opensea' === $nft_gallery['source'] ) {
            $nft_gallery['api_key'] = $nft_gallery['api_key'] ? $nft_gallery['api_key'] :  'b61c8a54123d4dcb9acc1b9c26a01cd1';
            
            $url = "https://api.opensea.io/api/v1";
            $param = array();

            if ( 'collections' === $nft_gallery['opensea_type'] ) {
                $url .= "/collections";
                $nft_gallery['filterby_value'] = ! empty( $settings['eael_nft_gallery_opensea_filterby_collections_wallet'] ) ? $settings['eael_nft_gallery_opensea_filterby_collections_wallet'] : '';

                $args = array(
                    'asset_owner' => sanitize_text_field( $nft_gallery['filterby_value'] ),
                    'limit' => $nft_gallery['posts_per_page'],
                    'offset' => 0,
                );
                $param = array_merge($param, $args);
            } elseif ( 'items' === $nft_gallery['opensea_type'] ) {
                $url .= "/assets";
                $args = array(
                    'include_orders' => true,
                    'limit' => $nft_gallery['posts_per_page'],
                    'order_direction' => $nft_gallery['order'],
                );
                
                if ( ! empty( $nft_gallery['filterby'] ) ) {
                    if ( "slug" === $nft_gallery['filterby'] ) {
                        $nft_gallery['filterby_value'] = ! empty( $settings['eael_nft_gallery_opensea_filterby_slug'] ) ? $settings['eael_nft_gallery_opensea_filterby_slug'] : '';
                        $args['collection_slug'] = sanitize_text_field( $nft_gallery['filterby_value'] );
                    } elseif ( "wallet" === $nft_gallery['filterby'] ) {
                        $nft_gallery['filterby_value'] = ! empty( $settings['eael_nft_gallery_opensea_filterby_wallet'] ) ? $settings['eael_nft_gallery_opensea_filterby_wallet'] : '';
                        $args['owner'] = sanitize_text_field( $nft_gallery['filterby_value'] );
                    }
                }
                $param = array_merge( $param, $args );
            }

            $headers = array(
                'headers' => array(
                    'Content-Type' => 'application/json',
                    'X-API-KEY' => $nft_gallery['api_key'],
                )
            );
            $options = array(
                'timeout' => 240
            );

            $options = array_merge($headers, $options);
            $response = wp_remote_get(
                esc_url_raw( add_query_arg( $param, $url ) ), 
                $options
            );

            $body = json_decode( wp_remote_retrieve_body( $response ) );
            $response = ! empty( $body->assets ) ? $body->assets : [];
            return $response;
        }

        return $response;
    }

    protected function render()
    {
        $nft_gallery_items = $this->fetch_nft_gallery_from_api();
        $this->print_nft_gallery( $nft_gallery_items );
    }
}
