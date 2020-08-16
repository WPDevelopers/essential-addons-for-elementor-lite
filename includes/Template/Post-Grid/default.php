<?php
/**
 * Template Name: Default
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if ( $settings['eael_post_grid_preset_style'] === 'two' ) {
    echo '<article class="eael-grid-post eael-post-grid-column" data-id="' . get_the_ID() . '">
        <div class="eael-grid-post-holder">
            <div class="eael-grid-post-holder-inner">';
            // $this->render_template__thumbnail( $settings, 'two' );

            if ( $settings['eael_show_title'] || $settings['eael_show_meta'] || $settings['eael_show_excerpt'] ) {
                echo '<div class="eael-entry-wrapper">';
                $this->render_template__header( $settings, 'two' );
                $this->render_template__excerpt( $settings );
                if ( $settings['meta_position'] == 'meta-entry-footer' ) {
                    $this->render_template__meta_style_two( $settings );
                }
                echo '</div>';
            }
            echo '</div>
        </div>
    </article>';
} else if ( $settings['eael_post_grid_preset_style'] === 'three' ) {
    echo '<article class="eael-grid-post eael-post-grid-column" data-id="' . get_the_ID() . '">
        <div class="eael-grid-post-holder">
            <div class="eael-grid-post-holder-inner">';
            // $this->render_template__thumbnail( $settings, 'three' );

            if ( $settings['eael_show_title'] || $settings['eael_show_meta'] || $settings['eael_show_excerpt'] ) {
                echo '<div class="eael-entry-wrapper">';
                $this->render_template__header( $settings, 'three' );
                $this->render_template__excerpt( $settings );
                echo '</div>';
            }
            echo '</div>
        </div>
    </article>';
} else {
    echo '<article class="eael-grid-post eael-post-grid-column" data-id="' . get_the_ID() . '">
        <div class="eael-grid-post-holder">
            <div class="eael-grid-post-holder-inner">';
            // $this->render_template__thumbnail( $settings );

            if ( $settings['eael_show_title'] || $settings['eael_show_meta'] || $settings['eael_show_excerpt'] ) {
                echo '<div class="eael-entry-wrapper">';
                $this->render_template__header( $settings );
                $this->render_template__excerpt( $settings );
                $this->render_template__footer_meta( $settings );
                echo '</div>';
            }
            echo '</div>
        </div>
    </article>';
}

