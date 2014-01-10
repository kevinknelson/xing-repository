<?php

namespace Xing\Repository\PropertyMap {
    class CustomMap extends APropertyMap {
        public function __construct( $columnName, \Closure $loader=null, \Closure $saver=null ) {
            $this->ColumnName   = $columnName;
            $this->Load         = $loader;
            $this->Save         = $saver;
        }
    }
}