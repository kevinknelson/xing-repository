<?php
/**
 * @package Xing\System
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\System\Collections {
    class KeyList extends AEnumerable {
        public function has( $key ) {
            return isset($this->_array[$key]) && $this->_array[$key] == true;
        }
        public function add( $key, $exists=true ) {
            $this->_array[$key]     = $exists;
        }
        public function addRange( array $arr ) {
            foreach( $arr AS $key ) {
                $this->_array[$key] = true;
            }
        }

        function cast( array $arr ) {
            return new self($arr);
        }
    }
}