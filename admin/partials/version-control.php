<div id="version-control" class="eael-settings-tab eael-elements-list">
    <div class="row eael-admin-general-wrapper">
        <div class="eael-admin-general-inner">
            <div class="eael-admin-block-wrapper">
                <div class="eael-admin-block eael-admin-block-community">
                    <header class="eael-admin-block-header">
                        <div class="eael-admin-block-header-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24px" height="24px">
                                <path d="M 2 2 L 4.9394531 4.9394531 C 3.1262684 6.7482143 2 9.2427079 2 12 C 2 17.514 6.486 22 12 22 C 17.514 22 22 17.514 22 12 C 22 6.486 17.514 2 12 2 L 12 4 C 16.411 4 20 7.589 20 12 C 20 16.411 16.411 20 12 20 C 7.589 20 4 16.411 4 12 C 4 9.7940092 4.9004767 7.7972757 6.3496094 6.3496094 L 9 9 L 9 2 L 2 2 z"/>
                            </svg>
                        </div>
                        <h4 class="eael-admin-title">Rollback to Previous Version</h4>
                    </header>
                    <div class="eael-admin-block-content">
                        <h3>Rollback Version</h3>
                        <div><?php
                            $vh = sprintf( '<a target="_blank" href="%s" class="button eael-btn eael-version-rollback elementor-button-spinner">Reinstall Version</a>', wp_nonce_url( admin_url( 'admin-post.php?action=eael_version_rollback' ), 'eael_version_rollback' ));
                            echo apply_filters('insert_eael_versions_html', $vh ); 
                        ?> </div>
                        <div class="warning">
                            <div class="eael-admin-block-header-icon">
                                <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                    viewBox="0 0 451.74 451.74" style="enable-background:new 0 0 451.74 451.74;" xml:space="preserve">
                                <path style="fill:#E24C4B;" d="M446.324,367.381L262.857,41.692c-15.644-28.444-58.311-28.444-73.956,0L5.435,367.381
                                    c-15.644,28.444,4.267,64,36.978,64h365.511C442.057,429.959,461.968,395.825,446.324,367.381z"/>
                                <path style="fill:#FFFFFF;" d="M225.879,63.025l183.467,325.689H42.413L225.879,63.025L225.879,63.025z"/>
                                <g>
                                    <path style="fill:#3F4448;" d="M196.013,212.359l11.378,75.378c1.422,8.533,8.533,15.644,18.489,15.644l0,0
                                        c8.533,0,17.067-7.111,18.489-15.644l11.378-75.378c2.844-18.489-11.378-34.133-29.867-34.133l0,0
                                        C207.39,178.225,194.59,193.87,196.013,212.359z"/>
                                    <circle style="fill:#3F4448;" cx="225.879" cy="336.092" r="17.067"/>
                                </g>
                                </svg>
                            </div>
                            <h5 class="eael-admin-title">Warning: Please backup your database before making the rollback.</h5>
                        </div>
                    </div>
                </div>
            </div><!--admin block-wrapper end-->
        </div>
    </div>
</div><!-- # version-control -->