<?php
/**
 * @package Xing\System\Serialization
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\System\Serialization {
    interface JsonSerializable {
        public function jsonSerialize();
    }
}
