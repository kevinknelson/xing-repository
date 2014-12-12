<?php

namespace Xing\System {
	class GuidRandom extends AValueType {
		public function __construct( $value=null ) {
			if( !is_null($value) ) {
				$this->_value		= str_replace('-','',$value);
			}
			else {
				// Generate UUID version 4 compliant, random UUID
				$random			= bin2hex(openssl_random_pseudo_bytes(16));
				$choice			= substr('89AB',rand(0,3),1);
				$random			= substr_replace($random,'4',12,1);
				$this->_value   = strtoupper(substr_replace($random,$choice,16,1));
			}
		}
		public static function create() {
			return new self();
		}
		public function formatted() {
			return substr($this->_value,0,8).'-'.substr($this->_value,8,4).'-'.substr($this->_value,12,4)
				.'-'.substr($this->_value,16,4).'-'.substr($this->_value,20,12);
		}
	}
}