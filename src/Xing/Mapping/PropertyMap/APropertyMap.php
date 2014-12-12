<?php

    namespace Xing\Mapping\PropertyMap {
        abstract class APropertyMap {
            protected $_columnName;
            protected $_selectPrefix;
            protected $_defaultValue;
            protected $_isReadOnly;
            protected $_isWriteOnly;

            abstract public function toDbValue( $value );
            abstract public function fromDbValue( $data );

            public function __construct( $columnName, $selectPrefix=null, $defaultValue=null ) {
                $this->_columnName      = $columnName;
                $this->_selectPrefix    = $selectPrefix;
                $this->_defaultValue    = $defaultValue;
                $this->_isReadOnly      = false;
            }
            #region CHAIN-ABLE METHODS
            /**
             * @return $this
             */
            public function setReadOnly() {
                $this->_isReadOnly = true;
                return $this;
            }
            /**
             * @return $this
             */
            public function setWriteOnly() {
                $this->_isWriteOnly = true;
                return $this;
            }
            #endregion

            /**
             * @return string
             */
            public function getColumnName() {
                return $this->_columnName;
            }
            /**
             * @return string
             */
            public function getColumnNameForQuery() {
                return (empty($this->_selectPrefix) ? '' : "{$this->_selectPrefix}.").$this->_columnName;
            }
            /**
             * @return bool
             */
            public function isWritable() {
                return !$this->_isReadOnly;
            }
            /**
             * @return bool
             */
            public function isReadable() {
                return !$this->_isWriteOnly;
            }
        }
    }