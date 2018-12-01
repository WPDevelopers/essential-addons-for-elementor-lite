<div id="version-control" class="eael-settings-tab eael-elements-list">
    <div class="row eael-admin-general-wrapper">
        <div class="eael-admin-general-inner">
            <div class="eael-admin-block-wrapper">
                <div class="eael-admin-block eael-admin-block-community">
                    <header class="eael-admin-block-header">
                        <h4 class="eael-admin-title">Essential Addons Elementor</h4>
                        <br>
                        <p>Thank you for using Essential Addons Elementor. we hope you enjoy using it.</p>
                    </header>
                    <div class="eael-admin-block-content">
                        <h4>Rollback to Previous Version</h4>
                        <p>Rollback Version</p>
                        <div><?php echo  sprintf( '<a target="_blank" href="%s" class="button eael-version-rollback elementor-button-spinner">Reinstall Version 2.8.3</a>', wp_nonce_url( admin_url( 'admin-post.php?action=eael_version_rollback' ), 'eael_version_rollback' ) ); ?> </div>
                        <p><span style="color: red;">Warning: Please backup your database before making the rollback.</span></p>
                    </div>
                </div>
            </div><!--admin block-wrapper end-->
        </div>
    </div>
</div><!-- # version-control -->