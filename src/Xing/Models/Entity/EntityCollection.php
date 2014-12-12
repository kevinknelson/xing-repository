<?php
/**
 * @package Xing\Models\Entity
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\Models\Entity {
    use Xing\System\Collections\AEnumerable;
    use Xing\System\Collections\Collection;
    use Xing\System\Collections\Xinq;
    use \InvalidArgumentException;

    class EntityCollection extends Collection {
        /** @var bool */
        private $_isLoaded;
        /** @var callable */
        private $_loader;

        public static function create( array $arr ) {
            return new self($arr);
        }
        public function cast( array $arr ) {
            return new self($arr);
        }
        public function lazyLoad( \Closure $closure ) {
            $this->_isLoaded    = false;
            $this->_loader      = $closure;
        }

        public function __construct( array $arr=array() ) {
            $this->_isLoaded    = true;
            $this->_loader      = null;
            $this->_array		= $arr;
        }
        public function getIterator() {
            $this->testInitialization();
            $arr = new \ArrayIterator($this->_array);
            return $arr;
        }
        private function testInitialization() {
            if( !$this->_isLoaded ) {
                $this->_array       = call_user_func($this->_loader);
                $this->_isLoaded    = true;
            }
        }

        #region CHAIN-ABLE METHODS
        public function add( $obj ) {
            $this->testInitialization();
            $this->_array[] = $obj;
            return $this;
        }
        public function addRange( $array ) {
            $this->testInitialization();
            if( $array instanceof AEnumerable ) {
                $this->_array = array_merge($this->_array,$array->asArray());
            }
            elseif( is_array($array) ) {
                $this->_array	= array_merge($this->_array,$array);
            }
            else {
                throw new InvalidArgumentException('Collection::addRange() expects argument to be of type array or AEnumerable');
            }
        }
        public function clear() {
            $this->_isLoaded 	= true;
            $this->_array 		= array();
            return $this;
        }
        #endregion



        #region GETTERS/SETTERS
        public function get_Count() {
            $this->testInitialization();
            return count($this->_array);
        }
        public function get_IsEmpty() {
            $this->testInitialization();
            return $this->Count < 1;

        }
        #endregion

        #region OVERRIDE - RETRIEVAL METHODS
        public function asArray() {
            $this->testInitialization();
            return $this->_array;
        }

        /**
         * @param mixed|null $default
         * @return mixed|null
         */
        public function firstOrDefault( $default=null ) {
            $this->testInitialization();
            return $this->IsEmpty ? $default : reset($this->_array);
        }
        #endregion

        #region OVERRIDE - LINQ METHODS
        public function any( $predicate ) {
            $this->testInitialization();
            return Xinq::any($this->_array, $predicate);
        }
        public function all( $predicate ) {
            $this->testInitialization();
            return Xinq::all($this->_array, $predicate);
        }
        /**
         * @param $callback
         * @return \Xing\Repository\EntityCollection|array
         */
        public function select( $callback ) {
            $this->testInitialization();
            return $this->cast(Xinq::select($this->_array, $callback));
        }
        /**
         * @param $predicate
         * @return \Xing\Repository\EntityCollection|array
         */
        public function where( $predicate ) {
            $this->testInitialization();
            return $this->cast(Xinq::where($this->_array, $predicate));
        }
        /**
         * @param $callback
         * @return AEnumerable|array
         */
        public function orderByAsc( $callback ) {
            $this->testInitialization();
            return $this->cast(Xinq::orderByAsc($this->_array, $callback));
        }
        /**
         * @param $callback
         * @return AEnumerable|array
         */
        public function orderByDesc( $callback ) {
            $this->testInitialization();
            return $this->cast(Xinq::orderByDesc($this->_array, $callback));
        }
        #endregion

        public function count() {
            $this->testInitialization();
            return $this->Count;
        }
        public function asSerializable() {
            if( !$this->_isLoaded ) {
                return null;
            }
            return $this->_array;
        }
    }
}
