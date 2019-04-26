<?php
namespace Essential_Addons_Elementor\Classes;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly.

use \Elementor\Controls_Manager as Controls_Manager;
use \Elementor\Group_Control_Base as Group_Control_Base;

class Group_Control_EA_Posts extends Group_Control_Base
{
    use \Essential_Addons_Elementor\Traits\Helper;

    protected static $fields;

    public static function get_type()
    {
        return 'eaeposts';
    }

    public static function on_export_remove_setting_from_element($element, $control_id)
    {
        unset($element['settings'][$control_id . '_posts_ids']);
        unset($element['settings'][$control_id . '_authors']);

        foreach (Utils::get_post_types() as $post_type => $label) {
            $taxonomy_filter_args = [
                'show_in_nav_menus' => true,
                'object_type' => [$post_type],
            ];

            $taxonomies = get_taxonomies($taxonomy_filter_args, 'objects');

            foreach ($taxonomies as $taxonomy => $object) {
                unset($element['settings'][$control_id . '_' . $taxonomy . '_ids']);
            }
        }

        return $element;
    }

    protected function init_fields()
    {
        $fields = [];

        $fields['post_type'] = [
            'label' => __('Source', 'essential-addons-elementor'),
            'type' => Controls_Manager::SELECT,
        ];

        $fields['posts_ids'] = [
            'label'       => __('Search & Select', 'essential-addons-elementor'),
            'type'        => Controls_Manager::SELECT2,
            'post_type'   => '',
            'options'     => $this->eael_get_all_types_post(),
            'label_block' => true,
            'multiple'    => true,
            'condition'   => [
                'post_type' => 'by_id',
            ],
        ];

        $fields['authors'] = [
            'label' => __('Author', 'essential-addons-elementor'),
            'label_block' => true,
            'type' => Controls_Manager::SELECT2,
            'multiple' => true,
            'default' => [],
            'options' => $this->get_authors(),
            'condition' => [
                'post_type!' => [
                    'by_id',
                ],
            ],
        ];

        return $fields;
    }

    protected function prepare_fields($fields)
    {

        $post_types = $this->eael_get_post_types();

        $post_types_options = $post_types;

        $post_types_options['by_id'] = __('Manual Selection', 'essential-addons-elementor');

        $fields['post_type']['options'] = $post_types_options;

        $fields['post_type']['default'] = key($post_types);

        $fields['posts_ids']['object_type'] = array_keys($post_types);

        $taxonomy_filter_args = [
            'show_in_nav_menus' => true,
        ];

        if (!empty($args['post_type'])) {
            $taxonomy_filter_args['object_type'] = [$args['post_type']];
        }

        $taxonomies = get_taxonomies($taxonomy_filter_args, 'objects');

        foreach ($taxonomies as $taxonomy => $object) {
            $taxonomy_args = [
                'label' => $object->label,
                'type' => Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple' => true,
                'object_type' => $taxonomy,
                'options' => [],
                'condition' => [
                    'post_type' => $object->object_type,
                ],
            ];

            $options = [];

            $taxonomy_args['type'] = Controls_Manager::SELECT2;

            $terms = get_terms($taxonomy);

            foreach ($terms as $term) {
                $options[$term->term_id] = $term->name;
            }

            $taxonomy_args['options'] = $options;

            $fields[$taxonomy . '_ids'] = $taxonomy_args;
        }

        unset($fields['post_format_ids']);

        return parent::prepare_fields($fields);
    }

    /**
     * All authors name and ID, who published at least 1 post.
     * @return array
     */
    public function get_authors()
    {
        $user_query = new \WP_User_Query(
            [
                'who' => 'authors',
                'has_published_posts' => true,
                'fields' => [
                    'ID',
                    'display_name',
                ],
            ]
        );

        $authors = [];

        foreach ($user_query->get_results() as $result) {
            $authors[$result->ID] = $result->display_name;
        }

        return $authors;
    }

    protected function get_default_options()
    {
        return [
            'popover' => false,
        ];
    }
}
