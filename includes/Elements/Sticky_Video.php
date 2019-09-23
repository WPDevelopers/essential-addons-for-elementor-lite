<?php
namespace Essential_Addons_Elementor\Elements;

if (!defined('ABSPATH')) { exit; }

use \Elementor\Controls_Manager;
use \Elementor\Widget_Base;
use \Elementor\Group_Control_Image_Size;

class Sticky_Video extends Widget_Base {
	use \Essential_Addons_Elementor\Traits\Helper;

	public function get_name() {
		return 'sticky-video';
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
					'viemo'       	=> __( 'Vimeo', 'essential-addons-elementor' ),
					'dailymotion'	=> __( 'Dailymotion', 'essential-addons-elementor' ),
					'self_hosted'	=> __( 'Self Hosted', 'essential-addons-elementor' ),
				],
            ]
		);
		
		$this->add_control(
			'eael_video_source_youtube',
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
			'eael_video_source_viemo',
			[
				'label'         => __( 'Link', 'essential-addons-elementor' ),
				'type'          => Controls_Manager::TEXT,
				'placeholder'   => __( 'Enter your URL (Vimeo)', 'essential-addons-elementor' ),
				'label_block' => true,
                'condition'     => [
                    'eael_video_source' => 'viemo'
                ]
            ]
		);
		
		$this->add_control(
			'eael_video_source_dailymotion',
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
			'eael_video_source_external',
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
			'eael_video_image_overlay_options',
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
			'eael_video_image_overlay_choose_image',
			[
				'label'     => __( 'Choose Image1', 'essential-addons-elementor' ),
				'type'      => Controls_Manager::MEDIA,
				'label_block' => true,
				'condition'     => [
					'eael_video_image_overlay_options' => 'yes'
				],
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'selectors'     => [
                    '{{WRAPPER}} div.eael-sticky-video-player'  => 'background-image: {{VALUE}}'
                ]
            ]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'default'   => 'full',
				'name'      => 'eael_video_image_overlay_choose_image_size',
				'condition'     => [
					'eael_video_image_overlay_options' => 'yes'
				],
            ]
		);
		
		$this->add_control(
			'eael_video_image_overlay_play_icon',
			[
				'label'         => __( 'Play Icon', 'essential-addons-elementor' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_block' => false,
				'return_value' => 'yes',
				'default' => 'yes',
				'condition'     => [
					'eael_video_image_overlay_options' => 'yes'
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
					'eael_video_image_overlay_options' => 'yes'
				],
				'separator' => 'before',
            ]
		);

		$this->end_controls_section();
    }

    protected function render() {
		$settings = $this->get_settings();
		$source = $settings['eael_video_source'];
		$id = $settings['eael_video_source_youtube'];
		?>
		<div class="eael-sticky-video-wrapper">
            <div class="eael-sticky-video-player" data-id="<?php echo esc_attr( $id ); ?>">
                <div class="owp-play"><i class="eicon-play"></i></div>
            </div>
		</div>

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
		<script>
		document.addEventListener( 'DOMContentLoaded', function() {
			var i,
			video = document.getElementsByClassName( 'eael-sticky-video-player' );
				
			for (i = 0; i < video.length; i++) {
		
				// We get the thumbnail image from the YouTube ID
				//video[i].style.backgroundImage = 'url(//i.ytimg.com/vi/' + video[i].dataset.id + '/maxresdefault.jpg)';
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
	
	protected function render1() {
		$settings = $this->get_settings();
		$source = $settings['eael_video_source'];
		?>
		<div class="eael_video_sticky_wrapper">
		<?php
		if('youtube'==$source):
			echo $src = $settings['eael_video_source_youtube'];
			?>
			<iframe width="420" height="315" src="<?php echo esc_url($src); ?>"></iframe>
			<?php
		endif; ?>
		</div>
		<?php
    }

}