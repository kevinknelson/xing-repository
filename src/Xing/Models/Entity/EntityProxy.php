<?php
/**
 * @package Xing\Models\Entity
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\Models\Entity {
    class EntityProxy extends AEntity {
        /** @var bool */
        private $_isLoaded;
        /** @var callable */
        private $_loader;
        /** @var AEntity */
        private $_entity;

        public function __construct( $id, \Closure $loader ) {
            $this->_isLoaded    = false;
            $this->_loader      = $loader;
            $this->_id          = $id;
        }
        public function entity() {
            $this->testInitialization();
            return $this->_entity;
        }
        public function exists() {
            $this->testInitialization();
            return $this->_entity != null;
        }
        protected function get_Id() {
            return $this->_id;
        }
        protected function set_Id($value) {
            $this->_id = $value;
        }
        private function testInitialization() {
            if( !$this->_isLoaded ) {
                $this->_entity      = call_user_func($this->_loader);
                $this->_isLoaded    = true;
            }
        }
        public function __get( $var ) {
            if( $var == 'Id' ) { return $this->get_Id() ?: $this->entity()->Id; }

            $this->testInitialization();
            return is_null($this->_entity) ? null : $this->_entity->{$var};
        }
        public function __set( $var, $value ) {
            if( $var == 'Id' ) { $this->_id = $value; }

            $this->testInitialization();
            if( !is_null($this->_entity) ) {
                $this->_entity->{$var} = $value;
            }
        }
        public function __call( $method, $args ) {
            $this->testInitialization();
            return call_user_func_array(array($this->_entity,$method),$args);
        }
        public function __isset( $var ) {
            if( $var == 'Id' ) { return is_null($this->_id); }

            $this->testInitialization();
            return isset($this->_entity->{$var});
        }
        public function asSerializable() {
            if( is_null($this->_entity) ) {
                return null;
            }
            return $this->_entity->asSerializable();
        }
    }
}
