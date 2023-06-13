<?php

namespace Essential_Addons_Elementor\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

use Elementor\Plugin;
use Essential_Addons_Elementor\Traits\Library;

class Elements_Manager {
	use Library;

	/**
	 * custom key name which are used for store widget list in option table
	 */
	const ELEMENT_KEY = '_eael_widget_elements';

	/**
	 * This is hold custom js data in option table
	 */
	const JS_KEY = '_eael_custom_js';

	/**
	 * Post id
	 * @var string
	 */
	protected $post_id;

	/**
	 * registered element list from essential addons settings
	 * @var array
	 */
	protected $registered_elements;

	/**
	 * registered extensions list from essential addons settings
	 * @var array
	 */
	protected $registered_extensions;

	/**
	 * __construct
	 * @param array $registered_elements
	 * @param array $registered_extensions
	 */
	public function __construct( $registered_elements, $registered_extensions ) {
		$this->registered_elements   = $registered_elements;
		$this->registered_extensions = $registered_extensions;
		add_action( 'elementor/editor/after_save', array( $this, 'eael_elements_cache' ), 10, 2 );
	}

	/**
	 * eael_elements_cache
	 * Save widget name list in option table for improve performance.
	 * @param int $post_id
	 * @param array $data
	 */
	public function eael_elements_cache( $post_id, $data ) {
		$widget_list  = $this->get_widget_list( $data );
		$page_setting = get_post_meta( $post_id, '_elementor_page_settings', true );
		$custom_js    = isset( $page_setting['eael_custom_js'] ) ? trim( $page_setting['eael_custom_js'] ) : '';
		$this->save_widgets_list( $post_id, $widget_list, $custom_js );
	}

	/**
	 * get_widget_list
	 * get widget names
	 * @param array $data
	 *
	 * @return array
	 */
	public function get_widget_list( $data ) {
		$widget_list = [];
		$replace     = $this->replace_widget_name();

		if ( is_object( Plugin::$instance->db ) ) {
			Plugin::$instance->db->iterate_data( $data, function ( $element ) use ( &$widget_list, $replace ) {

				if ( empty( $element['widgetType'] ) ) {
					$type = $element['elType'];
				} else {
					$type = $element['widgetType'];
				}

				if ( ! empty( $element['widgetType'] ) && $element['widgetType'] === 'global' ) {
					$document = Plugin::$instance->documents->get( $element['templateID'] );
					$type     = is_object( $document ) ? current( $this->get_widget_list( $document->get_elements_data() ) ) : $type;

					if ( ! empty( $type ) ) {
						$type = 'eael-' . $type;
					}
				}

				if ( ! empty( $type ) && ! is_array( $type ) ) {

					if ( isset( $replace[ $type ] ) ) {
						$type = $replace[ $type ];
					}

					if ( strpos( $type, 'eael-' ) !== false ) {

						$type = str_replace( 'eael-', '', $type );
						if ( ! isset( $widget_list[ $type ] ) ) {
							$widget_list[ $type ] = $type;
						}
					}

					$widget_list += $this->get_extension_list( $element );
				}

			} );
		}

		return $widget_list;
	}

	/**
	 * get_element_list
	 * get cached widget list
	 * @param $post_id
	 *
	 * @return bool
	 */
	public function get_element_list( $post_id ) {

		if ( is_object( Plugin::instance()->editor ) && Plugin::instance()->editor->is_edit_mode() ) {
			return false;
		}

		if ( $this->has_exist( $post_id ) ) {
			return false;
		}

		$document = is_object( Plugin::$instance->documents ) ? Plugin::$instance->documents->get( $post_id ) : [];
		$data     = is_object( $document ) ? $document->get_elements_data() : [];
		$data     = $this->get_widget_list( $data );
		$this->save_widgets_list( $post_id, $data, false );

		return true;
	}

