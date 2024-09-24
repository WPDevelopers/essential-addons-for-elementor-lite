<?php
namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
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

class NFT_Gallery extends Widget_Base {
	private $nft_gallery_items_count = 0;
	private $nft_documentation_url = 'https://essential-addons.com/elementor/docs/ea-nft-gallery/';

	public function get_name() {
		return 'eael-nft-gallery';
	}

	public function get_title() {
		return esc_html__( 'NFT Gallery', 'essential-addons-for-elementor-lite' );
	}

	public function get_icon() {
		return 'eaicon-nft-gallery';
	}

	public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

	public function get_keywords() {
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

	public function get_custom_help_url() {
		return 'https://essential-addons.com/elementor/docs/ea-nft-gallery/';
	}

	protected function register_controls() {

		/**
		 * NFT Settings
		 */
		$this->start_controls_section(
			'eael_section_nft_gallery_general_settings',
			[
				'label' => esc_html__( 'Query', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'eael_nft_gallery_sources',
			[
				'label'   => __( 'Source', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'opensea',
				'options' => [
					'opensea' => __( 'OpenSea', 'essential-addons-for-elementor-lite' ),
				],
			]
		);

		$this->add_control(
			'eael_nft_gallery_source_key',
			[
				'label'       => __( 'API Key', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => 'Enter API key',
				'description' => sprintf( __( 'Get your API key from <a href="https://docs.opensea.io/reference/api-keys" class="eael-btn" target="_blank">%s</a>',
					'essential-addons-for-elementor-lite' ), esc_html__( 'here', 'essential-addons-for-elementor-lite' ) ),
				'ai' => [
					'active' => false,
				],
			]
		);

		$this->add_control(
			'eael_nft_gallery_opensea_type',
			[
				'label'     => esc_html__( 'Type', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'collections',
				'options'   => [
					// 'assets'      => esc_html__( 'Assets', 'essential-addons-for-elementor-lite' ),
					'collections' => esc_html__( 'Collections', 'essential-addons-for-elementor-lite' ),
				],
				'condition' => [
					'eael_nft_gallery_sources' => 'opensea'
				],
			]
		);

		$this->add_control(
			'eael_nft_gallery_opensea_filterby',
			[
				'label'     => esc_html__( 'Filter By', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'collection-slug',
				'options'   => [
					'collection-slug' => esc_html__( 'Collection Slug', 'essential-addons-for-elementor-lite' ),
					'wallet-address'  => esc_html__( 'Wallet Address', 'essential-addons-for-elementor-lite' ),
				],
				'condition' => [
					'eael_nft_gallery_opensea_type' => 'assets',
				],
			]
		);

		$this->add_control(
			'eael_nft_gallery_opensea_filterby_slug',
			[
				'label'       => __( 'Collection Slug', 'essential-addons-for-elementor-lite' ),
				'description' => sprintf( __( 'Checkout this <a target="_blank" href="%s">document</a> to learn how to obtain a collection slug.', 'essential-addons-for-elementor-lite' ), esc_url( $this->nft_documentation_url ) ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => 'Collection slug',
				'condition'   => [
					'eael_nft_gallery_opensea_type'     => 'assets',
					'eael_nft_gallery_opensea_filterby' => 'collection-slug',
				],
				'ai' => [
					'active' => false,
				],
			]
		);

		$this->add_control(
			'eael_nft_gallery_opensea_filterby_wallet',
			[
				'label'       => __( 'Collection Slug', 'essential-addons-for-elementor-lite' ),
				'description' => sprintf( __( 'Checkout this <a target="_blank" href="%s">document</a> to learn how to obtain a wallet address.', 'essential-addons-for-elementor-lite' ), esc_url( $this->nft_documentation_url ) ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => 'collection_slug',
				'conditions'  => [
					'relation' => 'or',
					'terms'    => [
						[
							'relation' => 'and',
							'terms'    => [
								[
									'name'  => 'eael_nft_gallery_opensea_type',
									'value' => 'assets',
								],
								[
									'name'  => 'eael_nft_gallery_opensea_filterby',
									'value' => 'wallet-address',
								],
							]
						],
						[
							'relation' => 'and',
							'terms'    => [
								[
									'name'  => 'eael_nft_gallery_opensea_type',
									'value' => 'collections',
								],
							]
						],

					],
				],
				'ai' => [
					'active' => false,
				],
			]
		);

		$this->add_control( 'eael_nft_gallery_opensea_order', [
			'label'     => __( 'Order', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => [
				'asc'  => 'Ascending',
				'desc' => 'Descending',
			],
			'default'   => 'desc',
			'condition' => [
				'eael_nft_gallery_opensea_type!' => 'collections'
			],
		] );

		$this->add_control(
			'eael_nft_gallery_opensea_item_limit',
			[
				'label'       => __( 'Item Limit', 'essential-addons-for-elementor-lite' ),
				'description' => __( 'Total number of items to show', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => '12',
				'min'         => '1',
			]
		);

		$this->add_control(
			'eael_nft_gallery_opensea_data_cache_time',
			[
				'label'       => __( 'Data Cache Time', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'default'     => 60,
				'description' => __( 'Cache expiration time (in Minutes), 0 or empty sets 1 day.', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->end_controls_section();

		/**
		 * NFT Settings
		 */
		$this->start_controls_section(
			'eael_section_nft_gallery_settings',
			[
				'label' => esc_html__( 'Layout', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'eael_nft_gallery_items_layout',
			[
				'label'   => esc_html__( 'Layout Type', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'grid',
				'options' => [
					'grid' => esc_html__( 'Grid', 'essential-addons-for-elementor-lite' ),
					'list' => esc_html__( 'List', 'essential-addons-for-elementor-lite' ),
				]
			]
		);

		$this->add_control(
			'eael_nft_gallery_style_preset',
			[
				'label'     => esc_html__( 'Style Preset', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'preset-1',
				'options'   => [
					'preset-1' => esc_html__( 'Preset 1', 'essential-addons-for-elementor-lite' ),
					'preset-2' => esc_html__( 'Preset 2', 'essential-addons-for-elementor-lite' ),
				],
				'condition' => [
					'eael_nft_gallery_items_layout' => 'grid'
				],
			]
		);

		$this->add_responsive_control(
			'eael_nft_gallery_column',
			[
				'label'     => esc_html__( 'Columns', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '4',
				'options'   => [
					'1' => esc_html__( '1', 'essential-addons-for-elementor-lite' ),
					'2' => esc_html__( '2', 'essential-addons-for-elementor-lite' ),
					'3' => esc_html__( '3', 'essential-addons-for-elementor-lite' ),
					'4' => esc_html__( '4', 'essential-addons-for-elementor-lite' ),
					'5' => esc_html__( '5', 'essential-addons-for-elementor-lite' ),
					'6' => esc_html__( '6', 'essential-addons-for-elementor-lite' ),
				],
				'condition' => [
					'eael_nft_gallery_items_layout' => 'grid'
				],
				'selectors' => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-grid' => 'grid-template-columns: repeat( {{VALUE}}, 1fr);',
				]
			]
		);

		$this->add_control(
			'eael_nft_gallery_show_image',
			[
				'label'        => __( 'NFT Image', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'eael_nft_gallery_image_clickable',
			[
				'label'        => __( 'Image Clickable?', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'conditions'   => [
					'relation' => 'or',
					'terms'    => [
						[
							'relation' => 'and',
							'terms'    => [
								[
									'name'  => 'eael_nft_gallery_items_layout',
									'value' => 'grid',
								],
								[
									'name'  => 'eael_nft_gallery_show_image',
									'value' => 'yes',
								],
							]
						],
						[
							'relation' => 'and',
							'terms'    => [
								[
									'name'  => 'eael_nft_gallery_items_layout',
									'value' => 'list',
								],
								[
									'name'  => 'eael_nft_gallery_show_image',
									'value' => 'yes',
								],
							]
						],
					],
				],
			]
		);

		$this->add_control(
			'eael_nft_gallery_show_title',
			[
				'label'        => __( 'Title', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'eael_nft_gallery_show_current_price',
			[
				'label'        => __( 'Current Price', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'eael_nft_gallery_show_owner',
			[
				'label'        => __( 'Current Owner', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::HIDDEN,
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => '',
				'conditions'   => [
					'relation' => 'or',
					'terms'    => [
						[
							'relation' => 'and',
							'terms'    => [
								[
									'name'  => 'eael_nft_gallery_items_layout',
									'value' => 'grid',
								],
								[
									'name'  => 'eael_nft_gallery_style_preset',
									'value' => 'preset-1',
								],
							]
						],
						[
							'name'  => 'eael_nft_gallery_items_layout',
							'value' => 'list',
						],

					],
				],
			]
		);

		$this->add_control(
			'eael_nft_gallery_show_creator',
			[
				'label'        => __( 'Creator', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => '',
				'conditions'   => [
					'relation' => 'or',
					'terms'    => [
						[
							'relation' => 'and',
							'terms'    => [
								[
									'name'  => 'eael_nft_gallery_items_layout',
									'value' => 'grid',
								],
								[
									'name'  => 'eael_nft_gallery_style_preset',
									'value' => 'preset-1',
								],
							]
						],
						[
							'name'  => 'eael_nft_gallery_items_layout',
							'value' => 'list',
						],

					],
				],
			]
		);

		$this->add_control(
			'eael_nft_gallery_show_last_sale_ends_in',
			[
				'label'        => __( 'Last Sale / Ends In', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'conditions'   => [
					'relation' => 'or',
					'terms'    => [
						[
							'relation' => 'and',
							'terms'    => [
								[
									'name'  => 'eael_nft_gallery_items_layout',
									'value' => 'grid',
								],
								[
									'name'  => 'eael_nft_gallery_style_preset',
									'value' => 'preset-1',
								],
							]
						],
						[
							'name'  => 'eael_nft_gallery_items_layout',
							'value' => 'list',
						],

					],
				],
			]
		);

		$this->add_control(
			'eael_nft_gallery_show_button',
			[
				'label'        => __( 'Button', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'conditions'   => [
					'relation' => 'or',
					'terms'    => [
						[
							'relation' => 'and',
							'terms'    => [
								[
									'name'  => 'eael_nft_gallery_items_layout',
									'value' => 'grid',
								],
								[
									'name'  => 'eael_nft_gallery_style_preset',
									'value' => 'preset-1',
								],
							]
						],
					],
				],
			]
		);

		$this->add_control(
			'eael_nft_gallery_show_chain',
			[
				'label'        => __( 'Chain', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'eael_nft_gallery_items_layout' => 'grid'
				],
			]
		);

		$this->end_controls_section();

		/**
		 * NFT Content
		 */
		$this->start_controls_section(
			'eael_section_nft_gallery_content',
			[
				'label' => esc_html__( 'Content', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'eael_section_nft_gallery_content_label',
			[
				'label' => esc_html__( 'Label', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control( 'eael_nft_gallery_content_owned_by_label', [
			'label'       => esc_html__( 'Owner', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => false,
			'default'     => esc_html__( 'Owner', 'essential-addons-for-elementor-lite' ),
			'condition'   => [
				'eael_nft_gallery_show_owner' => 'yes'
			],
			'ai' => [
				'active' => false,
			],
		] );

		$this->add_control( 'eael_nft_gallery_content_created_by_label', [
			'label'       => esc_html__( 'Creator', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => false,
			'default'     => esc_html__( 'Creator', 'essential-addons-for-elementor-lite' ),
			'condition'   => [
				'eael_nft_gallery_show_creator' => 'yes'
			],
			'ai' => [
				'active' => false,
			],
		] );

		$this->add_control( 'eael_nft_gallery_content_view_details_label', [
			'label'       => esc_html__( 'View Details', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => false,
			'default'     => esc_html__( 'View Details', 'essential-addons-for-elementor-lite' ),
			'ai' => [
				'active' => false,
			],
		] );

		$this->add_control( 'eael_nft_gallery_content_no_items_label', [
			'label'       => esc_html__( 'No Items', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => false,
			'default'     => esc_html__( 'No Items Found!', 'essential-addons-for-elementor-lite' ),
			'ai' => [
				'active' => false,
			],
		] );

		$this->add_control( 'eael_nft_gallery_content_last_sale_label', [
			'label'       => esc_html__( 'Last sale', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => false,
			'default'     => esc_html__( 'Last sale:', 'essential-addons-for-elementor-lite' ),
			'ai' => [
				'active' => false,
			],
		] );

		$this->add_control( 'eael_nft_gallery_content_ends_in_label', [
			'label'       => esc_html__( 'Ends in', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => false,
			'default'     => esc_html__( 'Ends in:', 'essential-addons-for-elementor-lite' ),
			'ai' => [
				'active' => false,
			],
		] );

		$this->end_controls_section();

		/**
		 * Content Tab: Load More Button
		 */
		$this->start_controls_section(
			'eael_nft_gallery_section_pagination',
			[
				'label' => __( 'Load More Button', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'eael_nft_gallery_pagination',
			[
				'label'              => __( 'Show Load More', 'essential-addons-for-elementor-lite' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'false',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'eael_nft_gallery_posts_per_page',
			[
				'label'       => __( 'Items Per Page', 'essential-addons-for-elementor-lite' ),
				'description' => __( 'Make sure this value is less than <b>Post Limit</b>', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 8,
				'condition'   => [
					'eael_nft_gallery_pagination' => 'yes',
				],
				'ai' => [
					'active' => false,
				],
			]
		);

		$this->add_control(
			'eael_nft_gallery_load_more_text',
			[
				'label'     => __( 'Button Text', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => [ 'active' => true ],
				'default'   => __( 'Load More', 'essential-addons-for-elementor-lite' ),
				'condition' => [
					'eael_nft_gallery_pagination' => 'yes',
				],
				'ai' => [
					'active' => false,
				],
			]
		);

		$this->add_control(
			'eael_nft_gallery_nomore_items_text',
			[
				'label'     => __( 'No More Items Text', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => [ 'active' => true ],
				'default'   => __( 'No more items!', 'essential-addons-for-elementor-lite' ),
				'condition' => [
					'eael_nft_gallery_pagination' => 'yes',
				],
				'ai' => [
					'active' => false,
				],
			]
		);

		$this->add_control(
			'eael_nft_gallery_button_size',
			[
				'label'     => __( 'Size', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'sm',
				'options'   => [
					'xs' => __( 'Extra Small', 'essential-addons-for-elementor-lite' ),
					'sm' => __( 'Small', 'essential-addons-for-elementor-lite' ),
					'md' => __( 'Medium', 'essential-addons-for-elementor-lite' ),
					'lg' => __( 'Large', 'essential-addons-for-elementor-lite' ),
					'xl' => __( 'Extra Large', 'essential-addons-for-elementor-lite' ),
				],
				'condition' => [
					'eael_nft_gallery_pagination'      => 'yes',
					'eael_nft_gallery_load_more_text!' => '',
				],
			]
		);

		$this->add_control(
			'eael_nft_gallery_load_more_icon_new',
			[
				'label'            => __( 'Button Icon', 'essential-addons-for-elementor-lite' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'eael_nft_gallery_load_more_icon',
				'condition'        => [
					'eael_nft_gallery_pagination' => 'yes',
				],
			]
		);

		$this->add_control(
			'eael_nft_gallery_button_icon_position',
			[
				'label'     => __( 'Icon Position', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'after',
				'options'   => [
					'after'  => __( 'After', 'essential-addons-for-elementor-lite' ),
					'before' => __( 'Before', 'essential-addons-for-elementor-lite' ),
				],
				'condition' => [
					'eael_nft_gallery_pagination' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'eael_nft_gallery_load_more_align',
			[
				'label'     => __( 'Alignment', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'   => 'center',
				'selectors' => [
					'{{WRAPPER}} .eael-nft-gallery-loadmore-wrap' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'eael_nft_gallery_pagination' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style (NFT Gallery Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_nft_gallery_gallery_style',
			[
				'label' => esc_html__( 'Gallery', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'eael_nft_gallery_wrap_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_nft_gallery_wrap_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'eael_nft_gallery_wrap_border_radius',
			[
				'label'     => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'default'   => [
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->start_controls_tabs( 'eael_nft_gallery_wrap_controls_tabs' );

		$this->start_controls_tab( 'eael_nft_gallery_wrap_control_normal', [
			'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ),
		] );

		$this->add_control(
			'eael_nft_gallery_wrap_normal_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'eael_nft_gallery_wrap_normal_border',
				'selector' => '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'eael_nft_gallery_wrap_normal_box_shadow',
				'selector' => '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'eael_nft_gallery_wrap_control_hover', [
			'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ),
		] );

		$this->add_control(
			'eael_nft_gallery_wrap_hover_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items:hover' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'eael_nft_gallery_wrap_hover_border',
				'selector' => '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'eael_nft_gallery_wrap_hover_box_shadow',
				'selector' => '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style ( Gallery Item Style )
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_nft_gallery_gallery_item_style',
			[
				'label' => esc_html__( 'Items', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'eael_nft_gallery_item_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items .eael-nft-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_nft_gallery_item_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items .eael-nft-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'eael_nft_gallery_item_border_radius',
			[
				'label'     => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'default'   => [
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items .eael-nft-item,
					{{WRAPPER}} .eael-nft-gallery-wrapper .preset-2 .eael-nft-item > a' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->add_control(
			'eael_nft_gallery_item_overlay_color',
			[
				'label'     => esc_html__( 'Overlay Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .preset-2 .eael-nft-item .eael-nft-thumbnail::before' => 'background: {{VALUE}};',
				],
				'default'   => 'rgba(0, 0, 0, 0.5)',
				'condition' => [
					'eael_nft_gallery_items_layout' => 'grid',
					'eael_nft_gallery_style_preset' => 'preset-2',
				],
			]
		);

		$this->start_controls_tabs( 'eael_nft_gallery_item_controls_tabs' );

		$this->start_controls_tab( 'eael_nft_gallery_item_control_normal', [
			'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ),
		] );

		$this->add_control(
			'eael_nft_gallery_item_normal_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items .eael-nft-item' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'eael_nft_gallery_item_normal_border',
				'selector' => '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items .eael-nft-item',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'eael_nft_gallery_item_normal_box_shadow',
				'selector' => '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items .eael-nft-item',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'eael_nft_gallery_item_control_hover', [
			'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ),
		] );

		$this->add_control(
			'eael_nft_gallery_item_hover_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items .eael-nft-item:hover' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'eael_nft_gallery_item_hover_border',
				'selector' => '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items .eael-nft-item:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'eael_nft_gallery_item_hover_box_shadow',
				'selector' => '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items .eael-nft-item:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style ( NFT Image Style )
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_nft_gallery_nft_image_style',
			[
				'label'     => esc_html__( 'Image', 'essential-addons-for-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'eael_nft_gallery_show_image' => 'yes',
				],
			]
		);

		$this->add_responsive_control( "eael_nft_gallery_nft_image_width", [
			'label'      => esc_html__( 'Width', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [
				'px',
				'rem',
				'%',
			],
			'range'      => [
				'px'  => [
					'min'  => 0,
					'max'  => 1000,
					'step' => 5,
				],
				'rem' => [
					'min'  => 0,
					'max'  => 100,
					'step' => .5,
				],
				'%'   => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-thumbnail img"      => 'width: {{SIZE}}{{UNIT}};',
				"{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-list .eael-nft-list-thumbnail img" => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( "eael_nft_gallery_nft_image_height", [
			'label'      => esc_html__( 'Height', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [
				'px',
				'rem',
			],
			'range'      => [
				'px'  => [
					'min'  => 0,
					'max'  => 1000,
					'step' => 5,
				],
				'rem' => [
					'min'  => 0,
					'max'  => 100,
					'step' => .5,
				],
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-thumbnail img"      => 'height: {{SIZE}}{{UNIT}};',
				"{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-list .eael-nft-list-thumbnail img" => 'height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( "eael_nft_gallery_nft_image_margin", [
			'label'      => __( 'Margin', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-thumbnail"      => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				"{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-list .eael-nft-list-thumbnail" => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( "eael_nft_gallery_nft_image_padding", [
			'label'      => __( 'Padding', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-thumbnail"      => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				"{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-list .eael-nft-list-thumbnail" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => "eael_nft_gallery_nft_image_border",
			'selector' => "{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-thumbnail img, {{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-list .eael-nft-list-thumbnail img",
		] );

		$this->add_control(
			'eael_nft_gallery_nft_image_border_radius',
			[
				'label'     => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-thumbnail img'      => 'border-radius: {{SIZE}}px;',
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-list .eael-nft-list-thumbnail img' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style ( Title Style )
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_nft_gallery_title_style',
			[
				'label'     => esc_html__( 'Title', 'essential-addons-for-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'eael_nft_gallery_show_title' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'eael_nft_gallery_title_typography',
				'selector' => '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-title',
			]
		);

		$this->add_control(
			'eael_nft_gallery_title_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-title' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-list .eael-nft-content .eael-nft-title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_nft_gallery_title_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_nft_gallery_title_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style ( Current Price Style )
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_nft_gallery_current_price_style',
			[
				'label'     => esc_html__( 'Price', 'essential-addons-for-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'eael_nft_gallery_show_current_price' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'eael_nft_gallery_current_price_typography',
				'selector' => '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-current-price',
			]
		);

		$this->add_control(
			'eael_nft_gallery_current_price_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-current-price' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_nft_gallery_current_price_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-current-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_nft_gallery_current_price_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-current-price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style ( Last Sale / Ends In Style )
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_nft_gallery_last_sale_ends_in_style',
			[
				'label'     => esc_html__( 'Last Sale / Ends In', 'essential-addons-for-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'eael_nft_gallery_show_last_sale_ends_in' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'eael_nft_gallery_last_sale_ends_in_typography',
				'selector' => '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-ends-in span, {{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-last-sale span',
			]
		);

		$this->add_control(
			'eael_nft_gallery_last_sale_ends_in_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-ends-in span'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-last-sale span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_nft_gallery_last_sale_ends_in_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-ends-in'   => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-last-sale' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_nft_gallery_last_sale_ends_in_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-ends-in'   => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-last-sale' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style ( Creator Style )
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_nft_gallery_created_by_style',
			[
				'label'     => esc_html__( 'Creator', 'essential-addons-for-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'eael_nft_gallery_show_creator' => 'yes',
				],
			]
		);

		$this->add_control( 'eael_nft_gallery_created_by_image', [
			'label' => __( 'Image', 'essential-addons-for-elementor-lite' ),
			'type'  => Controls_Manager::HEADING,
		] );

		$this->add_responsive_control( "eael_nft_gallery_created_by_image_width", [
			'label'      => esc_html__( 'Width', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [
				'px',
				'rem',
				'%',
			],
			'range'      => [
				'px'  => [
					'min'  => 0,
					'max'  => 1000,
					'step' => 5,
				],
				'rem' => [
					'min'  => 0,
					'max'  => 100,
					'step' => .5,
				],
				'%'   => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-creator-wrapper img" => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( "eael_nft_gallery_created_by_image_height", [
			'label'      => esc_html__( 'Height', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [
				'px',
				'rem',
			],
			'range'      => [
				'px'  => [
					'min'  => 0,
					'max'  => 1000,
					'step' => 5,
				],
				'rem' => [
					'min'  => 0,
					'max'  => 100,
					'step' => .5,
				],
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-creator-wrapper img" => 'height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( "eael_nft_gallery_created_by_image_margin", [
			'label'      => __( 'Margin', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-creator-wrapper img" => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( "eael_nft_gallery_created_by_image_padding", [
			'label'      => __( 'Padding', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-creator-wrapper img" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => "eael_nft_gallery_created_by_image_border",
			'selector' => "{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-creator-wrapper img",
		] );

		$this->add_control(
			'eael_nft_gallery_created_by_image_border_radius',
			[
				'label'     => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-creator-wrapper img' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->add_control( 'eael_nft_gallery_created_by_label', [
			'label'     => __( 'Label', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'eael_nft_gallery_created_by_label_typography',
				'selector' => '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-creator-wrapper span',
			]
		);

		$this->add_control(
			'eael_nft_gallery_created_by_label_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-creator-wrapper span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_nft_gallery_created_by_label_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-creator-wrapper span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_nft_gallery_created_by_label_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-creator-wrapper span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control( 'eael_nft_gallery_created_by_link', [
			'label'     => __( 'Link', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'eael_nft_gallery_created_by_link_typography',
				'selector' => '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-creator-wrapper a',
			]
		);

		$this->start_controls_tabs( 'eael_nft_gallery_created_by_controls_tabs' );

		$this->start_controls_tab( 'eael_nft_gallery_created_by_control_normal', [
			'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ),
		] );

		$this->add_control(
			'eael_nft_gallery_created_by_link_normal_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-creator-wrapper a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'eael_nft_gallery_created_by_control_hover', [
			'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ),
		] );

		$this->add_control(
			'eael_nft_gallery_created_by_link_hover_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-creator-wrapper a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'eael_nft_gallery_created_by_link_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-creator-wrapper a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_nft_gallery_created_by_link_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-creator-wrapper a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style ( Owner Style )
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_nft_gallery_owned_by_style',
			[
				'label'     => esc_html__( 'Owner', 'essential-addons-for-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'eael_nft_gallery_show_owner' => 'yes',
				],
			]
		);

		$this->add_control( 'eael_nft_gallery_owned_by_image', [
			'label' => __( 'Image', 'essential-addons-for-elementor-lite' ),
			'type'  => Controls_Manager::HEADING,
		] );

		$this->add_responsive_control( "eael_nft_gallery_owned_by_image_width", [
			'label'      => esc_html__( 'Width', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [
				'px',
				'rem',
				'%',
			],
			'range'      => [
				'px'  => [
					'min'  => 0,
					'max'  => 1000,
					'step' => 5,
				],
				'rem' => [
					'min'  => 0,
					'max'  => 100,
					'step' => .5,
				],
				'%'   => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-owner-wrapper img" => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( "eael_nft_gallery_owned_by_image_height", [
			'label'      => esc_html__( 'Height', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [
				'px',
				'rem',
				'%',
			],
			'range'      => [
				'px'  => [
					'min'  => 0,
					'max'  => 1000,
					'step' => 5,
				],
				'rem' => [
					'min'  => 0,
					'max'  => 100,
					'step' => .5,
				],
				'%'   => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-owner-wrapper img" => 'height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( "eael_nft_gallery_owned_by_image_margin", [
			'label'      => __( 'Margin', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-owner-wrapper img" => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( "eael_nft_gallery_owned_by_image_padding", [
			'label'      => __( 'Padding', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-owner-wrapper img" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => "eael_nft_gallery_owned_by_image_border",
			'selector' => "{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-owner-wrapper img",
		] );

		$this->add_control(
			'eael_nft_gallery_owned_by_image_border_radius',
			[
				'label'     => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-owner-wrapper img' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->add_control( 'eael_nft_gallery_owned_by_label', [
			'label'     => __( 'Label', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'eael_nft_gallery_owned_by_label_typography',
				'selector' => '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-owner-wrapper span',
			]
		);

		$this->add_control(
			'eael_nft_gallery_owned_by_label_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-owner-wrapper span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_nft_gallery_owned_by_label_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-owner-wrapper span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_nft_gallery_owned_by_label_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-owner-wrapper span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control( 'eael_nft_gallery_owned_by_link', [
			'label'     => __( 'Link', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'eael_nft_gallery_owned_by_link_typography',
				'selector' => '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-owner-wrapper a',
			]
		);

		$this->start_controls_tabs( 'eael_nft_gallery_owned_by_controls_tabs' );

		$this->start_controls_tab( 'eael_nft_gallery_owned_by_control_normal', [
			'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ),
		] );

		$this->add_control(
			'eael_nft_gallery_owned_by_link_normal_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-owner-wrapper a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'eael_nft_gallery_owned_by_control_hover', [
			'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ),
		] );

		$this->add_control(
			'eael_nft_gallery_owned_by_link_hover_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-owner-wrapper a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'eael_nft_gallery_owned_by_link_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-owner-wrapper a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_nft_gallery_owned_by_link_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-item .eael-nft-content .eael-nft-owner-wrapper a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style ( Button Style )
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_nft_gallery_button_style',
			[
				'label'     => esc_html__( 'Button', 'essential-addons-for-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'eael_nft_gallery_show_button' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'eael_nft_gallery_button_typography',
				'selector' => '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items .eael-nft-button button a',
			]
		);

		$this->add_responsive_control( "eael_nft_gallery_button_width", [
			'label'      => esc_html__( 'Width', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [
				'px',
				'rem',
				'%',
			],
			'range'      => [
				'px'  => [
					'min'  => 0,
					'max'  => 1000,
					'step' => 5,
				],
				'rem' => [
					'min'  => 0,
					'max'  => 100,
					'step' => .5,
				],
				'%'   => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items .eael-nft-button button" => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control(
			'eael_nft_gallery_button_alignment',
			[
				'label'   => esc_html__( 'Alignment', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'left'   => [
						'title' => esc_html__( 'Left', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default' => 'left',
			]
		);

		$this->add_responsive_control(
			'eael_nft_gallery_button_padding',
			[
				'label'      => esc_html__( 'Button Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items .eael-nft-button button a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'eael_nft_gallery_button_border_radius',
			[
				'label'     => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'default'   => [
					'size' => 4,
				],
				'selectors' => [
					// '{{WRAPPER}} .eael-nft-gallery-wrapper .preset-2 .eael-nft-button button a' => 'border-radius: {{SIZE}}px;',
					// '{{WRAPPER}} .eael-nft-gallery-wrapper .preset-3 .eael-nft-button button a' => 'border-radius: {{SIZE}}px;',
				],
				'condition' => [
					'eael_nft_gallery_style_preset!' => 'preset-1'
				],
			]
		);

		$this->add_control(
			'eael_nft_gallery_button_border_radius_preset_1',
			[
				'label'     => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .preset-1 .eael-nft-button button a' => 'border-radius: {{SIZE}}px;',
				],
				'condition' => [
					'eael_nft_gallery_style_preset' => 'preset-1'
				],
			]
		);

		$this->start_controls_tabs( 'eael_nft_gallery_button_controls_tabs' );

		$this->start_controls_tab( 'eael_nft_gallery_button_control_normal', [
			'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ),
		] );

		$this->add_control(
			'eael_nft_gallery_button_normal_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items .eael-nft-button button a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_nft_gallery_button_normal_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgb(32, 129, 226)',
				'selectors' => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items .eael-nft-button button a' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'eael_nft_gallery_button_normal_border',
				'selector' => '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items .eael-nft-button button a',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'eael_nft_gallery_button_normal_box_shadow',
				'selector' => '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items .eael-nft-button button a',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'eael_nft_gallery_button_control_hover', [
			'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ),
		] );

		$this->add_control(
			'eael_nft_gallery_button_hover_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items .eael-nft-button button a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_nft_gallery_button_hover_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgb(46, 142, 238)',
				'selectors' => [
					'{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items .eael-nft-button button a:hover' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'eael_nft_gallery_button_hover_border',
				'selector' => '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items .eael-nft-button button a:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'eael_nft_gallery_button_hover_box_shadow',
				'selector' => '{{WRAPPER}} .eael-nft-gallery-wrapper .eael-nft-gallery-items .eael-nft-button button a:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * Style Tab: Load More Button
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_nft_gallery_load_more_style',
			[
				'label'     => __( 'Load More Button', 'essential-addons-for-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'eael_nft_gallery_pagination'      => 'yes',
					'eael_nft_gallery_load_more_text!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'eael_nft_gallery_load_more_margin_top',
			[
				'label'      => __( 'Top Spacing', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 80,
						'step' => 1,
					],
				],
				'size_units' => '',
				'selectors'  => [
					'{{WRAPPER}} .eael-nft-gallery-load-more' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_eael_nft_gallery_load_more_button_style' );

		$this->start_controls_tab(
			'tab_nft_gallery_load_more_button_normal',
			[
				'label'     => __( 'Normal', 'essential-addons-for-elementor-lite' ),
				'condition' => [
					'eael_nft_gallery_pagination'      => 'yes',
					'eael_nft_gallery_load_more_text!' => '',
				],
			]
		);

		$this->add_control(
			'eael_nft_gallery_load_more_bg_color_normal',
			[
				'label'     => __( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#29D8D8',
				'selectors' => [
					'{{WRAPPER}} .eael-nft-gallery-load-more' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'eael_nft_gallery_pagination'      => 'yes',
					'eael_nft_gallery_load_more_text!' => '',
				],
			]
		);

		$this->add_control(
			'eael_nft_gallery_load_more_text_color_normal',
			[
				'label'     => __( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} .eael-nft-gallery-load-more' => 'color: {{VALUE}}',
				],
				'condition' => [
					'eael_nft_gallery_pagination'      => 'yes',
					'eael_nft_gallery_load_more_text!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'eael_nft_gallery_load_more_border_normal',
				'label'       => __( 'Border', 'essential-addons-for-elementor-lite' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .eael-nft-gallery-load-more',
				'condition'   => [
					'eael_nft_gallery_pagination'      => 'yes',
					'eael_nft_gallery_load_more_text!' => '',
				],
			]
		);

		$this->add_control(
			'eael_nft_gallery_load_more_border_radius',
			[
				'label'      => __( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-nft-gallery-load-more' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'eael_nft_gallery_pagination'      => 'yes',
					'eael_nft_gallery_load_more_text!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'eael_nft_gallery_load_more_typography',
				'label'     => __( 'Typography', 'essential-addons-for-elementor-lite' ),
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector'  => '{{WRAPPER}} .eael-nft-gallery-load-more .eael-nft-gallery-load-more-text',
				'condition' => [
					'eael_nft_gallery_pagination'      => 'yes',
					'eael_nft_gallery_load_more_text!' => '',
				],
			]
		);

		$this->add_control(
			'eael_nft_gallery_load_more_icon_size',
			[
				'label'     => __( 'Icon Size', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 15,
				],
				'range'     => [
					'px' => [
						'min'  => 20,
						'max'  => 500,
						'step' => 1,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .eael-nft-gallery-load-more .eael-nft-gallery-load-more-icon'    => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eael-nft-gallery-load-more img.eael-nft-gallery-load-more-icon' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};'
				]
			]
		);

		$this->add_control(
			'eael_nft_gallery_load_more_icon_spacing',
			[
				'label'     => __( 'Icon Spacing', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 50
					]
				],
				'selectors' => [
					'{{WRAPPER}} .eael-nft-gallery-load-more .nft-gallery-load-more-icon-left'  => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eael-nft-gallery-load-more .nft-gallery-load-more-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'eael_nft_gallery_load_more_padding',
			[
				'label'      => __( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-nft-gallery-load-more' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'eael_nft_gallery_pagination'      => 'yes',
					'eael_nft_gallery_load_more_text!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'eael_nft_gallery_load_more_box_shadow',
				'selector'  => '{{WRAPPER}} .eael-nft-gallery-load-more',
				'condition' => [
					'eael_nft_gallery_pagination'      => 'yes',
					'eael_nft_gallery_load_more_text!' => '',
				],
			]
		);

		$this->add_control(
			'eael_nft_gallery_load_more_icon_heading',
			[
				'label'     => __( 'Button Icon', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'eael_nft_gallery_pagination'          => 'yes',
					'eael_nft_gallery_load_more_icon_new!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'eael_nft_gallery_load_more_icon_margin',
			[
				'label'       => __( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', '%' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .eael-nft-gallery-load-more .eael-nft-gallery-load-more-icon' => 'margin-top: {{TOP}}{{UNIT}}; margin-left: {{LEFT}}{{UNIT}}; margin-right: {{RIGHT}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
				],
				'condition'   => [
					'eael_nft_gallery_pagination'          => 'yes',
					'eael_nft_gallery_load_more_icon_new!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_nft_gallery_load_more_button_hover',
			[
				'label'     => __( 'Hover', 'essential-addons-for-elementor-lite' ),
				'condition' => [
					'eael_nft_gallery_pagination'      => 'yes',
					'eael_nft_gallery_load_more_text!' => '',
				],
			]
		);

		$this->add_control(
			'nft_gallery_load_more_bg_color_hover',
			[
				'label'     => __( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .eael-nft-gallery-load-more:hover' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'eael_nft_gallery_pagination'      => 'yes',
					'eael_nft_gallery_load_more_text!' => '',
				],
			]
		);

		$this->add_control(
			'nft_gallery_load_more_text_color_hover',
			[
				'label'     => __( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .eael-nft-gallery-load-more:hover' => 'color: {{VALUE}}',
				],
				'condition' => [
					'eael_nft_gallery_pagination'      => 'yes',
					'eael_nft_gallery_load_more_text!' => '',
				],
			]
		);

		$this->add_control(
			'nft_gallery_load_more_border_color_hover',
			[
				'label'     => __( 'Border Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .eael-nft-gallery-load-more:hover' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'eael_nft_gallery_pagination'      => 'yes',
					'eael_nft_gallery_load_more_text!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'nft_gallery_load_more_box_shadow_hover',
				'selector'  => '{{WRAPPER}} .eael-nft-gallery-load-more:hover',
				'condition' => [
					'eael_nft_gallery_pagination'      => 'yes',
					'eael_nft_gallery_load_more_text!' => '',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

    public function print_nft_gallery_item_grid($nft_gallery, $item){
        $item_formatted = $item;
        $pagination_class = ! empty( $item_formatted['pagination_class'] ) ? $item_formatted['pagination_class'] : '';
        $unit_convert = ! empty( $item_formatted['unit_convert'] ) ? $item_formatted['unit_convert'] : 1;
        ?>
        <div class="eael-nft-item <?php echo esc_attr( $pagination_class ); ?> ">
            <!-- Chain -->
            <?php if( $nft_gallery['show_chain'] ) : ?>
            <div class="eael-nft-chain">
                <button class="eael-nft-chain-button">
                    <svg fill="white" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 20px;"><path d="M18.527 12.2062L12 16.1938L5.46875 12.2062L12 1L18.527 12.2062ZM12 17.4742L5.46875 13.4867L12 23L18.5312 13.4867L12 17.4742V17.4742Z" fill="white"></path></svg>
                </button>
            </div>
            <?php endif; ?>

            <!-- Thumbnail -->
            <div class="eael-nft-thumbnail">
		        <?php
		        if ( $nft_gallery['show_thumbnail'] ) {
			        if ( ! empty( $item_formatted['thumbnail'] ) ) {
				        if ( $nft_gallery['thumbnail_clickable'] && 'preset-1' === $nft_gallery['preset'] ) {
					        printf( '<a href="%s" target="_blank" >', esc_url( $item_formatted['view_details_link'] ) );
				        }

				        printf( '<img src="%s" alt="%s">', esc_attr( $item_formatted['thumbnail'] ), esc_attr__( 'NFT Gallery', 'essential-addons-for-elementor-lite' ) );

				        if ( $nft_gallery['thumbnail_clickable'] && 'preset-1' === $nft_gallery['preset'] ) {
					        printf( '</a>' );
				        }
			        }
		        }
		        ?>
            </div>

            <div class="eael-nft-main-content">
                <!-- Content  -->
                <div class="eael-nft-content">
                    <!-- Title  -->
                    <?php if( $nft_gallery['show_title'] ) : ?>
                    <h3 class="eael-nft-title"><?php printf('%s', esc_html( $item_formatted['title'] ) ); ?></h3>
                    <?php endif; ?>

                    <!-- Current Price -->
                    <?php if( ! empty( $nft_gallery['show_current_price'] ) ) : ?>
                    <div class="eael-nft-current-price-wrapper">
                        <?php if( floatval($item_formatted['current_price']) > 0 ): ?>
                        <p class="eael-nft-current-price"><?php printf('%s %s', floatval( $item_formatted['current_price'] / $unit_convert ), esc_html( $item_formatted['currency'] ) ); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Creator -->
                    <?php if( 'grid' === $nft_gallery['layout'] && 'preset-1' === $nft_gallery['preset'] && $nft_gallery['show_creator'] && $item_formatted['show_created_by_content'] ) : ?>
                    <div class="eael-nft-creator-wrapper">
                        <div class="eael-nft-creator-img">
                            <?php
                            if (!empty($item_formatted['creator_thumbnail'])) {
                                printf('<img src="%s" alt="%s">', esc_url($item_formatted['creator_thumbnail']), esc_attr__('EA NFT Creator Thumbnail', 'essential-addons-for-elementor-lite'));
                                
                                if($item_formatted['creator_verified']) {
                                    printf('<a class="%s" href="%s" target="_blank"><svg aria-label="verified-icon" class="sc-9c65691d-0 ghqJwW sc-3bcbbab4-0 iuhSVk" fill="none" viewBox="0 0 30 30"><path d="M13.474 2.80108C14.2729 1.85822 15.7271 1.85822 16.526 2.80108L17.4886 3.9373C17.9785 4.51548 18.753 4.76715 19.4892 4.58733L20.9358 4.23394C22.1363 3.94069 23.3128 4.79547 23.4049 6.0278L23.5158 7.51286C23.5723 8.26854 24.051 8.92742 24.7522 9.21463L26.1303 9.77906C27.2739 10.2474 27.7233 11.6305 27.0734 12.6816L26.2903 13.9482C25.8918 14.5928 25.8918 15.4072 26.2903 16.0518L27.0734 17.3184C27.7233 18.3695 27.2739 19.7526 26.1303 20.2209L24.7522 20.7854C24.051 21.0726 23.5723 21.7315 23.5158 22.4871L23.4049 23.9722C23.3128 25.2045 22.1363 26.0593 20.9358 25.7661L19.4892 25.4127C18.753 25.2328 17.9785 25.4845 17.4886 26.0627L16.526 27.1989C15.7271 28.1418 14.2729 28.1418 13.474 27.1989L12.5114 26.0627C12.0215 25.4845 11.247 25.2328 10.5108 25.4127L9.06418 25.7661C7.86371 26.0593 6.6872 25.2045 6.59513 23.9722L6.48419 22.4871C6.42773 21.7315 5.94903 21.0726 5.24777 20.7854L3.86969 20.2209C2.72612 19.7526 2.27673 18.3695 2.9266 17.3184L3.70973 16.0518C4.10824 15.4072 4.10824 14.5928 3.70973 13.9482L2.9266 12.6816C2.27673 11.6305 2.72612 10.2474 3.86969 9.77906L5.24777 9.21463C5.94903 8.92742 6.42773 8.26854 6.48419 7.51286L6.59513 6.0278C6.6872 4.79547 7.86371 3.94069 9.06418 4.23394L10.5108 4.58733C11.247 4.76715 12.0215 4.51548 12.5114 3.9373L13.474 2.80108Z" class="sc-9c65691d-1 jiZrqV"></path><path d="M13.5 17.625L10.875 15L10 15.875L13.5 19.375L21 11.875L20.125 11L13.5 17.625Z" fill="white" stroke="white"></path></svg></a>', 
                                            esc_attr('creator-verified-icon'), esc_url($item_formatted['creator_thumbnail']));
                                }
                            } else {
                                // default creator svg
                            }
                            ?>
                        </div>
                        <div class="eael-nft-created-by">
                            <div><span><?php printf('%s', esc_html( $nft_gallery['created_by_label'] ) ); ?> </span></div>
                            <div><?php printf('<a target="_blank" href="%s">%s</a>', esc_attr( $item_formatted['created_by_link'] ), esc_html( $item_formatted['created_by_link_text'] ) ); ?></div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Owner -->
                    <?php if( $nft_gallery['show_owner'] && $item_formatted['show_owned_by_content'] ) : ?>
                    <div class="eael-nft-owner-wrapper">
                        <div class="eael-nft-owner-img">
                            <?php
                            if (!empty($item_formatted['owner_thumbnail'])) {
                                printf('<img src="%s" alt="%s">', esc_url( $item_formatted['owner_thumbnail'] ), esc_attr__('EA NFT Owner Thumbnail', 'essential-addons-for-elementor-lite') );
                                
                                if($item_formatted['owner_verified']) {
                                    printf('<a class="%s" href="%s" target="_blank"><svg aria-label="verified-icon" class="sc-9c65691d-0 ghqJwW sc-3bcbbab4-0 iuhSVk" fill="none" viewBox="0 0 30 30"><path d="M13.474 2.80108C14.2729 1.85822 15.7271 1.85822 16.526 2.80108L17.4886 3.9373C17.9785 4.51548 18.753 4.76715 19.4892 4.58733L20.9358 4.23394C22.1363 3.94069 23.3128 4.79547 23.4049 6.0278L23.5158 7.51286C23.5723 8.26854 24.051 8.92742 24.7522 9.21463L26.1303 9.77906C27.2739 10.2474 27.7233 11.6305 27.0734 12.6816L26.2903 13.9482C25.8918 14.5928 25.8918 15.4072 26.2903 16.0518L27.0734 17.3184C27.7233 18.3695 27.2739 19.7526 26.1303 20.2209L24.7522 20.7854C24.051 21.0726 23.5723 21.7315 23.5158 22.4871L23.4049 23.9722C23.3128 25.2045 22.1363 26.0593 20.9358 25.7661L19.4892 25.4127C18.753 25.2328 17.9785 25.4845 17.4886 26.0627L16.526 27.1989C15.7271 28.1418 14.2729 28.1418 13.474 27.1989L12.5114 26.0627C12.0215 25.4845 11.247 25.2328 10.5108 25.4127L9.06418 25.7661C7.86371 26.0593 6.6872 25.2045 6.59513 23.9722L6.48419 22.4871C6.42773 21.7315 5.94903 21.0726 5.24777 20.7854L3.86969 20.2209C2.72612 19.7526 2.27673 18.3695 2.9266 17.3184L3.70973 16.0518C4.10824 15.4072 4.10824 14.5928 3.70973 13.9482L2.9266 12.6816C2.27673 11.6305 2.72612 10.2474 3.86969 9.77906L5.24777 9.21463C5.94903 8.92742 6.42773 8.26854 6.48419 7.51286L6.59513 6.0278C6.6872 4.79547 7.86371 3.94069 9.06418 4.23394L10.5108 4.58733C11.247 4.76715 12.0215 4.51548 12.5114 3.9373L13.474 2.80108Z" class="sc-9c65691d-1 jiZrqV"></path><path d="M13.5 17.625L10.875 15L10 15.875L13.5 19.375L21 11.875L20.125 11L13.5 17.625Z" fill="white" stroke="white"></path></svg></a>', 
                                            esc_attr('owner-verified-icon'), esc_url($item_formatted['owner_thumbnail']));
                                }
                            } else {
                                // default owner svg
                            }
                            ?>
                        </div>
                        <div class="eael-nft-owned-by">
                            <div><span><?php printf('%s', esc_html( $nft_gallery['owned_by_label'] ) ); ?> </span></div>
                            <div><?php printf('<a target="_blank" href="%s">%s</a>', esc_url( $item_formatted['owned_by_link'] ), esc_html( $item_formatted['owned_by_link_text'] ) ); ?></div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Last Sale / Ends In -->
                    <?php if( ! empty( $nft_gallery['show_last_sale_ends_in'] ) ) : ?>
                    <div class="eael-nft-last-sale-wrapper">
                        <?php if( intval($item_formatted['last_sale']) > 0 ): ?>
                            <p class="eael-nft-last-sale"><?php printf('<span class="%s">%s</span> <span class="%s">%s %s</span>', esc_attr('eael-nft-last-sale-text') , esc_html__($nft_gallery['last_sale_label'], 'essential-addons-for-elementor-lite'), esc_attr('eael-nft-last-sale-price'), floatval($item_formatted['last_sale'] / $unit_convert ), esc_html( $item_formatted['currency'] )); ?></p>
                        <?php elseif( ! empty($item_formatted['ends_in']) ): ?>
                            <p class="eael-nft-ends-in"><?php printf('<span class="%s">%s</span> <span class="%s">%s</span>', esc_attr('eael-nft-ends-in-text') , esc_html__($nft_gallery['ends_in_label'], 'essential-addons-for-elementor-lite'), esc_attr('eael-nft-ends-in-time'), $item_formatted['ends_in'] ); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Button -->
                <div class="eael-nft-button">
                    <?php if( $nft_gallery['show_button'] ) : ?>
                    <button <?php echo $this->get_render_attribute_string('eael-nft-gallery-button'); ?>>
                        <?php printf('<a target="_blank" href="%s">%s</a>', esc_attr( $item_formatted['view_details_link'] ), esc_html( $nft_gallery['view_details_text'] ) ) ?>
                    </button>
                    <?php endif; ?>
                </div>
            </div>

	        <?php
	        if ( $nft_gallery['thumbnail_clickable'] && 'preset-2' === $nft_gallery['preset'] ) {
		        printf( '<a href="%s" target="_blank" ></a>', esc_url( $item_formatted['view_details_link'] ) );
	        }
	        ?>
        </div>
        <?php 
    }
    
    public function print_nft_gallery_item_list($nft_gallery, $item){
        $item_formatted = $item;
        $pagination_class = ! empty( $item_formatted['pagination_class'] ) ? $item_formatted['pagination_class'] : '';
        $unit_convert = ! empty( $item_formatted['unit_convert'] ) ? $item_formatted['unit_convert'] : 1;
		$item_formatted['view_details_link'] = ! empty( $item_formatted['view_details_link'] ) ? $item_formatted['view_details_link'] : '#';
        ?>
        <div class="eael-nft-item <?php echo esc_attr( $pagination_class ); ?> ">
            <div class="eael-nft-main-content">
                <!-- Content  -->
                <div class="eael-nft-content eael-nft-grid-container">
                    <!-- Thumbnail -->
                    <div class="eael-nft-list-thumbnail eael-nft-grid-item">
                        <?php
                        if ( $nft_gallery['show_thumbnail'] ) {
	                        if ( ! empty( $item_formatted['thumbnail'] ) ) {
		                        if ( $nft_gallery['thumbnail_clickable'] ) {
			                        printf( '<a href="%s" target="_blank" >', esc_url( $item_formatted['view_details_link'] ) );
		                        }

		                        printf( '<img src="%s" alt="%s">', esc_attr( $item_formatted['thumbnail'] ), esc_attr__( 'NFT Gallery', 'essential-addons-for-elementor-lite' ) );

		                        if ( $nft_gallery['thumbnail_clickable'] ) {
			                        printf( '</a>' );
		                        }
	                        }
                        }
                        ?>
                    </div>
                    
                    <!-- Title  -->
                    <?php if( $nft_gallery['show_title'] ) : ?>
                    <div class="eael-nft-title-wrapper eael-nft-grid-item">
                        <h3 class="eael-nft-title"><?php printf('<a href="%s" target="_blank">%s</a>', esc_url( $item_formatted['view_details_link'] ), esc_html( $item_formatted['title'] ) ); ?></h3>
                    </div>
                    <?php endif; ?>

                    <!-- Current Price -->
                    <?php if( ! empty( $nft_gallery['show_current_price'] ) ) : ?>
                    <div class="eael-nft-current-price-wrapper eael-nft-grid-item">
                        <?php if( floatval($item_formatted['current_price']) > 0 ): ?>
                        <p class="eael-nft-current-price"><?php printf('%s %s', floatval( $item_formatted['current_price'] / $unit_convert ), esc_html( $item_formatted['currency'] ) ); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Last Sale / Ends In -->
                    <?php if( ! empty( $nft_gallery['show_last_sale_ends_in'] ) ) : ?>
                    <div class="eael-nft-last-sale-wrapper eael-nft-grid-item">
                        <?php if( intval($item_formatted['last_sale']) > 0 ): ?>
                            <p class="eael-nft-last-sale"><?php printf('<span class="%s">%s</span> <span class="%s">%s %s</span>', esc_attr('eael-nft-last-sale-text') , esc_html__($nft_gallery['last_sale_label'], 'essential-addons-for-elementor-lite'), esc_attr('eael-nft-last-sale-price'), floatval($item_formatted['last_sale'] / $unit_convert ), esc_html( $item_formatted['currency'] )); ?></p>
                        <?php elseif( ! empty($item_formatted['ends_in']) ): ?>
                            <p class="eael-nft-ends-in"><?php printf('<span class="%s">%s</span> <span class="%s">%s</span>', esc_attr('eael-nft-ends-in-text') , esc_html__($nft_gallery['ends_in_label'], 'essential-addons-for-elementor-lite'), esc_attr('eael-nft-ends-in-time'), $item_formatted['ends_in'] ); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Creator -->
                    <?php if( $nft_gallery['show_creator'] && $item_formatted['show_created_by_content'] ) : ?>
                    <div class="eael-nft-creator-wrapper eael-nft-grid-item">
                        <div class="eael-nft-creator-img">
                            <?php
                            if (!empty($item_formatted['creator_thumbnail'])) {
                                printf('<img src="%s" alt="%s">', esc_url($item_formatted['creator_thumbnail']), esc_attr__('EA NFT Creator Thumbnail', 'essential-addons-for-elementor-lite'));
                                
                                if($item_formatted['creator_verified']) {
                                    printf('<a class="%s" href="%s" target="_blank"><svg aria-label="verified-icon" class="sc-9c65691d-0 ghqJwW sc-3bcbbab4-0 iuhSVk" fill="none" viewBox="0 0 30 30"><path d="M13.474 2.80108C14.2729 1.85822 15.7271 1.85822 16.526 2.80108L17.4886 3.9373C17.9785 4.51548 18.753 4.76715 19.4892 4.58733L20.9358 4.23394C22.1363 3.94069 23.3128 4.79547 23.4049 6.0278L23.5158 7.51286C23.5723 8.26854 24.051 8.92742 24.7522 9.21463L26.1303 9.77906C27.2739 10.2474 27.7233 11.6305 27.0734 12.6816L26.2903 13.9482C25.8918 14.5928 25.8918 15.4072 26.2903 16.0518L27.0734 17.3184C27.7233 18.3695 27.2739 19.7526 26.1303 20.2209L24.7522 20.7854C24.051 21.0726 23.5723 21.7315 23.5158 22.4871L23.4049 23.9722C23.3128 25.2045 22.1363 26.0593 20.9358 25.7661L19.4892 25.4127C18.753 25.2328 17.9785 25.4845 17.4886 26.0627L16.526 27.1989C15.7271 28.1418 14.2729 28.1418 13.474 27.1989L12.5114 26.0627C12.0215 25.4845 11.247 25.2328 10.5108 25.4127L9.06418 25.7661C7.86371 26.0593 6.6872 25.2045 6.59513 23.9722L6.48419 22.4871C6.42773 21.7315 5.94903 21.0726 5.24777 20.7854L3.86969 20.2209C2.72612 19.7526 2.27673 18.3695 2.9266 17.3184L3.70973 16.0518C4.10824 15.4072 4.10824 14.5928 3.70973 13.9482L2.9266 12.6816C2.27673 11.6305 2.72612 10.2474 3.86969 9.77906L5.24777 9.21463C5.94903 8.92742 6.42773 8.26854 6.48419 7.51286L6.59513 6.0278C6.6872 4.79547 7.86371 3.94069 9.06418 4.23394L10.5108 4.58733C11.247 4.76715 12.0215 4.51548 12.5114 3.9373L13.474 2.80108Z" class="sc-9c65691d-1 jiZrqV"></path><path d="M13.5 17.625L10.875 15L10 15.875L13.5 19.375L21 11.875L20.125 11L13.5 17.625Z" fill="white" stroke="white"></path></svg></a>', 
                                            esc_attr('creator-verified-icon'), esc_url($item_formatted['creator_thumbnail']));
                                }
                            } else {
                                // default creator svg
                            }
                            ?>
                        </div>
                        <div class="eael-nft-created-by">
                            <div><span><?php printf('%s', esc_html( $nft_gallery['created_by_label'] ) ); ?> </span></div>
                            <div><?php printf('<a target="_blank" href="%s">%s</a>', esc_attr( $item_formatted['created_by_link'] ), esc_html( $item_formatted['created_by_link_text'] ) ); ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                </div>
            </div>
        </div>
        <?php 
    }

	public function print_nft_gallery( $opensea_items ) {
		$settings = $this->get_settings();
		ob_start();

		$nft_gallery   = [];
		$items         = isset( $opensea_items['items'] ) ? $opensea_items['items'] : false;
		// echo "<pre>";
		// print_r($items);
		$error_message = ! empty( $opensea_items['error_message'] ) ? $opensea_items['error_message'] : "";

		$post_per_page      = ! empty( $settings['eael_nft_gallery_posts_per_page'] ) ? absint( $settings['eael_nft_gallery_posts_per_page'] ) : 6;
		$post_limit         = ! empty( $settings['eael_nft_gallery_opensea_item_limit'] ) ? $settings['eael_nft_gallery_opensea_item_limit'] : 9;
		$no_more_items_text = Helper::eael_wp_kses( $settings['eael_nft_gallery_nomore_items_text'] );

		$counter      = 0;
		$current_page = 1;

		$nft_gallery['source']            = ! empty( $settings['eael_nft_gallery_sources'] ) ? esc_html( $settings['eael_nft_gallery_sources'] ) : 'opensea';
		$nft_gallery['layout']            = ! empty( $settings['eael_nft_gallery_items_layout'] ) ? $settings['eael_nft_gallery_items_layout'] : 'grid';
		$nft_gallery['opensea_type']      = ! empty( $settings['eael_nft_gallery_opensea_type'] ) ? esc_html( $settings['eael_nft_gallery_opensea_type'] ) : 'assets';
		$nft_gallery['preset']            = ! empty( $settings['eael_nft_gallery_style_preset'] ) && 'grid' === $nft_gallery['layout'] ? $settings['eael_nft_gallery_style_preset'] : 'preset-1';
		$nft_gallery['owned_by_label']    = ! empty( $settings['eael_nft_gallery_content_owned_by_label'] ) ? $settings['eael_nft_gallery_content_owned_by_label'] : __( 'Owner', 'essential-addons-for-elementor-lite' );
		$nft_gallery['created_by_label']  = ! empty( $settings['eael_nft_gallery_content_created_by_label'] ) ? $settings['eael_nft_gallery_content_created_by_label'] : __( 'Owner', 'essential-addons-for-elementor-lite' );
		$nft_gallery['view_details_text'] = ! empty( $settings['eael_nft_gallery_content_view_details_label'] ) ? $settings['eael_nft_gallery_content_view_details_label'] : __( 'View Details', 'essential-addons-for-elementor-lite' );


		$nft_gallery['api_url']                = 'opensea' === $nft_gallery['source'] ? 'https://opensea.io' : '';
		$nft_gallery['show_thumbnail']         = ! empty( $settings['eael_nft_gallery_show_image'] ) && 'yes' === $settings['eael_nft_gallery_show_image'];
		$nft_gallery['thumbnail_clickable']    = ! empty( $settings['eael_nft_gallery_image_clickable'] ) && 'yes' === $settings['eael_nft_gallery_image_clickable'];
		$nft_gallery['show_title']             = ! empty( $settings['eael_nft_gallery_show_title'] ) && 'yes' === $settings['eael_nft_gallery_show_title'];
		$nft_gallery['show_owner']             = ! empty( $settings['eael_nft_gallery_show_owner'] ) && 'yes' === $settings['eael_nft_gallery_show_owner'];
		$nft_gallery['show_creator']           = ! empty( $settings['eael_nft_gallery_show_creator'] ) && 'yes' === $settings['eael_nft_gallery_show_creator'];
		$nft_gallery['show_current_price']     = ! empty( $settings['eael_nft_gallery_show_current_price'] ) && 'yes' === $settings['eael_nft_gallery_show_current_price'];
		$nft_gallery['show_last_sale_ends_in'] = ! empty( $settings['eael_nft_gallery_show_last_sale_ends_in'] ) && 'yes' === $settings['eael_nft_gallery_show_last_sale_ends_in'];
		$nft_gallery['show_button']            = ! empty( $settings['eael_nft_gallery_show_button'] ) && 'yes' === $settings['eael_nft_gallery_show_button'];
		$nft_gallery['show_chain']             = ! empty( $settings['eael_nft_gallery_show_chain'] ) && 'yes' === $settings['eael_nft_gallery_show_chain'];
		$nft_gallery['button_alignment_class'] = ! empty( $settings['eael_nft_gallery_button_alignment'] ) ? 'eael-nft-gallery-button-align-' . $settings['eael_nft_gallery_button_alignment'] : ' ';
		$nft_gallery['last_sale_label']        = ! empty( $settings['eael_nft_gallery_content_last_sale_label'] ) ? $settings['eael_nft_gallery_content_last_sale_label'] : 'Last sale:';
		$nft_gallery['ends_in_label']          = ! empty( $settings['eael_nft_gallery_content_ends_in_label'] ) ? $settings['eael_nft_gallery_content_ends_in_label'] : 'Ends in:';

		$this->add_render_attribute( 'eael-nft-gallery-wrapper', [
			'class'                 => [
				'eael-nft-gallery-wrapper',
				'eael-nft-gallery-' . $this->get_id(),
				'clearfix',
			],
			'data-posts-per-page'   => $post_per_page,
			'data-total-posts'      => $post_limit,
			'data-nomore-item-text' => $no_more_items_text,
			'data-next-page'        => 2,
		] );

		$this->add_render_attribute(
			'eael-nft-gallery-items',
			[
				'id'    => 'eael-nft-gallery-' . esc_attr( $this->get_id() ),
				'class' => [
					'eael-nft-gallery-items',
					'eael-nft-' . esc_attr( $nft_gallery['layout'] ),
					esc_attr( $nft_gallery['preset'] ),
				],
			]
		);

		$this->add_render_attribute(
			'eael-nft-gallery-button',
			[
				'class' => [
					esc_attr( $nft_gallery['button_alignment_class'] ),
				],
			]
		);
?>
        <div <?php echo $this->get_render_attribute_string('eael-nft-gallery-wrapper') ?> >
            <?php if ( is_array( $items ) && count( $items ) ) : ?>
            <div <?php echo $this->get_render_attribute_string('eael-nft-gallery-items'); ?> >
                    <?php foreach ($items as $item) :
                        $counter++;
                        if ($post_per_page > 0) {
                            $current_page = ceil($counter / $post_per_page);
                        }

                        $show_pagination = ! empty($settings['eael_nft_gallery_pagination']) && 'yes' === $settings['eael_nft_gallery_pagination'] ? true : false;
            
                        if($show_pagination){
                            $pagination_class = ' page-' . $current_page;
                            $pagination_class .= 1 === intval( $current_page ) ? ' eael-d-block' : ' eael-d-none';
                        } else {
                            $pagination_class = 'page-1 eael-d-block';
                        }
                        
                        if ($counter == count($items)) {
                            $pagination_class .= ' eael-last-nft-gallery-item';
                        }

                        $item_formatted['thumbnail'] = ! empty( $item->image_url ) ? $item->image_url : EAEL_PLUGIN_URL . '/assets/front-end/img/flexia-preview.jpg';
                        $item_formatted['title'] = ! empty( $item->name ) ? $item->name : '';
                        $item_formatted['creator_thumbnail'] = ! empty( $item->creator->profile_img_url ) ? $item->creator->profile_img_url : '';
                        $item_formatted['creator_verified'] = ! empty( $item->creator->config ) && 'verified' === $item->creator->config ? true : false;
                        $item_formatted['created_by_link'] = ! empty( $item->creator->address ) ? esc_url( $nft_gallery['api_url'] . '/' . $item->creator->address ) : '#';
                        $item_formatted['created_by_link_text'] = ! empty( $item->creator->user ) && ! empty( $item->creator->user->username ) ? esc_html( $item->creator->user->username ) : '';
                        $item_formatted['show_created_by_content'] = ! empty( $item_formatted['created_by_link_text'] ) && 'NullAddress' !== $item_formatted['created_by_link_text'];

                        $item_formatted['owner_thumbnail'] = ! empty( $item->owner ) && ! empty( $item->owner->profile_img_url ) ? $item->owner->profile_img_url : '';
                        $item_formatted['owner_verified'] = ! empty( $item->owner->config ) && 'verified' === $item->owner->config ? true : false;
                        $item_formatted['owned_by_link'] = ! empty( $item->owner ) && ! empty( $item->owner->address ) ? esc_url( $nft_gallery['api_url'] . '/' . $item->owner->address ) : '#';
                        $item_formatted['owned_by_link_text'] = ! empty( $item->owner ) && ! empty( $item->owner->user ) && ! empty( $item->owner->user->username ) ? esc_html( $item->owner->user->username ) : '';
                        $item_formatted['show_owned_by_content'] = ! empty( $item_formatted['owned_by_link_text'] ) && 'NullAddress' !== $item_formatted['owned_by_link_text'];

                        $item_formatted['view_details_link'] = ! empty( $item->opensea_url ) ? $item->opensea_url : '#';
                        // if( 'collections' === $nft_gallery['opensea_type'] ){
                        //     $item_formatted['view_details_link'] = ! empty( $item->slug ) ? esc_url( "{$nft_gallery['api_url']}/collection/{$item->slug}" ) : '#'; 
                        // }
                        $item_formatted['current_price'] = ! empty( $item->seaport_sell_orders[0]->current_price ) ? $item->seaport_sell_orders[0]->current_price : 0;
                        $item_formatted['last_sale'] = ! empty( $item->last_sale->total_price ) ? $item->last_sale->total_price : 0;
                        $item_formatted['currency'] = 'ETH';
                        $item_formatted['pagination_class'] = $pagination_class;
                        $item_formatted['unit_convert'] = 1000000000000000000;
                        
                        $datediff_in_days = $datediff_in_hours = 0;
                        $item_formatted['ends_in'] = '';
                        if( ! empty( $item->seaport_sell_orders[0]->expiration_time ) ){
                            $expiration_time = $item->seaport_sell_orders[0]->expiration_time;
                            $now = time();
                            $datediff_strtotime = $expiration_time > $now ? $item->seaport_sell_orders[0]->expiration_time - $now : 0;
                            
                            $datediff_in_days = round($datediff_strtotime / (60 * 60 * 24));
                            $datediff_in_hours = round($datediff_strtotime / (60 * 60));
                        }

                        if( ! empty( $datediff_in_days ) || ! empty( $datediff_in_hours ) ){
                            $item_formatted['ends_in'] = $datediff_in_days . __(' days', 'essential-addons-for-elementor-lite');
                            $item_formatted['ends_in'] = $datediff_in_days < 1 ? $datediff_in_hours . __(' hours', 'essential-addons-for-elementor-lite') : $item_formatted['ends_in'];
                        }

	                    'grid' === $nft_gallery['layout'] ? $this->print_nft_gallery_item_grid( $nft_gallery, $item_formatted ) : $this->print_nft_gallery_item_list( $nft_gallery, $item_formatted );
                    endforeach; ?>
                <!-- /.column  -->
            </div>
            <?php else: ?>
	            <?php printf( '<div class="eael-nft-gallery-error-message">%s</div>', esc_html( $error_message ) ); ?>
            <?php endif; ?>
        </div>

        <div class="clearfix">
            <?php $this->render_loadmore_button() ?>
        </div>
<?php
        echo ob_get_clean();
    }

    /**
     * API Call to Get NFT Data
     */
	public function fetch_nft_gallery_from_api() {
		$settings = $this->get_settings();

		$response                        = [];
		$nft_gallery                     = [];
		$nft_gallery['source']           = ! empty( $settings['eael_nft_gallery_sources'] ) ? esc_html( $settings['eael_nft_gallery_sources'] ) : 'opensea';
		$nft_gallery['api_key']          = ! empty( $settings['eael_nft_gallery_source_key'] ) ? esc_html( $settings['eael_nft_gallery_source_key'] ) : '';
		$nft_gallery['opensea_type']     = ! empty( $settings['eael_nft_gallery_opensea_type'] ) ? esc_html( $settings['eael_nft_gallery_opensea_type'] ) : 'assets';
		$nft_gallery['opensea_filterby'] = ! empty( $settings['eael_nft_gallery_opensea_filterby'] ) ? esc_html( $settings['eael_nft_gallery_opensea_filterby'] ) : 'none';
		$nft_gallery['order']            = ! empty( $settings['eael_nft_gallery_opensea_order'] ) ? esc_html( $settings['eael_nft_gallery_opensea_order'] ) : 'desc';
		$nft_gallery['item_limit']       = ! empty( $settings['eael_nft_gallery_opensea_item_limit'] ) ? esc_html( $settings['eael_nft_gallery_opensea_item_limit'] ) : 9;

		$expiration = ! empty( $settings['eael_nft_gallery_opensea_data_cache_time'] ) ? absint( $settings['eael_nft_gallery_opensea_data_cache_time'] ) * MINUTE_IN_SECONDS : DAY_IN_SECONDS;
		$md5        = md5( $nft_gallery['opensea_type'] . $nft_gallery['opensea_filterby'] . $settings['eael_nft_gallery_opensea_filterby_slug'] . $settings['eael_nft_gallery_opensea_filterby_wallet'] . $nft_gallery['item_limit'] . $nft_gallery['order'] . $this->get_id() );
		$cache_key  = "{$nft_gallery['source']}_{$expiration}_{$md5}_nftg_cache";
		$items      = get_transient( $cache_key );

		$error_message = '';

		if ( false === $items && 'opensea' === $nft_gallery['source'] ) {
			$nft_gallery['filterby_slug']   = ! empty( $settings['eael_nft_gallery_opensea_filterby_slug'] ) ? $settings['eael_nft_gallery_opensea_filterby_slug'] : '';
			$nft_gallery['filterby_wallet'] = ! empty( $settings['eael_nft_gallery_opensea_filterby_wallet'] ) ? $settings['eael_nft_gallery_opensea_filterby_wallet'] : '';

			$url   = "https://api.opensea.io/api/v2";
			$param = array();

			if ( 'collections' === $nft_gallery['opensea_type'] ) {
				$url .= "/collection/";
				$url .= sanitize_text_field( $nft_gallery['filterby_wallet'] )."/nfts";

				$args = array(
					'limit'  => $nft_gallery['item_limit'],
					'offset' => 0,
				);

				if ( ! empty( $nft_gallery['filterby_wallet'] ) ) {
					$args['asset_owner'] = sanitize_text_field( $nft_gallery['filterby_wallet'] );
				}

				$param = array_merge( $param, $args );
			} elseif ( 'assets' === $nft_gallery['opensea_type'] ) {
				$url  .= "/assets";
				$args = array(
					'include_orders'  => true,
					'limit'           => $nft_gallery['item_limit'],
					'order_direction' => $nft_gallery['order'],
				);

				if ( ! empty( $nft_gallery['filterby_slug'] ) && 'collection-slug' === $nft_gallery['opensea_filterby'] ) {
					$args['collection_slug'] = sanitize_text_field( $nft_gallery['filterby_slug'] );
				}

				if ( ! empty( $nft_gallery['filterby_wallet'] ) && 'wallet-address' === $nft_gallery['opensea_filterby'] ) {
					$args['owner'] = sanitize_text_field( $nft_gallery['filterby_wallet'] );
				}

				$param = array_merge( $param, $args );
			} else {
				$error_message = esc_html__( 'Please provide a valid Type!', 'essential-addons-for-elementor-lite' );
			}

			$headers = array(
				'headers' => array(
					'Content-Type' => 'application/json',
					'X-API-KEY'    => $nft_gallery['api_key'],
				)
			);
			$options = array(
				'timeout' => 240
			);

			$options = array_merge( $headers, $options );

			if ( empty( $error_message ) ) {
				$response = wp_remote_get(
					esc_url_raw( add_query_arg( $param, $url ) ),
					$options
				);
				
				$body     = json_decode( wp_remote_retrieve_body( $response ) );
				$response = 'assets' === $nft_gallery['opensea_type'] && ! empty( $body->assets ) ? $body->assets : $body;
				// $response = 'collections' === $nft_gallery['opensea_type'] && ! empty( $response->collections ) ? $response->collections : $response;
				$response = 'collections' === $nft_gallery['opensea_type'] && ! empty( $response->nfts ) ? $response->nfts : $response;

				if ( is_array( $response ) ) {
					$response = array_splice( $response, 0, absint( $settings['eael_nft_gallery_opensea_item_limit'] ) );
					set_transient( $cache_key, $response, $expiration );
					$this->nft_gallery_items_count = count( $response );
				} else {
					$error_message_text_wallet = $error_message_text_slug = '';

					if ( isset( $body->assets ) && is_array( $body->assets ) && 0 === count( $body->assets ) ) {
						$error_message_text_slug = __( 'Please provide a valid collection slug!', 'essential-addons-for-elementor-lite' );
					}

					if ( ! empty( $body->asset_owner ) && isset( $body->asset_owner[0] ) ) {
						$error_message_text_wallet = ! empty( $body->asset_owner[0] ) ? $body->asset_owner[0] : __( 'Please provide a valid wallet address!', 'essential-addons-for-elementor-lite' );
					} else if ( ! empty( $body->owner ) && isset( $body->owner[0] ) ) {
						$error_message_text_wallet = ! empty( $body->owner[0] ) ? $body->owner[0] : __( 'Please provide a valid wallet address!', 'essential-addons-for-elementor-lite' );
					}

					if ( 'assets' === $nft_gallery['opensea_type'] && 'collection-slug' === $nft_gallery['opensea_filterby'] ) {
						$error_message_text = $error_message_text_slug;
					}

					if ( 'collections' === $nft_gallery['opensea_type'] || ( 'assets' === $nft_gallery['opensea_type'] && 'wallet-address' === $nft_gallery['opensea_filterby'] ) ) {
						$error_message_text = $error_message_text_wallet;
					}

					if ( ! empty( $error_message_text ) ) {
						$error_message = esc_html( $error_message_text );
					}
				}
			}

			$data = [
				'items'         => $response,
				'error_message' => $error_message,
			];

			return $data;
		}

		$response                      = $items ? $items : $response;
		$this->nft_gallery_items_count = count( $response );

		$data = [
			'items'         => $response,
			'error_message' => $error_message,
		];

		return $data;
	}

    protected function render_loadmore_button()
    {
        $settings = $this->get_settings_for_display();
        $icon_migrated = isset($settings['__fa4_migrated']['eael_nft_gallery_load_more_icon_new']);
        $icon_is_new = empty($settings['eael_nft_gallery_load_more_icon']);

        $post_per_page = ! empty($settings['eael_nft_gallery_posts_per_page']) ? intval( $settings['eael_nft_gallery_posts_per_page'] ) : 6;
        $post_limit = ! empty( $settings['eael_nft_gallery_opensea_item_limit'] ) ? $settings['eael_nft_gallery_opensea_item_limit'] : 9;
        // $load_more_class = $post_per_page < $post_limit ? 'eael-d-block' : 'eael-d-none';
        
        $this->add_render_attribute('nft-gallery-load-more-button', 'class', [
            'eael-nft-gallery-load-more',
            'elementor-button',
            'elementor-size-' . esc_attr( $settings['eael_nft_gallery_button_size'] ),
        ]);
        
        if ( 'yes' === $settings['eael_nft_gallery_pagination'] && $this->nft_gallery_items_count > $post_per_page ) { ?>
            <div class="eael-nft-gallery-loadmore-wrap">
                <a href="#" <?php echo $this->get_render_attribute_string('nft-gallery-load-more-button'); ?>>
                    <span class="eael-btn-loader"></span>
                    <?php if ($settings['eael_nft_gallery_button_icon_position'] == 'before') { ?>
                        <?php if ($icon_is_new || $icon_migrated) { ?>
                            <?php if (isset($settings['eael_nft_gallery_load_more_icon_new']['value']['url'])) : ?>
                                <img class="eael-nft-gallery-load-more-icon nft-gallery-load-more-icon-left" src="<?php echo esc_url($settings['eael_nft_gallery_load_more_icon_new']['value']['url']); ?>" alt="<?php echo esc_attr(get_post_meta($settings['eael_nft_gallery_load_more_icon_new']['value']['id'], '_wp_attachment_image_alt', true)); ?>" />
                            <?php else : ?>
                                <span class="eael-nft-gallery-load-more-icon nft-gallery-load-more-icon-left <?php echo esc_attr($settings['eael_nft_gallery_load_more_icon_new']['value']); ?>" aria-hidden="true"></span>
                            <?php endif; ?>
                        <?php } else { ?>
                            <span class="eael-nft-gallery-load-more-icon nft-gallery-load-more-icon-left <?php echo esc_attr($settings['eael_nft_gallery_load_more_icon']); ?>" aria-hidden="true"></span>
                        <?php } ?>
                    <?php } ?>
                    <span class="eael-nft-gallery-load-more-text">
                        <?php echo Helper::eael_wp_kses($settings['eael_nft_gallery_load_more_text']); ?>
                    </span>
                    <?php if ($settings['eael_nft_gallery_button_icon_position'] == 'after') { ?>
                        <?php if ($icon_is_new || $icon_migrated) { ?>
                            <?php if (isset($settings['eael_nft_gallery_load_more_icon_new']['value']['url'])) : ?>
                                <img class="eael-nft-gallery-load-more-icon nft-gallery-load-more-icon-right" src="<?php echo esc_url($settings['eael_nft_gallery_load_more_icon_new']['value']['url']); ?>" alt="<?php echo esc_attr(get_post_meta($settings['eael_nft_gallery_load_more_icon_new']['value']['id'], '_wp_attachment_image_alt', true)); ?>" />
                            <?php else : ?>
                                <span class="eael-nft-gallery-load-more-icon nft-gallery-load-more-icon-right <?php echo esc_attr($settings['eael_nft_gallery_load_more_icon_new']['value']); ?>" aria-hidden="true"></span>
                            <?php endif; ?>
                        <?php } else { ?>
                            <span class="eael-nft-gallery-load-more-icon nft-gallery-load-more-icon-right <?php echo esc_attr($settings['load_more_icon']); ?>" aria-hidden="true"></span>
                        <?php } ?>
                    <?php } ?>
                </a>
            </div>
        <?php }
    }

	protected function render() {
		$nft_gallery_items = $this->fetch_nft_gallery_from_api();
		if( empty ( $nft_gallery_items['items'] ) ) {
			?>
			<p class="eael-nft-gallery-error-message">
				<?php esc_html_e( 'Please insert a valid API Key', 'essential-addons-for-elementor-lite' ); ?>
			</p>
			<?php
			return;
		}
		$this->print_nft_gallery( $nft_gallery_items );
	}
}
