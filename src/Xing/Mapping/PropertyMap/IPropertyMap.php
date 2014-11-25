<?php

    namespace Xing\Mapping\PropertyMap {
        use Xing\System\Get;

        interface IPropertyMap {
            public function toDbValue( $value );
            public function fromDbValue( $data );

            #region CHAIN-ABLE METHODS
            /**
             * @return $this
             */
            public function setReadOnly();
            /**
             * @return $this
             */
            public function setWriteOnly();
            #endregion

            /**
             * @return string
             */
            public function getColumnName();
            /**
             * @return string
             */
            public function getColumnNameForQuery();
            /**
             * @return bool
             */
            public function isWritable();
            /**
             * @return bool
             */
            public function isReadable();
        }
    }