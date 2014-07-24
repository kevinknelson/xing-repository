<?php

namespace Xing\System\Collections {
    use Xing\System\Get;

    class DictionaryCast extends Dictionary {
        public static function create( array $arr = array() ) {
            return new self($arr);
        }
        public function getBoolOrDefault( $key, $default=null ) {
            return Get::boolOrDefault($this->getValueOrDefault($key),$default);
        }
        public function getBoolAsIntOrDefault( $key, $default=null ) {
            return Get::boolAsIntOrDefault($this->getValueOrDefault($key),$default);
        }
        public function getIntOrDefault( $key, $default=null ) {
            return Get::intOrDefault($this->getValueOrDefault($key),$default);
        }
        public function getIntRange( $key, $min, $max, $default=null ) {
            return Get::intRange($this->getIntOrDefault($key,$default),$min,$max,$default);
        }
        public function getFloatOrDefault( $key, $default=null ) {
            return Get::floatOrDefault($this->getValueOrDefault($key),$default);
        }
        public function getDateTimeOrDefault( $key, $default=null ) {
            return Get::dateTimeOrDefault($this->getValueOrDefault($key),$default);
        }
        public function getStringOrDefault( $key, $default=null, $maxLength=null ) {
            $str    =  $this->getValueOrDefault($key,$default);
            return is_null($maxLength) ? $str : (strlen($str ?: '') > $maxLength ? substr($str,0,$maxLength) : $str);
        }
    }
}