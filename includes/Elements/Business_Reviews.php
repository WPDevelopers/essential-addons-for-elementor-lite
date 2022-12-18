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
				'label' => esc_html__( 'Query', 'essential-addons-for-elementor-lite' ),
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

                        $item_formatted['view_details_link'] = ! empty( $item->permalink ) ? $item->permalink : '#';
                        if( 'collections' === $nft_gallery['opensea_type'] ){
                            $item_formatted['view_details_link'] = ! empty( $item->slug ) ? esc_url( "{$nft_gallery['api_url']}/collection/{$item->slug}" ) : '#'; 
                        }
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
		$this->print_nft_gallery( $nft_gallery_items );
	}
}
