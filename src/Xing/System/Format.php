<?php
/**
 * @package Xing\System
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\System {
    use DateTimeZone;
    use Xing\System\DateTime\DateTime;
    use Xing\System\DateTime\Timezone;

    class Format {
        /**
         * Format::string(string $str, params[] string $arr)
         * @static
         * @param string $string
         * @param string ...$var
         * @return string
         */
        public static function string($string) {
            $args   = func_get_args();
            $string = array_shift($args);
            for( $i=0; $i < count($args); $i++ ) {
                $string = str_replace('{'.$i.'}',$args[$i],$string);
            }
            return $string;
        }

        /**
         * create singular/plural forms of a string.  Zero defaults to plural string
         * unless $zero template is populated.
         * @param int  $count
         * @param string $singularFormat
         * @param string $pluralFormat
         * @param string|null $noneFormat
         * @return bool|string
         */
        public static function pluralize( $count, $singularFormat, $pluralFormat, $noneFormat=null ) {
            if( !is_null($noneFormat) && empty($count) ) {
                return Format::string($noneFormat,0);
            }
            return $count != 1 ? Format::string($pluralFormat,$count) : Format::string($singularFormat,$count);
        }
        public static function subString( $string, $length, $suffix='...') {
            return strlen($string) > $length ? substr($string,0,$length).$suffix : $string;
        }
        public static function addZeroPrefix( $num, $digits=2 ) {
            $result = (string) $num;
            while( strlen($result) < $digits ) {
                $result = '0'.$result;
            }
            return $result;
        }
        public static function time( $hours, $minutes, $format=null ) {
            $format	= $format ?: 'h:ia';
            return is_null($hours) ? '' : DateTime::now()->setTime($hours,$minutes)->format($format);
        }
        public static function dateTime( $date, $format, DateTimeZone $timezone=null, $defaultValue=null ) {
            $parsed = $date instanceof \DateTime ? clone $date : Get::dateTimeOrDefault($date,$timezone);
            if( !is_null($parsed) && !is_null($timezone) ) {
                $parsed->setTimezone( Timezone::Utc()->PhpTimezone );
            }
            return is_null($parsed) ? $defaultValue : $parsed->format($format);
        }
        public static function toUpperCamelCase( $string ) {
            return empty($string) ? '' : str_replace(' ','',ucwords(str_replace('_',' ',$string)));
        }
        public static function toLowerCamelCase( $string ) {
            return lcfirst(self::toUpperCamelCase($string));
        }
        public static function toLowerUnderscored( $string ) {
            return empty($string) ? '' : strtolower(str_replace(' ','_',trim(preg_replace('/([A-Z])/',' \\1',$string))));
        }
        public static function toSpacedString( $string ) {
            return empty($string) ? '' : ucwords(str_replace('_',' ',trim(preg_replace('/((\d+)|[A-Z])/',' \\1',$string))));
        }
    }
}
