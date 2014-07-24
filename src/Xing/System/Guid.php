<?php

namespace Xing\System {
	class Guid extends AValueType {
		public function __construct( $value=null ) {
			$this->_value = is_null($value) ? strtoupper(md5(uniqid('',true))) : $value;
		}
		public static function create() {
			return new Guid();
		}
	}
}