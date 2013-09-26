<?php
/**
 * @package Xing\System
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\System\Collections {
    class ValidationErrors extends Dictionary {
        public function __construct() {
            $this->_array                   = array();
        }

        #region CHAIN-ABLE METHODS
        public function addIfNotTrue( $condition, $key, $message, $isRequired=true ) {
            if( !$condition && $isRequired ) {
                $this->add($key,$message);
            }
            return $this;
        }
        public function addIfEmpty( $value, $key, $message, $isRequired=true ) {
            if( empty($value) && $isRequired ) {
                $this->add($key,$message);
            }
            return $this;
        }
        public function addIfNumberOutOfRange( $value, $minValue=null, $maxValue=null, $key, $underMinMessage, $overMaxMessage ) {
            $intVal = (int) $value;
            if( $minValue != null && $intVal < $minValue ) {
                $this->add($key,$underMinMessage);
            }
            if( $maxValue != null && $intVal > $maxValue ) {
                $this->add($key,$overMaxMessage);
            }
            return $this;
        }
        public function addIfNotMatched( $value, $regEx, $key, $message ) {
            if( !empty($value) && !preg_match($regEx,$value) ) {
                $this->add($key,$message);
            }
            return $this;
        }
        #endregion

        public function hasErrors() {
            return count($this->_array) > 0;
        }
    }
}

