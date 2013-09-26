<?php
/**
 * @package Xing\Repository
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\Repository {
    use Xing\System\APropertiedObject;

    /**
     * @property-read mixed $Id
     */
    abstract class AEntity extends APropertiedObject {
        protected $_id;
        protected $_isDbLoadingComplete = false;

        protected function get_Id() {
            return $this->_id;
        }
        protected function set_Id( $value ) {
            $this->_id	= $value;
        }
        public function exists() {
            return true;
        }
        public function entity() {
            return $this;
        }
        public function setDbLoadingComplete() {
            $this->_isDbLoadingComplete = true;
        }
        public static function isNull( AEntity $entity=null ) {
            return is_null($entity) || is_null($entity->entity());
        }
    }
}