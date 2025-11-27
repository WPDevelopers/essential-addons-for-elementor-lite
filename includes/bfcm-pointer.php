<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

add_action(
	'in_admin_header',
	function () {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_script( 'wp-pointer' );

		$pointer_pririty = get_option( '_wpdeveloper_plugin_pointer_priority' );
		if ( empty( $pointer_pririty ) || $pointer_pririty > 1 ) {
			update_option( '_wpdeveloper_plugin_pointer_priority', 1 );
			$pointer_pririty = 1;
		}

		if ( absint( $pointer_pririty ) === 1 && ! get_transient( 'eael_bfcm25_pointer_dismiss' ) ) {
			?>
			<script>
                jQuery(
                    function () {
                        jQuery('#toplevel_page_eael-settings').pointer(
                            {
                                content:
                                    "<h3 style='font-weight: 600;'>Essential Addons: Black Friday Sale<\/h3>" +
                                    "<p style='margin: 1em 0;'>Unlock the full power of Elementor with 100+ advanced elements. Build faster, design smarter.</p>" +
                                    "<p><a class='button button-primary' href='https://essential-addons.com/bfcm-wp-admin-pointer' target='_blank'>Save $120</a></p>",


                                position:
                                    {
                                        edge: 'left',
                                        align: 'center'
                                    },

                                pointerClass:
                                    'wp-pointer arrow-top',

                                pointerWidth: 420,

                                close: function () {
                                    jQuery.post(
                                        ajaxurl,
                                        {
                                            pointer: 'eael',
                                            action: 'dismiss-wp-pointer',
                                        }
                                    );
                                },

                            }
                        ).pointer('open');
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
		if ( isset( $_POST['action'] ) && 'dismiss-wp-pointer' == $_POST['action'] && isset( $_POST['pointer'] ) && 'eael' == $_POST['pointer'] ) {
			set_transient( 'eael_bfcm25_pointer_dismiss', true, DAY_IN_SECONDS * 30 );
		}
	}
);