<?php
/**
 * @package Xing\System
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\System\Collections {
    interface ICollection extends IEnumerable {
        public function add( $object );
        /**
         * @param array|IEnumerable $array
         */
        public function addRange( $array );
    }
}