<?php

namespace Essential_Addons_Elementor\Elements\Atomic\Creative_Button;

use Elementor\Modules\AtomicWidgets\Controls\Section;
use Elementor\Modules\AtomicWidgets\Controls\Types\Inline_Editing_Control;
use Elementor\Modules\AtomicWidgets\Controls\Types\Link_Control;
use Elementor\Modules\AtomicWidgets\Controls\Types\Select_Control;
use Elementor\Modules\AtomicWidgets\Controls\Types\Text_Control;
use Elementor\Modules\AtomicWidgets\Elements\Base\Atomic_Widget_Base;
use Elementor\Modules\AtomicWidgets\Elements\Base\Has_Template;
use Elementor\Modules\AtomicWidgets\PropTypes\Attributes_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Background_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Classes_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Color_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Dimensions_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Html_V3_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Link_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Primitives\String_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Size_Prop_Type;
use Elementor\Modules\AtomicWidgets\Styles\Style_Definition;
use Elementor\Modules\AtomicWidgets\Styles\Style_Variant;
use Elementor\Modules\Components\PropTypes\Overridable_Prop_Type;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Atomic (V4 editor) variant of the EA Creative Button.
 *
 * Scope: text, secondary text, link, and the free hover effects. Pro presets are
 * injected at runtime through the `eael/atomic/creative_button/effects` and
 * `eael/atomic/creative_button/style_depends` filters (see EA Pro's Extender).
 * Not supported vs. the classic widget: icon (V4 has no FontAwesome icon-library
 * picker, and the SVG control lacks a clear/remove action), gradient
 * background, and liquid-glass effects. The classic widget
 * (eael-creative-button) remains the full-feature implementation.
 */
class Creative_Button extends Atomic_Widget_Base {
	use Has_Template;

	public static $widget_description = 'A call-to-action button with animated hover effects (atomic / V4).';

	public static function get_element_type(): string {
		return 'eael-creative-button-atomic';
	}

	public function get_title() {
		return esc_html__( 'Creative Button', 'essential-addons-for-elementor-lite' );
	}

	public function get_keywords() {
		return [ 'button', 'cta', 'creative', 'atomic', 'ea', 'essential addons' ];
	}

	public function get_icon() {
		return 'eaicon-creative-button atomic';
	}

	public function get_style_depends(): array {
		// Pro hooks here to append its own effect stylesheet handles (mechanics +
		// atomic reveal-color defaults) so the Pro presets render on the front end.
		return apply_filters( 'eael/atomic/creative_button/style_depends', [ 'eael-cb-atomic' ] );
	}

	/**
	 * Free effects ship here. Pro appends its presets via the
	 * `eael/atomic/creative_button/effects` filter — the `effect` prop enum and the
	 * Effect dropdown are both built from this list, so a Pro key becomes a valid
	 * choice automatically once Pro injects it.
	 */
	public static function get_effects(): array {
		$effects = [
			'eael-creative-button--default' => __( 'Default', 'essential-addons-for-elementor-lite' ),
			'eael-creative-button--winona'  => __( 'Winona', 'essential-addons-for-elementor-lite' ),
			'eael-creative-button--ujarak'  => __( 'Ujarak', 'essential-addons-for-elementor-lite' ),
			'eael-creative-button--wayra'   => __( 'Wayra', 'essential-addons-for-elementor-lite' ),
			'eael-creative-button--tamaya'  => __( 'Tamaya', 'essential-addons-for-elementor-lite' ),
			'eael-creative-button--rayen'   => __( 'Rayen', 'essential-addons-for-elementor-lite' ),
		];

		return apply_filters( 'eael/atomic/creative_button/effects', $effects );
	}

	protected static function define_props_schema(): array {
		return [
			'classes' => Classes_Prop_Type::make()
				->default( [] ),

			'text' => Html_V3_Prop_Type::make()
				->default( [
					'content'  => String_Prop_Type::generate( __( 'Click Me!', 'essential-addons-for-elementor-lite' ) ),
					'children' => [],
				] )
				->description( 'The primary text displayed on the button.' ),

			'secondary_text' => String_Prop_Type::make()
				->default( __( 'Go!', 'essential-addons-for-elementor-lite' ) )
				->description( 'Secondary text revealed on hover (Winona, Rayen, Tamaya effects).' ),

			'effect' => String_Prop_Type::make()
				->enum( array_keys( self::get_effects() ) )
				->default( 'eael-creative-button--default' )
				->description( 'The hover animation style.' ),

			'link' => Link_Prop_Type::make(),

			'attributes' => Attributes_Prop_Type::make()->meta( Overridable_Prop_Type::ignore() ),
		];
	}

	protected function define_atomic_controls(): array {
		$content_section = Section::make()
			->set_label( __( 'Content', 'essential-addons-for-elementor-lite' ) )
			->set_items( [
				Inline_Editing_Control::bind_to( 'text' )
					->set_placeholder( __( 'Type your button text here', 'essential-addons-for-elementor-lite' ) )
					->set_label( __( 'Button text', 'essential-addons-for-elementor-lite' ) ),
				Text_Control::bind_to( 'secondary_text' )
					->set_placeholder( __( 'Hover text', 'essential-addons-for-elementor-lite' ) )
					->set_label( __( 'Secondary text', 'essential-addons-for-elementor-lite' ) ),
			] );

		return [
			$content_section,
			Section::make()
				->set_label( __( 'Settings', 'essential-addons-for-elementor-lite' ) )
				->set_id( 'settings' )
				->set_items( $this->get_settings_controls() ),
		];
	}

	protected function get_settings_controls(): array {
		$options = [];
		foreach ( self::get_effects() as $value => $label ) {
			$options[] = [
				'value' => $value,
				'label' => $label,
			];
		}

		return [
			Select_Control::bind_to( 'effect' )
				->set_options( $options )
				->set_label( __( 'Effect', 'essential-addons-for-elementor-lite' ) ),
			Link_Control::bind_to( 'link' )
				->set_placeholder( __( 'Type or paste your URL', 'essential-addons-for-elementor-lite' ) )
				->set_label( __( 'Link', 'essential-addons-for-elementor-lite' ) )
				->set_meta( [ 'topDivider' => true ] ),
			Text_Control::bind_to( '_cssid' )
				->set_label( __( 'ID', 'essential-addons-for-elementor-lite' ) )
				->set_meta( $this->get_css_id_control_meta() ),
		];
	}

	protected function define_base_styles(): array {
		$padding_value = Dimensions_Prop_Type::generate( [
			'block-start'  => Size_Prop_Type::generate( [ 'size' => 15, 'unit' => 'px' ] ),
			'inline-end'   => Size_Prop_Type::generate( [ 'size' => 40, 'unit' => 'px' ] ),
			'block-end'    => Size_Prop_Type::generate( [ 'size' => 15, 'unit' => 'px' ] ),
			'inline-start' => Size_Prop_Type::generate( [ 'size' => 40, 'unit' => 'px' ] ),
		] );

		return [
			'base' => Style_Definition::make()
				->add_variant(
					Style_Variant::make()
						->add_prop( 'display', String_Prop_Type::generate( 'inline-block' ) )
						->add_prop( 'cursor', String_Prop_Type::generate( 'pointer' ) )
						->add_prop( 'text-align', String_Prop_Type::generate( 'center' ) )
						->add_prop( 'text-decoration', String_Prop_Type::generate( 'none' ) )
						->add_prop( 'padding', $padding_value )
						->add_prop( 'border-radius', Size_Prop_Type::generate( [ 'size' => 4, 'unit' => 'px' ] ) )
						// NOTE: deliberately NOT setting `color` here. A hardcoded base
						// text color overrides whatever the user picks in the V4 Style
						// panel (base styles win in the editor's live preview), so text
						// color would appear "stuck". Elementor's own atomic Button omits
						// it for the same reason — let the Style panel own text color.
						->add_prop( 'background', Background_Prop_Type::generate( [
							'color' => Color_Prop_Type::generate( '#6c63ff' ),
						] ) )
				),
		];
	}

	protected function get_templates(): array {
		return [
			'eael/elements/atomic-creative-button' => __DIR__ . '/atomic-creative-button.html.twig',
		];
	}

	public function render_markdown(): string {
		$settings = $this->get_atomic_settings();
		$text     = wp_strip_all_tags( $settings['text'] ?? '' );

		if ( empty( $text ) ) {
			return '';
		}

		if ( ! empty( $settings['link']['href'] ) ) {
			return '[' . $text . '](' . esc_url( $settings['link']['href'] ) . ')';
		}

		return '**' . $text . '**';
	}
}
