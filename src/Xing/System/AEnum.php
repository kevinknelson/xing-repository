<?php
/**
 * @package Xing\System
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\System {
    use \Xing\System\Collections\Collection;
    use \Xing\System\Collections\Dictionary;
    /**
     * @property-read int $Value
     * @property-read string $Description
     */
    abstract class AEnum extends AValueType {
        /** @var Dictionary $_definedEnums */
        protected static $_definedEnums;
        protected $_value;

        public function is( $value ) {
            return $value instanceof AValueType ? $this->_value == $value->Value : $this->_value == $value;
        }
        public function isIn() {
            foreach( func_get_args() AS $arg ) {
                if( $this->is($arg) ) { return true; }
            }
            return false;
        }
        protected function get_Description() {
            //use late static binding and reflection to build enum collection
            $instance   = get_called_class();
            $reflection = new \ReflectionClass($instance);
            foreach( $reflection->getConstants() AS $key => $value ) {
                if( $value == $this->_value ) {
                    return Format::toSpacedString($key);
                }
            }
            return null;
        }
        protected function get_Value() {
            return $this->_value;
        }

        /**
         * @param int $value
         */
        public function __construct( $value ) {
            $this->_value = $value;
        }
        public function __toString() {
            return (string) $this->_value;
        }

        /**
         * @return Collection|AEnum[]
         */
        public static function getCollection() {
            //use late static binding and reflection to build enum collection
            $instance   = get_called_class();
            self::testSetup($instance);
            return self::$_definedEnums->getValueOrDefault($instance);
        }
        public static function containsValue( $value ) {
            //use late static binding and reflection to build enum collection
            $instance   = get_called_class();
            self::testSetup($instance);
            /** @var Collection $collection */
            $collection	= self::$_definedEnums->getValueOrDefault($instance,new Collection());
            return $collection->any( function(AEnum $enum) use( $value ) { return $enum->Value == $value; } );
        }
        private static function testSetup($instance) {
            if( is_null(self::$_definedEnums) ) {
                self::$_definedEnums = new Dictionary();
            }
            if( !self::$_definedEnums->containsKey($instance) ) {
                $reflection = new \ReflectionClass($instance);
                $array      = array();
                foreach( $reflection->getConstants() AS $value ) {
                    array_push($array,new $instance($value));
                }
                self::$_definedEnums->add($instance, Collection::create($array));
            }
        }
        public static function __callStatic( $name, $arguments ) {
            //use late static binding and reflection to build enum collection
            $instance   = get_called_class();
            return new $instance( constant("{$instance}::{$name}") );
        }
    }
}
