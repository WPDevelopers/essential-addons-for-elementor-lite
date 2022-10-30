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
         * NFT Image Settings
         */
        $this->start_controls_section(
            'eael_section_nft_gallery_settings',
            [
                'label' => esc_html__('NFT Gallery', 'essential-addons-for-elementor-lite'),
            ]
        );

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

        $this->end_controls_section();
    }

    public function print_nft_gallery()
    {
        ob_start();
?>
        <div class="eael-nft-gallery-wrapper">
            <div class="eael-nft-gallery-items eael-nft-grid preset-1">
                <?php for ($i = 0; $i < 5; $i++) : ?>
                    <div class="eael-nft-item">
                        <!-- Thumbnail -->
                        <div class="eael-nft-thumbnail">
                            <img src="https://lh3.googleusercontent.com/z2cLJdA7S3i6y2GYHvMDhDTsmL0UtXzVaIdj7aXZ8Y2gr_2MxoPH1RIcJrUlwulmDggw4xMEcDrY_GL6eK6C9R-VvI7ZgpUm_EYn" alt="Angry Cat #3297">
                        </div>

                        <div class="eael-nft-main-content">
                            <!-- Content  -->
                            <div class="eael-nft-content">
                                <!-- Title  -->
                                <h3 class="eael-nft-title">Angry Cat #3297</h3>

                                <!-- Creator -->
                                <div class="eael-nft-creator-wrapper">
                                    <div class="eael-nft-creator-img">
                                        <img src="https://i.seadn.io/gcs/files/856e56e379fcd78b5ad956a3eb2d7247.png?w=500&amp;auto=format" alt="AngryCat_dev">
                                    </div>
                                    <div class="eael-nft-created-by">
                                        <span>Owned by </span>
                                        <a target="_blank" href="https://opensea.io/AngryCat_dev">AngryCat_dev</a>
                                    </div>
                                </div>

                                <!-- Owner -->
                                <div class="eael-nft-owner-wrapper">
                                    <div class="eael-nft-owner-img">
                                        <img src="https://i.seadn.io/gcs/files/856e56e379fcd78b5ad956a3eb2d7247.png?w=500&amp;auto=format" alt="AngryCat_dev">
                                    </div>
                                    <div class="eael-nft-owned-by">
                                        <span>Created by </span>
                                        <a target="_blank" href="https://opensea.io/AngryCat_dev">AngryCat_dev</a>
                                    </div>
                                </div>

                                <!-- Price -->
                                <div class="eael-nft-price-wrapper">
                                    <span class="eael-nft-price-label eael-d-none">Price</span>
                                    <span class="eael-nft-price-currency eael-d-none">
                                        <svg width="1200" height="450" viewBox="0 0 256 417" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid">
                                            <path fill="#343434" d="m127.961 0-2.795 9.5v275.668l2.795 2.79 127.962-75.638z"></path>
                                            <path fill="#8C8C8C" d="M127.962 0 0 212.32l127.962 75.639V154.158z"></path>
                                            <path fill="#3C3C3B" d="m127.961 312.187-1.575 1.92v98.199l1.575 4.6L256 236.587z">
                                            </path>
                                            <path fill="#8C8C8C" d="M127.962 416.905v-104.72L0 236.585z"></path>
                                            <path fill="#141414" d="m127.961 287.958 127.96-75.637-127.96-58.162z"></path>
                                            <path fill="#393939" d="m0 212.32 127.96 75.638v-133.8z"></path>
                                        </svg>
                                    </span>
                                    <span class="eael-nft-price-amount eael-d-none">0</span>
                                </div>

                                <!-- Last Sale -->
                                <div class="eael-nft-last-sale-wrapper eael-d-none">
                                    <span class="eael-nft-last-sale-label">Last Sale</span>
                                    <span class="eael-nft-last-sale-currency">
                                        <svg width="1200" height="450" viewBox="0 0 256 417" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid">
                                            <path fill="#343434" d="m127.961 0-2.795 9.5v275.668l2.795 2.79 127.962-75.638z"></path>
                                            <path fill="#8C8C8C" d="M127.962 0 0 212.32l127.962 75.639V154.158z"></path>
                                            <path fill="#3C3C3B" d="m127.961 312.187-1.575 1.92v98.199l1.575 4.6L256 236.587z">
                                            </path>
                                            <path fill="#8C8C8C" d="M127.962 416.905v-104.72L0 236.585z"></path>
                                            <path fill="#141414" d="m127.961 287.958 127.96-75.637-127.96-58.162z"></path>
                                            <path fill="#393939" d="m0 212.32 127.96 75.638v-133.8z"></path>
                                        </svg>
                                    </span>
                                    <span class="eael-nft-last-sale-amount">0.298</span>
                                </div>
                            </div>

                            <!-- Button -->
                            <div class="eael-nft-button">
                                <button>
                                    <a target="_blank" href="https://opensea.io/assets/ethereum/0xdcf68c8ebb18df1419c7dff17ed33505faf8a20c/9999">
                                        View Details</a>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endfor; ?>
                <!-- /.column  -->
            </div>
        </div>
<?php
        echo ob_get_clean();
    }

    protected function render()
    {
        $this->print_nft_gallery();
    }
}
