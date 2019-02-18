<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

abstract class Module_Base {

    /**
     * @var	\RefelectionClass
     */
    private $reflection;

    /**
     * @var Module_Base
     */
    protected static $_instance = [];

    /**
     * Class active status
     */
    public static function is_active() {
        return true;
    }

    /**
     * Retrive class name
     */
    public static function class_name() {
        return get_called_class();
    }

    /**
     * @return static
     */
    public static function instance() {
        if( empty(static::$_instance[ static::class_name() ]) ) {
            static::$_instance[ static::class_name() ] = new static();
        }

        return static::$_instance[ static::class_name() ];
    }

    public function __construct() {
        $this->reflection = new \ReflectionClass( $this );
    }

}