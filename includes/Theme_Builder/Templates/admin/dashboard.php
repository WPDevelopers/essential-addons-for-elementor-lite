<?php
/**
 * Theme Builder dashboard screen.
 *
 * @package Essential_Addons_Elementor
 * @since   6.7.3
 *
 * @var \Essential_Addons_Elementor\Theme_Builder\Admin\Templates_List_Table|null $list_table Prepared list table.
 * @var string                                                                            $notice     Result of the last action.
 * @var string                                                                            $warning    Templates deactivated by a condition cleanup.
 */

use Essential_Addons_Elementor\Theme_Builder\Core\Template_Types;
use Essential_Addons_Elementor\Theme_Builder\Theme_Builder;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

$current_type   = $list_table ? $list_table->get_request_type() : '';
$current_status = $list_table ? $list_table->get_request_status() : '';
$elementor_ok   = Theme_Builder::is_enabled();

/*
 * Elementor is missing: point at the one action that fixes it rather than
 * leaving people to work out what "needs Elementor" means from here.
 */
$elementor_file   = 'elementor/elementor.php';
$elementor_action = '';

if ( ! $elementor_ok ) {
	if ( file_exists( WP_PLUGIN_DIR . '/' . $elementor_file ) ) {
		if ( current_user_can( 'activate_plugin', $elementor_file ) ) {
			$elementor_action = sprintf(
				'<a class="button button-primary" href="%1$s">%2$s</a>',
				esc_url(
					wp_nonce_url(
						self_admin_url( 'plugins.php?action=activate&plugin=' . rawurlencode( $elementor_file ) ),
						'activate-plugin_' . $elementor_file
					)
				),
				esc_html__( 'Activate Elementor', 'essential-addons-for-elementor-lite' )
			);
		}
	} elseif ( current_user_can( 'install_plugins' ) ) {
		$elementor_action = sprintf(
			'<a class="button button-primary" href="%1$s">%2$s</a>',
			esc_url( self_admin_url( 'plugin-install.php?tab=search&type=term&s=' . rawurlencode( 'Elementor Website Builder' ) ) ),
			esc_html__( 'Install Elementor', 'essential-addons-for-elementor-lite' )
		);
	}
}

?>
<div class="wrap eael-tb-wrap">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Templates', 'essential-addons-for-elementor-lite' ); ?></h1>

	<?php if ( $elementor_ok ) : ?>
		<button type="button" class="page-title-action eael-tb-add-new">
			<?php esc_html_e( 'Add New Template', 'essential-addons-for-elementor-lite' ); ?>
		</button>
	<?php endif; ?>

	<hr class="wp-header-end">

	<?php if ( ! $elementor_ok ) : ?>
		<div class="notice notice-error eael-tb-requirement">
			<p><?php esc_html_e( 'Theme Builder needs Elementor to be installed and activated. Your templates and their display conditions are kept safe in the meantime — they come back as soon as Elementor is active again.', 'essential-addons-for-elementor-lite' ); ?></p>
			<?php if ( $elementor_action ) : ?>
				<p><?php echo $elementor_action; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from escaped parts above. ?></p>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $notice ) ) : ?>
		<div class="notice notice-success is-dismissible">
			<p><?php echo esc_html( $notice ); ?></p>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $warning ) ) : ?>
		<div class="notice notice-warning is-dismissible">
			<p><?php echo esc_html( $warning ); ?></p>
		</div>
	<?php endif; ?>

	<?php if ( $elementor_ok ) : ?>
	<nav class="nav-tab-wrapper eael-tb-tabs wp-clearfix">
		<a href="<?php echo esc_url( Theme_Builder::page_url() ); ?>"
			class="nav-tab <?php echo '' === $current_type ? 'nav-tab-active' : ''; ?>">
			<?php esc_html_e( 'All', 'essential-addons-for-elementor-lite' ); ?>
		</a>
		<?php foreach ( Template_Types::instance()->get_types() as $slug => $type ) : ?>
			<a href="<?php echo esc_url( Theme_Builder::page_url( [ 'template_type' => $slug ] ) ); ?>"
				class="nav-tab <?php echo $current_type === $slug ? 'nav-tab-active' : ''; ?>">
				<?php echo esc_html( $type['label'] ); ?>
			</a>
		<?php endforeach; ?>
	</nav>
	<?php endif; ?>

	<?php if ( $list_table ) : ?>
		<?php $list_table->views(); ?>

		<form id="eael-tb-templates-filter" method="get">
			<input type="hidden" name="page" value="<?php echo esc_attr( Theme_Builder::page_slug() ); ?>" />
			<?php if ( $current_status ) : ?>
				<input type="hidden" name="post_status" value="<?php echo esc_attr( $current_status ); ?>" />
			<?php endif; ?>

			<?php
			$list_table->search_box( __( 'Search Template', 'essential-addons-for-elementor-lite' ), 'eael-tb-template' );
			$list_table->display();
			?>
		</form>
	<?php endif; ?>

	<?php
	/*
	 * Mount point for the Theme Builder React app — it renders the "Add New
	 * Template" and "Display Conditions" modals, and hooks the buttons above
	 * through delegated listeners. See includes/templates/admin/theme-builder/.
	 */
	?>
	<div id="eael-theme-builder-app"></div>
</div>
