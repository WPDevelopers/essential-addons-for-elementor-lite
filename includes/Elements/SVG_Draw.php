<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use \Elementor\Plugin;
use Elementor\Repeater;
use \Elementor\Utils;
use \Elementor\Widget_Base;
use \Essential_Addons_Elementor\Classes\Helper;

class SVG_Draw Extends Widget_Base
{
    public function get_name()
    {
        return 'eael-svg-draw';
    }

    public function get_title()
    {
        return esc_html__('SVG Draw', 'essential-addons-for-elementor-lite');
    }

    public function get_icon()
    {
        return 'eaicon-advanced-tabs';
    }

    public function get_categories()
    {
        return ['essential-addons-elementor'];
    }

    public function get_keywords()
    {
        return [
            'svg',
            'draq',
            'ea svg',
            'ea svg draw',
            'animation',
            'icon',
            'icon animation',
            'ea',
            'essential addons',
        ];
    }

    public function get_custom_help_url()
    {
        return 'https://essential-addons.com/elementor/docs/advanced-tabs/';
    }

    protected function default_custom_svg(){
        return '<?xml version="1.0" encoding="utf-8"?>
        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
             viewBox="0 0 453 446" style="enable-background:new 0 0 453 446;" xml:space="preserve">
        <style type="text/css">
                .st0{fill-rule:evenodd;clip-rule:evenodd;fill:none;stroke:url(#Oval-2_00000151511668215971844540000016224869794113085858_);stroke-miterlimit:10;}
                .st1{fill-rule:evenodd;clip-rule:evenodd;fill:none;stroke:url(#path-2_00000067215102227892227950000017682701467040769152_);stroke-miterlimit:10;}
                .st2{fill-rule:evenodd;clip-rule:evenodd;fill:none;stroke:url(#path-4_00000095309718583342431940000012296790016936032700_);stroke-miterlimit:10;}
                .st3{fill-rule:evenodd;clip-rule:evenodd;fill:none;stroke:url(#SVGID_1_);stroke-miterlimit:10;}
                .st4{fill-rule:evenodd;clip-rule:evenodd;fill:none;stroke:url(#path-6_00000140703348777575726570000012115332783839925414_);stroke-miterlimit:10;}
                .st5{fill-rule:evenodd;clip-rule:evenodd;fill:none;stroke:url(#SVGID_00000183211002921542310410000012442653706632503952_);stroke-miterlimit:10;}
                .st6{fill-rule:evenodd;clip-rule:evenodd;fill:none;stroke:url(#path-8_00000062902146658847885510000014059784765189564040_);stroke-miterlimit:10;}
                .st7{fill-rule:evenodd;clip-rule:evenodd;fill:none;stroke:url(#SVGID_00000003078952430119260560000001354442033507913135_);stroke-miterlimit:10;}
                .st8{fill-rule:evenodd;clip-rule:evenodd;fill:none;stroke:url(#Oval-3-Copy-4_00000015335616516388208180000000617709353380865980_);stroke-miterlimit:10;}
                .st9{fill-rule:evenodd;clip-rule:evenodd;fill:none;stroke:url(#Oval-3-Copy-5_00000119821939156648283890000002877478347537821626_);stroke-miterlimit:10;}
                .st10{opacity:0.8979;fill-rule:evenodd;clip-rule:evenodd;fill:none;stroke:url(#Fill-1_00000152227201135957055690000017554085413242967734_);stroke-miterlimit:10;enable-background:new    ;}
                .st11{opacity:9.687500e-02;fill-rule:evenodd;clip-rule:evenodd;fill:#FFFFFF;enable-background:new    ;}
        </style>

		<linearGradient id="Oval-2_00000161607733814802183690000013150901469743589285_" gradientUnits="userSpaceOnUse" x1="215.3" y1="21.8" x2="215.3" y2="419">
            <stop  offset="0" style="stop-color:#2460FF"/>
            <stop  offset="1" style="stop-color:#B80AFF"/>
        </linearGradient>
		<circle id="Oval-2" style="fill-rule:evenodd;clip-rule:evenodd;fill:none;stroke:url(#Oval-2_00000161607733814802183690000013150901469743589285_);stroke-miterlimit:10;" cx="215.3" cy="220.4" r="198.1"/>
	    <g>
			<linearGradient id="path-2_00000063630012216441537080000002634623382565555639_" gradientUnits="userSpaceOnUse" x1="97.5" y1="215" x2="342.1" y2="215">
                <stop  offset="0" style="stop-color:#2460FF"/>
                <stop  offset="1" style="stop-color:#B80AFF"/>
            </linearGradient>
		
			<path id="path-2" style="fill-rule:evenodd;clip-rule:evenodd;fill:none;stroke:url(#path-2_00000063630012216441537080000002634623382565555639_);stroke-miterlimit:10;" d="
			M108.9,151.3h62.3l0.3,0.1h0.2c3.7,0,6.8,1.9,9.1,5.7c0.8,1.6,1.2,3.2,1.2,4.9c0,4.1-2,7.3-6.1,9.5c-1.4,0.6-2.8,1-4.2,1h-63.5
			c-3,0-5.6-1.4-7.9-4.1c-1.5-2-2.2-4-2.2-6.1v-0.9c0-3.3,1.6-6.1,4.9-8.4c1.8-1.1,3.6-1.6,5.3-1.6h0.3L108.9,151.3z M108.7,204.5
			l32.1,0.1c2.1,0,4.4,1.1,6.9,3.3c1.9,2.2,2.9,4.7,2.9,7.3c0,3.7-1.7,6.7-5.1,9c-1.7,1-3.3,1.5-4.9,1.5h-32.5
			c-3.2,0-5.9-1.6-8.3-4.8c-1.1-1.8-1.7-3.6-1.7-5.4v-0.9c0-3.4,1.8-6.3,5.3-8.7c1.8-0.9,3.5-1.3,5.1-1.3h0.1
			C108.7,204.7,108.7,204.6,108.7,204.5z M171.1,204.7h0.5c4,0,7.2,2.1,9.5,6.3c0.6,1.5,0.9,2.9,0.9,4.3c0,4.2-2.1,7.4-6.2,9.6
			c-1.5,0.6-2.8,0.9-3.9,0.9h-1c-3.4,0-6.3-1.8-8.6-5.3c-0.9-1.8-1.4-3.6-1.4-5.3c0-4.1,2-7.3,6.1-9.5
			C168.4,205,169.8,204.7,171.1,204.7z M108.9,257.6h62.3c0,0.1,0.8,0.2,2.3,0.4c2.6,0.6,4.5,1.8,5.9,3.5c1.7,2,2.6,4.4,2.6,7
			c0,4-2,7.2-6.1,9.5c-1.2,0.4-2.1,0.7-2.5,0.7h-66.7c-1.7,0-3.8-1.2-6.3-3.5c-1.6-2.1-2.4-4.3-2.4-6.5V268c0-3.7,1.9-6.7,5.6-9
			c1.1-0.6,2.8-1.1,5.1-1.3C108.7,257.6,108.8,257.6,108.9,257.6z M277.8,152.4h0.9c3.9,0,7.1,1.9,9.6,5.8
			c18.9,37.6,28.5,56.8,28.8,57.6c0.7,2.4,1,3.9,1,4.4c0,4.5-2.2,8-6.5,10.5c-1.8,0.9-3.7,1.3-5.4,1.3c-3.9,0-7.1-1.8-9.8-5.3
			c-0.3-0.4-5.6-11-15.9-31.6c-0.7-0.7-1.5-1-2.3-1c-1.6,0-2.9,1.4-3.9,4.2l-38.1,75.5c-1.5,2.2-3.7,3.8-6.6,4.8
			c-0.4,0.1-0.7,0.1-1,0.1h-3.9c-1.7,0-3.8-1-6.2-3c-2.4-2.4-3.6-5.3-3.6-8.5c0-2.2,1.7-6.5,5.1-12.7c0-0.1,13.2-26.4,39.5-78.9
			c5.8-11.6,8.9-17.8,9.3-18.4C271.5,154,274.5,152.4,277.8,152.4z M329.5,255.9h1c3.8,0,6.9,1.9,9.5,5.7c1.1,2,1.6,3.9,1.6,5.7
			c0,4.3-2.1,7.7-6.3,10.3c-1.5,0.7-2.6,1-3.4,1H328c-1.8,0-3.9-1.1-6.4-3.3c-2.1-2.4-3.2-5.1-3.2-8c0-3.7,1.6-6.8,4.7-9.3
			C325.3,256.6,327.5,255.9,329.5,255.9z"/>
	    </g>
        <g id="Oval-3">
            <g>
				<linearGradient id="path-4_00000013187280261654325360000008157552587463575961_" gradientUnits="userSpaceOnUse" x1="295.3" y1="64.1" x2="413.9" y2="64.1">
                    <stop  offset="0" style="stop-color:#2460FF"/>
                    <stop  offset="1" style="stop-color:#B80AFF"/>
                </linearGradient>
			
				<circle id="path-4" style="fill-rule:evenodd;clip-rule:evenodd;fill:none;stroke:url(#path-4_00000013187280261654325360000008157552587463575961_);stroke-miterlimit:10;" cx="354.6" cy="64.1" r="58.8"/>
            </g>
            <g>
                <linearGradient id="SVGID_1_" gradientUnits="userSpaceOnUse" x1="295.3" y1="64.1" x2="413.9" y2="64.1">
                    <stop  offset="0" style="stop-color:#2460FF"/>
                    <stop  offset="1" style="stop-color:#B80AFF"/>
                </linearGradient>
                <circle class="st3" cx="354.6" cy="64.1" r="58.8"/>
            </g>
	    </g>
        <g>
            <g>
				<linearGradient id="path-6_00000151514500429612142290000005873217836294445706_" gradientUnits="userSpaceOnUse" x1="336.7" y1="64.6" x2="373.7" y2="64.6">
                    <stop  offset="0" style="stop-color:#2460FF"/>
                    <stop  offset="1" style="stop-color:#B80AFF"/>
                </linearGradient>
				<circle id="path-6" style="fill-rule:evenodd;clip-rule:evenodd;fill:none;stroke:url(#path-6_00000151514500429612142290000005873217836294445706_);stroke-miterlimit:10;" cx="355.2" cy="64.6" r="18"/>
		    </g>
		<g>
			
        <linearGradient id="SVGID_00000091011230016077184440000017029732425011264692_" gradientUnits="userSpaceOnUse" x1="336.7" y1="64.6" x2="373.7" y2="64.6">
            <stop  offset="0" style="stop-color:#2460FF"/>
            <stop  offset="1" style="stop-color:#B80AFF"/>
        </linearGradient>
		<circle style="fill-rule:evenodd;clip-rule:evenodd;fill:none;stroke:url(#SVGID_00000091011230016077184440000017029732425011264692_);stroke-miterlimit:10;" cx="355.2" cy="64.6" r="18"/>
		</g>
	</g>
	<g id="Oval-3-Copy-2" transform="translate(63.500000, 348.500000) rotate(-7.000000) translate(-63.500000, -348.500000) ">
		<g>
			<linearGradient id="path-8_00000168815893573796518970000004757256474992916367_" gradientUnits="userSpaceOnUse" x1="71.2464" y1="370.3007" x2="92.4485" y2="370.3007">
				<stop  offset="0" style="stop-color:#2460FF"/>
				<stop  offset="1" style="stop-color:#B80AFF"/>
			</linearGradient>
			<circle id="path-8" style="fill-rule:evenodd;clip-rule:evenodd;fill:none;stroke:url(#path-8_00000168815893573796518970000004757256474992916367_);stroke-miterlimit:10;" cx="81.8" cy="371.6" r="10.1"/>
		</g>
		<g>
			<linearGradient id="SVGID_00000016054022669116523870000016562714246145798554_" gradientUnits="userSpaceOnUse" x1="71.2464" y1="370.3007" x2="92.4485" y2="370.3007">
				<stop  offset="0" style="stop-color:#2460FF"/>
				<stop  offset="1" style="stop-color:#B80AFF"/>
			</linearGradient>
			<circle style="fill-rule:evenodd;clip-rule:evenodd;fill:none;stroke:url(#SVGID_00000016054022669116523870000016562714246145798554_);stroke-miterlimit:10;" cx="81.8" cy="371.6" r="10.1"/>
		</g>
	</g>
		<linearGradient id="Oval-3-Copy-4_00000174568940665330228410000007983366974174749594_" gradientUnits="userSpaceOnUse" x1="411.9" y1="124" x2="438.3" y2="124">
            <stop  offset="0" style="stop-color:#2460FF"/>
            <stop  offset="1" style="stop-color:#B80AFF"/>
        </linearGradient>
	
		<circle id="Oval-3-Copy-4" style="fill-rule:evenodd;clip-rule:evenodd;fill:none;stroke:url(#Oval-3-Copy-4_00000174568940665330228410000007983366974174749594_);stroke-miterlimit:10;" cx="425.1" cy="124" r="12.7"/>
	
		<linearGradient id="Oval-3-Copy-5_00000101817245756979779460000007449712466010602672_" gradientUnits="userSpaceOnUse" x1="279.5" y1="9.5" x2="299.5" y2="9.5">
            <stop  offset="0" style="stop-color:#2460FF"/>
            <stop  offset="1" style="stop-color:#B80AFF"/>
        </linearGradient>
	
		<circle id="Oval-3-Copy-5" style="fill-rule:evenodd;clip-rule:evenodd;fill:none;stroke:url(#Oval-3-Copy-5_00000101817245756979779460000007449712466010602672_);stroke-miterlimit:10;" cx="289.5" cy="9.5" r="9.5"/>
	
		<linearGradient id="Fill-1_00000135673001837680446080000018409398477174358943_" gradientUnits="userSpaceOnUse" x1="231.3463" y1="381.0921" x2="94.9135" y2="430.2864">
            <stop  offset="0" style="stop-color:#FF710F"/>
            <stop  offset="1" style="stop-color:#CB16A9"/>
        </linearGradient>
	
            <path id="Fill-1" style="opacity:0.8979;fill-rule:evenodd;clip-rule:evenodd;fill:none;stroke:url(#Fill-1_00000135673001837680446080000018409398477174358943_);stroke-miterlimit:10;enable-background:new    ;" d="
            M198.3,367.6c-7.7,1.5-14.1,5.7-18.5,11.5c-4.8,6.3-11.8,10.5-19.5,12.1c-7.7,1.5-15.7,0.3-22.5-3.8c-6.7-4-14.8-5.5-23-3.5
            c-7.6,1.9-13.9,6.5-18.1,12.6l-0.1,0.1c-4.1,6.1-6.1,13.8-4.9,21.5c2.6,17.4,19.1,29,36.2,25.7c7.7-1.5,14.1-5.7,18.5-11.5
            c4.8-6.3,11.8-10.5,19.5-12.1c7.7-1.5,15.7-0.3,22.5,3.8c6.6,4,14.8,5.5,23,3.5c7.6-1.9,13.9-6.5,18.1-12.6l0.1-0.2
            c4.1-6.1,6-13.7,4.9-21.5c-1.3-9-6.3-16.4-13.2-21C214.8,367.9,206.7,366,198.3,367.6"/>
            <path class="st11" d="M251.3,44.5c-7.8,0-14.2,6.4-14.2,14.2c0,3.6,1.4,6.9,3.6,9.4c2.4,2.7,3.8,6.3,3.8,9.9l0,0
            c0,3.4-1.1,6.7-3.2,9.3c-2.6,2.1-5.9,3.2-9.3,3.2c-3.7,0-7.2-1.3-9.9-3.8c-2.5-2.2-5.8-3.6-9.4-3.6c-8.1,0-14.6,6.7-14.2,14.9
            c0.3,7.3,6.2,13.2,13.5,13.5c3.9,0.2,7.5-1.2,10.2-3.6s6.3-3.7,9.9-3.7c3.7,0,7.2,1.3,10,3.8c2.5,2.2,5.8,3.6,9.4,3.6
            c1.8,0,3.5-0.3,5.1-0.9l0,0c0.2-0.1,0.4-0.2,0.6-0.3c0,0,0,0,0.1,0c0.2-0.1,0.4-0.2,0.6-0.3h0.1c0.2-0.1,0.4-0.2,0.5-0.3
            c0,0,0.1,0,0.1-0.1c0.2-0.1,0.3-0.2,0.5-0.3l0.1-0.1c0.1-0.1,0.3-0.2,0.4-0.3c0.1,0,0.1-0.1,0.2-0.1c0.1-0.1,0.3-0.2,0.4-0.3
            c0.1-0.1,0.1-0.1,0.2-0.2c0.1-0.1,0.2-0.2,0.3-0.3c0.1-0.1,0.2-0.1,0.2-0.2c0.1-0.1,0.2-0.2,0.3-0.3c0.1-0.1,0.2-0.2,0.2-0.2
            c0.1-0.1,0.2-0.2,0.2-0.2c0.1-0.1,0.2-0.2,0.3-0.3c0.1-0.1,0.1-0.1,0.2-0.2c0.1-0.1,0.2-0.2,0.3-0.3c0.1-0.1,0.1-0.1,0.2-0.2
            c0.1-0.1,0.2-0.3,0.3-0.4c0-0.1,0.1-0.1,0.1-0.2c0.1-0.1,0.2-0.3,0.3-0.4l0.1-0.1c0.1-0.2,0.2-0.3,0.3-0.5c0,0,0-0.1,0.1-0.1
            c0.1-0.2,0.2-0.4,0.3-0.5v-0.1c0.1-0.2,0.2-0.4,0.3-0.6c0,0,0,0,0-0.1c0.1-0.2,0.2-0.4,0.3-0.6l0,0c0.6-1.6,0.9-3.3,0.9-5.1
            c0-3.6-1.4-6.9-3.6-9.4c-2.4-2.7-3.8-6.3-3.8-10c0-3.6,1.3-7.2,3.7-9.9s3.8-6.3,3.6-10.2c-0.3-7.3-6.3-13.2-13.5-13.5
            c-0.2,0-0.4,0-0.6,0C251.4,44.5,251.3,44.5,251.3,44.5z"/>
        </g>
    </svg>
    ';
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'eael_section_svg_content_settings',
            [
                'label' => esc_html__('General', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'eael_svg_src',
            [
                'label' => esc_html__( 'Source', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'custom',
                'options' => [
                    'icon' => esc_html__( 'Icon', 'essential-addons-for-elementor-lite' ),
                    'custom' => esc_html__( 'Custom HTML', 'essential-addons-for-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'eael_svg_icon',
            [
                'label' => esc_html__( 'Icon', 'textdomain' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-circle',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'eael_svg_src' => 'icon'
                ]
            ]
        );

        $this->add_control(
            'svg_html',
            [
                'label' => esc_html__( 'SVG Code', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::TEXTAREA,
                'condition' => [
                    'eael_svg_src' => 'custom'
                ],
                'default' => $this->default_custom_svg(),
                'description' => esc_html__( 'SVG draw works best on path elements.', 'essential-addons-for-elementor-lite' ),
            ]
        );



        $this->add_control(
            'eael_svg_exclude_style',
            [
                'label' => esc_html__( 'Exclude Style', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'essential-addons-for-elementor-lite' ),
                'label_off' => esc_html__( 'No', 'essential-addons-for-elementor-lite' ),
                'default' => 'no',
                'description' => esc_html__( 'Exclude style from SVG Source (If any).', 'essential-addons-for-elementor-lite' )
            ]
        );

        $this->add_responsive_control(
            'eael_svg_width',
            [
                'label' => esc_html__( 'Width', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 500,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 200,
                ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} svg' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_svg_height',
            [
                'label' => esc_html__( 'Height', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 200,
                ],
                'selectors' => [
                    '{{WRAPPER}} svg' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_svg_alignment',
            [
                'label' => esc_html__( 'Alignment', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'essential-addons-for-elementor-lite' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'essential-addons-for-elementor-lite' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'essential-addons-for-elementor-lite' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .eael-svg-draw-container' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_svg_link',
            [
                'label' => esc_html__( 'Link', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'essential-addons-for-elementor-lite' ),
                'options' => [ 'url' ],
                'label_block' => true,
                'separator' => 'before'
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'eael_section_svg_appearance',
            [
                'label' => esc_html__('Appearance', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'eael_svg_fill',
            [
                'label' => esc_html__( 'SVG Fill Type', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => esc_html__( 'None', 'essential-addons-for-elementor-lite' ),
                    'after' => esc_html__( 'Fill After Draw', 'essential-addons-for-elementor-lite' ),
                    'before' => esc_html__( 'Fill Before Draw', 'essential-addons-for-elementor-lite' ),
                ],
            ]
        );


        $this->add_control(
            'eael_svg_fill_transition',
            [
                'label' => esc_html__( 'Fill Transition', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 1,
                'min' => 0,
                'selectors' => [
                    '{{WRAPPER}} .fill-svg svg path' => 'animation-duration: {{SIZE}}s;',
                ],
                'description' => esc_html__( 'Duration on SVG fills (in seconds)', 'essential-addons-for-elementor-lite' )
            ]
        );

        $this->add_control(
            'eael_svg_animation_on',
            [
                'label' => esc_html__( 'Animation', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'page-load',
                'options' => [
                    'none' => esc_html__( 'None', 'essential-addons-for-elementor-lite' ),
                    'page-load' => esc_html__( 'On Page Load', 'essential-addons-for-elementor-lite' ),
                    'page-scroll' => esc_html__( 'On Page Scroll', 'essential-addons-for-elementor-lite' ),
                    'hover'  => esc_html__( 'Mouse Hover', 'essential-addons-for-elementor-lite' ),
                ],
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'eael_svg_draw_offset',
            [
                'label' => esc_html__( 'Drawing Start Point', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 1000,
                'step' => 1,
                'default' => 50,
                'condition' => [
                    'eael_svg_animation_on' => [ 'page-scroll'],
                ],
                'description' => esc_html__( 'The point at which the drawing begins to animate as scrolls down (in pixels).', 'essential-addons-for-elementor-lite' )
            ]
        );

        $this->add_control(
            'eael_svg_pause_on_hover',
            [
                'label' => esc_html__( 'Pause on Hover Off', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'essential-addons-for-elementor-lite' ),
                'label_off' => esc_html__( 'No', 'essential-addons-for-elementor-lite' ),
                'default' => 'yes',
                'condition' => [
                    'eael_svg_animation_on' => 'hover',
                ],
                'description' => esc_html__( 'Pause SVG drawing on mouse leave', 'essential-addons-for-elementor-lite' )
            ]
        );

        $this->add_control(
            'eael_svg_loop',
            [
                'label' => esc_html__( 'Repeat Drawing', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'essential-addons-for-elementor-lite' ),
                'label_off' => esc_html__( 'No', 'essential-addons-for-elementor-lite' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'eael_svg_animation_on!' => [ 'page-scroll', 'none'],
                ]
            ]
        );

        $this->add_control(
            'eael_svg_animation_direction',
            [
                'label' => esc_html__( 'Direction', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'reverse',
                'options' => [
                    'reverse' => esc_html__( 'Reverse', 'essential-addons-for-elementor-lite' ),
                    'restart' => esc_html__( 'Restart', 'essential-addons-for-elementor-lite' ),
                ],
                'condition' => [
                    'eael_svg_animation_on!' => [ 'page-scroll', 'none'],
                    'eael_svg_loop' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'eael_svg_draw_speed',
            [
                'label' => esc_html__( 'Speed', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 300,
                'step' => 1,
                'default' => 20,
                'condition' => [
                    'eael_svg_animation_on!' => [ 'page-scroll'],
                ],
                'description' => esc_html__( 'Duration on SVG draws (in ms)', 'essential-addons-for-elementor-lite' )
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'eael_section_svg_style_settings',
            [
                'label' => esc_html__('Style', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_svg_path_thickness',
            [
                'label' => esc_html__( 'Path Thickness', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => .1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 3,
                ],
                'selectors' => [
                    '{{WRAPPER}} svg path' => 'stroke-width: {{SIZE}};',
                    '{{WRAPPER}} svg circle' => 'stroke-width: {{SIZE}};',
                    '{{WRAPPER}} svg rect' => 'stroke-width: {{SIZE}};',
                    '{{WRAPPER}} svg polygon' => 'stroke-width: {{SIZE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_svg_color',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
                'selectors' => [
                    '{{WRAPPER}} svg path' => 'stroke:{{VALUE}};',
                    '{{WRAPPER}} svg circle' => 'stroke:{{VALUE}};',
                    '{{WRAPPER}} svg rect' => 'stroke:{{VALUE}};',
                    '{{WRAPPER}} svg polygon' => 'stroke:{{VALUE}};',
                ],
                'default' => '#974CF3'
            ]
        );

        $this->add_control(
            'eael_svg_fill_color',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__( 'Fill Color', 'essential-addons-for-elementor-lite' ),
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container .fill-svg svg path' => 'fill:{{VALUE}};',
                    '{{WRAPPER}} .elementor-widget-container .eael-svg-draw-container.fill-svg svg path' => 'fill:{{VALUE}};',
                    '{{WRAPPER}} .elementor-widget-container .eael-svg-draw-container.fill-svg svg circle' => 'fill:{{VALUE}};',
                    '{{WRAPPER}} .elementor-widget-container .eael-svg-draw-container.fill-svg svg rect' => 'fill:{{VALUE}};',
                    '{{WRAPPER}} .elementor-widget-container .eael-svg-draw-container.fill-svg svg polygon' => 'fill:{{VALUE}};'
                ],
                'default' => '#D8C2F3',
                'condition' => [
                    'eael_svg_fill!' => 'none'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'eael_svg_background',
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .eael-svg-draw-container svg',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_svg_border',
                'selector' => '{{WRAPPER}} .eael-svg-draw-container svg',
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'eael_svg_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .eael-svg-draw-container svg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_svg_padding',
            [
                'label' => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .eael-svg-draw-container svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_svg_margin',
            [
                'label' => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .eael-svg-draw-container svg' => 'Margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'selector' => '{{WRAPPER}} .eael-svg-draw-container svg',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $svg_html = isset( $settings['svg_html'] ) ? preg_replace('#<script(.*?)>(.*?)</script>#is', '', $settings['svg_html'] ) : '';
        $this->add_render_attribute('eael-svg-drow-wrapper', [
            'class'           => [
                'eael-svg-draw-container',
                esc_attr( $settings['eael_svg_animation_on'] ),
                $settings['eael_svg_fill'] === 'before' ? 'fill-svg' : ''
            ],
        ]);

        $svg_options = [
            'fill' => $settings['eael_svg_fill'] === 'after' ? 'fill-svg' : '',
            'speed' => esc_attr( $settings['eael_svg_draw_speed'] ),
            'offset' => esc_attr( $settings['eael_svg_draw_offset'] ),
            'loop' => $settings['eael_svg_loop'] ? esc_attr( $settings['eael_svg_loop'] ) : 'no',
            'pause' => $settings['eael_svg_pause_on_hover'] ? esc_attr( $settings['eael_svg_pause_on_hover'] ) : 'no',
            'direction' => esc_attr( $settings['eael_svg_animation_direction'] ),
            'excludeStyle' => esc_attr( $settings['eael_svg_exclude_style'] )
        ];

        $this->add_render_attribute('eael-svg-drow-wrapper', [
            'data-settings' => json_encode($svg_options)
        ]);

        if ( ! empty( $settings['eael_svg_link']['url'] ) ) {
            $this->add_link_attributes( 'eael_svg_link', $settings['eael_svg_link'] );
            echo '<a ' . $this->get_render_attribute_string( 'eael_svg_link' ) . '>';
        }

        echo '<div ' . $this->get_render_attribute_string('eael-svg-drow-wrapper') . '>';

        if ( $settings['eael_svg_src'] === 'icon' ):

            if ( $settings['eael_svg_icon']['library'] === 'svg' ):
                Icons_Manager::render_icon($settings['eael_svg_icon'], ['aria-hidden' => 'true', 'class' => ['eael-svg-drow-wrapper']]);
            else:
               echo Helper::get_svg_by_icon( $settings['eael_svg_icon'] );
            endif;

        else:
            echo $svg_html;
        endif;

        echo ' </div>';

        if ( ! empty( $settings['eael_svg_link']['url'] ) ) {
            echo "</a>";
        }

    }
}