<div id="tools" class="eael-grid eael-admin-setting-tab">
    <div class="eael-container eael-block">
        <div class="p30">
            <div class="eael-grid">
                <div class="eael-col-md-5">
                    <div class="eael-tool__card eael-tool__card--flex">
                        <div class="icon">
                            <img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/tool-1.svg' ) ?>" alt="">
                        </div>
                        <div class="content">
                            <h3><?php _e('Regenerate Assets','essential-addons-for-elementor-lite'); ?></h3>
                            <p><?php _e('Essential Addons styles & scripts are saved in Uploads folder. This option will clear all
                                those generated files.','essential-addons-for-elementor-lite'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="eael-col-md-7">
                    <div class="eael-tool__card">
                        <div class="content">
                            <a href="#" id="eael-regenerate-files" class="eael-button button__themeColor mb20"><?php _e('Regenerate Assets','essential-addons-for-elementor-lite'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="line"></div>
        <div class="p30">
            <div class="eael-grid">
                <div class="eael-col-md-5">
                    <div class="eael-tool__card eael-tool__card--flex">
                        <div class="icon">
                            <img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/tool-2.svg' ) ?>" alt="">
                        </div>
                        <div class="content">
                            <h3><?php _e('Assets Embed Method','essential-addons-for-elementor-lite'); ?></h3>
                            <p><?php _e('Configure the Essential Addons assets embed method. Keep it as default (Recommended).','essential-addons-for-elementor-lite'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="eael-col-md-7">
                    <div class="eael-tool__card">
                        <div class="content">
                            <a href="<?php echo esc_url(admin_url('admin.php?page=elementor#tab-advanced')); ?>" target="_blank" class="eael-button button__themeColor mb20"><?php _e('CSS Print Method','essential-addons-for-elementor-lite'); ?></a>
                            <p><?php _e('CSS Print Method is handled by Elementor Settings itself. Use External CSS Files for
                                better
                                performance (Recommended).','essential-addons-for-elementor-lite'); ?></p>
                        </div>
	                    <?php
	                    $print_method = get_option('eael_js_print_method','external');
	                    ?>
                        <div class="content mt30">
                            <div class="eael__flex  align__center mb20">
                                <h5 class="mr20"><?php _e('JS Print Method','essential-addons-for-elementor-lite'); ?></h5>
                                <div class="eael-select">
                                    <select name="eael-js-print-method" id="eael-js-print-method">
                                        <option value="external" <?php echo $print_method == 'external' ? 'selected' : '' ?>><?php _e('External File', 'essential-addons-for-elementor-lite');?></option>
                                        <option value="internal" <?php echo $print_method == 'internal' ? 'selected' : '' ?>><?php _e('Internal Embedding', 'essential-addons-for-elementor-lite');?></option>
                                    </select>
                                </div>
                            </div>
                            <p style="display: <?php echo ($print_method==='external')?'block':'none'; ?>" class="quick-tools-description eael-external-printjs"><?php _e('Use external JS files for all generated scripts. Choose this setting for better performance (Recommended).', 'essential-addons-for-elementor-lite');?></p>
                            <p style="display: <?php echo ($print_method==='internal')?'block':'none'; ?>" class="quick-tools-description eael-internal-printjs"><?php _e('Use internal JS that is embedded in the head of the page. For troubleshooting server configuration conflicts and managing development environments.', 'essential-addons-for-elementor-lite');?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="border__line mt30"><span></span></div>
    <div class="eael__flex justify__end mt30">
        <button class="eael-button button__themeColor js-eael-settings-save"><?php _e('Save Settings', 'essential-addons-for-elementor-lite');?></button>
    </div>
</div>
