<?php
/**
 * @package Xing\System
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\System\Collections {
    class Dictionary extends AEnumerable implements IDictionary {
        public function __construct( array $associativeArray = array() ) {
            $this->_array = $associativeArray;
        }

        public function cast( array $arr ) {
            return new self($arr);
        }
        public static function create( array $arr = array() ) {
            return new self($arr);
        }

        #region CHAIN-ABLE METHODS
        public function add( $key, $value ) {
            $this->_array[$key] = $value;
            return $this;
        }
        public function remove( $key ) {
            if( $this->containsKey($key) ) {
                unset($this->_array[$key]);
            }
            return $this;
        }
        #endregion

        #region RETRIEVAL METHODS
        public function containsKey( $key ) {
            return isset($this->_array[$key]);
        }
        public function getValueOrDefault( $key, $default = null ) {
            if( $this->containsKey($key) ) {
                return $this->_array[$key];
            }
            return $default;
        }
        public function tryGetValue( $key, &$value ) {
            if( $this->containsKey($key) ) {
                $value = $this->_array[$key];
                return true;
            }
            return false;
        }
        #endregion
    }
}
