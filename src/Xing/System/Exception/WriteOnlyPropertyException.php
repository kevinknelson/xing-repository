<?php
/**
 * @package Xing\System
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\System\Exception {
    class WriteOnlyPropertyException extends \Exception {
        public function __construct( $propertyName, $code = 0, \Exception $previous = null ) {
            parent::__construct("Attempted to read from write-only property: '{$propertyName}'", $code);
        }
    }
}
