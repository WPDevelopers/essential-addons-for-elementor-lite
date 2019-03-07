<div id="extensions" class="eael-settings-tab eael-extensions-list">
    <div class="row">
        <div class="col-full">
            <!-- Content Element Starts -->
            <!-- <h3>Available Extensions</h3>
            <div class="eael-checkbox-container">

            </div> -->
            <h3>Premium Extensions</h3>
            <div class="eael-checkbox-container">

                <div class="eael-checkbox">
                    <input type="checkbox" id="section-parallax" name="section-parallax" disabled>
                    <label for="section-parallax" class="<?php if( (bool) $this->is_pro === false ) : echo 'eael-get-pro'; endif; ?>"></label>
                    <p class="eael-el-title"><?php _e( 'Section Parallax Scrolling', 'essential-addons-elementor' ) ?></p>
                </div>

                <div class="eael-checkbox">
                    <input type="checkbox" id="section-particles" name="section-particles" disabled>
                    <label for="section-particles" class="<?php if( (bool) $this->is_pro === false ) : echo 'eael-get-pro'; endif; ?>"></label>
                    <p class="eael-el-title"><?php _e( 'Section Particles', 'essential-addons-elementor' ) ?></p>
                </div>

            </div>
            <!-- Content Element Ends -->

            <div class="eael-save-btn-wrap">
                <button type="submit" class="button eael-btn js-eael-settings-save"><?php _e('Save settings', 'essential-addons-elementor'); ?></button>
            </div>
        </div>
    </div>
</div>