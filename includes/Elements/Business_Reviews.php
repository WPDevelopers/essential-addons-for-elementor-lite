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

class Business_Reviews extends Widget_Base {
	
	public function get_name() {
		return 'eael-business-reviews';
	}

	public function get_title() {
		return esc_html__( 'Business Reviews', 'essential-addons-for-elementor-lite' );
	}

	public function get_icon() {
		return 'eaicon-nft-gallery';
	}

	public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

	public function get_keywords() {
		return [
			'reviews',
			'ea reviews',
			'business reviews',
			'ea business reviews',
			'google reviews',
			'ea google reviews',
			'ea',
			'essential addons'
		];
	}

	public function get_custom_help_url() {
		return 'https://essential-addons.com/elementor/docs/business-reviews/';
	}

	protected function register_controls() {

		/**
		 * Business Reviews Settings
		 */
		$this->start_controls_section(
			'eael_section_business_reviews_general_settings',
			[
				'label' => esc_html__( 'General', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'eael_business_reviews_sources',
			[
				'label'   => __( 'Source', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'google-reviews',
				'options' => [
					'google-reviews' => __( 'Google Reviews', 'essential-addons-for-elementor-lite' ),
				],
			]
		);

        if (empty(get_option('eael_br_google_place_api_key'))) {
            $this->add_control('eael_br_google_place_api_key_missing', [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => sprintf(__('Google Place API key is missing. Please add it from EA Dashboard » Elements » <a href="%s" target="_blank">Business Reviews Settings</a>', 'essential-addons-elementor'), esc_attr( site_url('/wp-admin/admin.php?page=eael-settings') )),
                'content_classes' => 'eael-warning',
                'condition' => [
                    'eael_business_reviews_sources' => 'google-reviews',
                ],
            ]);
        }

		$this->end_controls_section();

		/**
		 * Business Reviews Layout Settings
		 */
		$this->start_controls_section(
			'eael_section_business_reviews_layout_settings',
			[
				'label' => esc_html__( 'Layout', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->end_controls_section();

		/**
		 * Business Reviews Content
		 */
		$this->start_controls_section(
			'eael_section_business_reviews_content',
			[
				'label' => esc_html__( 'Content', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->end_controls_section();
	}

	/**
     * API Call to Get Business Reviews
     */
	public function fetch_business_reviews_from_api(){
		$settings = $this->get_settings();

		$response                        = [];
		$nft_gallery                     = [];
		$nft_gallery['source']           = ! empty( $settings['eael_nft_gallery_sources'] ) ? esc_html( $settings['eael_nft_gallery_sources'] ) : 'opensea';
		$nft_gallery['api_key']          = ! empty( $settings['eael_nft_gallery_source_key'] ) ? esc_html( $settings['eael_nft_gallery_source_key'] ) : 'b61c8a54123d4dcb9acc1b9c26a01cd1';
		$nft_gallery['opensea_type']     = ! empty( $settings['eael_nft_gallery_opensea_type'] ) ? esc_html( $settings['eael_nft_gallery_opensea_type'] ) : 'assets';
		$nft_gallery['opensea_filterby'] = ! empty( $settings['eael_nft_gallery_opensea_filterby'] ) ? esc_html( $settings['eael_nft_gallery_opensea_filterby'] ) : 'none';
		$nft_gallery['order']            = ! empty( $settings['eael_nft_gallery_opensea_order'] ) ? esc_html( $settings['eael_nft_gallery_opensea_order'] ) : 'desc';
		$nft_gallery['item_limit']       = ! empty( $settings['eael_nft_gallery_opensea_item_limit'] ) ? esc_html( $settings['eael_nft_gallery_opensea_item_limit'] ) : 9;

		$expiration = ! empty( $settings['eael_nft_gallery_opensea_data_cache_time'] ) ? absint( $settings['eael_nft_gallery_opensea_data_cache_time'] ) * MINUTE_IN_SECONDS : DAY_IN_SECONDS;
		$md5        = md5( $nft_gallery['api_key'] . $nft_gallery['opensea_type'] . $nft_gallery['opensea_filterby'] . $settings['eael_nft_gallery_opensea_filterby_slug'] . $settings['eael_nft_gallery_opensea_filterby_wallet'] . $nft_gallery['item_limit'] . $nft_gallery['order'] . $this->get_id() );
		$cache_key  = "{$nft_gallery['source']}_{$expiration}_{$md5}_nftg_cache";
		$items      = get_transient( $cache_key );

		$error_message = '';

		if ( false === $items && 'opensea' === $nft_gallery['source'] ) {
			$nft_gallery['filterby_slug']   = ! empty( $settings['eael_nft_gallery_opensea_filterby_slug'] ) ? $settings['eael_nft_gallery_opensea_filterby_slug'] : '';
			$nft_gallery['filterby_wallet'] = ! empty( $settings['eael_nft_gallery_opensea_filterby_wallet'] ) ? $settings['eael_nft_gallery_opensea_filterby_wallet'] : '';

			$url   = "https://api.opensea.io/api/v1";
			$param = array();

			if ( 'collections' === $nft_gallery['opensea_type'] ) {
				$url .= "/collections";

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
				$response = 'collections' === $nft_gallery['opensea_type'] && ! empty( $response->collections ) ? $response->collections : $response;

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

	protected function render() {
		echo "Business Reviews";
		$api_data = $this->fetch_business_reviews_from_api(); 
	}
}