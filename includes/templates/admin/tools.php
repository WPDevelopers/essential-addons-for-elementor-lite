<div id="tools" class="eael-settings-tab">
    <div class="row go-premium">

        <div class="col-half">
            <div class="eael-admin-block-wrapper">
                <div class="eael-admin-block eael-admin-block-docs">
                    <header class="eael-admin-block-header">
                        <div class="eael-admin-block-header-icon">
                            <img src="<?php echo EAEL_PLUGIN_URL . 'assets/admin/images/icon-clean-cache.svg'; ?>" alt="essential-addons-tools-clean-cache">
                        </div>
                        <h4 class="eael-admin-title"><?php _e('Regenerate Assets', 'essential-addons-for-elementor-lite');?></h4>
                    </header>
                    <div class="eael-admin-block-content">
                        <p><?php _e('Essential Addons styles & scripts are saved in Uploads folder. This option will clear all those generated files.', 'essential-addons-for-elementor-lite');?></p>
                        <a href="#" id="eael-regenerate-files" class="button eael-btn" target="_blank"><?php _e('Regenerate Assets', 'essential-addons-for-elementor-lite');?></a>

                        <div class="eael-checkbox eael-checkbox-quick-tools">
                            <p class="eael-el-title"><?php _e('Show Quick Tools in Admin Bar?', 'essential-addons-for-elementor-lite');?></p>

                            <input type="checkbox" id="<?php echo esc_attr('quick_tools'); ?>" name="<?php echo esc_attr('quick_tools'); ?>" <?php echo checked(1, $this->get_settings('quick_tools'), false); ?>>
                            <label for="<?php echo esc_attr('quick_tools'); ?>"></label>
                        </div>
                        <p class="quick-tools-description"><?php _e('Display quick assets generating tools in admin bar from where you can regenerate global assets or page assets.', 'essential-addons-for-elementor-lite');?></p>
                    </div>
                </div>
            </div>
        </div><!-- /.col-half -->


        <div class="col-half">
            <div class="eael-admin-block-wrapper">
                <div class="eael-admin-block eael-admin-block-docs">
                    <header class="eael-admin-block-header">
                        <div class="eael-admin-block-header-icon">
                            <img src="<?php echo EAEL_PLUGIN_URL . 'assets/admin/images/icon-clean-cache.svg'; ?>" alt="essential-addons-tools-clean-cache">
                        </div>
                        <h4 class="eael-admin-title"><?php _e('Assets Embed method', 'essential-addons-for-elementor-lite');?></h4>
                    </header>
                    <div class="eael-admin-block-content">
                        <p><?php _e('Essential Addons styles & scripts are saved in Uploads folder. This option will clear all those generated files.', 'essential-addons-for-elementor-lite');?></p>


                        <div class="eael-admin-inside-select-block">
                            <div class="eael-admin-select-block-label">
                                <h4><?php _e('CSS Print Method', 'essential-addons-for-elementor-lite');?></h4>
                            </div>

                            <div class="eael-admin-select-block-select">
                                <a href="<?php echo esc_url(admin_url('admin.php?page=elementor#tab-advanced')); ?>"><?php _e('#Go To', 'essential-addons-for-elementor-lite');?></a>
                            </div>
                        </div>

                        <div class="eael-admin-inside-select-block">
                            <div class="eael-admin-select-block-label">
                                <h4><?php _e('JS Print Method', 'essential-addons-for-elementor-lite');?></h4>
                            </div>
                            <div class="eael-admin-select-block-select">
                                <select name="eael-js-print-method" id="eael-js-print-method-options">
                                    <option value="external-file"><?php _e('External File', 'essential-addons-for-elementor-lite');?></option>
                                    <option value="internal-embedding"><?php _e('Internal Embedding', 'essential-addons-for-elementor-lite');?></option>
                                </select>

                                <p class="quick-tools-description"><?php _e('Use external CSS files for all generated stylesheets. Choose this setting for better performance (recommended).', 'essential-addons-for-elementor-lite');?></p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div><!-- /.col-half -->

    </div>
</div>