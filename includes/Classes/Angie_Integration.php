<?php

namespace Essential_Addons_Elementor\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

use Elementor\Plugin;
use Elementor\User;
use Elementor\Widget_Base;

/**
 * Angie (Elementor AI Assistant) integration.
 *
 * Registers a browser-side MCP server (via @elementor/angie-sdk) that lets
 * Angie discover Essential Addons widgets installed on the site and fetch
 * their control schemas, so the agent can build designs using EA widgets.
 *
 * Everything is gated on the Angie plugin being active — zero footprint
 * otherwise.
 *
 * @since 6.7.4
 */
class Angie_Integration {

	const NONCE_ACTION = 'eael_angie_integration';

	/**
	 * Category slug EA widgets register under.
	 */
	const EA_CATEGORY = 'essential-addons-elementor';

	/**
	 * Hard cap on any single string handed to the agent.
	 *
	 * Widget titles/keywords are read by an LLM as part of its working
	 * context, so an unbounded string is an unbounded injection surface.
	 */
	const MAX_STRING_LENGTH = 200;

	/**
	 * Lookup map of the widget classes Essential Addons (Lite + Pro) itself
	 * registers, keyed by lowercased fully-qualified class name.
	 *
	 * This — not the self-declared Elementor category — is what identifies a
	 * widget as "ours".
	 *
	 * @var array<string,true>
	 */
	protected $ea_widget_classes = [];

	/**
	 * Absolute, normalized directories EA widget classes may be defined in.
	 *
	 * @var string[]
	 */
	protected $ea_plugin_paths = [];

	/**
	 * @param array $registered_elements EA's element classmap
	 *                                   (`eael/registered_elements`), i.e. the
	 *                                   authoritative list of EA widget classes.
	 */
	public function __construct( $registered_elements = [] ) {
		if ( ! defined( 'ANGIE_VERSION' ) ) {
			return;
		}

		$this->ea_widget_classes = $this->build_class_allowlist( $registered_elements );
		$this->ea_plugin_paths   = $this->build_plugin_paths();

		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

		add_action( 'wp_ajax_eael_angie_widget_catalog', [ $this, 'ajax_widget_catalog' ] );
		add_action( 'wp_ajax_eael_angie_widget_schema', [ $this, 'ajax_widget_schema' ] );
	}

	/**
	 * Enqueue the MCP server bundle wherever the Angie sidebar can appear.
	 */
	public function enqueue_scripts() {
		if ( ! $this->current_user_can_access() ) {
			return;
		}

		wp_enqueue_script(
			'eael-angie-integration',
			EAEL_PLUGIN_URL . 'assets/admin/js/angie-integration.min.js',
			[],
			EAEL_PLUGIN_VERSION,
			true
		);

		wp_localize_script(
			'eael-angie-integration',
			'eaelAngie',
			[
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( self::NONCE_ACTION ),
				'version' => EAEL_PLUGIN_VERSION,
			]
		);
	}

	/**
	 * List active EA widgets: name, title, keywords, categories.
	 *
	 * Reads from Elementor's widgets manager so the list automatically
	 * respects the user's enabled/disabled element settings and includes
	 * Pro widgets when Pro is active.
	 */
	public function ajax_widget_catalog() {
		$this->verify_request();

		$widgets = [];

		foreach ( Plugin::$instance->widgets_manager->get_widget_types() as $widget ) {
			if ( ! $this->is_ea_widget( $widget ) ) {
				continue;
			}

			$widgets[] = [
				'name'       => $widget->get_name(),
				'title'      => $this->sanitize_agent_string( $widget->get_title() ),
				'keywords'   => $this->sanitize_agent_strings( (array) $widget->get_keywords() ),
				'categories' => $this->sanitize_agent_strings( (array) $widget->get_categories() ),
			];
		}

		wp_send_json_success( [ 'widgets' => $widgets ] );
	}