	/**
	 * get_extension_list
	 * get extension name those name had been changed for some reason.
	 * @param array $element
	 *
	 * @return array
	 */
	public function get_extension_list( $element ) {
		$list = [];
		if ( isset( $element['elType'] ) && ( $element['elType'] == 'section' || $element['elType'] == 'container' ) ) {
			if ( ! empty( $element['settings']['eael_particle_switch'] ) ) {
				$list['section-particles'] = 'section-particles';
			}
			if ( ! empty( $element['settings']['eael_parallax_switcher'] ) ) {
				$list['section-parallax'] = 'section-parallax';
			}
		} else {
			if ( ! empty( $element['settings']['eael_tooltip_section_enable'] ) ) {
				$list['tooltip-section'] = 'tooltip-section';
			}
			if ( ! empty( $element['settings']['eael_ext_content_protection'] ) ) {
				$list['content-protection'] = 'content-protection';
			}
		}

		if ( ! empty( $element['settings']['eael_wrapper_link_switch'] ) ) {
			$list['wrapper-link'] = 'wrapper-link';
		}

		return $list;
	}

	/*
	 * replace_widget_name
	 * Added backward compatibility
	 */
	public static function replace_widget_name() {
		return [
			'eicon-woocommerce'               => 'eael-product-grid',
			'eael-countdown'                  => 'eael-count-down',
			'eael-creative-button'            => 'eael-creative-btn',
			'eael-team-member'                => 'eael-team-members',
			'eael-testimonial'                => 'eael-testimonials',
			'eael-weform'                     => 'eael-weforms',
			'eael-cta-box'                    => 'eael-call-to-action',
			'eael-dual-color-header'          => 'eael-dual-header',
			'eael-pricing-table'              => 'eael-price-table',
			'eael-filterable-gallery'         => 'eael-filter-gallery',
			'eael-one-page-nav'               => 'eael-one-page-navigation',
			'eael-interactive-card'           => 'eael-interactive-cards',
			'eael-image-comparison'           => 'eael-img-comparison',
			'eael-dynamic-filterable-gallery' => 'eael-dynamic-filter-gallery',
			'eael-google-map'                 => 'eael-adv-google-map',
			'eael-instafeed'                  => 'eael-instagram-gallery',
			'eael-ninja'                      => 'eael-ninja-form',
		];
	}

