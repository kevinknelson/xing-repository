<?php

    namespace Xing\System\DateTime {
        use DateTimeZone;
        use Xing\System\AEnum;

        /**
         * Class Timezone
         * @package Xing\System\DateTime
         *
         * @property-read DateTimeZone $PhpTimezone
         *
         * @method static Timezone Utc()
         * @method static Timezone Pst()
         * @method static Timezone Mst()
         * @method static Timezone Cst()
         * @method static Timezone Est()
         */
        class Timezone extends AEnum {
            const Utc       = 'UTC';
            const Pst       = 'America/Los_Angeles';
            const Mst       = 'America/Denver';
            const Cst       = 'America/Chicago';
            const Est       = 'America/New_York';

            public function get_PhpTimezone() {
                return new DateTimeZone($this->_value);
            }
        }
    }