<?php
/**
 * @package Xing\System\DateTime
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\System\DateTime {
    use DateTimeZone;
    use Xing\System\Serialization\ISerializable;

    /**
     * @property-read int $Year
     * @property-read int $Month
     * @property-read int $Day
     * @property-read int $Hours
     * @property-read int $Minutes
     * @property-read int $Seconds
     * @property-read int $DayOfWeekInt
     * @property-read DayOfWeek $DayOfWeek
     */
    class DateTime extends \DateTime implements ISerializable  {
        /**
         * @static
         * @return DateTime
         */
        public static function now() {
            return new self();
        }
        public static function today() {
            $new = new self();
            $new->setTime(0,0,0);
            return $new;
        }
        public function getDiffOrDefault( $compare, $default = null ) {
            if( !empty($compare) && $compare instanceof \DateTime ) {
                return $this->diff($compare);
            }
            return $default;
        }

        #region GETTERS
        protected function get_Year() {
            return (int) $this->format('Y');
        }
        protected function get_Month() {
            return (int) $this->format('m');
        }
        protected function get_Day() {
            return (int) $this->format('d');
        }
        protected function get_Hours() {
            return (int) $this->format('G');
        }
        protected function get_Minutes() {
            return (int) $this->format('i');
        }
        protected function get_Seconds() {
            return (int) $this->format('s');
        }
        protected function get_DayOfWeek() {
            return new DayOfWeek($this->format('w'));
        }
        protected function get_DayOfWeekInt() {
            return (int) $this->format('w');
        }
        #endregion

        #region IMMUTABLE MODIFICATION METHODS
        /**
         * @param DateTimeZone $timezone
         * @return DateTime
         */
        public function atTimezone( DateTimeZone $timezone ) {
            $newDate = clone $this;
            $newDate->setTimezone($timezone);
            return $newDate;
        }
        /**
         * @param int $hour
         * @param int $minutes
         * @param int $seconds
         * @return DateTime
         */
        public function atTime( $hour, $minutes, $seconds ) {
            $newDate = clone $this;
            $newDate->setTime($hour,$minutes,$seconds);
            return $newDate;
        }
        public function atHour( $hour ) {
            return $this->atTime($hour,$this->Minutes,$this->Seconds);
        }
        public function atMinute( $minutes ) {
            return $this->atTime($this->Hours,$minutes,$this->Seconds);
        }
        public function atSecond( $seconds ) {
            return $this->atTime($this->Hours,$this->Minutes,$seconds);
        }
        /**
         * @param int $year
         * @param int $month
         * @param int $day
         * @return DateTime
         */
        public function atDate( $year, $month, $day ) {
            $newDate = clone $this;
            $newDate->setDate($year,$month,$day);
            return $newDate;
        }
        public function atYear( $year ) {
            return $this->atDate( $year, $this->Month, $this->Day );
        }
        public function atMonth( $month ) {
            return $this->atDate( $this->Year, $month, $this->Day );
        }
        public function atDay( $day ) {
            return $this->atDate( $this->Year, $this->Month, $day );
        }
        /**
         * @param $yearsToAdd
         * @return DateTime
         */
        public function addYears( $yearsToAdd ) {
            $newDate = clone $this;
            $newDate->setDate($this->Year + $yearsToAdd, $this->Month, $this->Day);
            return $newDate;
        }
        /**
         * @param $monthsToAdd
         * @return DateTime
         */
        public function addMonths( $monthsToAdd ) {
            $newDate = clone $this;
            $newDate->setDate($this->Year, $this->Month + $monthsToAdd, $this->Day);
            return $newDate;
        }
        /**
         * @param $daysToAdd
         * @return DateTime
         */
        public function addDays( $daysToAdd ) {
            $newDate = clone $this;
            $newDate->setDate($this->Year, $this->Month, $this->Day + $daysToAdd);
            return $newDate;
        }
        /**
         * @param $hoursToAdd
         * @return DateTime
         */
        public function addHours( $hoursToAdd ) {
            $newDate = clone $this;
            $newDate->setTime($this->Hours + $hoursToAdd, $this->Minutes, $this->Seconds);
            return $newDate;
        }
        /**
         * @param $minutesToAdd
         * @return DateTime
         */
        public function addMinutes( $minutesToAdd ) {
            $newDate = clone $this;
            $newDate->setTime($this->Hours, $this->Minutes + $minutesToAdd, $this->Seconds);
            return $newDate;
        }
        /**
         * @param $secondsToAdd
         * @return DateTime
         */
        public function addSeconds( $secondsToAdd ) {
            $newDate = clone $this;
            $newDate->setTime($this->Hours, $this->Minutes, $this->Seconds + $secondsToAdd);
            return $newDate;
        }
        #endregion

        #region IMMUTABLE DATE-FIND METHODS
        public function getStartOfWeek( $dayOfWeek=DayOfWeek::Sunday ) {
            return $this->atDate($this->Year, $this->Month, $this->Day - ($this->DayOfWeek->Value+$dayOfWeek));
        }
        public function getFirstDayOfMonth() {
            return $this->atDate($this->Year, $this->Month, 1);
        }
        public function getLastDayOfMonth() {
            return $this->atDate($this->Year, $this->Month+1, 0);
        }
        #endregion

        #region MAGIC METHODS
        public function __toString() {
            return $this->format("Y-m-d H:i:s");
        }
        public function __get( $var ) {
            $method = "get_{$var}";
            if( method_exists($this, $method) ) {
                return call_user_func(array( $this, $method ));
            }
            elseif( method_exists($this, "set_{$var}") ) {
                throw new \Exception("Attempt to read from a write-only property: '{$var}'");
            }
            else {
                throw new \Exception("Attempt to read from an unknown property: '{$var}'");
            }
        }
        public function __set( $var, $value ) {
            $method = "set_{$var}";
            if( method_exists($this, $method) ) {
                call_user_func(array( $this, $method ), $value);
            }
            elseif( method_exists($this, "set_{$var}") ) {
                throw new \Exception("Attempt to write to a read-only property: '{$var}'");
            }
            else {
                throw new \Exception("Attempt to write to an unknown property: '{$var}'");
            }
        }
        public function __isset( $var ) {
            $getMethod = "get_{$var}";
            if( method_exists($this, $getMethod) ) {
                $value = call_user_func(array( $this, $getMethod ));
                return !is_null($value);
            }
            return false;
        }
        #endregion

        public function asSerializable() {
            return $this->format('r');
        }
    }
}