	/**
	 * Return the control schema for one EA widget so the agent can produce
	 * valid settings JSON.
	 *
	 * By default only Content-tab controls are returned (what the agent
	 * needs to compose a design); pass tab=all for everything.
	 */
	public function ajax_widget_schema() {
		$this->verify_request();

		$widget_name = isset( $_POST['widget'] ) ? sanitize_text_field( wp_unslash( $_POST['widget'] ) ) : '';
		$tab         = isset( $_POST['tab'] ) ? sanitize_key( wp_unslash( $_POST['tab'] ) ) : 'content';

		if ( '' === $widget_name ) {
			wp_send_json_error( [ 'message' => __( 'Missing widget name.', 'essential-addons-for-elementor-lite' ) ], 400 );
		}

		$widget = Plugin::$instance->widgets_manager->get_widget_types( $widget_name );

		if ( ! $this->is_ea_widget( $widget ) ) {
			wp_send_json_error( [ 'message' => __( 'Unknown Essential Addons widget.', 'essential-addons-for-elementor-lite' ) ], 404 );
		}

		$stack    = $widget->get_stack( false );
		$controls = isset( $stack['controls'] ) ? $stack['controls'] : [];

		wp_send_json_success(
			[
				'widget'   => $widget->get_name(),
				'title'    => $this->sanitize_agent_string( $widget->get_title() ),
				'controls' => $this->trim_controls( $controls, $tab ),
			]
		);
	}

	/**
	 * Whether a widget instance genuinely belongs to Essential Addons.
	 *
	 * The Elementor category is self-declared: any plugin can register a
	 * widget under `essential-addons-elementor` and have its title/keywords
	 * flow into the agent's context. Identity is therefore established by the
	 * widget's PHP class — checked first against EA's own registered
	 * classmap, then (for widgets added through EA's element filters) against
	 * the file the class is actually defined in.
	 *
	 * @param mixed $widget Widget instance from Elementor's widgets manager.
	 *
	 * @return bool
	 */
	protected function is_ea_widget( $widget ) {
		if ( ! $widget instanceof Widget_Base ) {
			return false;
		}

		if ( ! in_array( self::EA_CATEGORY, (array) $widget->get_categories(), true ) ) {
			return false;
		}

		$class = get_class( $widget );

		$is_ea = isset( $this->ea_widget_classes[ strtolower( ltrim( $class, '\\' ) ) ] )
			|| $this->class_is_defined_in_ea( $class );

		/**
		 * Filter whether a widget counts as an Essential Addons widget for the
		 * Angie catalog.
		 *
		 * Escape hatch for EA extensions that register widgets outside the
		 * `eael/registered_elements` classmap. Whatever this returns true for is
		 * described to a write-capable AI agent, so only add widgets you own.
		 *
		 * @since 6.7.4
		 *
		 * @param bool        $is_ea
		 * @param Widget_Base $widget
		 */
		return (bool) apply_filters( 'eael/angie/is_ea_widget', $is_ea, $widget );
	}

	/**
	 * Build the class allowlist from EA's element classmap.
	 *
	 * @param array $registered_elements
	 *
	 * @return array<string,true>
	 */
	protected function build_class_allowlist( $registered_elements ) {
		$classes = [];

		foreach ( (array) $registered_elements as $element ) {
			if ( empty( $element['class'] ) || ! is_string( $element['class'] ) ) {
				continue;
			}

			$classes[ strtolower( ltrim( $element['class'], '\\' ) ) ] = true;
		}

		return $classes;
	}

	/**
	 * Directories a genuine EA widget class can live in (Lite + Pro).
	 *
	 * @return string[]
	 */
	protected function build_plugin_paths() {
		$paths = [];

		foreach ( [ 'EAEL_PLUGIN_PATH', 'EAEL_PRO_PLUGIN_PATH' ] as $constant ) {
			if ( ! defined( $constant ) ) {
				continue;
			}

			$real = realpath( constant( $constant ) );

			if ( $real ) {
				$paths[] = trailingslashit( wp_normalize_path( $real ) );
			}
		}

		return $paths;
	}

