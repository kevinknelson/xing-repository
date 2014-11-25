<?php
/**
 * @package Xing\System
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\System\DateTime {
    use Xing\System\AEnum;

    /**
     * @method static DayOfWeek[] getCollection()
     * @property-read string $Abbreviation
     *
     * @method static DayOfWeek Sunday()
     * @method static DayOfWeek Monday()
     * @method static DayOfWeek Tuesday()
     * @method static DayOfWeek Wednesday()
     * @method static DayOfWeek Thursday()
     * @method static DayOfWeek Friday()
     * @method static DayOfWeek Saturday()
     */
    class DayOfWeek extends AEnum {
        const Sunday    = 0;
        const Monday    = 1;
        const Tuesday   = 2;
        const Wednesday = 3;
        const Thursday  = 4;
        const Friday    = 5;
        const Saturday  = 6;

        public static function today() {
            return DateTime::Now()->DayOfWeek;
        }
        /**
         * @return DayOfWeek
         */
        public function previous() {
            return new self($this->Value == self::Sunday ? self::Saturday : $this->Value-1);
        }
        /**
         * @return DayOfWeek
         */
        public function next() {
            return new self($this->Value == self::Saturday ? self::Sunday : $this->Value+1);
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