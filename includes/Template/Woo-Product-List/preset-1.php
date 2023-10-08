<?php
/**
 * Template Name: Preset 1
 */
?>

<div class="eael-product-list-wrapper preset-1">
    <div class="eael-product-list-body">
        <div class="eael-product-list-container">
            <?php $this->get_account_dashboard_navbar($current_user); ?>
            <?php $this->get_account_dashboard_content($current_user, $is_editor); ?>
        </div>
    </div>
</div>