	/**
	 * Whether a class is physically defined inside an EA plugin directory.
	 *
	 * @param string $class
	 *
	 * @return bool
	 */
	protected function class_is_defined_in_ea( $class ) {
		if ( empty( $this->ea_plugin_paths ) ) {
			return false;
		}

		try {
			$file = ( new \ReflectionClass( $class ) )->getFileName();
		} catch ( \ReflectionException $e ) {
			return false;
		}

		if ( ! $file ) {
			return false;
		}

		$file = realpath( $file );

		if ( ! $file ) {
			return false;
		}

		$file = wp_normalize_path( $file );

		foreach ( $this->ea_plugin_paths as $path ) {
			if ( 0 === strpos( $file, $path ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Flatten a string for agent consumption.
	 *
	 * Strips markup and control characters (newlines included, so a value can
	 * never fake a new "line" of instructions in the tool result) and caps the
	 * length.
	 *
	 * @param mixed $value
	 *
	 * @return string
	 */
	protected function sanitize_agent_string( $value ) {
		if ( ! is_scalar( $value ) ) {
			return '';
		}

		$value = wp_strip_all_tags( (string) $value );
		// Byte class only — UTF-8 continuation bytes are >= 0x80, so this is
		// multibyte-safe without the /u modifier (which would fail outright on
		// malformed input).
		$value = preg_replace( '/[\x00-\x1F\x7F]+/', ' ', $value );
		$value = trim( preg_replace( '/\s+/', ' ', $value ) );

		if ( function_exists( 'mb_substr' ) ) {
			return mb_substr( $value, 0, self::MAX_STRING_LENGTH );
		}

		return substr( $value, 0, self::MAX_STRING_LENGTH );
	}

	/**
	 * @param array $values
	 *
	 * @return string[]
	 */
	protected function sanitize_agent_strings( $values ) {
		$sanitized = [];

		foreach ( (array) $values as $value ) {
			$value = $this->sanitize_agent_string( $value );

			if ( '' !== $value ) {
				$sanitized[] = $value;
			}
		}

		return array_values( array_unique( $sanitized ) );
	}

	/**
	 * Strip render-only keys and (optionally) non-content tabs so the
	 * payload stays small enough for an agent context window.
	 *
	 * @param array  $controls Raw control stack.
	 * @param string $tab      'content' or 'all'.
	 *
	 * @return array
	 */
	protected function trim_controls( $controls, $tab ) {
		$strip_keys = [ 'selectors', 'selector', 'render_type', 'prefix_class', 'classes', 'style_transfer', 'separator' ];
		$trimmed    = [];

		foreach ( $controls as $key => $control ) {
			if ( 'all' !== $tab && isset( $control['tab'] ) && 'content' !== $control['tab'] ) {
				continue;
			}

			foreach ( $strip_keys as $strip_key ) {
				unset( $control[ $strip_key ] );
			}

			// Repeater fields carry their own nested control stacks.
			if ( isset( $control['fields'] ) && is_array( $control['fields'] ) ) {
				$control['fields'] = $this->trim_controls( $control['fields'], 'all' );
			}

			$trimmed[ $key ] = $control;
		}

		return $trimmed;
	}

	/**
	 * Whether the current user may reach the Angie widget-discovery tools.
	 *
	 * `edit_posts` on its own is the Contributor floor, which is broader than
	 * this feature's real boundary: a Contributor can only create unpublished
	 * drafts and is not an Elementor page builder. Requiring
	 * `edit_published_posts` too puts the gate at Author+, matching Elementor's
	 * practical editor access, and Elementor's own Role Manager exclusions are
	 * honoured on top of that.
	 *
	 * @return bool
	 */
	protected function current_user_can_access() {
		$can_access = current_user_can( 'edit_posts' ) && current_user_can( 'edit_published_posts' );

		// Role Manager → "Exclude Roles". Note the inverted name upstream:
		// is_current_user_in_editing_black_list() returns TRUE when the user is
		// *not* excluded.
		if ( $can_access && class_exists( '\Elementor\User' ) && ! User::is_current_user_in_editing_black_list() ) {
			$can_access = false;
		}

		/**
		 * Filter whether the current user can use EA's Angie integration.
		 *
		 * @since 6.7.4
		 *
		 * @param bool $can_access
		 */
		return (bool) apply_filters( 'eael/angie/current_user_can_access', $can_access );
	}

	/**
	 * Nonce + capability gate shared by both AJAX handlers.
	 */
	protected function verify_request() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, self::NONCE_ACTION ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid nonce.', 'essential-addons-for-elementor-lite' ) ], 403 );
		}

		if ( ! $this->current_user_can_access() ) {
			wp_send_json_error( [ 'message' => __( 'Insufficient permissions.', 'essential-addons-for-elementor-lite' ) ], 403 );
		}

		if ( ! did_action( 'elementor/loaded' ) ) {
			wp_send_json_error( [ 'message' => __( 'Elementor is not active.', 'essential-addons-for-elementor-lite' ) ], 400 );
		}
	}
}
