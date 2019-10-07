<?php
namespace Essential_Addons_Elementor\Elements;

if (!defined('ABSPATH')) { exit; }

use \Elementor\Controls_Manager;
use \Elementor\Widget_Base;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Image_Size;
use Elementor\Modules\DynamicTags\Module as TagsModule;

class Sticky_Video extends Widget_Base {
	use \Essential_Addons_Elementor\Traits\Helper;

	public function get_name() {
		return 'eael-sticky-video';
	}

	public function get_title() {
		return esc_html__( 'EA Sticky Video', 'essential-addons-elementor' );
	}

	public function get_icon() {
		return 'eicon-youtube';
	}

   public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}
	
	protected function _register_controls() {
		//add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'eaelsv_custom_scripts' ] );

  		$this->start_controls_section(
  			'eael_section_video_settings',
  			[
				'label' => esc_html__( 'Video', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
  			]
		);

		$this->add_control(
			'eael_video_source',
			[
				'label'         => __( 'Source', 'essential-addons-elementor' ),
                'type'          => Controls_Manager::SELECT,
                'default'       => 'youtube',
                'options'       => [
					'youtube'   	=> __( 'YouTube', 'essential-addons-elementor' ),
					'vimeo'       	=> __( 'Vimeo', 'essential-addons-elementor' ),
					'dailymotion'	=> __( 'Dailymotion', 'essential-addons-elementor' ),
					'self_hosted'	=> __( 'Self Hosted', 'essential-addons-elementor' ),
				],
            ]
		);
		
		$this->add_control(
			'eaelsv_link_youtube',
			[
				'label'         => __( 'Link', 'essential-addons-elementor' ),
				'type'          => Controls_Manager::TEXT,
				'placeholder'   => __( 'Enter your URL (YouTube)', 'essential-addons-elementor' ),
				'label_block' => true,
                'condition'     => [
                    'eael_video_source' => 'youtube'
                ]
            ]
		);
		
		$this->add_control(
			'eaelsv_link_vimeo',
			[
				'label'         => __( 'Link', 'essential-addons-elementor' ),
				'type'          => Controls_Manager::TEXT,
				'placeholder'   => __( 'Enter your URL (Vimeo)', 'essential-addons-elementor' ),
				'label_block' => true,
                'condition'     => [
                    'eael_video_source' => 'vimeo'
                ]
            ]
		);
		
		$this->add_control(
			'eaelsv_link_dailymotion',
			[
				'label'         => __( 'Link', 'essential-addons-elementor' ),
				'type'          => Controls_Manager::TEXT,
				'placeholder'   => __( 'Enter your URL (Dailymotion)', 'essential-addons-elementor' ),
				'label_block' => true,
                'condition'     => [
                    'eael_video_source' => 'dailymotion'
                ]
            ]
		);
		
		$this->add_control(
			'eaelsv_link_external',
			[
				'label'         => __( 'External URL', 'essential-addons-elementor' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_block' => false,
                'condition'     => [
					'eael_video_source' => 'self_hosted',
					//'eaelsv_link_external' => 'yes'
                ]
            ]
		);

		$this->add_control(
			'eaelsv_hosted_url',
			[
				'label' => __( 'Choose File', 'elementor' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::MEDIA_CATEGORY,
					],
				],
				'media_type' => 'video',
				'condition' => [
					'eael_video_source' => 'self_hosted',
					'eaelsv_link_external' => '',
				],
			]
		);
		
		$this->add_control(
			'eaelsv_external_url',
			[
				'label'         => __( 'Link', 'essential-addons-elementor' ),
				'type'          => Controls_Manager::TEXT,
				'placeholder'   => __( 'Enter your URL', 'essential-addons-elementor' ),
				'label_block' => true,
				'show_label'  => false,
                'condition'     => [
					'eael_video_source' => 'self_hosted',
                    'eaelsv_link_external' => 'yes'
                ]
            ]
		);

		$this->add_control(
			'eael_video_self_hosted_link',
			[
				'label'     => __( 'Choose File', 'essential-addons-elementor' ),
				'type'      => Controls_Manager::MEDIA,
				'label_block' => true,
				'condition'     => [
					'eael_video_source' => 'self_hosted',
					'eael_video_source_external' => ''
                ]
             ]
		);
		
		$this->add_control(
			'eaelsv_start_time',
			[
				'label' => __( 'Start Time', 'essential-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 10000,
				'step' => 1,
				'default' => 0,
				'description' => 'Specify a start time (in seconds)',
			]
		);

		$this->add_control(
			'eaelsv_end_time',
			[
				'label' => __( 'End Time', 'essential-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 10000,
				'step' => 1,
				'default' => 0,
				'description' => 'Specify an end time (in seconds)',
			]
		);

		$this->add_control(
			'eael_video_video_options',
			[
				'label' => __( 'Video Options', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'eaelsv_autopaly',
			[
				'label'         => __( 'Autoplay', 'essential-addons-elementor' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_block' => false
            ]
		);

		$this->add_control(
			'eaelsv_mute',
			[
				'label'         => __( 'Mute', 'essential-addons-elementor' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_block' => false
            ]
		);

		$this->add_control(
			'eaelsv_loop',
			[
				'label'         => __( 'Loop', 'essential-addons-elementor' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_block' => false
            ]
		);
		/*
		$this->add_control(
			'eaelsv_display_options',
			[
				'label' => __( 'Display', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition'     => [
					'eael_video_source' => 'self_hosted'
                ]
			]
		);
		*/
		$this->add_control(
			'eaelsv_sh_show_bar',
			[
				'label'         => __( 'Show Bar', 'essential-addons-elementor' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_block' => false,
				'condition'     => [
					'eael_video_source' => 'self_hosted'
                ]
            ]
		);

		$this->add_control(
			'eaelsv_sh_show_fullscreen',
			[
				'label'         => __( 'Show Fullscreen', 'essential-addons-elementor' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_block' => false,
				'condition'     => [
					'eael_video_source' => 'self_hosted',
					'eaelsv_sh_show_bar' => 'yes'
                ]
            ]
		);

		$this->end_controls_section();


		//=========================================================================
		$this->start_controls_section(
			'eael_video_image_overlay_section',
			[
				'label' => __( 'Image Overlay', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'eaelsv_overlay_options',
			[
				'label'         => __( 'Image Overlay', 'essential-addons-elementor' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_block' => false,
				'label_on' => __( 'Show', 'essential-addons-elementor' ),
				'label_off' => __( 'Hide', 'essential-addons-elementor' ),
				'return_value' => 'yes',
				'default' => '',
            ]
		);

		$this->add_control(
			'eaelsv_overlay_image',
			[
				'label'     => __( 'Choose Image1', 'essential-addons-elementor' ),
				'type'      => Controls_Manager::MEDIA,
				'label_block' => true,
				'condition'     => [
					'eaelsv_overlay_options' => 'yes'
				],
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'selectors' => [
                   	//'{{WRAPPER}} div.eael-sticky-video-player'  => 'background-image: url("'.\Elementor\Utils::get_placeholder_image_src().'")'
				]
            ]
		);
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'default'   => 'full',
				'name'      => 'eaelsv_overlay_image_size',
				'condition'     => [
					'eaelsv_overlay_options' => 'yes'
				],
            ]
		);
		
		$this->add_control(
			'eaelsv_overlay_play_icon',
			[
				'label'         => __( 'Play Icon', 'essential-addons-elementor' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_block' => false,
				'return_value' => 'yes',
				'default' => 'yes',
				'condition'     => [
					'eaelsv_overlay_options' => 'yes'
				],
            ]
		);

		$this->add_control(
			'eaelsv_icon_new',
			[
				'label' => esc_html__( 'Icon', 'essential-addons-elementor' ),
				'type'  => Controls_Manager::ICONS,
				'fa4compatibility' => 'eaelsv_icon',
				'condition'     => [
					'eaelsv_overlay_options' => 'yes',
					'eaelsv_overlay_play_icon' => 'yes'
				],
			]
		);

		$this->add_control(
			'eael_video_image_overlay_lightbox',
			[
				'label'         => __( 'Lightbox', 'essential-addons-elementor' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_block' => false,
				'label_on' => __( 'On', 'essential-addons-elementor' ),
				'label_off' => __( 'Off', 'essential-addons-elementor' ),
				'return_value' => 'yes',
				'default' => '',
				'condition'     => [
					'eaelsv_overlay_options' => 'yes'
				],
				'separator' => 'before',
            ]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'eaelsv_sticky_option_section',
			[
				'label' => __( 'Sticky Ootions', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'eaelsv_is_sticky',
			[
				'label'         => __( 'Sticky', 'essential-addons-elementor' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_block' 	=> false,
				'label_on'		=> __( 'On', 'essential-addons-elementor' ),
				'label_off'    	=> __( 'Off', 'essential-addons-elementor' ),
				'return_value'	=> 'yes',
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} div.eaelsv-sticky-player'  => 'display: block',
				]
            ]
		);

		$this->add_control(
			'eaelsv_sticky_position',
			[
				'label'     => __( 'Position', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::SELECT,
                'options'	=> [
					'top-left'   	=> __( 'Top Left', 'essential-addons-elementor' ),
					'top-right'     => __( 'Top Right', 'essential-addons-elementor' ),
					'bottom-left'	=> __( 'Bottom Left', 'essential-addons-elementor' ),
					'bottom-right'	=> __( 'Bottom Right', 'essential-addons-elementor' ),
				],
				'default' 	=> 'bottom-right',
            ]
		);

		$this->add_control(
			'eaelsv_sticky_width',
			[
				'label'     => __( 'Width', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
						'min' => 100,
						'max' => 500,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} div.eaelsv-sticky-player'  => 'width: {{SIZE}}px'
				]
            ]
        );
		
		$this->add_control(
			'eaelsv_sticky_height',
			[
				'label'     => __( 'Height', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
						'min' => 100,
						'max' => 500,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} div.eaelsv-sticky-player'  => 'height: {{SIZE}}px'
				]
            ]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'eaelsv_sh_player_section',
			[
				'label' => __( 'Player', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'eaelsv_sh_video_width',
			[
				'label'      => esc_html__( 'Width', 'essential-addons-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 600,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ckin__player' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'eaelsv_sh_video_border_type',
			[
				'label'     => __( 'Border Type', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'none',
                'options'	=> [
					'none'   	=> __( 'None', 'essential-addons-elementor' ),
					'solid'     => __( 'Solid', 'essential-addons-elementor' ),
					'double'	=> __( 'Double', 'essential-addons-elementor' ),
					'dotted'	=> __( 'Dotted', 'essential-addons-elementor' ),
					'dashed'	=> __( 'Dashed', 'essential-addons-elementor' ),
				],
				'selectors' => [
					'{{WRAPPER}} .ckin__player' => 'border: 5px {{VALUE}} #009900;',
				],
            ]
		);

		$this->end_controls_section();
    }

    protected function render() {
		$settings = $this->get_settings_for_display();
		$image = $settings['eaelsv_overlay_image']['url'];
		$id = $this->eaelsv_get_url_id($settings);
		$iconNew = $settings['eaelsv_icon_new'];
		$st = $settings['eaelsv_start_time'];
		$et = $settings['eaelsv_end_time'];
		$sticky = $settings['eaelsv_is_sticky'];
		$position = $this->eaelvs_sticky_video_position($settings['eaelsv_sticky_position']);
		$hostedUrl = $settings['eaelsv_hosted_url']['url'];
		?>
		<div class="eael-sticky-video-wrapper">
		<?php
		if('yes' === $settings['eaelsv_overlay_options']):
			if('yes' === $settings['eaelsv_overlay_play_icon']):
				if($iconNew['value']!=''):
					$icon = $iconNew['value'];
				else:
					$icon = 'eicon-play';
				endif;
			endif;
			if($image!=''):
				$img = $image;
			else:
				$img = 'http://i.ytimg.com/vi/'.$id.'/maxresdefault.jpg';
			endif;
			?>
			<div class="eael-sticky-video-player" 
				style="background-image:url('<?php echo esc_attr($img); ?>');"
				data-overlay="<?php echo esc_attr($settings['eaelsv_overlay_options']); ?>"
				data-id="<?php echo esc_attr( $id ); ?>"
				data-image="<?php echo esc_attr( $img ); ?>"
				data-start="<?php echo esc_attr( $st ); ?>"
				data-end="<?php echo esc_attr( $et ); ?>"
				data-sticky="<?php echo esc_attr( $sticky ); ?>"
				data-source="<?php echo esc_attr($settings['eael_video_source']); ?>"
				data-autoplay="<?php echo esc_attr($settings['eaelsv_autopaly']) ?>"
				data-mute="<?php echo esc_attr($settings['eaelsv_mute']) ?>"
				data-loop="<?php echo esc_attr($settings['eaelsv_loop']) ?>">
                <div class="owp-play"><i class="<?php echo esc_attr($icon); ?>"></i></div>
			</div>
		<?php else: ?>
			<!-- div class="eael-sticky-video-player" 
				data-id="<?php //echo esc_attr( $id ); ?>"
				data-start="<?php //echo esc_attr( $st ); ?>"
				data-end="<?php //echo esc_attr( $et ); ?>"
				data-sticky="<?php //echo esc_attr( $sticky ); ?>"
				data-source="<?php //echo esc_attr($settings['eael_video_source']); ?>"
				data-autoplay="<?php //echo esc_attr($settings['eaelsv_autopaly']) ?>"
				data-mute="<?php //echo esc_attr($settings['eaelsv_mute']) ?>"
				data-loop="<?php //echo esc_attr($settings['eaelsv_loop']) ?>" -->
				<?php $this->eaelsv_load_player($settings); ?>
			<!-- /div -->
		<?php endif; ?>
		</div>
		<div class="eaelsv-sticky-player" 
			style="<?php echo esc_attr($position); ?>"
			data-sticky="<?php echo esc_attr( $sticky ); ?>"
			data-source="<?php echo esc_attr($settings['eael_video_source']); ?>"
			data-id="<?php echo esc_attr( $id ); ?>"></div>
		<?php
		//$this->eaelsv_enqueue_styles();
		$this->eaelsv_sticky_video_styles($settings);
	}
	
	protected function eaelsv_load_player($settings){
		$id = $this->eaelsv_get_url_id($settings);
		switch ($settings['eael_video_source']) {
			case "youtube":
				$this->eaelsv_load_player_youtube($id);
				break;
			case "vimeo":
				$this->eaelsv_load_player_vimeo($id);
				break;
			case "self_hosted":
				$this->eaelsv_load_player_self_hosted($settings);
				break;
			default:
				$this->eaelsv_load_player_youtube($id);
		}
	}

	protected function eaelsv_load_player_youtube($id){
		//XHOmBV4js_E
		?>
		<iframe width="420" height="315"
			src="https://www.youtube.com/embed/<?php echo esc_attr($id); ?>?autoplay=0">
		</iframe>
		<?php
	}

	protected function eaelsv_load_player_vimeo($id){
		?>
		<iframe 
			src="https://player.vimeo.com/video/<?php echo esc_attr($id); ?>"
			width="100%" height="100%" webkitallowfullscreen mozallowfullscreen allowfullscreen>
		</iframe>
		<?php
	}

	protected function eaelsv_load_player_self_hosted($settings){
		$video = $settings['eaelsv_hosted_url']['url'];
		$controlBars = $settings['eaelsv_sh_show_bar'];
		$autoplay = $settings['eaelsv_autopaly'];
		$mute = $settings['eaelsv_mute'];
		$loop = $settings['eaelsv_loop'];
		//$fullScreen	= $settings['eaelsv_sh_show_fullscreen'];
		?>
		<video poster="ckin.jpg" 
		src="<?php echo esc_attr($video); ?>" 
		data-color="#fff000" 
		data-ckin="compact" 
		data-overlay="2">
		</video>
		<?php
	}

	protected function eaelsv_load_player_self_hosted1($settings){
		$video = $settings['eaelsv_hosted_url']['url'];
		$controlBars = $settings['eaelsv_sh_show_bar'];
		$autoplay = $settings['eaelsv_autopaly'];
		$mute = $settings['eaelsv_mute'];
		$loop = $settings['eaelsv_loop'];
		//$fullScreen	= $settings['eaelsv_sh_show_fullscreen'];
		?>
		<video
			id="player" 
			preload 
			<?php if('yes'==$controlBars) echo "controls"; ?> 
			<?php if('yes'==$autoplay) echo "autoplay"; ?>  
			<?php if('yes'==$mute) echo "muted"; ?> 
			<?php if('yes'==$loop) echo "loop"; ?> 
			height="100%" 
			poster="images/poster.jpg">
			<source src="<?php echo esc_attr($video); ?>" type="video/mp4">
		</video>
		<?php
	}

	protected function eaelsv_get_url_id( $settings ){
		if('youtube' === $settings['eael_video_source']){
			$url = $settings['eaelsv_link_youtube'];
			$link = explode( '=', parse_url($url, PHP_URL_QUERY) );
			$id = $link[1];
		}
		if('vimeo' === $settings['eael_video_source']){
			$url = $settings['eaelsv_link_vimeo'];
			$link = explode('/', $url);
			$id = $link[3];
		}
		if('self_hosted' === $settings['eael_video_source']){
			$externalUrl = $settings['eaelsv_link_external'];
			if('yes'==$externalUrl){
				$id = $settings['eaelsv_external_url'];
			} else{
				$id = $settings['eaelsv_hosted_url']['url'];
			}
		}
		return $id;
	}

	protected function eaelvs_sticky_video_position($position){
		if('top-left' === $position){
			$pos = "top:50px; left: 50px;";
		}
		if('top-right' === $position){
			$pos = "top:50px; right: 50px;";
		}
		if('bottom-left' === $position){
			$pos = "bottom:50px; left: 50px;";
		}
		if('bottom-right' === $position){
			$pos = "bottom:50px; right: 50px;";
		}
		return $pos;
	}

	public function eaelsv_custom_scripts(){
		wp_enqueue_script( 'eaelsv-js', plugins_url( '../../assets/front-end/js/sticky-video/index.js', __FILE__ ), ['jquery'], time(), true );
	}

	protected function eaelsv_enqueue_styles(){
		?>
		<style>
		.controls {
			border:5px solid #CC0000;
		}
		.eael-sticky-video-wrapper {
			position: relative;
			width:100%;
			min-height:10px;
			margin:0px; padding:0px;
			transition: 0.5s;
			text-align:left;
			overflow: hidden;
		}

		.eael-sticky-video-wrapper iframe,
		.eael-sticky-video-wrapper video {
			position: relative;
			/*top: 0;
			left: 0;*/
			margin:0px; padding:0px;
			height:auto;
			border: 0;
			line-height: 1;
		}

		.eael-sticky-video-player {
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background-size: cover;
			background-position: 50%;
			cursor: pointer;
			text-align: center;
		}

		.eael-sticky-video-player1 {
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background-size: cover;
			background-position: 50%;
			cursor: pointer;
			text-align: center;
			background:#009900;
			z-index:1000;
			opacity:0.5;
		}
		/*
		.eael-sticky-video-wrapper.out{
			position:fixed;
			bottom:20px;
			right:20px;
			width:300px;
			height:200px;
			z-index:999;
		}
		*/
		.eael-sticky-video-wrapper.out iframe{
			
		}

		.eael-sticky-video-player img {
			display: block;
			width: 100%;
		}

		.owp-play {
			position: absolute;
			top: 50%;
			left: 50%;
			-webkit-transform: translateX(-50%) translateY(-50%);
			-ms-transform: translateX(-50%) translateY(-50%);
			transform: translateX(-50%) translateY(-50%);
		}

		.owp-play i {
			font-size: 100px;
			color: #fff;
			opacity: 0.8;
			text-shadow: 1px 0 6px rgba(0, 0, 0, 0.3);
			-webkit-transition: all .5s;
			-o-transition: all .5s;
			transition: all .5s;
		}

		.eael-sticky-video-player:hover .owp-play i {
			opacity: 1;
		}

		.eaelsv-sticky-player {
			height: 200px;
			width: 300px;
			position: fixed;
			bottom: 50px;
			right: 50px;
			border: 0px solid #009900;
			background-size: cover;
			z-index: 1000;
			background: transparent;
			display:none!important;
		}
		.eaelsv-sticky-player.eaelsv-display-player{
			display:block!important;
			-webkit-animation: fadeIn 1s;
    		animation: fadeIn 1s;
		}
		</style>
		<?php
	}

	public function eaelsv_sticky_video_styles($settings){
		$sticky = $settings['eaelsv_is_sticky'];
		$position = $settings['eaelsv_sticky_position'];
		if('top-left'==$position){
			$pos = 'top:20px; left:20px;';
		}
		if('top-right'==$position){
			$pos = 'top:20px; right:20px;';
		}
		if('bottom-right'==$position){
			$pos = 'bottom:20px; right:20px;';
		}
		if('bottom-left'==$position){
			$pos = 'bottom:20px; left:20px;';
		}
		if('yes'==$sticky){ ?>
		<style>
		.eael-sticky-video-wrapper.out{
			position:fixed;
			<?php echo $pos; ?>
			width:300px;
			height:200px;
			z-index:999;
		}
		</style>
		<?php }
	}

}