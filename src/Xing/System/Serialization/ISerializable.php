<?php
/**
 * @package Xing\System
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace {
    if( !interface_exists('JsonSerializable') ) {
        interface JsonSerializable {
            public function jsonSerialize();
        }
    }
}
namespace Xing\System\Serialization {
    interface ISerializable extends \JsonSerializable {
        public function asSerializable();
    }
}
