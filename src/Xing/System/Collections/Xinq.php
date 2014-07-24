<?php
/**
 * @package Xing\System
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\System\Collections {
    class Xinq {
        public static function all( $array, $predicate ) {
            foreach( $array AS $key => $value ) {
                if( !$predicate($value, $key) ) {
                    return false;
                }
            }
            return true;
        }
        public static function sum( $array, $method ) {
            $sum = 0;
            foreach( $array AS $key => $value ) {
                $sum += $method($value,$key);
            }
            return $sum;
        }
        public static function any( $array, $predicate ) {
            foreach( $array AS $key => $value ) {
                if( $predicate($value, $key) ) {
                    return true;
                }
            }
            return false;
        }

        public static function split( $string, $delimiter = ',', $callback = null ) {
            $callback = $callback
                ? : function( $value, $key ) {
                    return $value;
                };
            return self::select(explode($delimiter, $string), $callback);
        }

        public static function join( array $arr, $delimiter = ',', $callback = null ) {
            $callback = $callback
                ? : function( $value, $key ) {
                    return $value;
                };
            $result   = '';
            foreach( $arr AS $key => $value ) {
                $result .= ($result==='' ? '' : $delimiter) . $callback($value, $key);
            }
            return $result;
        }

        public static function select( $array, $callback ) {
            $newArray = array();
            foreach( $array AS $key => $object ) {
                $newArray[$key] = $callback($object, $key);
            }
            return $newArray;
        }

        public static function first( $array, $predicate ) {
            foreach( $array AS $key => $value ) {
                if( $predicate($value, $key) ) {
                    return $value;
                }
            }
            return null;
        }

        public static function where( $array, $predicate ) {
            $newArray = array();
            foreach( $array AS $key => $value ) {
                if( $predicate($value, $key) ) {
                    $newArray[$key] = $value;
                }
            }
            return $newArray;
        }

        public static function orderByAsc( $array, $callback ) {
            usort( $array, function( $a, $b ) use( $callback ) {
                $aVal = $callback($a);
                $bVal = $callback($b);
                return $aVal == $bVal ? 0 : ($aVal < $bVal ? -1 : 1);
            });
            return $array;
        }
        public static function orderByDesc( $array, $callback ) {
            usort( $array, function( $a, $b ) use( $callback ) {
                $aVal = $callback($a);
                $bVal = $callback($b);
                return $aVal == $bVal ? 0 : ($aVal > $bVal ? -1 : 1);
            });
            return $array;
        }

        /**
         * @param IEnumerable|array $array
         * @param \Closure $callback
         */
        public static function forEachItem( $array, \Closure $callback ) {
            foreach( $array AS $key => $item ) {
                $callback($item,$key);
            }
        }
    }
}
