<?php
/**
 * @package Xing\System
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\System {
    use Xing\System\Exception\ReadOnlyPropertyException;
    use Xing\System\Exception\UndefinedPropertyException;
    use Xing\System\Exception\WriteOnlyPropertyException;
    use Xing\System\Serialization\ISerializable;

    abstract class APropertiedObject implements ISerializable {
        public function __get( $var ) {
            $method = "get_{$var}";
            if( method_exists($this, $method) ) {
                return call_user_func(array( $this, $method ));
            }
            elseif( method_exists($this, "set_{$var}") ) {
                throw new WriteOnlyPropertyException($var);
            }
            else {
                throw new UndefinedPropertyException($var);
            }
        }
        public function __set( $var, $value ) {
            $method = "set_{$var}";
            if( method_exists($this, $method) ) {
                call_user_func(array( $this, $method ), $value);
            }
            elseif( method_exists($this, "get_{$var}") ) {
                throw new ReadOnlyPropertyException($var);
            }
            else {
                throw new UndefinedPropertyException($var);
            }
        }
        public function __isset( $var ) {
            $getMethod = "get_{$var}";
            if( method_exists($this, $getMethod) ) {
                $value = call_user_func(array( $this, $getMethod ));
                return !is_null($value);
            }
            return false;
        }
        public function asSerializable() {
            $members		= get_object_vars($this);
            $result			= array();
            foreach( $members AS $member => $value ) {
                $property	= Format::toUpperCamelCase($member);
                $result[$property]	= $value instanceof AValueType ? $value->Value : $value;
            }
            return $result;
        }
    }
}
