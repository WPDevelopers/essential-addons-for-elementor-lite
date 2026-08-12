<?php
/**
 * Theme Builder templates list table.
 *
 * @package Essential_Addons_Elementor
 * @since   6.7.3
 */

namespace Essential_Addons_Elementor\Theme_Builder\Admin;

use Essential_Addons_Elementor\Theme_Builder\Conditions\Conditions_Manager;
use Essential_Addons_Elementor\Theme_Builder\Core\Post_Type;
use Essential_Addons_Elementor\Theme_Builder\Core\Template_Types;
use Essential_Addons_Elementor\Theme_Builder\Models\Template;
use Essential_Addons_Elementor\Theme_Builder\Theme_Builder;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( '\WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Renders the templates table on the Theme Builder dashboard.
 *
 * @since 6.7.3
 */
class Templates_List_Table extends \WP_List_Table {

	/**
	 * Nonce action used for both bulk and row actions.
	 */
	const NONCE_ACTION = 'eael-tb-templates';

	/**
	 * Currently filtered template type, or an empty string for "All".
	 *
	 * @var string
	 */
	protected $current_type = '';

	/**
	 * Currently filtered post status, or an empty string for "All".
	 *
	 * @var string
	 */
	protected $current_status = '';

	/**
	 * Constructor.
	 *
	 * @since 6.7.3
	 */
	public function __construct() {
		parent::__construct(
			[
				'singular' => 'eael-tb-template',
				'plural'   => self::NONCE_ACTION,
				'ajax'     => false,
			]
		);

		$this->current_type   = $this->get_request_type();
		$this->current_status = $this->get_request_status();
	}

	/**
	 * The template type requested through the tabs.
	 *
	 * @since 6.7.3
	 *
	 * @return string
	 */
	public function get_request_type() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only list filter.
		$type = isset( $_REQUEST['template_type'] ) ? sanitize_key( wp_unslash( $_REQUEST['template_type'] ) ) : '';

		return Template_Types::instance()->type_exists( $type ) ? $type : '';
	}

	/**
	 * The post status requested through the views links.
	 *
	 * @since 6.7.3
	 *
	 * @return string
	 */
	public function get_request_status() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only list filter.
		$status = isset( $_REQUEST['post_status'] ) ? sanitize_key( wp_unslash( $_REQUEST['post_status'] ) ) : '';

		return in_array( $status, $this->get_countable_statuses(), true ) ? $status : '';
	}

	/**
	 * Statuses that get their own view link.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	protected function get_countable_statuses() {
		return [ 'publish', 'draft', 'trash' ];
	}

	/**
	 * Statuses included in the "All" view.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	protected function get_default_statuses() {
		return [ 'publish', 'draft', 'pending', 'future', 'private' ];
	}

	/**
	 * Table columns.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	public function get_columns() {
		return [
			'cb'            => '<input type="checkbox" />',
			'title'         => __( 'Title', 'essential-addons-for-elementor-lite' ),
			'template_type' => __( 'Type', 'essential-addons-for-elementor-lite' ),
			'conditions'    => __( 'Display Conditions', 'essential-addons-for-elementor-lite' ),
			'platform'      => __( 'Platform', 'essential-addons-for-elementor-lite' ),
			'author'        => __( 'Author', 'essential-addons-for-elementor-lite' ),
			'date'          => __( 'Date', 'essential-addons-for-elementor-lite' ),
		];
	}

	/**
	 * Sortable columns.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		return [
			'title' => [ 'title', false ],
			'date'  => [ 'date', true ],
		];
	}

	/**
	 * Primary column, used for the row actions and the responsive toggle.
	 *
	 * @since 6.7.3
	 *
	 * @return string
	 */
	protected function get_default_primary_column_name() {
		return 'title';
	}

	/**
	 * Bulk actions.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		if ( 'trash' === $this->current_status ) {
			return [
				'untrash' => __( 'Restore', 'essential-addons-for-elementor-lite' ),
				'delete'  => __( 'Delete Permanently', 'essential-addons-for-elementor-lite' ),
			];
		}

		// Activate / Deactivate are deliberately not offered here. Toggling the
		// active flag is a per-template decision made against that template's
		// conditions, which Quick Edit shows in context; in a bulk dropdown it is
		// an easy mis-click that silently takes several headers off the site.
		// `Admin::handle_bulk_action()` still accepts both, so the `bulk_actions`
		// filter can put them back without any further change.
		return [
			'edit'  => __( 'Bulk Edit', 'essential-addons-for-elementor-lite' ),
			'trash' => __( 'Move to Trash', 'essential-addons-for-elementor-lite' ),
		];
	}

	/**
	 * Status view links.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	protected function get_views() {
		$counts  = $this->get_status_counts();
		$total   = 0;
		$views   = [];
		$base    = $this->get_base_url();
		$current = $this->current_status;

		foreach ( $this->get_default_statuses() as $status ) {
			$total += isset( $counts[ $status ] ) ? $counts[ $status ] : 0;
		}

		$views['all'] = sprintf(
			'<a href="%1$s"%2$s>%3$s <span class="count">(%4$s)</span></a>',
			esc_url( $base ),
			'' === $current ? ' class="current" aria-current="page"' : '',
			esc_html__( 'All', 'essential-addons-for-elementor-lite' ),
			number_format_i18n( $total )
		);

		$labels = [
			'publish' => __( 'Published', 'essential-addons-for-elementor-lite' ),
			'draft'   => __( 'Draft', 'essential-addons-for-elementor-lite' ),
			'trash'   => __( 'Trash', 'essential-addons-for-elementor-lite' ),
		];

		foreach ( $this->get_countable_statuses() as $status ) {
			$count = isset( $counts[ $status ] ) ? $counts[ $status ] : 0;

			if ( ! $count ) {
				continue;
			}

			$views[ $status ] = sprintf(
				'<a href="%1$s"%2$s>%3$s <span class="count">(%4$s)</span></a>',
				esc_url( add_query_arg( 'post_status', $status, $base ) ),
				$current === $status ? ' class="current" aria-current="page"' : '',
				esc_html( $labels[ $status ] ),
				number_format_i18n( $count )
			);
		}

		return $views;
	}

	/**
	 * Number of templates per status for the active type filter.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	protected function get_status_counts() {
		$counts   = [];
		$statuses = array_unique( array_merge( $this->get_default_statuses(), $this->get_countable_statuses() ) );

		foreach ( $statuses as $status ) {
			$query = new \WP_Query( $this->build_query_args( [ 'post_status' => $status, 'posts_per_page' => 1 ] ) );

			$counts[ $status ] = (int) $query->found_posts;
		}

		return $counts;
	}

	/**
	 * Build the WP_Query arguments for the current screen state.
	 *
	 * @since 6.7.3
	 *
	 * @param array $overrides Arguments overriding the defaults.
	 *
	 * @return array
	 */
	protected function build_query_args( $overrides = [] ) {
		$args = [
			'post_type'              => Post_Type::CPT,
			'post_status'            => $this->current_status ? $this->current_status : $this->get_default_statuses(),
			'posts_per_page'         => 20,
			'update_post_term_cache' => false,
			'ignore_sticky_posts'    => true,
		];

		if ( $this->current_type ) {
			$args['meta_query'] = [
				[
					'key'   => Post_Type::META_TYPE,
					'value' => $this->current_type,
				],
			];
		}

		// phpcs:disable WordPress.Security.NonceVerification.Recommended -- read-only list filters.
		if ( ! empty( $_REQUEST['s'] ) ) {
			$args['s'] = sanitize_text_field( wp_unslash( $_REQUEST['s'] ) );
		}

		if ( ! empty( $_REQUEST['m'] ) ) {
			$args['m'] = absint( $_REQUEST['m'] );
		}

		$orderby = isset( $_REQUEST['orderby'] ) ? sanitize_key( wp_unslash( $_REQUEST['orderby'] ) ) : 'date';
		$order   = isset( $_REQUEST['order'] ) ? sanitize_key( wp_unslash( $_REQUEST['order'] ) ) : 'desc';
		// phpcs:enable WordPress.Security.NonceVerification.Recommended

		$args['orderby'] = in_array( $orderby, [ 'title', 'date' ], true ) ? $orderby : 'date';
		$args['order']   = 'asc' === $order ? 'ASC' : 'DESC';

		return array_merge( $args, $overrides );
	}

	/**
	 * Query the templates for the current screen.
	 *
	 * @since 6.7.3
	 */
	public function prepare_items() {
		$per_page = $this->get_items_per_page( 'eael_tb_templates_per_page', 20 );

		$this->_column_headers = [
			$this->get_columns(),
			[],
			$this->get_sortable_columns(),
			$this->get_default_primary_column_name(),
		];

		$query = new \WP_Query(
			$this->build_query_args(
				[
					'posts_per_page' => $per_page,
					'paged'          => $this->get_pagenum(),
				]
			)
		);

		$this->items = $query->posts;

		$this->set_pagination_args(
			[
				'total_items' => (int) $query->found_posts,
				'per_page'    => $per_page,
				'total_pages' => (int) $query->max_num_pages,
			]
		);
	}

	/**
	 * Message shown when the query returns nothing.
	 *
	 * @since 6.7.3
	 */
	public function no_items() {
		esc_html_e( 'No templates found. Create your first header or footer to get started.', 'essential-addons-for-elementor-lite' );
	}

	/**
	 * Checkbox column.
	 *
	 * @since 6.7.3
	 *
	 * @param \WP_Post $item Template post.
	 *
	 * @return string
	 */
	public function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="template_ids[]" value="%1$d" />',
			(int) $item->ID
		);
	}

	/**
	 * Title column with row actions.
	 *
	 * @since 6.7.3
	 *
	 * @param \WP_Post $item Template post.
	 *
	 * @return string
	 */
	public function column_title( $item ) {
		$template = Template::get( $item );

		if ( ! $template ) {
			return '';
		}

		$title = $template->get_title();

		if ( '' === trim( $title ) ) {
			$title = __( '(no title)', 'essential-addons-for-elementor-lite' );
		}

		$is_trashed = 'trash' === $template->get_status();

		if ( $is_trashed ) {
			$name = '<strong>' . esc_html( $title ) . '</strong>';
		} else {
			$name = sprintf(
				'<strong><a class="row-title" href="%1$s">%2$s</a></strong>',
				esc_url( $template->get_edit_url() ),
				esc_html( $title )
			);
		}

		$name .= '<span class="eael-tb-post-states">' . self::render_post_states( $template ) . '</span>';
		$name .= $this->get_inline_data( $template );

		return $name . $this->row_actions( $this->get_row_actions( $template ) );
	}

	/**
	 * Current field values, stashed for the Quick Edit form to read.
	 *
	 * Mirrors how core's `edit.php` ships an `#inline_{id}` payload next to each
	 * row instead of re-fetching it over AJAX.
	 *
	 * @since 6.7.3
	 *
	 * @param Template $template Template model.
	 *
	 * @return string
	 */
	protected function get_inline_data( $template ) {
		$post = $template->get_post();

		return sprintf(
			'<div class="hidden eael-tb-inline-data" id="eael-tb-inline_%1$d"'
			. ' data-title="%2$s" data-slug="%3$s" data-date="%4$s" data-password="%5$s" data-private="%6$s"'
			. ' data-page-template="%7$s" data-status="%8$s" data-type="%9$s" data-priority="%10$d" data-active="%11$s"></div>',
			$template->get_id(),
			esc_attr( $template->get_title() ),
			esc_attr( $post->post_name ),
			esc_attr( $post->post_date ),
			esc_attr( $post->post_password ),
			'private' === $post->post_status ? 'yes' : 'no',
			esc_attr( (string) get_post_meta( $template->get_id(), '_wp_page_template', true ) ),
			esc_attr( $template->get_status() ),
			esc_attr( $template->get_type() ),
			$template->get_priority(),
			$template->is_active() ? 'yes' : 'no'
		);
	}

	/**
	 * Post state labels appended to the title.
	 *
	 * Public and static so the Quick Edit endpoint can re-render the label without
	 * building a whole list table.
	 *
	 * @since 6.7.3
	 *
	 * @param Template $template Template model.
	 *
	 * @return string
	 */
	public static function render_post_states( $template ) {
		$states = [];

		if ( 'elementor' === $template->get_platform() ) {
			$states[] = __( 'Elementor', 'essential-addons-for-elementor-lite' );
		}

		switch ( $template->get_status() ) {
			case 'draft':
				$states[] = __( 'Draft', 'essential-addons-for-elementor-lite' );
				break;

			case 'pending':
				$states[] = __( 'Pending', 'essential-addons-for-elementor-lite' );
				break;

			case 'private':
				$states[] = __( 'Private', 'essential-addons-for-elementor-lite' );
				break;

			case 'future':
				$states[] = __( 'Scheduled', 'essential-addons-for-elementor-lite' );
				break;
		}

		if ( '' !== $template->get_post()->post_password ) {
			$states[] = __( 'Password protected', 'essential-addons-for-elementor-lite' );
		}

		if ( ! $template->is_active() ) {
			$states[] = __( 'Inactive', 'essential-addons-for-elementor-lite' );
		}

		if ( empty( $states ) ) {
			return '';
		}

		return ' — <span class="post-state">' . esc_html( implode( ', ', $states ) ) . '</span>';
	}

	/**
	 * Row actions for a template.
	 *
	 * @since 6.7.3
	 *
	 * @param Template $template Template model.
	 *
	 * @return array
	 */
	protected function get_row_actions( $template ) {
		$id      = $template->get_id();
		$actions = [];

		if ( 'trash' === $template->get_status() ) {
			$actions['untrash'] = sprintf(
				'<a href="%1$s">%2$s</a>',
				esc_url( $this->get_action_url( 'untrash', $id ) ),
				esc_html__( 'Restore', 'essential-addons-for-elementor-lite' )
			);

			$actions['delete'] = sprintf(
				'<a class="submitdelete eael-tb-confirm" href="%1$s">%2$s</a>',
				esc_url( $this->get_action_url( 'delete', $id ) ),
				esc_html__( 'Delete Permanently', 'essential-addons-for-elementor-lite' )
			);

			return $actions;
		}

		// Order matters: `row_actions()` prints the array as given. The sequence
		// below is the agreed one — Edit, Quick Edit, Trash, View, Duplicate,
		// Edit Conditions, Edit with Elementor — so the two destructive-looking
		// entries sit next to the everyday ones and the Theme Builder specific
		// links close the row.
		$wp_edit_url = get_edit_post_link( $id );

		if ( $wp_edit_url ) {
			$actions['edit'] = sprintf(
				'<a href="%1$s">%2$s</a>',
				esc_url( $wp_edit_url ),
				esc_html__( 'Edit', 'essential-addons-for-elementor-lite' )
			);
		}

		// Key carries `hide-if-no-js` so row_actions() puts it on the wrapping span
		// — same convention core uses for its own Quick Edit.
		$actions['inline hide-if-no-js'] = sprintf(
			'<button type="button" class="button-link eael-tb-quick-edit" data-template-id="%1$d" aria-expanded="false">%2$s</button>',
			$id,
			esc_html__( 'Quick Edit', 'essential-addons-for-elementor-lite' )
		);

		$actions['trash'] = sprintf(
			'<a class="submitdelete" href="%1$s">%2$s</a>',
			esc_url( $this->get_action_url( 'trash', $id ) ),
			esc_html__( 'Trash', 'essential-addons-for-elementor-lite' )
		);

		$preview_url = get_preview_post_link( $template->get_post() );

		if ( $preview_url ) {
			$actions['view'] = sprintf(
				'<a href="%1$s" target="_blank" rel="noopener">%2$s</a>',
				esc_url( $preview_url ),
				esc_html__( 'View', 'essential-addons-for-elementor-lite' )
			);
		}

		$actions['duplicate'] = sprintf(
			'<a href="%1$s">%2$s</a>',
			esc_url( $this->get_action_url( 'duplicate', $id ) ),
			esc_html__( 'Duplicate', 'essential-addons-for-elementor-lite' )
		);

		$actions['conditions'] = sprintf(
			'<a href="#" class="eael-tb-edit-conditions" data-template-id="%1$d" data-template-type="%2$s" data-conditions="%3$s">%4$s</a>',
			$id,
			esc_attr( $template->get_type() ),
			esc_attr( wp_json_encode( $this->prepare_conditions_for_ui( $template ) ) ),
			esc_html__( 'Edit Conditions', 'essential-addons-for-elementor-lite' )
		);

		$actions['elementor'] = sprintf(
			'<a href="%1$s">%2$s</a>',
			esc_url( $template->get_edit_url() ),
			esc_html__( 'Edit with Elementor', 'essential-addons-for-elementor-lite' )
		);

		/**
		 * Filters the row actions of a Theme Builder template.
		 *
		 * @since 6.7.3
		 *
		 * @param array    $actions  Row actions keyed by slug.
		 * @param Template $template The template.
		 */
		return apply_filters( 'eael/theme_builder/row_actions', $actions, $template );
	}

	/**
	 * Conditions of a template, decorated with the labels the modal needs.
	 *
	 * @since 6.7.3
	 *
	 * @param Template $template Template model.
	 *
	 * @return array
	 */
	protected function prepare_conditions_for_ui( $template ) {
		$manager    = Conditions_Manager::instance();
		$conditions = $template->get_conditions();
		$prepared   = [];

		foreach ( $conditions as $condition ) {
			$rule                    = \Essential_Addons_Elementor\Theme_Builder\Conditions\Rules::get_rule( $condition['name'] );
			$condition['sub_label']  = ( $rule && ! empty( $rule['sub_source'] ) && $condition['sub_id'] )
				? $manager->get_object_label( $rule['sub_source'], $condition['sub_id'] )
				: '';
			$prepared[]              = $condition;
		}

		return $prepared;
	}

	/**
	 * Nonce-protected URL for a row action.
	 *
	 * @since 6.7.3
	 *
	 * @param string $action  Action slug.
	 * @param int    $post_id Template ID.
	 *
	 * @return string
	 */
	protected function get_action_url( $action, $post_id ) {
		$url = add_query_arg(
			[
				'action'       => 'eael_tb_' . $action,
				'template_id'  => (int) $post_id,
			],
			$this->get_base_url()
		);

		return wp_nonce_url( $url, 'eael_tb_row_' . $action . '_' . (int) $post_id );
	}

	/**
	 * Base URL of the dashboard, preserving the active filters.
	 *
	 * @since 6.7.3
	 *
	 * @return string
	 */
	protected function get_base_url() {
		$args = [];

		if ( $this->current_type ) {
			$args['template_type'] = $this->current_type;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only list filter.
		if ( ! empty( $_REQUEST['s'] ) ) {
			$args['s'] = sanitize_text_field( wp_unslash( $_REQUEST['s'] ) );
		}

		return Theme_Builder::page_url( $args );
	}

	/**
	 * Template type column.
	 *
	 * @since 6.7.3
	 *
	 * @param \WP_Post $item Template post.
	 *
	 * @return string
	 */
	public function column_template_type( $item ) {
		$type = get_post_meta( $item->ID, Post_Type::META_TYPE, true );

		return esc_html( Template_Types::instance()->get_label( $type ) );
	}

	/**
	 * Display conditions summary column.
	 *
	 * @since 6.7.3
	 *
	 * @param \WP_Post $item Template post.
	 *
	 * @return string
	 */
	public function column_conditions( $item ) {
		$template = Template::get( $item );

		if ( ! $template ) {
			return '';
		}

		$manager    = Conditions_Manager::instance();
		$conditions = $template->get_conditions();
		$summary    = esc_html( $manager->get_conditions_summary( $conditions ) );
		$broken     = $manager->find_broken_conditions( $conditions );

		if ( empty( $broken ) ) {
			return $summary;
		}

		$warning = _n(
			'One of the targeted items has been deleted, so this condition can never match. Edit the conditions to fix it.',
			'Some of the targeted items have been deleted, so those conditions can never match. Edit the conditions to fix them.',
			count( $broken ),
			'essential-addons-for-elementor-lite'
		);

		return sprintf(
			'<span class="eael-tb-broken-conditions" title="%1$s"><span class="dashicons dashicons-warning" aria-hidden="true"></span>%2$s<span class="screen-reader-text"> %1$s</span></span>',
			esc_attr( $warning ),
			$summary
		);
	}

	/**
	 * Platform column.
	 *
	 * @since 6.7.3
	 *
	 * @param \WP_Post $item Template post.
	 *
	 * @return string
	 */
	public function column_platform( $item ) {
		$platform = get_post_meta( $item->ID, Post_Type::META_PLATFORM, true );

		return esc_html( $platform ? $platform : 'elementor' );
	}

	/**
	 * Author column.
	 *
	 * @since 6.7.3
	 *
	 * @param \WP_Post $item Template post.
	 *
	 * @return string
	 */
	public function column_author( $item ) {
		$author = get_the_author_meta( 'display_name', $item->post_author );

		return esc_html( $author ? $author : __( '(unknown)', 'essential-addons-for-elementor-lite' ) );
	}

	/**
	 * Date column.
	 *
	 * @since 6.7.3
	 *
	 * @param \WP_Post $item Template post.
	 *
	 * @return string
	 */
	public function column_date( $item ) {
		return self::render_date( $item );
	}

	/**
	 * Date cell markup.
	 *
	 * Public and static so the Quick Edit endpoint can refresh the cell after a
	 * status change without building a whole list table.
	 *
	 * @since 6.7.3
	 *
	 * @param \WP_Post $item Template post.
	 *
	 * @return string
	 */
	public static function render_date( $item ) {
		if ( 'future' === $item->post_status ) {
			$label = __( 'Scheduled', 'essential-addons-for-elementor-lite' );
		} elseif ( in_array( $item->post_status, [ 'publish', 'private' ], true ) ) {
			$label = __( 'Published', 'essential-addons-for-elementor-lite' );
		} else {
			$label = __( 'Last Modified', 'essential-addons-for-elementor-lite' );
		}

		if ( '0000-00-00 00:00:00' === $item->post_date ) {
			return '&mdash;';
		}

		$format = get_option( 'date_format' ) . ' ' . get_option( 'time_format' );

		return sprintf(
			'%1$s<br />%2$s',
			esc_html( $label ),
			esc_html( get_the_time( $format, $item ) )
		);
	}

	/**
	 * Fallback column renderer.
	 *
	 * @since 6.7.3
	 *
	 * @param \WP_Post $item        Template post.
	 * @param string   $column_name Column slug.
	 *
	 * @return string
	 */
	public function column_default( $item, $column_name ) {
		return '';
	}

	/**
	 * Extra filters above the table.
	 *
	 * @since 6.7.3
	 *
	 * @param string $which `top` or `bottom`.
	 */
	protected function extra_tablenav( $which ) {
		if ( 'top' !== $which ) {
			return;
		}

		?>
		<div class="alignleft actions">
			<?php
			$this->months_dropdown( Post_Type::CPT );
			$this->type_dropdown();

			submit_button( __( 'Filter', 'essential-addons-for-elementor-lite' ), '', 'filter_action', false, [ 'id' => 'eael-tb-post-query-submit' ] );
			?>
		</div>
		<?php
	}

	/**
	 * Template type dropdown, mirroring the tabs for keyboard/AT users.
	 *
	 * @since 6.7.3
	 */
	protected function type_dropdown() {
		$types = Template_Types::instance()->get_types();

		if ( count( $types ) < 2 ) {
			return;
		}

		?>
		<label for="eael-tb-filter-type" class="screen-reader-text">
			<?php esc_html_e( 'Filter by template type', 'essential-addons-for-elementor-lite' ); ?>
		</label>
		<select name="template_type" id="eael-tb-filter-type">
			<option value=""><?php esc_html_e( 'All types', 'essential-addons-for-elementor-lite' ); ?></option>
			<?php foreach ( $types as $slug => $type ) : ?>
				<option value="<?php echo esc_attr( $slug ); ?>" <?php selected( $this->current_type, $slug ); ?>>
					<?php echo esc_html( $type['label'] ); ?>
				</option>
			<?php endforeach; ?>
		</select>
		<?php
	}
}
