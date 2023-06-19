<?php
/**
 * Template Name: Style 1
 *
 * @var $cs_product
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

echo $cs_product->get_title();
echo $cs_product->get_image('medium');
echo $cs_product->get_price();
echo '<br>';