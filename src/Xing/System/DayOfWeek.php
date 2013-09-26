<?php
/**
 * @package Xing\System
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\System {
    /**
     * @method static DayOfWeek[] getCollection()
     * @property-read string $Abbreviation
     */
    class DayOfWeek extends AEnum {
        const Sunday    = 0;
        const Monday    = 1;
        const Tuesday   = 2;
        const Wednesday = 3;
        const Thursday  = 4;
        const Friday    = 5;
        const Saturday  = 6;

        /**
         * @param int $value
         * @return DayOfWeek
         */
        public static function getPreviousDay( $value ) {
            return new self($value == self::Sunday ? self::Saturday : $value-1);
        }
        /**
         * @param int $value
         * @return DayOfWeek
         */
        public static function getNextDay( $value ) {
            return new self($value == self::Saturday ? self::Sunday : $value+1);
        }
        public static function today() {
            return DateTime::Now()->DayOfWeek;
        }
        public function get_Abbreviation() {
            switch( $this->_value ) {
                case self::Sunday: 		return 'Sun';
                case self::Monday: 		return 'Mon';
                case self::Tuesday: 	return 'Tue';
                case self::Wednesday: 	return 'Wed';
                case self::Thursday: 	return 'Thu';
                case self::Friday: 		return 'Fri';
                case self::Saturday: 	return 'Sat';
            }
            return '';
        }
    }
}