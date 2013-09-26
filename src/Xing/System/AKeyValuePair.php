<?php
/**
 * @package Xing\System
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\System {

    /**
     * @property-read mixed $Key
     * @property-read mixed $Value
     */
    abstract class AKeyValuePair extends APropertiedObject {
        protected $_key;
        protected $_value;
        protected function get_Key() {
            return $this->_key;
        }
        protected function get_Value() {
            return $this->_value;
        }

        /**
         * @param mixed $key
         * @param mixed $value
         */
        public function __construct( $key, $value ) {
            $this->_key     = $key;
            $this->_value   = $value;
        }
        public function __toString() {
            return (string) $this->Value;
        }
    }
}
