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
                        <h4 class="eael-admin-title"><?php _e('Assets Embed Method', 'essential-addons-for-elementor-lite');?></h4>
                    </header>
                    <div class="eael-admin-block-content">
                        <p><?php _e('Configure the Essential Addons assets embed method. Keep it as default (recommended).', 'essential-addons-for-elementor-lite');?></p>


                        <div class="eael-admin-inside-select-block">
                            <div class="eael-admin-select-block-label">
                                <h4><?php _e('CSS Print Method', 'essential-addons-for-elementor-lite');?></h4>
                            </div>

                            <div class="eael-admin-select-block-select">
                                <a href="<?php echo esc_url(admin_url('admin.php?page=elementor#tab-advanced')); ?>" target="_blank" id="eael-css-print-method" class="button eael-btn"><?php _e('Configure Settings', 'essential-addons-for-elementor-lite');?></a>
                                
                                <p class="quick-tools-description"><?php _e('CSS Print Method is handled by Elementor Settings itself. Use External CSS Files for better performance (recommended).', 'essential-addons-for-elementor-lite');?></p>
                            </div>
                        </div>

                        <div class="eael-admin-inside-select-block">
                            <div class="eael-admin-select-block-label">
                                <h4><?php _e('JS Print Method', 'essential-addons-for-elementor-lite');?></h4>
                            </div>

                            <?php
                                $print_method = get_option('eael_js_print_method','external');
                            ?>
                            <div class="eael-admin-select-block-select">
                                <select name="eael-js-print-method" id="eael-js-print-method">
                                    <option value="external" <?php echo $print_method == 'external' ? 'selected' : '' ?>><?php _e('External File', 'essential-addons-for-elementor-lite');?></option>
                                    <option value="internal" <?php echo $print_method == 'internal' ? 'selected' : '' ?>><?php _e('Internal Embedding', 'essential-addons-for-elementor-lite');?></option>
                                </select>
                                <p style="display: <?php echo ($print_method==='external')?'block':'none'; ?>" class="quick-tools-description eael-external-printjs"><?php _e('Use external JS files for all generated scripts. Choose this setting for better performance (recommended).', 'essential-addons-for-elementor-lite');?></p>
                                <p style="display: <?php echo ($print_method==='internal')?'block':'none'; ?>" class="quick-tools-description eael-internal-printjs"><?php _e('Use internal JS that is embedded in the head of the page. For troubleshooting server configuration conflicts and managing development environments.', 'essential-addons-for-elementor-lite');?></p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div><!-- /.col-half -->

    </div>
</div>