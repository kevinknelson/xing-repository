<?php

namespace Xing\System {
	class Guid extends AValueType {
		public function __construct( $value=null ) {
            if( !is_null($value) ) {
                $this->_value 	= strtoupper(str_replace('-','',$value));
            }
            else {
                $this->_value   = strtoupper(substr_replace(bin2hex(openssl_random_pseudo_bytes(16)),uniqid(),0,13 ));
            }
		}
		public static function create( $value=null ) {
			return new self($value);
		}
		public function formatted() {
			return substr($this->_value,0,8).'-'.substr($this->_value,8,4).'-'.substr($this->_value,12,4)
				.'-'.substr($this->_value,16,4).'-'.substr($this->_value,20,12);
		}
	}
}