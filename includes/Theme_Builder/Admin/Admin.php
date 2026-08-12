<?php
/**
 * Theme Builder admin screens.
 *
 * @package Essential_Addons_Elementor
 * @since   6.7.3
 */

namespace Essential_Addons_Elementor\Theme_Builder\Admin;

use Essential_Addons_Elementor\Theme_Builder\Conditions\Conditions_Cleanup;
use Essential_Addons_Elementor\Theme_Builder\Conditions\Rules;
use Essential_Addons_Elementor\Theme_Builder\Core\Post_Type;
use Essential_Addons_Elementor\Theme_Builder\Core\Template_Cache;
use Essential_Addons_Elementor\Theme_Builder\Core\Template_Types;
use Essential_Addons_Elementor\Theme_Builder\Models\Template;
use Essential_Addons_Elementor\Theme_Builder\Theme_Builder;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Registers the Theme Builder dashboard under the Essential Addons menu.
 *
 * @since 6.7.3
 */
class Admin {

	/**
	 * Parent menu slug of the Essential Addons dashboard.
	 */
	const PARENT_SLUG = 'eael-settings';

	/**
	 * Page hook suffix returned by add_submenu_page().
	 *
	 * @var string
	 */
	private $hook_suffix = '';

	/**
	 * The list table, built on `load-{hook}`.
	 *
	 * @var Templates_List_Table|null
	 */
	private $list_table = null;

	/**
	 * Register the admin hooks.
	 *
	 * @since 6.7.3
	 */
	public function __construct() {
		// Priority 11: the Essential Addons top level menu is registered at 10.
		add_action( 'admin_menu', [ $this, 'register_menu' ], 11 );
		add_filter( 'set-screen-option', [ $this, 'save_screen_option' ], 10, 3 );
		add_filter( 'set_screen_option_eael_tb_templates_per_page', [ $this, 'save_screen_option_value' ], 10, 3 );
	}

	/**
	 * Add the Theme Builder submenu.
	 *
	 * @since 6.7.3
	 */
	public function register_menu() {
		global $submenu;

		$capability = Theme_Builder::capability();

		// A top level menu with children needs an explicit self-referencing child,
		// otherwise the dashboard itself becomes unreachable from the submenu.
		// if ( empty( $submenu[ self::PARENT_SLUG ] ) ) {
		// 	add_submenu_page(
		// 		self::PARENT_SLUG,
		// 		__( 'Essential Addons', 'essential-addons-for-elementor-lite' ),
		// 		__( 'Dashboard', 'essential-addons-for-elementor-lite' ),
		// 		$capability,
		// 		self::PARENT_SLUG
		// 	);
		// }

		$this->hook_suffix = add_submenu_page(
			self::PARENT_SLUG,
			__( 'Theme Builder', 'essential-addons-for-elementor-lite' ),
			__( 'Theme Builder', 'essential-addons-for-elementor-lite' ),
			$capability,
			Theme_Builder::page_slug(),
			[ $this, 'render_page' ]
		);

		if ( $this->hook_suffix ) {
			add_action( 'load-' . $this->hook_suffix, [ $this, 'on_load' ] );
			add_action( 'admin_print_styles-' . $this->hook_suffix, [ $this, 'enqueue_assets' ] );
		}
	}

	/**
	 * Prepare the screen: handle actions, register options, build the table.
	 *
	 * @since 6.7.3
	 */
	public function on_load() {
		if ( ! current_user_can( Theme_Builder::capability() ) ) {
			return;
		}

		$this->handle_row_action();
		$this->handle_bulk_action();

		add_screen_option(
			'per_page',
			[
				'label'   => __( 'Templates per page', 'essential-addons-for-elementor-lite' ),
				'default' => 20,
				'option'  => 'eael_tb_templates_per_page',
			]
		);

		$this->list_table = new Templates_List_Table();
		$this->list_table->prepare_items();
	}

	/**
	 * Persist the per-page screen option.
	 *
	 * @since 6.7.3
	 *
	 * @param mixed  $status Screen option value, false by default.
	 * @param string $option Option name.
	 * @param mixed  $value  Submitted value.
	 *
	 * @return mixed
	 */
	public function save_screen_option( $status, $option, $value ) {
		return 'eael_tb_templates_per_page' === $option ? absint( $value ) : $status;
	}

