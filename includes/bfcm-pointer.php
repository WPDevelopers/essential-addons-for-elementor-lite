<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

add_action(
	'in_admin_header',
	function () {

		if ( \Essential_Addons_Elementor\Classes\ThinkRank_Promotion::promotions_disabled() || $this->pro_enabled || time() < strtotime( '12:00:00am 14th November, 2026' ) || time() > strtotime( '11:59:59pm 14th December, 2026' ) || ( $GLOBALS["pagenow"] !== 'index.php' && get_current_screen()->id !== 'toplevel_page_eael-settings' ) || get_transient( 'eael_bfcm26_pointer_dismiss' ) ) {
			return;
		}

		wp_enqueue_script( 'jquery' );
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_script( 'wp-pointer' );

		$pointer_pririty = get_option( '_wpdeveloper_plugin_pointer_priority' );
		if ( empty( $pointer_pririty ) || $pointer_pririty > 1 ) {
			update_option( '_wpdeveloper_plugin_pointer_priority', 1 );
			$pointer_pririty = 1;
		}

		if ( absint( $pointer_pririty ) === 1 ) {
			?>
			<script>
                jQuery(
                    function () {
                        var item = jQuery('#toplevel_page_eael-settings').pointer(
                            {
                                content:
                                    "<h3 style='font-weight: 600;'>Black Friday Is On!</h3>" +
                                    "<p style='margin: 1em 0;'>Up to 40% off Essential Addons Pro. Unlock 120+ elements and 3,000+ templates for your Elementor site.</p>" +
                                    "<p><a class='button button-primary' href='https://essential-addons.com/bfcm2026/admin-notice' target='_blank'>View Deals</a></p>",

                                position:
                                    {
                                        edge: 'left',
                                        align: 'center'
                                    },

                                pointerClass:
                                    'wp-pointer',

                                close: function () {
                                    jQuery(window).off('.eaelBfcmPointer');
                                    jQuery.post(
                                        ajaxurl,
                                        {
                                            pointer: 'eael',
                                            action: 'dismiss-wp-pointer',
                                        }
                                    );
                                },

                            }
                        );
                        item.pointer('open');

                        // Keep the pointer on the menu item: the admin menu is fixed (or
                        // absolute when it is taller than the window), so give the pointer
                        // the same position and re-place it on scroll and resize.
                        var widget  = item.pointer('widget'),
                            menu    = jQuery('#adminmenuwrap'),
                            pending = false,
                            stick   = function () {
                                pending = false;
                                if (!widget.is(':visible')) {
                                    return;
                                }
                                widget.css('position', 'fixed' === menu.css('position') ? 'fixed' : 'absolute');
                                item.pointer('reposition');
                            };

                        jQuery(window).on('scroll.eaelBfcmPointer resize.eaelBfcmPointer', function () {
                            if (!pending) {
                                pending = true;
                                window.requestAnimationFrame(stick);
                            }
                        });
                        stick();
                    }
                );
			</script>
			<?php
		}
	}
);

add_action(
	'admin_init',
	function () {
        // phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( isset( $_POST['action'] ) && 'dismiss-wp-pointer' == $_POST['action'] && isset( $_POST['pointer'] ) && 'eael' == $_POST['pointer'] ) {
			set_transient( 'eael_bfcm26_pointer_dismiss', true, DAY_IN_SECONDS * 30 );
			delete_option( '_wpdeveloper_plugin_pointer_priority' );
		}
	}
);