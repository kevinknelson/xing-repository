<?php
/**
 * @package Xing\Repository
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\Repository\Sql {
	class SaveArray {
		public $TableName;
		public $PrimaryKey;
		public $Columns;

		public function __construct() {
			$this->Columns	= array();
		}
	}
}