	/**
	 * Persist the per-page screen option (WP 5.4+ named filter).
	 *
	 * @since 6.7.3
	 *
	 * @param mixed  $screen_option Screen option value.
	 * @param string $option        Option name.
	 * @param mixed  $value         Submitted value.
	 *
	 * @return int
	 */
	public function save_screen_option_value( $screen_option, $option, $value ) {
		return absint( $value );
	}

	/**
	 * Handle a single-template row action.
	 *
	 * @since 6.7.3
	 */
	private function handle_row_action() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- nonce checked below against the resolved action.
		$action = isset( $_REQUEST['action'] ) ? sanitize_key( wp_unslash( $_REQUEST['action'] ) ) : '';

		if ( 0 !== strpos( $action, 'eael_tb_' ) ) {
			return;
		}

		$action      = substr( $action, strlen( 'eael_tb_' ) );
		$template_id = isset( $_REQUEST['template_id'] ) ? absint( $_REQUEST['template_id'] ) : 0;

		if ( ! $template_id || ! in_array( $action, [ 'trash', 'untrash', 'delete', 'activate', 'deactivate', 'duplicate' ], true ) ) {
			return;
		}

		check_admin_referer( 'eael_tb_row_' . $action . '_' . $template_id );

		$processed = $this->apply_action( $action, [ $template_id ] );

