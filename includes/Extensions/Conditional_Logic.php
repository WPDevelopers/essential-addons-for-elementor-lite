<?php

namespace Essential_Addons_Elementor\Extensions;

use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Repeater;
use \Essential_Addons_Elementor\Classes\Helper as ControlsHelper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Conditional_Logic {

	/**
	 * Initialize hooks
	 */
	public function __construct() {
		add_action( 'elementor/element/common/_section_style/after_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/column/section_advanced/after_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/section/section_advanced/after_section_end', [ $this, 'register_controls' ] );
		add_filter( 'elementor/frontend/widget/should_render', [ $this, 'content_render' ], 10, 2 );
		add_filter( 'elementor/frontend/column/should_render', [ $this, 'content_render' ], 10, 2 );
		add_filter( 'elementor/frontend/section/should_render', [ $this, 'content_render' ], 10, 2 );
	}

	public function register_controls( $element ) {
		$element->start_controls_section(
			'eael_conditional_logic_section',
			[
				'label' => __( '<i class="eaicon-logo"></i> Conditional Logic', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_ADVANCED
			]
		);

		$element->add_control(
			'eael_cl_enable',
			[
				'label'        => __( 'Enable Logic', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Yes', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'No', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
			]
		);

		$element->add_control(
			'eael_cl_visibility_action',
			[
				'label'     => __( 'Visibility Action', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'show'            => [
						'title' => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eaicon-eye-solid',
					],
					'hide'            => [
						'title' => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eaicon-eye-slash-solid',
					],
					'forcefully_hide' => [
						'title' => esc_html__( 'Hide Without Condition', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eaicon-ban-solid',
					],
				],
				'default'   => 'show',
				'toggle'    => false,
				'condition' => [
					'eael_cl_enable' => 'yes',
				]
			]
		);

		$element->add_control(
			'eael_cl_action_apply_if',
			[
				'label'     => __( 'Action Applicable if', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'all' => [
						'title' => esc_html__( 'True All Logic', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eaicon-dice-six-solid',
					],
					'any' => [
						'title' => esc_html__( 'True Any Logic', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eaicon-dice-one-solid',
					],
				],
				'default'   => 'all',
				'toggle'    => false,
				'condition' => [
					'eael_cl_enable'             => 'yes',
					'eael_cl_visibility_action!' => 'forcefully_hide',
				]
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'logic_type',
			[
				'label'   => __( 'Type', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'login_status',
				'options' => [
					'login_status' => __( 'Login Status', 'essential-addons-for-elementor-lite' ),
					'user_role'    => __( 'User Role', 'essential-addons-for-elementor-lite' ),
					'user'         => __( 'User', 'essential-addons-for-elementor-lite' ),
					'post_type'    => __( 'Post Type', 'essential-addons-for-elementor-lite' ),
					'post'         => __( 'Post', 'essential-addons-for-elementor-lite' ),
					'browser'      => __( 'Browser', 'essential-addons-for-elementor-lite' ),
					'date_time'    => __( 'Date & Time', 'essential-addons-for-elementor-lite' ),
				],
			]
		);

		$repeater->add_control(
			'login_status_operand',
			[
				'label'     => __( 'Login Status', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'logged_in'     => [
						'title' => esc_html__( 'Logged In', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eaicon-user-solid',
					],
					'not_logged_in' => [
						'title' => esc_html__( 'Not Logged In', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eaicon-user-slash-solid',
					],
				],
				'default'   => 'logged_in',
				'toggle'    => false,
				'condition' => [
					'logic_type' => 'login_status',
				]
			]
		);

		$repeater->add_control(
			'user_role_logic',
			[
				'label'      => __( 'User Role Logic', 'essential-addons-for-elementor-lite' ),
				'show_label' => false,
				'type'       => Controls_Manager::CHOOSE,
				'options'    => [
					'equal'       => [
						'title' => esc_html__( 'Is', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eaicon-equals-solid',
					],
					'not_equal'   => [
						'title' => esc_html__( 'Is Not', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eaicon-not-equal-solid',
					],
					'between'     => [
						'title' => esc_html__( 'Include', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eaicon-folder-open-solid',
					],
					'not_between' => [
						'title' => esc_html__( 'Exclude', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eaicon-folder-open-regular',
					],
				],
				'default'    => 'equal',
				'toggle'     => false,
				'condition'  => [
					'logic_type' => 'user_role',
				]
			]
		);

		$roles = $this->get_editable_roles();

		$repeater->add_control(
			'user_role_operand_single',
			[
				'label'       => __( 'User Roles', 'essential-addons-for-elementor-lite' ),
				'show_label'  => false,
				'label_block' => true,
				'type'        => Controls_Manager::SELECT,
				'options'     => $roles,
				'conditions'  => [
					'relation' => 'and',
					'terms'    => [
						[
							'name'     => 'logic_type',
							'operator' => '===',
							'value'    => 'user_role',
						],
						[
							'name'     => 'user_role_logic',
							'operator' => '!==',
							'value'    => 'between',
						],
						[
							'name'     => 'user_role_logic',
							'operator' => '!==',
							'value'    => 'not_between',
						],
					],
				]
			]
		);

		$repeater->add_control(
			'user_role_operand_multi',
			[
				'label'       => __( 'User Roles', 'essential-addons-for-elementor-lite' ),
				'show_label'  => false,
				'label_block' => true,
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => $roles,
				'conditions'  => [
					'relation' => 'and',
					'terms'    => [
						[
							'name'     => 'logic_type',
							'operator' => '===',
							'value'    => 'user_role',
						],
						[
							'name'     => 'user_role_logic',
							'operator' => '!==',
							'value'    => 'equal',
						],
						[
							'name'     => 'user_role_logic',
							'operator' => '!==',
							'value'    => 'not_equal',
						],
					],
				]
			]
		);

		$repeater->add_control(
			'user_logic',
			[
				'label'      => __( 'User Logic', 'essential-addons-for-elementor-lite' ),
				'show_label' => false,
				'type'       => Controls_Manager::CHOOSE,
				'options'    => [
					'between'     => [
						'title' => esc_html__( 'Include', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eaicon-folder-open-solid',
					],
					'not_between' => [
						'title' => esc_html__( 'Exclude', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eaicon-folder-open-regular',
					],
				],
				'default'    => 'between',
				'toggle'     => false,
				'condition'  => [
					'logic_type' => 'user',
				]
			]
		);

		$repeater->add_control(
			'user_operand',
			[
				'label'       => esc_html__( 'Select Users', 'essential-addons-for-elementor-lite' ),
				'type'        => 'eael-select2',
				'source_name' => 'user',
				'source_type' => 'all',
				'label_block' => true,
				'show_label'  => false,
				'multiple'    => true,
				'condition'   => [
					'logic_type' => 'user',
				]
			]
		);

		$repeater->add_control(
			'post_type_logic',
			[
				'label'      => __( 'Post Type Logic', 'essential-addons-for-elementor-lite' ),
				'show_label' => false,
				'type'       => Controls_Manager::CHOOSE,
				'options'    => [
					'equal'       => [
						'title' => esc_html__( 'Is', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eaicon-equals-solid',
					],
					'not_equal'   => [
						'title' => esc_html__( 'Is Not', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eaicon-not-equal-solid',
					],
					'between'     => [
						'title' => esc_html__( 'Include', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eaicon-folder-open-solid',
					],
					'not_between' => [
						'title' => esc_html__( 'Exclude', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eaicon-folder-open-regular',
					],
				],
				'default'    => 'equal',
				'toggle'     => false,
				'condition'  => [
					'logic_type' => 'post_type',
				]
			]
		);

		$post_types = ControlsHelper::get_post_types();

		$repeater->add_control(
			'post_type_operand_multi',
			[
				'label'       => esc_html__( 'Select Post Types', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'show_label'  => false,
				'multiple'    => true,
				'options'     => $post_types,
				'default'     => key( $post_types ),
				'conditions'  => [
					'relation' => 'and',
					'terms'    => [
						[
							'name'     => 'logic_type',
							'operator' => '===',
							'value'    => 'post_type',
						],
						[
							'name'     => 'post_type_logic',
							'operator' => '!==',
							'value'    => 'equal',
						],
						[
							'name'     => 'post_type_logic',
							'operator' => '!==',
							'value'    => 'not_equal',
						],
					],
				]
			]
		);

		$repeater->add_control(
			'post_type_operand_single',
			[
				'label'       => esc_html__( 'Select Post Type', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'show_label'  => false,
				'options'     => $post_types,
				'default'     => key( $post_types ),
				'conditions'  => [
					'relation' => 'and',
					'terms'    => [
						[
							'name'     => 'logic_type',
							'operator' => '===',
							'value'    => 'post_type',
						],
						[
							'name'     => 'post_type_logic',
							'operator' => '!==',
							'value'    => 'between',
						],
						[
							'name'     => 'post_type_logic',
							'operator' => '!==',
							'value'    => 'not_between',
						],
					],
				]
			]
		);

		$repeater->add_control(
			'post_logic',
			[
				'label'      => __( 'Post Logic', 'essential-addons-for-elementor-lite' ),
				'show_label' => false,
				'type'       => Controls_Manager::CHOOSE,
				'options'    => [
					'between'     => [
						'title' => esc_html__( 'Include', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eaicon-folder-open-solid',
					],
					'not_between' => [
						'title' => esc_html__( 'Exclude', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eaicon-folder-open-regular',
					],
				],
				'default'    => 'between',
				'toggle'     => false,
				'condition'  => [
					'logic_type' => 'post',
				]
			]
		);

		$repeater->add_control(
			'post_operand',
			[
				'label'       => esc_html__( 'Select Posts', 'essential-addons-for-elementor-lite' ),
				'type'        => 'eael-select2',
				'source_name' => 'post_type',
				'source_type' => 'any',
				'label_block' => true,
				'show_label'  => false,
				'multiple'    => true,
				'condition'   => [
					'logic_type' => 'post',
				]
			]
		);

		$repeater->add_control(
			'browser_logic',
			[
				'label'      => __( 'Browser Logic', 'essential-addons-for-elementor-lite' ),
				'show_label' => false,
				'type'       => Controls_Manager::CHOOSE,
				'options'    => [
					'between'     => [
						'title' => esc_html__( 'Include', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eaicon-folder-open-solid',
					],
					'not_between' => [
						'title' => esc_html__( 'Exclude', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eaicon-folder-open-regular',
					],
				],
				'default'    => 'between',
				'toggle'     => false,
				'condition'  => [
					'logic_type' => 'browser',
				]
			]
		);

		$repeater->add_control(
			'browser_operand',
			[
				'label'       => __( 'Browser List', 'essential-addons-for-elementor-lite' ),
				'show_label'  => false,
				'label_block' => true,
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => $this->get_browser_list(),
				'default'     => key( $this->get_browser_list() ),
				'condition'   => [
					'logic_type' => 'browser',
				]
			]
		);

		$repeater->add_control(
			'date_time_logic',
			[
				'label'      => __( 'Date and time', 'essential-addons-for-elementor-lite' ),
				'show_label' => false,
				'type'       => Controls_Manager::CHOOSE,
				'options'    => [
					'equal'       => [
						'title' => esc_html__( 'Is', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eaicon-equals-solid',
					],
					'not_equal'   => [
						'title' => esc_html__( 'Is Not', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eaicon-not-equal-solid',
					],
					'between'     => [
						'title' => esc_html__( 'Between', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eaicon-folder-open-solid',
					],
					'not_between' => [
						'title' => esc_html__( 'Not Between', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eaicon-folder-open-regular',
					],
				],
				'default'    => 'equal',
				'toggle'     => false,
				'condition'  => [
					'logic_type' => 'date_time',
				]
			]
		);

		$repeater->add_control(
			'single_date',
			[
				'label'          => esc_html__( 'Date', 'plugin-name' ),
				'label_block'    => false,
				'type'           => Controls_Manager::DATE_TIME,
				'picker_options' => [
					'enableTime' => false,
					'altInput'   => true,
					'altFormat'  => 'M j, Y',
					'dateFormat' => 'Y-m-d'
				],
				'conditions'     => [
					'relation' => 'and',
					'terms'    => [
						[
							'name'     => 'logic_type',
							'operator' => '===',
							'value'    => 'date_time',
						],
						[
							'name'     => 'date_time_logic',
							'operator' => '!==',
							'value'    => 'between',
						],
						[
							'name'     => 'date_time_logic',
							'operator' => '!==',
							'value'    => 'not_between',
						],
					],
				]
			]
		);

		$repeater->add_control(
			'from_date',
			[
				'label'          => esc_html__( 'From', 'plugin-name' ),
				'label_block'    => false,
				'type'           => Controls_Manager::DATE_TIME,
				'picker_options' => [
					'altInput'   => true,
					'altFormat'  => 'M j, Y h:i K',
					'dateFormat' => 'Y-m-d H:i:S'
				],
				'conditions'     => [
					'relation' => 'and',
					'terms'    => [
						[
							'name'     => 'logic_type',
							'operator' => '===',
							'value'    => 'date_time',
						],
						[
							'name'     => 'date_time_logic',
							'operator' => '!==',
							'value'    => 'equal',
						],
						[
							'name'     => 'date_time_logic',
							'operator' => '!==',
							'value'    => 'not_equal',
						],
					],
				]
			]
		);

		$repeater->add_control(
			'to_date',
			[
				'label'          => esc_html__( 'To', 'plugin-name' ),
				'label_block'    => false,
				'type'           => Controls_Manager::DATE_TIME,
				'picker_options' => [
					'altInput'   => true,
					'altFormat'  => 'M j, Y h:i K',
					'dateFormat' => 'Y-m-d H:i:S'
				],
				'conditions'     => [
					'relation' => 'and',
					'terms'    => [
						[
							'name'     => 'logic_type',
							'operator' => '===',
							'value'    => 'date_time',
						],
						[
							'name'     => 'date_time_logic',
							'operator' => '!==',
							'value'    => 'equal',
						],
						[
							'name'     => 'date_time_logic',
							'operator' => '!==',
							'value'    => 'not_equal',
						],
					],
				]
			]
		);

		$element->add_control(
			'eael_cl_logics',
			[
				'label'       => __( 'Logics', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'logic_type'           => 'login_status',
						'login_status_operand' => 'logged_in',
					],
				],
				'title_field' => '{{{ ea_conditional_logic_type_title(logic_type) }}}',
				'condition'   => [
					'eael_cl_enable'             => 'yes',
					'eael_cl_visibility_action!' => 'forcefully_hide',
				]
			]
		);

		$element->end_controls_section();
	}

	/**
	 * Get All editable roles and return array with simple slug|name pare
	 *
	 * @param $first_index
	 * @param $output
	 *
	 * @return array|string
	 */
	public function get_editable_roles() {
		$wp_roles       = [ '' => __( 'Select', 'essential-addons-for-elementor-lite' ) ];
		$all_roles      = wp_roles()->roles;
		$editable_roles = apply_filters( 'editable_roles', $all_roles );

		foreach ( $editable_roles as $slug => $editable_role ) {
			$wp_roles[ $slug ] = $editable_role['name'];
		}

		return $wp_roles;
	}

	/**
	 * Get all browser list and return array with simple slug|name pare
	 *
	 * @return array
	 */
	public function get_browser_list() {
		return [
			'chrome'    => __( 'Google Chrome', 'essential-addons-for-elementor-lite' ),
			'firefox'   => __( 'Mozilla Firefox', 'essential-addons-for-elementor-lite' ),
			'safari'    => __( 'Safari', 'essential-addons-for-elementor-lite' ),
			'i_safari'  => __( 'Iphone Safari', 'essential-addons-for-elementor-lite' ),
			'opera'     => __( 'Opera', 'essential-addons-for-elementor-lite' ),
			'edge'      => __( 'Edge', 'essential-addons-for-elementor-lite' ),
			'ie'        => __( 'Internet Explorer', 'essential-addons-for-elementor-lite' ),
			'mac_ie'    => __( 'Internet Explorer for Mac OS X', 'essential-addons-for-elementor-lite' ),
			'netscape4' => __( 'Netscape 4', 'essential-addons-for-elementor-lite' ),
			'lynx'      => __( 'Lynx', 'essential-addons-for-elementor-lite' ),
			'others'    => __( 'Others', 'essential-addons-for-elementor-lite' ),
		];
	}

	/**
	 * Get current browser
	 *
	 * @return string
	 */
	public function get_current_browser() {
		global $is_lynx, $is_gecko, $is_winIE, $is_macIE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone, $is_edge;

		$browser = 'others';

		switch ( true ) {
			case $is_chrome:
				$browser = 'chrome';
				break;
			case $is_gecko:
				$browser = 'firefox';
				break;
			case $is_safari:
				$browser = 'safari';
				break;
			case $is_iphone:
				$browser = 'i_safari';
				break;
			case $is_opera:
				$browser = 'opera';
				break;
			case $is_edge:
				$browser = 'edge';
				break;
			case $is_winIE:
				$browser = 'ie';
				break;
			case $is_macIE:
				$browser = 'mac_ie';
				break;
			case $is_NS4:
				$browser = 'netscape4';
				break;
			case $is_lynx:
				$browser = 'lynx';
				break;

		}

		return $browser;
	}

	public function parse_arg( $arg ) {
		$arg = wp_parse_args( $arg, [
			'eael_cl_enable'            => '',
			'eael_cl_visibility_action' => '',
			'eael_cl_logics'            => [],
			'eael_cl_action_apply_if'   => '',
		] );

		return $arg;
	}

	/**
	 * Check all logics and return the final result
	 *
	 * @param $settings
	 *
	 * @return bool
	 */
	public function check_logics( $settings ) {
		$return                = false;
		$needed_any_logic_true = $settings['eael_cl_action_apply_if'] === 'any';
		$needed_all_logic_true = $settings['eael_cl_action_apply_if'] === 'all';

		foreach ( $settings['eael_cl_logics'] as $cl_logic ) {
			switch ( $cl_logic['logic_type'] ) {
				case 'login_status':
					$return = $cl_logic['login_status_operand'] === 'logged_in' ? is_user_logged_in() : ! is_user_logged_in();

					if ( $needed_any_logic_true && $return ) {
						break( 2 );
					}

					if ( $needed_all_logic_true && ! $return ) {
						break( 2 );
					}

					break;
				case 'user_role':
					$return = false;
					if ( is_user_logged_in() ) {
						$user_roles = get_userdata( get_current_user_id() )->roles;
						$operand    = ( $cl_logic['user_role_logic'] === 'equal' || $cl_logic['user_role_logic'] === 'not_equal' ) ? [ $cl_logic['user_role_operand_single'] ] : $cl_logic['user_role_operand_multi'];
						$result     = array_intersect( $user_roles, $operand );
						$return     = ( $cl_logic['user_role_logic'] === 'equal' || $cl_logic['user_role_logic'] === 'between' ) ? count( $result ) > 0 : count( $result ) == 0;
					}

					if ( $needed_any_logic_true && $return ) {
						break( 2 );
					}

					if ( $needed_all_logic_true && ! $return ) {
						break( 2 );
					}

					break;
				case 'user':
					$return = false;
					if ( is_user_logged_in() ) {
						$user    = get_current_user_id();
						$operand = array_map( 'intval', (array) $cl_logic['user_operand'] );
						$return  = $cl_logic['user_logic'] === 'between' ? in_array( $user, $operand ) : ! in_array( $user, $operand );
					}

					if ( $needed_any_logic_true && $return ) {
						break( 2 );
					}

					if ( $needed_all_logic_true && ! $return ) {
						break( 2 );
					}

					break;
				case 'post_type':
					$ID        = get_the_ID();
					$post_type = get_post_type( $ID );
					$operand   = ( $cl_logic['post_type_logic'] === 'equal' || $cl_logic['post_type_logic'] === 'not_equal' ) ? [ $cl_logic['post_type_operand_single'] ] : $cl_logic['post_type_operand_multi'];
					$return    = ( $cl_logic['post_type_logic'] === 'equal' || $cl_logic['post_type_logic'] === 'between' ) ? in_array( $post_type, $operand ) : ! in_array( $post_type, $operand );

					if ( $needed_any_logic_true && $return ) {
						break( 2 );
					}

					if ( $needed_all_logic_true && ! $return ) {
						break( 2 );
					}

					break;
				case 'post':
					$ID      = get_the_ID();
					$operand = (array) $cl_logic['post_operand'];
					$return  = $cl_logic['post_logic'] === 'between' ? in_array( $ID, $operand ) : ! in_array( $ID, $operand );

					if ( $needed_any_logic_true && $return ) {
						break( 2 );
					}

					if ( $needed_all_logic_true && ! $return ) {
						break( 2 );
					}

					break;
				case 'browser':
					$browser = $this->get_current_browser();
					$operand = (array) $cl_logic['browser_operand'];
					$return  = $cl_logic['browser_logic'] === 'between' ? in_array( $browser, $operand ) : ! in_array( $browser, $operand );

					if ( $needed_any_logic_true && $return ) {
						break( 2 );
					}

					if ( $needed_all_logic_true && ! $return ) {
						break( 2 );
					}

					break;
				case 'date_time':
					$current_time = current_time( 'U' );
					$from         = ( $cl_logic['date_time_logic'] === 'equal' || $cl_logic['date_time_logic'] === 'not_equal' ) ? strtotime( "{$cl_logic['single_date']} 00:00:00" ) : strtotime( $cl_logic['from_date'] );
					$to           = ( $cl_logic['date_time_logic'] === 'equal' || $cl_logic['date_time_logic'] === 'not_equal' ) ? strtotime( "{$cl_logic['single_date']} 23:59:59" ) : strtotime( $cl_logic['to_date'] );
					$return       = $cl_logic['date_time_logic'] === 'equal' || $cl_logic['date_time_logic'] === 'between' ? $from <= $current_time && $current_time <= $to : $from >= $current_time || $current_time >= $to;

					if ( $needed_any_logic_true && $return ) {
						break( 2 );
					}

					if ( $needed_all_logic_true && ! $return ) {
						break( 2 );
					}

					break;
			}
		}

		return $return;
	}

	public function content_render( $should_render, Element_Base $element ) {
		$settings = $element->get_settings();
		$settings = $this->parse_arg( $settings );

		if ( $settings['eael_cl_enable'] === 'yes' ) {
			switch ( $settings['eael_cl_visibility_action'] ) {
				case 'show':
					return $this->check_logics( $settings ) ? true : false;
					break;
				case 'hide':
					return $this->check_logics( $settings ) ? false : true;
					break;
				case 'forcefully_hide':
					return false;
			}
		}

		return $should_render;
	}

}
