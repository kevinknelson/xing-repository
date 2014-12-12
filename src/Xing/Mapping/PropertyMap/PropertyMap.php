<?php

    namespace Xing\Mapping\PropertyMap {
        use Closure;
        use Xing\Mapping\Sql\PreParsedField;
        use Xing\System\AValueType;
        use Xing\System\DateTime\Timezone;
        use Xing\System\Format;
        use Xing\System\Get;
        use Xing\System\Serialization\JsonSerializer;

        class PropertyMap extends APropertyMap {
            const Int               = 1;
            const Float             = 2;
            const Bool              = 3;
            const DateTime          = 4;
            const ValueType         = 5;
            const ValueTypeString   = 6;
            const Json              = 7;
            const Hex               = 8;

            protected $_type;
            protected $_valueType;
            protected $_defaultValue;
            protected $_customFromDb;
            protected $_customToDb;

            public static function column( $columnName, $selectPrefix=null, $defaultValue=null ) {
                return new self($columnName, $selectPrefix, $defaultValue);
            }

            #region CHAIN-ABLE METHODS
            public function asInt() {
                $this->_type        = self::Int;
                return $this;
            }
            public function asFloat() {
                $this->_type        = self::Float;
                return $this;
            }
            public function asBool() {
                $this->_type        = self::Bool;
                return $this;
            }
            public function asDateTime() {
                $this->_type        = self::DateTime;
                return $this;
            }
            public function asJson() {
                $this->_type        = self::Json;
                return $this;
            }
            public function asHex() {
                $this->_type        = self::Hex;
                return $this;
            }
            public function asValueType( AValueType $valueType ) {
                $this->_type        = self::ValueType;
                $this->_valueType   = $valueType;
                return $this;
            }
            public function customFromDb( Closure $callback ) {
                $this->_customFromDb    = $callback;
                return $this;
            }
            public function customToDb( Closure $callback ) {
                $this->_customToDb      = $callback;
                return $this;
            }
            #endregion
            public function toDbValue( $value ) {
                if( !is_null($this->_customToDb) ) {
                    return call_user_func($this->_customToDb,$value);
                }
                $dbValue    = null;
                switch( $this->_type ) {
                    case self::Int              : $dbValue  = Get::intOrDefault($value, $this->_defaultValue); break;
                    case self::Float            : $dbValue  = Get::floatOrDefault($value, $this->_defaultValue); break;
                    case self::Bool             : $dbValue  = Get::boolAsIntOrDefault($value, $this->_defaultValue); break;
                    case self::DateTime         : $dbValue  = Format::dateTime($value, 'Y-m-d H:i:s', Timezone::Utc()->PhpTimezone, null); break;
                    case self::Json             : $dbValue  = JsonSerializer::encode($value); break;
                    case self::Hex              : $dbValue  = new PreParsedField($value, 'HEX({0})'); break;
                    case self::ValueType        : $dbValue  = (int) AValueType::parseValue($value); break;
                    case self::ValueTypeString  : $dbValue  = AValueType::parseValue($value); break;
                    default                     : $dbValue  = $value ?: $this->_defaultValue;
                }
                return $dbValue;
            }
            public function fromDbValue( $data ) {
                if( !is_null($this->_customFromDb) ) {
                    return call_user_func($this->_customFromDb,$data);
                }
                $dbValue    = isset($data[$this->_columnName]) ? $data[$this->_columnName] : $this->_defaultValue;
                switch( $this->_type ) {
                    case self::Int              : $dbValue  = Get::intOrDefault($dbValue); break;
                    case self::Float            : $dbValue  = Get::floatOrDefault($dbValue); break;
                    case self::Bool             : $dbValue  = Get::boolOrDefault($dbValue); break;
                    case self::DateTime         : $dbValue  = Get::dateTimeOrDefault($dbValue,Timezone::Utc()->PhpTimezone); break;
                    case self::Json             : $dbValue  = json_decode($dbValue); break;
                    case self::ValueType        :
                    case self::ValueTypeString  :
                        $typeClass  = get_class($this->_valueType);
                        $dbValue    = new $typeClass( $this->_type == self::ValueTypeString ? $dbValue : intval($dbValue) );
                        break;
                }
                return $dbValue;
            }
        }
    }