	/**
	 * save_widgets_list
	 * save widget list and custom js data in option table
	 * @param int $post_id
	 * @param array $list
	 * @param string $custom_js
	 *
	 * @return bool|mixed
	 */
	public function save_widgets_list( $post_id, $list, $custom_js = '' ) {

		if ( \defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		$documents = is_object( Plugin::$instance->documents ) ? Plugin::$instance->documents->get( $post_id ) : [];

		if ( ! in_array( get_post_status( $post_id ), [ 'publish', 'private' ] ) || ( is_object( $documents ) && ! $documents->is_built_with_elementor() ) ) {
			return false;
		}

		if ( in_array( get_post_meta( $post_id, '_elementor_template_type', true ), $this->excluded_template_type() ) ) {
			return false;
		}

		if ( $custom_js !== false ) {
			update_post_meta( $post_id, '_eael_custom_js', $custom_js );
		}

		if ( md5( implode( '', (array) $list ) ) == md5( implode( '', (array) get_post_meta( $post_id, self::ELEMENT_KEY, true ) ) ) ) {
			return false;
		}

		try {
			update_post_meta( $post_id, self::ELEMENT_KEY, $list );
			$this->remove_files( $post_id );

			if ( $this->has_exist( $post_id ) ) {
				$this->update_asset( $post_id, $list );
			}

			return true;
		} catch ( \Exception $e ) {
			return false;
		}
	}

	/**
	 * generate_script
	 * create js/css file as per widget loaded in page
	 * @param int $post_id
	 * @param array $elements
	 * @param string $context
	 * @param string $ext
	 */
	public function generate_script( $post_id, $elements, $context, $ext ) {
		// if folder not exists, create new folder
		if ( ! file_exists( EAEL_ASSET_PATH ) ) {
			wp_mkdir_p( EAEL_ASSET_PATH );
		}

		// naming asset file
		$file_name = 'eael' . ( $post_id ? '-' . $post_id : '' ) . '.' . $ext;

		// output asset string
		$output = $this->generate_strings( $elements, $context, $ext );

		// write to file
		$file_path = $this->safe_path( EAEL_ASSET_PATH . DIRECTORY_SEPARATOR . $file_name );
		file_put_contents( $file_path, $output );
	}

	/**
	 * generate_strings
	 * Load assets for inline loading
	 * @param string $elements
	 * @param string $context
	 * @param string $ext
	 *
	 * @return string
	 */
	public function generate_strings( $elements, $context, $ext ) {
		$output = '';

		$paths = $this->generate_dependency( $elements, $context, $ext );

		if ( ! empty( $paths ) ) {
			foreach ( $paths as $path ) {
				$output .= file_get_contents( $this->safe_path( $path ) );
			}
		}

		return $output;
	}

	/**
	 * generate_dependency
	 * Load core library for widget list which are defined on config.php file
	 * @param array $elements
	 * @param string $context
	 * @param string $type
	 *
	 * @return array
	 */
	public function generate_dependency( $elements, $context, $type ) {
		$lib  = [ 'view' => [], 'edit' => [] ];
		$self = [ 'general' => [], 'view' => [], 'edit' => [] ];

		if ( $type == 'js' ) {
			$self['general'][] = EAEL_PLUGIN_PATH . 'assets/front-end/js/view/general.min.js';
			$self['edit'][]    = EAEL_PLUGIN_PATH . 'assets/front-end/js/edit/promotion.min.js';
		} else if ( $type == 'css' && ! $this->is_edit_mode() ) {
			$self['view'][] = EAEL_PLUGIN_PATH . "assets/front-end/css/view/general.min.css";
		}

		foreach ( $elements as $element ) {

			if ( isset( $this->registered_elements[ $element ] ) ) {
				if ( ! empty( $this->registered_elements[ $element ]['dependency'][ $type ] ) ) {
					foreach ( $this->registered_elements[ $element ]['dependency'][ $type ] as $file ) {
						if ( ! empty( $file['type'] ) && ! empty( $file['context'] ) && ! empty( $file['file'] ) ) {
							${$file['type']}[ $file['context'] ][] = $file['file'];
						}
					}
				}
			} elseif ( isset( $this->registered_extensions[ $element ] ) ) {
				if ( ! empty( $this->registered_extensions[ $element ]['dependency'][ $type ] ) ) {
					foreach ( $this->registered_extensions[ $element ]['dependency'][ $type ] as $file ) {
						if ( ! empty( $file['type'] ) && ! empty( $file['context'] ) && ! empty( $file['file'] ) ) {
							${$file['type']}[ $file['context'] ][] = $file['file'];
						}
					}
				}
			}
		}

		if ( $context == 'view' ) {
			return array_unique( array_merge( $lib['view'], $self['view'] ) );
		}

		return array_unique( array_merge( $lib['view'], $lib['edit'], $self['edit'], $self['view'] ) );
	}

	/**
	 * has_exist
	 * @param $post_id
	 * check widget list already saved in option table weather load or not
	 * @return bool
	 */
	public function has_exist( $post_id ) {
		$status = get_post_meta( $post_id, self::ELEMENT_KEY, true );

		return ! empty( $status );
	}

	/**
	 * update_asset
	 * @param int $post_id
	 * @param  $elements
	 */
	public function update_asset( $post_id, $elements ) {

		if ( $this->css_print_method != 'internal' ) {
			$this->generate_script( $post_id, $elements, 'view', 'css' );
		}

		if ( $this->js_print_method != 'internal' ) {
			$this->generate_script( $post_id, $elements, 'view', 'js' );
		}

	}

	/**
	 * excluded_template_type
	 * @return string[]
	 */
	public function excluded_template_type() {
		return [
			'kit',
		];
	}
}
