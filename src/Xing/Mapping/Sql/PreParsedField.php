<?php
/**
 * @package Xing\Mapping\Sql
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\Mapping\Sql {
    use Closure;
    use Xing\System\AValueType;
    use Xing\System\Format;

    /**
     * Class PreParsedField
     * @package Xing\Mapping\Sql
     */
    class PreParsedField extends AValueType {
        protected $_format;

        public function __construct( $value, $format=null ) {
            $this->_format      = $format;
            $this->_value       = $value;
        }
        public function getParsedString( Closure $sanitizer ) {
            return is_null($this->_value) ? 'NULL' : Format::string($this->_format, call_user_func($sanitizer,$this->_value));
        }
    }
}