<?php

    namespace Xing\Mapping\PropertyMap {
        use Xing\System\AValueType;
        use Xing\System\Format;
        use Xing\System\Get;

        class PropertyMap implements IPropertyMap {
            const INT           = 1;
            const FLOAT         = 2;
            const BOOL          = 3;
            const DATETIME      = 4;
            const VALUE_TYPE    = 5;

            protected $_isReadOnly;
            protected $_isWriteOnly;
            protected $_selectPrefix;
            protected $_columnName;
            protected $_type;
            protected $_valueType;
            protected $_defaultValue;
            protected $_customFromDb;
            protected $_customToDb;

            public static function column( $columnName, $selectPrefix=null, $defaultValue=null ) {
                return new self($columnName, $selectPrefix, $defaultValue);
            }
            private function __construct( $columnName, $selectPrefix, $defaultValue ) {
                $this->_columnName      = $columnName;
                $this->_selectPrefix    = $selectPrefix;
                $this->_defaultValue    = $defaultValue;
                $this->_isReadOnly      = false;
            }

            #region CHAIN-ABLE METHODS
            public function setReadOnly() {
                $this->_isReadOnly = true;
                return $this;
            }
            public function setWriteOnly() {
                $this->_isWriteOnly = true;
                return $this;
            }
            public function asInt() {
                $this->_type        = self::INT;
                return $this;
            }
            public function asFloat() {
                $this->_type        = self::FLOAT;
                return $this;
            }
            public function asBool() {
                $this->_type        = self::BOOL;
                return $this;
            }
            public function asDateTime() {
                $this->_type        = self::DATETIME;
                return $this;
            }
            public function asValueType( AValueType $valueType ) {
                $this->_type        = self::VALUE_TYPE;
                $this->_valueType   = $valueType;
                return $this;
            }
            public function customFromDb( $callback ) {
                $this->_customFromDb    = $callback;
                return $this;
            }
            public function customToDb( $callback ) {
                $this->_customToDb      = $callback;
                return $this;
            }
            #endregion

            public function getColumnName() {
                return $this->_columnName;
            }
            public function getColumnNameForQuery() {
                return "{$this->_selectPrefix}.{$this->_columnName}";
            }
            public function isWritable() {
                return !$this->_isReadOnly;
            }
            public function isReadable() {
                return !$this->_isWriteOnly;
            }
            public function toDbValue( $value ) {
                if( !is_null($this->_customToDb) ) {
                    return call_user_func($this->_customToDb,$value);
                }
                $dbValue    = null;
                switch( $this->_type ) {
                    case self::INT          : $dbValue  = Get::intOrDefault($value, $this->_defaultValue); break;
                    case self::FLOAT        : $dbValue  = Get::floatOrDefault($value, $this->_defaultValue); break;
                    case self::BOOL         : $dbValue  = Get::boolAsIntOrDefault($value, $this->_defaultValue); break;
                    case self::DATETIME     : $dbValue  = Format::dateTime($value, 'Y-m-d H:i:s', null); break;
                    case self::VALUE_TYPE   : $dbValue  = AValueType::parseValue($value); break;
                    default                 : $dbValue  = $value ?: $this->_defaultValue;
                }
                return $dbValue;
            }
            public function fromDbValue( $data ) {
                if( !is_null($this->_customFromDb) ) {
                    return call_user_func($this->_customFromDb,$data);
                }
                $dbValue    = isset($data[$this->_columnName]) ? $data[$this->_columnName] : $this->_defaultValue;
                switch( $this->_type ) {
                    case self::INT          : $dbValue  = Get::intOrDefault($dbValue); break;
                    case self::FLOAT        : $dbValue  = Get::floatOrDefault($dbValue); break;
                    case self::BOOL         : $dbValue  = Get::boolOrDefault($dbValue); break;
                    case self::DATETIME     : $dbValue  = Get::dateTimeOrDefault($dbValue); break;
                    case self::VALUE_TYPE   :
                        $typeClass  = get_class($this->_valueType);
                        $dbValue    = new $typeClass( $dbValue );
                        break;
                }
                return $dbValue;
            }
        }
    }