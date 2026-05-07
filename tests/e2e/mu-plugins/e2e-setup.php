<?php
/**
 * MU-Plugin: Suppress Elementor onboarding and analytics during E2E tests.
 * External HTTP requests are blocked via WP_HTTP_BLOCK_EXTERNAL in .wp-env.json.
 */

// Mark Elementor onboarding as complete.
add_action( 'init', function () {
	if ( get_option( 'elementor_onboarded' ) !== '1' ) {
		update_option( 'elementor_onboarded', 1 );
	}
} );

// Disable Elementor usage tracking.
add_filter( 'elementor/tracker/send_override', '__return_false' );
