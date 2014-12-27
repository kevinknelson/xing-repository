<?php
/**
 * @package Xing\System
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\System {
    use Xing\System\Serialization\ISerializable;
    use Xing\System\Traits\PropertiedTrait;

    abstract class APropertiedObject implements ISerializable {
        use PropertiedTrait;

        public function asSerializable() {
            $members		= get_object_vars($this);
            $result			= array();
            foreach( $members AS $member => $value ) {
                $property	= Format::toUpperCamelCase($member);
                $result[$property]	= $value instanceof AValueType ? $value->Value : $value;
            }
            return $result;
        }
        public function jsonSerialize() {
            return $this->asSerializable();
        }
    }
}
