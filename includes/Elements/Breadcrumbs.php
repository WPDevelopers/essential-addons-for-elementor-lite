<?php 
namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
   exit;
}

use Elementor\Widget_Base;
use \Essential_Addons_Elementor\Classes\Helper;

class Breadcrumbs extends Widget_Base {
   public function get_name() {
		return 'eael-breadcrumbs';
	}

   public function get_title() {
		return esc_html__( 'Breadcrumbs', 'essential-addons-for-elementor-lite' );
	}

	public function get_icon() {
		return 'eicon-elementor-circle';
	}

   public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

   public function get_keywords() {
		return [ 'breadcrumb', 'shop', 'page', 'post' ];
	}

}