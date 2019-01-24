<div id="version-control" class="eael-settings-tab eael-elements-list">
    <div class="row eael-admin-general-wrapper">
        <div class="eael-admin-general-inner">
            <div class="eael-admin-block-wrapper">
                <div class="eael-admin-block eael-admin-block-community">
                    <header class="eael-admin-block-header">
                        <div class="eael-admin-block-header-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 48 48" version="1.1">
                            <g id="surface1">
                            <path style=" fill:#7CB342;" d="M 44 24 C 44 26.558594 43.519531 29.003906 42.644531 31.253906 L 37.605469 35.792969 L 32.691406 42.019531 C 30.0625 43.289063 27.113281 44 24 44 C 12.953125 44 4 35.046875 4 24 C 4 12.953125 12.953125 4 24 4 C 35.046875 4 44 12.953125 44 24 Z "></path>
                            <path style=" fill:#DCEDC8;" d="M 31.113281 38.335938 C 30.421875 38.679688 29.699219 38.976563 28.957031 39.21875 L 32.691406 42.019531 C 33.351563 41.699219 33.992188 41.34375 34.609375 40.957031 Z "></path>
                            <path style=" fill:#DCEDC8;" d="M 36 40 C 36.804688 39.394531 37.578125 38.71875 38.28125 38 L 36 38 Z "></path>
                            <path style=" fill:#DCEDC8;" d="M 40.5 33 C 40.9375 33 41.390625 33.039063 41.808594 33.113281 C 42.117188 32.507813 42.394531 31.894531 42.644531 31.253906 C 41.960938 31.09375 41.230469 31 40.5 31 L 38.390625 31 C 38.054688 31.695313 37.660156 32.367188 37.226563 33 Z "></path>
                            <path style=" fill:#FFFFFF;" d="M 40 24 C 40 32.835938 32.835938 40 24 40 C 15.164063 40 8 32.835938 8 24 C 8 15.164063 15.164063 8 24 8 C 32.835938 8 40 15.164063 40 24 Z "></path>
                            <path style=" " d="M 23 11 L 25 11 L 25 24 L 23 24 Z "></path>
                            <path style=" " d="M 23.148438 23.65625 L 29.65625 17.148438 L 31.28125 18.777344 L 24.777344 25.28125 Z "></path>
                            <path style=" " d="M 26 24 C 26 25.105469 25.105469 26 24 26 C 22.894531 26 22 25.105469 22 24 C 22 22.894531 22.894531 22 24 22 C 25.105469 22 26 22.894531 26 24 Z "></path>
                            <path style=" fill:#8BC34A;" d="M 25 24 C 25 24.550781 24.550781 25 24 25 C 23.449219 25 23 24.550781 23 24 C 23 23.449219 23.449219 23 24 23 C 24.550781 23 25 23.449219 25 24 Z "></path>
                            <path style=" fill:#E91E63;" d="M 40.5 33 L 35 33 L 35 38 L 40.5 38 C 41.882813 38 43 39.117188 43 40.5 C 43 41.882813 41.882813 43 40.5 43 L 39 43 L 39 48 L 40.5 48 C 44.640625 48 48 44.640625 48 40.5 C 48 36.359375 44.640625 33 40.5 33 Z "></path>
                            <path style=" fill:#E91E63;" d="M 36 29 L 28 36 L 36 42 Z "></path>
                            </g>
                            </svg>
                        </div>
                        <h4 class="eael-admin-title">Rollback to Previous Version</h4>
                    </header>
                    <div class="eael-admin-block-content">
                        <h3>Select the version you want to install</h3>
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
                            <p class="eael-notice-text">Usually you should not loose any data on the rollback process. But it's always good to have a backup of your database.</p>
                        </div>
                    </div>
                </div>
            </div><!--admin block-wrapper end-->
        </div>
    </div>
</div><!-- # version-control -->