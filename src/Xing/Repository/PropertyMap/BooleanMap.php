<?php

namespace Xing\Repository\PropertyMap {
    use Xing\System\Get;

    class BooleanMap extends APropertyMap {

        public function __construct( $columnName, $default=null ) {
            $this->ColumnName   = $columnName;
            $this->Load         = function( $arr, $entity ) use( $columnName, $default ) {
                $value = isset($arr[$columnName]) ? $arr[$columnName] : $default;
                return Get::boolOrDefault($value);
            };
            $this->Save         = function( $value ) use( $default )  {
                return Get::boolAsIntOrDefault($value,$default);
            };
        }
    }
}