<div id="general" class="eael-settings-tab active">
    <div class="row eael-admin-general-wrapper">
        <div class="eael-admin-general-inner">
            <div class="eael-admin-block-wrapper">

                    <?php
                        do_action('add_admin_license_markup');

                        if( !defined('EAEL_PRO_PLUGIN_BASENAME') ) :
                    ?>
                    <div class="eael-admin-block eael-admin-block-banner">
                        <a href="https://essential-addons.com/elementor/" target="_blank">
                            <img class="eael-preview-img" src="<?php echo EAEL_PLUGIN_URL . 'assets/admin/images/eael-featured.png'; ?>">
                        </a>
                    </div><!--preview image end-->
                    <?php endif; ?>

                    <div class="eael-admin-block eael-admin-block-docs">
                        <header class="eael-admin-block-header">
                            <div class="eael-admin-block-header-icon">
                                <img src="<?php echo EAEL_PLUGIN_URL . 'assets/admin/images/icon-documentation.svg'; ?>">
                            </div>
                            <h4 class="eael-admin-title">Documentation</h4>
                        </header>
                        <div class="eael-admin-block-content">
                            <p>Get started by spending some time with the documentation to get familiar with Essential Addons. Build awesome websites for you or your clients with ease.</a></p>
                            <a href="https://essential-addons.com/elementor/docs/" class="ea-button" target="_blank">Documentation</a>
                        </div>
                    </div>
                    <div class="eael-admin-block eael-admin-block-contribution">
                        <header class="eael-admin-block-header">
                            <div class="eael-admin-block-header-icon">
                                <img src="<?php echo EAEL_PLUGIN_URL . 'assets/admin/images/icon-contribute.svg'; ?>" alt="">
                            </div>
                            <h4 class="eael-admin-title">Contribute to Essential Addons</h4>
                        </header>
                        <div class="eael-admin-block-content">
                            <p>You can contribute to make Essential Addons better reporting bugs, creating issues, pull requests at <a href="https://github.com/rupok/essential-addons-for-elementor-lite/" target="_blank">Github.</a></p>
                            <a href="https://github.com/rupok/essential-addons-for-elementor-lite/issues/new" class="ea-button" target="_blank">Report a bug</a>
                        </div>
                    </div>
                    <div class="eael-admin-block eael-admin-block-support">
                        <header class="eael-admin-block-header">
                            <div class="eael-admin-block-header-icon">
                                <img src="<?php echo EAEL_PLUGIN_URL . 'assets/admin/images/icon-need-help.svg'; ?>" alt="">
                            </div>
                            <h4 class="eael-admin-title">Need Help?</h4>
                        </header>
                        <div class="eael-admin-block-content">

                        <?php if( !defined('EAEL_PRO_PLUGIN_BASENAME') ): ?>
                            <p>Stuck with something? Get help from the community on <a href="https://community.wpdeveloper.net/" target="_blank">WPDeveloper Forum</a> or <a href="https://www.facebook.com/groups/essentialaddons/" target="_blank">Facebook Community.</a> In case of emergency, initiate a live chat at <a href="https://essential-addons.com/elementor/" target="_blank">Essential Addons website.</a></p>
                            <a href="https://community.wpdeveloper.net/support-forum/forum/essential-addons-for-elementor/" class="ea-button" target="_blank">Get Community Support</a>
                        <?php
                            else :
                                do_action('eael_premium_support_link');
                            endif;
                        ?>

                        </div>
                    </div>

                    <?php if( !defined('EAEL_PRO_PLUGIN_BASENAME') )  : ?>
                    <div class="eael-admin-block eael-admin-block-review">
                        <header class="eael-admin-block-header">
                            <div class="eael-admin-block-header-icon">
                                <img src="<?php echo EAEL_PLUGIN_URL . 'assets/admin/images/icon-show-love.svg'; ?>" alt="">
                            </div>
                            <h4 class="eael-admin-title">Show your Love</h4>
                        </header>
                        <div class="eael-admin-block-content">
                            <p>We love to have you in Essential Addons family. We are making it more awesome everyday. Take your 2 minutes to review the plugin and spread the love to encourage us to keep it going.</p>

                            <a href="https://wpdeveloper.net/review-essential-addons-elementor" class="review-flexia ea-button" target="_blank">Leave a Review</a>
                        </div>
                    </div>
                    <?php
                        else :
                            do_action('eael_additional_support_links');
                        endif;
                    ?>
            </div><!--admin block-wrapper end-->
        </div>
        <div class="eael-admin-sidebar">
            <div class="eael-sidebar-block">
                <div class="eael-admin-sidebar-logo">
                    <img src="<?php echo EAEL_PLUGIN_URL . '/assets/admin/images/icon-ea-logo.svg'; ?>">
                </div>
                <div class="eael-admin-sidebar-cta">
                    <?php
                        if( !defined('EAEL_PRO_PLUGIN_BASENAME') ) {
                            printf( __( '<a href="%s" target="_blank">Upgrade to Pro</a>', 'essential-addons-elementor' ), 'https://wpdeveloper.net/in/upgrade-essential-addons-elementor' );
                        }else {
                            do_action('eael_manage_license_action_link');
                        }
                    ?>
                </div>
            </div>
        </div><!--admin sidebar end-->
    </div><!--Row end-->
</div>