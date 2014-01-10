<?php

namespace Xing\Repository\PropertyMap {
    use Xing\System\Get;

    class FloatMap extends APropertyMap {

        public function __construct( $columnName, $default=null ) {
            $this->ColumnName   = $columnName;
            $this->Load         = function( $arr, $entity ) use( $columnName, $default ) {
                return isset($arr[$columnName]) ? Get::floatOrDefault($arr[$columnName],$default) : $default;
            };
            $this->Save         = function( $value ) use( $default )  {
                return Get::floatOrDefault($value,$default);
            };
        }
    }
}