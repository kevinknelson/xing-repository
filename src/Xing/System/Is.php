<?php
/**
 * @package Xing\System
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\System {
    /**
     * @usage Is::this('5')->in('2','3','4');
     */
    class Is {
        private $_value;

        public function __construct( $val ) {
            $this->_value = $val;
        }
        public static function this( $val ) {
            return new Is($val);
        }
        public function in() {
            $args   = func_get_args();
            foreach( $args AS $arg ) {
                if( $this->_value == $arg ) {
                    return true;
                }
            }
            return false;
        }
    }
}