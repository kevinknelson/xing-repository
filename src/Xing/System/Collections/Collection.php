<?php
/**
 * @package Xing\System
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\System\Collections {
    use \InvalidArgumentException;

    class Collection extends AEnumerable implements ICollection {
        public function cast( array $arr ) {
            return new self($arr);
        }
        public static function create( array $arr ) {
            return new self($arr);
        }

        #region CHAIN-ABLE METHODS
        public function add( $obj ) {
            $this->_array[] = $obj;
            return $this;
        }

        /**
         * @param IEnumerable|array $array
         * @return $this
         * @throws InvalidArgumentException
         */
        public function addRange( $array ) {
            if( $array instanceof IEnumerable ) {
                $this->_array = array_merge($this->_array,$array->asArray());
            }
            elseif( is_array($array) ) {
                $this->_array	= array_merge($this->_array,$array);
            }
            else {
                throw new InvalidArgumentException('Collection::addRange expects argument to be of type array or AEnumerable');
            }
            return $this;
        }
        #endregion
    }
}
