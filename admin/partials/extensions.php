<div id="extensions" class="eael-settings-tab eael-extensions-list">
    <div class="row">
        <div class="col-full">
            <!-- Content Element Starts -->
            <h4>Available Extensions</h4>
            <div class="eael-checkbox-container">

                <div class="eael-checkbox">
                    <input type="checkbox" id="section-particles" name="section-particles" <?php checked( 1, $this->eael_get_settings['section-particles'], true ); ?> >
                    <label for="section-particles"></label>
                    <p class="eael-el-title"><?php _e( 'Particles', 'essential-addons-elementor' ) ?></p>
                </div>

            </div>
            <!-- Content Element Ends -->

            <div class="eael-save-btn-wrap">
                <button type="submit" class="button eael-btn js-eael-settings-save"><?php _e('Save settings', 'essential-addons-elementor'); ?></button>
            </div>
        </div>
    </div>
</div>