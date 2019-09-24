<?php
namespace Essential_Addons_Elementor\Elements;

if (!defined('ABSPATH')) { exit; }

use \Elementor\Controls_Manager;
use \Elementor\Widget_Base;
use \Elementor\Group_Control_Image_Size;

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
                    'eael_video_source' => 'self_hosted'
                ]
            ]
		);
		
		$this->add_control(
			'eael_video_link_external_url',
			[
				'label'         => __( 'Link', 'essential-addons-elementor' ),
				'type'          => Controls_Manager::TEXT,
				'placeholder'   => __( 'Enter your URL', 'essential-addons-elementor' ),
				'label_block' => true,
				'show_label'  => false,
                'condition'     => [
                    'eael_video_source_external' => 'yes'
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
			'eael_video_start_time',
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
			'eael_video_end_time',
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
			'eael_video_options_autopaly',
			[
				'label'         => __( 'Autoplay', 'essential-addons-elementor' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_block' => false
            ]
		);

		$this->add_control(
			'eael_video_options_mute',
			[
				'label'         => __( 'Mute', 'essential-addons-elementor' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_block' => false
            ]
		);

		$this->add_control(
			'eael_video_options_loop',
			[
				'label'         => __( 'Loop', 'essential-addons-elementor' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_block' => false
            ]
		);

		$this->add_control(
			'eael_video_options_player_controls',
			[
				'label'         => __( 'Player Controls', 'essential-addons-elementor' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_block' => false,
				'label_on' => __( 'Show', 'essential-addons-elementor' ),
				'label_off' => __( 'Hide', 'essential-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
            ]
		);

		$this->add_control(
			'eael_video_options_download_button',
			[
				'label'         => __( 'Download Button', 'essential-addons-elementor' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_block' => false,
				'label_on' => __( 'Show', 'essential-addons-elementor' ),
				'label_off' => __( 'Hide', 'essential-addons-elementor' ),
				'return_value' => 'yes',
				'default' => '',
            ]
		);

		$this->add_control(
			'eael_video_options_poster',
			[
				'label'     => __( 'Poster', 'essential-addons-elementor' ),
				'type'      => Controls_Manager::MEDIA,
				'label_block' => true,
            ]
		);

		$this->end_controls_section();

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
				/*
				'selectors'     => [
                    '{{WRAPPER}} div.eael-sticky-video-player'  => 'background-image: url("'.$this->get_settings().'")'
				]
				*/
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
    }

    protected function render() {
		$settings = $this->get_settings();
		$image = $settings['eaelsv_overlay_image']['url'];
		$id = $this->eaelsv_get_url_id($settings);
		$iconNew = $settings['eaelsv_icon_new'];
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
			<div class="eael-sticky-video-player" style="background-image:url('<?php echo esc_attr($img); ?>');" data-id="<?php echo esc_attr( $id ); ?>" data-image="<?php echo esc_attr( $image ); ?>">
                <div class="owp-play"><i class="<?php echo esc_attr($icon); ?>"></i></div>
			</div>
		<?php else:
			$this->eaelsv_load_player($settings);
		endif; ?>
		</div>
		<?php

		$this->eaelsv_enqueue_styles();
		$this->eaelsv_enqueue_scripts();
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
			case "green":
				echo "Your favorite color is green!";
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
			width="420" height="315" webkitallowfullscreen mozallowfullscreen allowfullscreen>
		</iframe>
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
		return $id;
	}

	protected function eaelsv_enqueue_styles(){
		?>
		<style>
		.eael-sticky-video-wrapper {
			position: relative;
			height: 0;
			padding-bottom: 56.25%;
		}

		.eael-sticky-video-wrapper iframe {
			position: absolute;
			top: 0;
			left: 0;
			height: 100%;
			width: 100%;
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
			/*
			background-image: url('https://cdn.pixabay.com/photo/2016/10/17/10/52/wind-farm-1747331__340.jpg');
			*/
			cursor: pointer;
			text-align: center;
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
		</style>
		<?php
	}

	protected function eaelsv_enqueue_scripts(){
		?>
		<script>
		jQuery(document).ready(function() {
			if (isEditMode) {
				var v = jQuery('.eael-sticky-video-player');
				for (i = 0; i < v.length; i++) {
					/*
					jQuery(v[i]).on('click', function(){
						var iframe1  = '<div style="border:1px solid #009900;"></div>';
						alert(iframe1);
						jQuery(this).parent().get().replaceChild(iframe1,this);
					});
					*/
					v[i].onclick = function() {
						var iframe1  =  document.createElement( 'iframe' );
						iframe1.setAttribute('src', 'http://dasda.com');
						alert(iframe1);
						jQuery(this).parent().empty().append(iframe1);
					}
				}
			}
		});
		document.addEventListener( 'DOMContentLoaded', function() {
			var i,
			video = document.getElementsByClassName( 'eael-sticky-video-player' );
				
			for (i = 0; i < video.length; i++) {
				var overlayImage = video[i].dataset.image;
				//alert(video[i].dataset.id);
				if(overlayImage!=''){
					video[i].style.backgroundImage = 'url('+overlayImage+')';
				} else{
					video[i].style.backgroundImage = 'url(//i.ytimg.com/vi/' + video[i].dataset.id + '/maxresdefault.jpg)';
				}
				// We get the thumbnail image from the YouTube ID
				//
				//video[i].style.backgroundImage = 'url(https://www.rowletteagles.org/wp-content/uploads/2015/06/bg-video-player.jpg)';
				video[i].onclick = function() {
					var iframe  = document.createElement( 'iframe' ),
						embed   = 'https://www.youtube.com/embed/ID?autoplay=1&rel=0&controls=1&showinfo=0&mute=0&wmode=opaque';
					iframe.setAttribute( 'src', embed.replace( 'ID', this.dataset.id ) );
					iframe.setAttribute( 'frameborder', '0' );
					iframe.setAttribute( 'allowfullscreen', '1' );
					this.parentNode.replaceChild( iframe, this );
				}
		
			}
		
		} );
		</script>
		<?php
	}

}