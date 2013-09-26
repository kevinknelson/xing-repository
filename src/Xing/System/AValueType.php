<?php
/**
 * @package Xing\System
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\System {
    /**
     * @property-read mixed $Value
     */
    abstract class AValueType extends APropertiedObject {
        protected $_value;
        protected function get_Value() {
            return $this->_value;
        }
        /**
         * @param mixed $value
         */
        public function __construct( $value ) {
            $this->_value = $value;
        }
        public function __toString() {
            return (string) $this->Value;
        }
    }
}
