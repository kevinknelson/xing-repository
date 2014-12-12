<?php
/**
 * @package Xing\System
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\System {
    use DateTimeZone;
    use Xing\System\DateTime\DateTime;

    class Get {
        public static function nonEmpty() {
            $args = func_get_args();
            foreach( $args AS $arg ) {
                if( !empty($arg) ) {
                    return $arg;
                }
            }
            return null;
        }
        public static function nonNull() {
            $args = func_get_args();
            foreach( $args AS $arg ) {
                if( $arg !== null ) {
                    return $arg;
                }
            }
            return null;
        }
        public static function intOrDefault( $val, $default=null ) {
            return preg_match("/^\-?\d+/", $val) ? intval($val) : $default;
        }
        public static function intRange( $val, $lowVal, $highVal, $default=null ) {
            $value = self::intOrDefault($val,null);
            return is_null($value) ? $default : ($value >= $lowVal && $value <= $highVal ? $value : $default);
        }
        public static function floatOrDefault( $val, $default=null ) {
            return preg_match("/^\-?(\d+|\.\d+)/", $val) ? floatval($val) : $default;
        }
        public static function boolOrDefault( $val, $default=null ) {
            if( $val === true || $val === false || $val === "1" || $val === "0" || $val === 1 || $val === 0 || $val === "true" || $val === "false" ) {
                return $val === true || $val === "1" || $val === 1 || $val === "true";
            }
            return $default;
        }
        public static function boolAsIntOrDefault( $val, $default=null ) {
            $bool = self::boolOrDefault($val);
            return is_null($bool) ? null : ($bool ? 1 : 0);
        }

        /**
         * @param DateTime|string   $val
         * @param DateTimeZone      $timezone
         * @param mixed|null        $default
         * @return null|DateTime
         */
        public static function dateTimeOrDefault( $val, DateTimeZone $timezone=null, $default = null ) {
             return $val instanceof DateTime
                 ? $val->atTimezone($timezone)
                 : (empty($val) ? $default : new DateTime($val, $timezone));
        }

    }
}
