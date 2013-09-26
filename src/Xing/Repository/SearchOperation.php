<?php
/**
 * @package Xing\Repository
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\Repository {
	use Xing\System\AEnum;

	class SearchOperation {
		public $Operator;
		public $Key;
		public $Value;

		public function __construct( SearchOperator $operator, $key=null, $value=null ) {
			$this->Operator	= $operator;
			$this->Key		= $key;
			$this->Value	= $value;
		}
	}
}