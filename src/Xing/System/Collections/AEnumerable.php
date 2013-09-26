<?php
/**
 * @package Xing\System
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\System\Collections {
    use Xing\System\APropertiedObject;

    /**
     * @property-read int $Count
     * @property-read bool $IsEmpty
     */
    abstract class AEnumerable extends APropertiedObject implements IEnumerable {
        protected $_array;

        abstract function cast( array $arr );

        /**
         * @return self
         */
        public function clear() {
            $this->_array   = array();
            return $this;
        }

        #region GETTERS/SETTERS
        public function get_Count() {
            return count($this->_array);
        }
        public function get_IsEmpty() {
            return $this->Count < 1;

        }
        #endregion

        public function __construct( $arr = array() ) {
            $this->_array = $arr;
        }

        #region RETRIEVAL METHODS
        /**
         * @return array
         */
        public function asArray() {
            return $this->_array;
        }
        /**
         * @param mixed $default
         * @return mixed
         */
        public function firstOrDefault( $default=null ) {
            return $this->IsEmpty ? $default : reset($this->_array);
        }
        #endregion

        #region Xinq METHODS
        /**
         * @param $predicate
         * @return bool
         */
        public function any( $predicate ) {
            return Xinq::any($this->_array, $predicate);
        }
        /**
         * @param $predicate
         * @return bool
         */
        public function all( $predicate ) {
            return Xinq::all($this->_array, $predicate);
        }
        /**
         * @param $callback
         * @return AEnumerable|array
         */
        public function select( $callback ) {
            return $this->cast(Xinq::select($this->_array, $callback));
        }
        /**
         * @param $predicate
         * @return AEnumerable|array
         */
        public function where( $predicate ) {
            return $this->cast(Xinq::where($this->_array, $predicate));
        }
        /**
         * @param $callback
         * @return AEnumerable|array
         */
        public function orderByAsc( $callback ) {
            return $this->cast(Xinq::orderByAsc($this->_array, $callback));
        }
        /**
         * @param $callback
         * @return AEnumerable|array
         */
        public function orderByDesc( $callback ) {
            return $this->cast(Xinq::orderByDesc($this->_array, $callback));
        }
        /**
         * @param $callback
         * @return self
         */
        public function forEachItem( $callback ) {
            Xinq::forEachItem($this->_array,$callback);
            return $this;
        }
        #endregion

        public function getIterator() {
            return new \ArrayIterator($this->_array);
        }
        public function count() {
            return $this->Count;
        }
        public function asSerializable() {
            return $this->_array;
        }
    }

}
