<?php

namespace Xing\Repository\PropertyMap {
    use Xing\System\DateTime;
    use Xing\System\Get;

    class DateTimeMap extends APropertyMap {

        public function __construct( $columnName, DateTime $default=null  ) {
            $this->ColumnName   = $columnName;
            $this->Load = function( $arr, $entity ) use( $columnName, $default ) {
                return isset($arr[$columnName]) ? Get::dateTimeOrDefault($arr[$columnName], $default) : $default;
            };
            $this->Save = function( $value ) use( $default ) {
                return $value instanceof DateTime
                    ? $value->format('Y-m-d H:i:s')
                    : (is_null($default) ? null : $default->format('Y-m-d H:i:s'));
            };
        }
    }
}