		$this->redirect_after_action( $action, $processed );
	}

	/**
	 * Handle a bulk action submitted from the table.
	 *
	 * @since 6.7.3
	 */
	private function handle_bulk_action() {
		// phpcs:disable WordPress.Security.NonceVerification.Recommended -- nonce checked below.
		$action = '';

		if ( isset( $_REQUEST['action'] ) && '-1' !== $_REQUEST['action'] ) {
			$action = sanitize_key( wp_unslash( $_REQUEST['action'] ) );
		}

		if ( ( '' === $action ) && isset( $_REQUEST['action2'] ) && '-1' !== $_REQUEST['action2'] ) {
			$action = sanitize_key( wp_unslash( $_REQUEST['action2'] ) );
		}
		// phpcs:enable WordPress.Security.NonceVerification.Recommended

		if ( ! in_array( $action, [ 'trash', 'untrash', 'delete', 'activate', 'deactivate' ], true ) ) {
			return;
		}

		check_admin_referer( 'bulk-' . Templates_List_Table::NONCE_ACTION );

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- nonce verified above.
		$ids = isset( $_REQUEST['template_ids'] ) ? array_map( 'absint', (array) wp_unslash( $_REQUEST['template_ids'] ) ) : [];
		$ids = array_filter( $ids );

		if ( empty( $ids ) ) {
			return;
		}

		$processed = $this->apply_action( $action, $ids );

		$this->redirect_after_action( $action, $processed );
	}

	/**
	 * Apply an action to a set of templates.
	 *
	 * @since 6.7.3
	 *
	 * @param string $action Action slug.
	 * @param array  $ids    Template IDs.
	 *
	 * @return int Number of templates the action was applied to.
	 */
	private function apply_action( $action, $ids ) {
		$processed = 0;

		foreach ( $ids as $id ) {
			if ( Post_Type::CPT !== get_post_type( $id ) ) {
				continue;
			}

			switch ( $action ) {
				case 'trash':
					if ( current_user_can( 'delete_post', $id ) && wp_trash_post( $id ) ) {
						$processed++;
					}
					break;

				case 'untrash':
					if ( current_user_can( 'delete_post', $id ) && wp_untrash_post( $id ) ) {
						$processed++;
					}
					break;

				case 'delete':
					if ( current_user_can( 'delete_post', $id ) && wp_delete_post( $id, true ) ) {
						$processed++;
					}
					break;

				case 'activate':
				case 'deactivate':
					if ( current_user_can( 'edit_post', $id ) ) {
						update_post_meta( $id, Post_Type::META_ACTIVE, 'activate' === $action ? 'yes' : 'no' );
						$processed++;
					}
					break;

				case 'duplicate':
					$template = Template::get( $id );

					if ( $template && current_user_can( 'edit_post', $id ) && ! is_wp_error( $template->duplicate() ) ) {
						$processed++;
					}
					break;
			}
		}

		if ( $processed ) {
			Template_Cache::flush();
		}

		return $processed;
	}

	/**
	 * Redirect back to the dashboard with a result message.
	 *
	 * @since 6.7.3
	 *
	 * @param string $action    Action slug.
	 * @param int    $processed Number of affected templates.
	 */
	private function redirect_after_action( $action, $processed ) {
		$args = [
			'eael_tb_action' => $action,
			'eael_tb_count'  => (int) $processed,
		];

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- preserving the active view only.
		if ( ! empty( $_REQUEST['template_type'] ) ) {
			$args['template_type'] = sanitize_key( wp_unslash( $_REQUEST['template_type'] ) );
		}

		wp_safe_redirect( Theme_Builder::page_url( $args ) );
		exit;
	}

	/**
	 * Notice describing the outcome of the last action.
	 *
	 * @since 6.7.3
	 *
	 * @return string
	 */
	public function get_action_notice() {
		// phpcs:disable WordPress.Security.NonceVerification.Recommended -- display-only message.
		if ( empty( $_GET['eael_tb_action'] ) ) {
			return '';
		}

		$action = sanitize_key( wp_unslash( $_GET['eael_tb_action'] ) );
		$count  = isset( $_GET['eael_tb_count'] ) ? absint( $_GET['eael_tb_count'] ) : 0;
		// phpcs:enable WordPress.Security.NonceVerification.Recommended

		if ( ! $count ) {
			return __( 'No templates were changed.', 'essential-addons-for-elementor-lite' );
		}

		switch ( $action ) {
			case 'trash':
				/* translators: %s: number of templates. */
				return sprintf( _n( '%s template moved to the Trash.', '%s templates moved to the Trash.', $count, 'essential-addons-for-elementor-lite' ), number_format_i18n( $count ) );

			case 'untrash':
				/* translators: %s: number of templates. */
				return sprintf( _n( '%s template restored from the Trash.', '%s templates restored from the Trash.', $count, 'essential-addons-for-elementor-lite' ), number_format_i18n( $count ) );

			case 'delete':
				/* translators: %s: number of templates. */
				return sprintf( _n( '%s template permanently deleted.', '%s templates permanently deleted.', $count, 'essential-addons-for-elementor-lite' ), number_format_i18n( $count ) );

			case 'activate':
				/* translators: %s: number of templates. */
				return sprintf( _n( '%s template activated.', '%s templates activated.', $count, 'essential-addons-for-elementor-lite' ), number_format_i18n( $count ) );

			case 'deactivate':
				/* translators: %s: number of templates. */
				return sprintf( _n( '%s template deactivated.', '%s templates deactivated.', $count, 'essential-addons-for-elementor-lite' ), number_format_i18n( $count ) );

			case 'duplicate':
				/* translators: %s: number of templates. */
				return sprintf( _n( '%s template duplicated as a draft.', '%s templates duplicated as drafts.', $count, 'essential-addons-for-elementor-lite' ), number_format_i18n( $count ) );
		}

		return '';
	}

	/**
	 * Notice listing templates deactivated because their target was deleted.
	 *
	 * @since 6.7.3
	 *
	 * @return string
	 */
	public function get_orphaned_notice() {
		$ids = Conditions_Cleanup::pull_deactivated();

		if ( empty( $ids ) ) {
			return '';
		}

		$titles = [];

		foreach ( $ids as $id ) {
			$title = get_the_title( $id );

			if ( $title ) {
				$titles[] = $title;
			}
		}

		if ( empty( $titles ) ) {
			return '';
		}

		return sprintf(
			/* translators: %s: comma separated template titles. */
			_n(
				'%s was deactivated: the page it was set to display on has been deleted, so it had no display conditions left. Add a condition to switch it back on.',
				'%s were deactivated: the pages they were set to display on have been deleted, so they had no display conditions left. Add a condition to switch them back on.',
				count( $titles ),
				'essential-addons-for-elementor-lite'
			),
			implode( ', ', $titles )
		);
	}

	/**
	 * Enqueue the dashboard assets.
	 *
	 * @since 6.7.3
	 */
	public function enqueue_assets() {
		wp_enqueue_style(
			'eael-theme-builder',
			EAEL_PLUGIN_URL . 'assets/admin/css/theme-builder.css',
			[],
			self::asset_version( 'assets/admin/css/theme-builder.css' )
		);

		// List-table behaviour that has to live next to WordPress's own markup:
		// Quick Edit and the delete confirmation.
		wp_enqueue_script(
			'eael-theme-builder',
			EAEL_PLUGIN_URL . 'assets/admin/js/theme-builder.js',
			[ 'jquery' ],
			self::asset_version( 'assets/admin/js/theme-builder.js' ),
			true
		);

		wp_localize_script( 'eael-theme-builder', 'eaelThemeBuilder', self::get_script_data() );

		// The React app owns the modals. It depends on the handle above purely
		// for ordering: both read the `eaelThemeBuilder` global printed with it.
		self::enqueue_app( [ 'eael-theme-builder' ] );
	}

	/**
	 * Enqueue the React modal bundle.
	 *
	 * Shared with the Elementor editor, which mounts the same app to gate
	 * publishing behind the condition builder — see `Integrations\Editor`.
	 *
	 * @since 6.7.3
	 *
	 * @param array      $deps Script dependencies.
	 * @param array|null $data Data to localize as `eaelThemeBuilder`, or null when
	 *                         a dependency already printed the global.
	 */
	public static function enqueue_app( $deps = [], $data = null ) {
		wp_enqueue_style(
			'eael-theme-builder-app',
			EAEL_PLUGIN_URL . 'includes/templates/admin/theme-builder/dist/theme-builder.min.css',
			[],
			self::asset_version( 'includes/templates/admin/theme-builder/dist/theme-builder.min.css' )
		);

		wp_enqueue_script(
			'eael-theme-builder-app',
			EAEL_PLUGIN_URL . 'includes/templates/admin/theme-builder/dist/theme-builder.min.js',
			$deps,
			self::asset_version( 'includes/templates/admin/theme-builder/dist/theme-builder.min.js' ),
			true
		);

		if ( null !== $data ) {
			wp_localize_script( 'eael-theme-builder-app', 'eaelThemeBuilder', $data );
		}
	}

	/**
	 * Cache-busting version for an asset.
	 *
	 * Debug builds use the file's modification time so a Vite rebuild
	 * (`npm run dev` in includes/templates/admin/theme-builder/) is picked up on
	 * a normal refresh — with the plugin version alone the browser keeps serving
	 * the cached bundle and edits look like they did nothing.
	 *
	 * Production keeps the plugin version: `filemtime()` can differ between
	 * servers behind a load balancer, which would break shared caching.
	 *
	 * @since 6.7.3
	 *
	 * @param string $relative_path Path relative to the plugin root.
	 *
	 * @return string
	 */
	public static function asset_version( $relative_path ) {
		$debug = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) || ( defined( 'WP_DEBUG' ) && WP_DEBUG );

		// Local/staging installs get it without having to turn WP_DEBUG on — that
		// is the case where someone is running the Vite watcher.
		if ( ! $debug && function_exists( 'wp_get_environment_type' ) ) {
			$debug = in_array( wp_get_environment_type(), [ 'local', 'development' ], true );
		}

		/**
		 * Filters whether Theme Builder assets are versioned by file modification
		 * time rather than by the plugin version.
		 *
		 * @since 6.7.3
		 *
		 * @param bool   $debug         Whether to use filemtime().
		 * @param string $relative_path Asset path relative to the plugin root.
		 */
		$debug = (bool) apply_filters( 'eael/theme_builder/version_assets_by_mtime', $debug, $relative_path );

		if ( $debug ) {
			$file = EAEL_PLUGIN_PATH . $relative_path;

			if ( file_exists( $file ) ) {
				return (string) filemtime( $file );
			}
		}

		return EAEL_PLUGIN_VERSION;
	}

	/**
	 * Data handed to the dashboard script.
	 *
	 * @since 6.7.3
	 *
	 * @param array $extra Context added on top — the editor passes the template
	 *                     it is editing under `editor`.
	 *
	 * @return array
	 */
	public static function get_script_data( $extra = [] ) {
		$types = [];

		foreach ( Template_Types::instance()->get_types() as $slug => $type ) {
			$types[] = [
				'slug'  => $slug,
				'label' => $type['label'],
			];
		}

		$data = [
			'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
			'nonce'         => wp_create_nonce( Ajax::NONCE_ACTION ),
			'types'         => $types,
			'priority'      => [
				'min'     => Post_Type::PRIORITY_MIN,
				'max'     => Post_Type::PRIORITY_MAX,
				'default' => Post_Type::PRIORITY_DEFAULT,
			],
			'rules'         => array_values( Rules::get_rules_for_ui() ),
			'groups'        => Rules::get_groups(),
			'pageTemplates' => Post_Type::get_page_template_options(),
			'months'        => self::get_month_options(),
			'i18n'          => [
				'templatesTitle'   => __( 'Templates', 'essential-addons-for-elementor-lite' ),
				'groupLabel'       => __( 'Condition group', 'essential-addons-for-elementor-lite' ),
				'ruleLabel'        => __( 'Condition', 'essential-addons-for-elementor-lite' ),
				'chooseType'       => __( 'Please choose a valid template type.', 'essential-addons-for-elementor-lite' ),
				'createTitle'      => __( 'Choose the type of template you want to work on', 'essential-addons-for-elementor-lite' ),
				'typeLabel'        => __( 'Template Type', 'essential-addons-for-elementor-lite' ),
				'typePlaceholder'  => __( 'Select', 'essential-addons-for-elementor-lite' ),
				'nameLabel'        => __( 'Template Name', 'essential-addons-for-elementor-lite' ),
				'namePlaceholder'  => __( 'Name your template', 'essential-addons-for-elementor-lite' ),
				'createButton'     => __( 'Create Template', 'essential-addons-for-elementor-lite' ),
				'conditionsTitle'  => __( 'Where Do You Want to Display This Template', 'essential-addons-for-elementor-lite' ),
				'conditionsIntro'  => __( 'Set the conditions that determine where your Template is used throughout your site. For example, choose \'Entire Site\' to display the template across your site.', 'essential-addons-for-elementor-lite' ),
				'include'          => __( 'Include', 'essential-addons-for-elementor-lite' ),
				'exclude'          => __( 'Exclude', 'essential-addons-for-elementor-lite' ),
				'addCondition'     => __( 'ADD NEW CONDITION', 'essential-addons-for-elementor-lite' ),
				'removeCondition'  => __( 'Remove condition', 'essential-addons-for-elementor-lite' ),
				'saveConditions'   => __( 'Save & Edit Template', 'essential-addons-for-elementor-lite' ),
				'saveOnly'         => __( 'Save Conditions', 'essential-addons-for-elementor-lite' ),
				'continueLabel'    => __( 'Continue', 'essential-addons-for-elementor-lite' ),
				'cancel'           => __( 'Cancel', 'essential-addons-for-elementor-lite' ),
				'close'            => __( 'Close', 'essential-addons-for-elementor-lite' ),
				'all'              => __( 'All', 'essential-addons-for-elementor-lite' ),
				'searchPlaceholder'=> __( 'Search…', 'essential-addons-for-elementor-lite' ),
				'noResults'        => __( 'No results found', 'essential-addons-for-elementor-lite' ),
				'clearSelection'   => __( 'Clear selection', 'essential-addons-for-elementor-lite' ),
				'loading'          => __( 'Loading…', 'essential-addons-for-elementor-lite' ),
				'saving'           => __( 'Saving…', 'essential-addons-for-elementor-lite' ),
				'creating'         => __( 'Creating…', 'essential-addons-for-elementor-lite' ),
				'genericError'     => __( 'Something went wrong. Please try again.', 'essential-addons-for-elementor-lite' ),
				'confirmDelete'    => __( 'This template will be permanently deleted. Continue?', 'essential-addons-for-elementor-lite' ),
				'quickEdit'        => __( 'Quick Edit', 'essential-addons-for-elementor-lite' ),
				'bulkEdit'         => __( 'Bulk Edit', 'essential-addons-for-elementor-lite' ),
				// A literal em dash, not `&mdash;` — this one is escaped before it
				// reaches the DOM (it is an <option> label and a placeholder), so an
				// entity would be printed as its own source text.
				'noChange'         => __( '— No change —', 'essential-addons-for-elementor-lite' ),
				'removeFromBulk'   => __( 'Remove from bulk edit', 'essential-addons-for-elementor-lite' ),
				'bulkNoSelection'  => __( 'Select at least one template to edit.', 'essential-addons-for-elementor-lite' ),
				'bulkNoChange'     => __( 'Choose at least one value to change.', 'essential-addons-for-elementor-lite' ),
				/* translators: %s: number of templates that could not be updated. */
				'bulkSkipped'      => __( '%s template(s) could not be updated.', 'essential-addons-for-elementor-lite' ),
				'update'           => __( 'Update', 'essential-addons-for-elementor-lite' ),
				'titleLabel'       => __( 'Title', 'essential-addons-for-elementor-lite' ),
				'slugLabel'        => __( 'Slug', 'essential-addons-for-elementor-lite' ),
				'dateLabel'        => __( 'Date', 'essential-addons-for-elementor-lite' ),
				'monthLabel'       => __( 'Month', 'essential-addons-for-elementor-lite' ),
				'dayLabel'         => __( 'Day', 'essential-addons-for-elementor-lite' ),
				'yearLabel'        => __( 'Year', 'essential-addons-for-elementor-lite' ),
				'hourLabel'        => __( 'Hour', 'essential-addons-for-elementor-lite' ),
				'minuteLabel'      => __( 'Minute', 'essential-addons-for-elementor-lite' ),
				'atLabel'          => __( 'at', 'essential-addons-for-elementor-lite' ),
				'passwordLabel'    => __( 'Password', 'essential-addons-for-elementor-lite' ),
				'orLabel'          => __( '&ndash;OR&ndash;', 'essential-addons-for-elementor-lite' ),
				'privateLabel'     => __( 'Private', 'essential-addons-for-elementor-lite' ),
				'templateLabel'    => __( 'Template', 'essential-addons-for-elementor-lite' ),
				'statusLabel'      => __( 'Status', 'essential-addons-for-elementor-lite' ),
				'statusPublish'    => __( 'Published', 'essential-addons-for-elementor-lite' ),
				'statusDraft'      => __( 'Draft', 'essential-addons-for-elementor-lite' ),
				'statusPending'    => __( 'Pending Review', 'essential-addons-for-elementor-lite' ),
				'priorityLabel'    => __( 'Priority', 'essential-addons-for-elementor-lite' ),
				'priorityHelp'     => __( 'Lower wins when two templates match a page equally well.', 'essential-addons-for-elementor-lite' ),
				'priorityRange'    => Ajax::priority_range_message(),
				'activeLabel'      => __( 'Active', 'essential-addons-for-elementor-lite' ),
				'inactiveLabel'    => __( 'Inactive', 'essential-addons-for-elementor-lite' ),
				'activeHelp'       => __( 'Inactive templates are never displayed, even when published.', 'essential-addons-for-elementor-lite' ),
				/* translators: %s: comma separated template titles. */
				'conflictWarning'  => __( 'Heads up: some of these pages are already targeted by %s. The most specific template wins, then the lowest priority number, then the most recently created template.', 'essential-addons-for-elementor-lite' ),
				'publishTitle'     => __( 'Where Do You Want to Display This Template', 'essential-addons-for-elementor-lite' ),
				'publishIntro'     => __( 'This is the last step: choose where the template appears, and it goes live as soon as you publish.', 'essential-addons-for-elementor-lite' ),
				'saveAndPublish'   => __( 'Save & Publish', 'essential-addons-for-elementor-lite' ),
				'publishing'       => __( 'Publishing…', 'essential-addons-for-elementor-lite' ),
				'publishCancelled' => __( 'Publishing cancelled. The template is still a draft.', 'essential-addons-for-elementor-lite' ),
			],
		];

		return array_merge( $data, $extra );
	}

	/**
	 * Month options for the Quick Edit date picker, in core's `08-Aug` format.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	private static function get_month_options() {
		global $wp_locale;

		$months = [];

		for ( $i = 1; $i <= 12; $i++ ) {
			$number = zeroise( $i, 2 );

			$months[] = [
				'value' => $number,
				/* translators: 1: month number (01, 02, …), 2: month abbreviation. */
				'label' => sprintf( __( '%1$s-%2$s', 'essential-addons-for-elementor-lite' ), $number, $wp_locale->get_month_abbrev( $wp_locale->get_month( $i ) ) ),
			];
		}

		return $months;
	}

	/**
	 * Render the Theme Builder dashboard.
	 *
	 * @since 6.7.3
	 */
	public function render_page() {
		if ( ! current_user_can( Theme_Builder::capability() ) ) {
			wp_die( esc_html__( 'You do not have permission to manage Theme Builder templates.', 'essential-addons-for-elementor-lite' ) );
		}

		$list_table = $this->list_table;
		$notice     = $this->get_action_notice();
		$warning    = $this->get_orphaned_notice();

		include Theme_Builder::path() . 'Templates/admin/dashboard.php';
	}
}
