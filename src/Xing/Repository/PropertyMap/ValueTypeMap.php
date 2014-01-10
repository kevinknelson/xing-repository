<?php

namespace Xing\Repository\PropertyMap {
    use Xing\System\AValueType;

    class ValueTypeMap extends APropertyMap {

        public function __construct( $columnName, AValueType $valueTypeInstance, AValueType $default=null ) {
            $type               = get_class($valueTypeInstance);
            $this->ColumnName   = $columnName;
            $this->Load         = function( $arr, $entity ) use( $columnName, $type, $default ) {
                return isset($arr[$columnName]) ? new $type($arr[$columnName]) : $default;
            };
            $this->Save         = function( $value ) use( $default )  {
                return $value instanceof AValueType
                    ? $value->Value
                    : (is_null($default) ? null : $default);
            };
        }
    }
}